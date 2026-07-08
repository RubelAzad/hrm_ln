<?php

namespace App\Services\AI;

use App\Models\AiInsight;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\JobPosting;

class AIService
{
    private AIProviderInterface $provider;

    public function __construct()
    {
        $this->provider = $this->resolveProvider();
    }

    public function screening(Candidate $candidate, ?string $resumeText = null): AiInsight
    {
        $existing = $this->getExisting($candidate, 'resume_screening');
        if ($existing) return $existing;

        $data = $this->provider->resumeScreening($candidate->toArray(), $resumeText);
        $score = $data['years_experience'] ?? null;

        return $this->store($candidate, 'resume_screening', $data, $score);
    }

    public function matchingScore(Candidate $candidate, JobPosting $job): AiInsight
    {
        $existing = $this->getExisting($candidate, "matching_score.{$job->id}");
        if ($existing) return $existing;

        $data = $this->provider->candidateJobMatching($candidate->toArray(), $job->toArray());
        $score = $data['overall_score'] ?? null;

        return $this->store($candidate, "matching_score.{$job->id}", $data, $score);
    }

    public function interviewQuestions(Candidate $candidate, JobPosting $job, string $interviewType): AiInsight
    {
        $key = "interview_questions.{$job->id}.{$interviewType}";
        $existing = $this->getExisting($candidate, $key);
        if ($existing) return $existing;

        $data = $this->provider->interviewQuestions($candidate->toArray(), $job->toArray(), $interviewType);

        return $this->store($candidate, $key, $data, null);
    }

    public function interviewSummary(Interview $interview): AiInsight
    {
        $existing = $this->getExisting($interview, 'interview_summary');
        if ($existing) return $existing;

        $feedbackData = [
            'candidate_name' => $interview->candidate?->full_name ?? 'Unknown',
            'feedback_text' => $interview->feedback,
            'rating' => $interview->rating,
        ];

        $data = $this->provider->interviewSummary($interview->toArray(), $feedbackData);
        $score = $interview->rating;

        return $this->store($interview, 'interview_summary', $data, $score);
    }

    public function ranking(JobPosting $job): AiInsight
    {
        $existing = $this->getExisting($job, 'candidate_ranking');
        if ($existing) return $existing;

        $candidates = Candidate::whereHas('pipelineStages', fn($q) => $q->where('job_posting_id', $job->id))
            ->get()
            ->toArray();

        if (empty($candidates)) {
            return $this->store($job, 'candidate_ranking', [
                'rankings' => [],
                'job_title' => $job->title,
                'total_candidates' => 0,
                'average_score' => 0,
                'message' => 'No candidates in pipeline for this job.',
            ], null);
        }

        $data = $this->provider->candidateRanking($candidates, $job->toArray());
        $score = $data['average_score'] ?? null;

        return $this->store($job, 'candidate_ranking', $data, $score);
    }

    public function skillGap(Candidate $candidate, JobPosting $job): AiInsight
    {
        $key = "skill_gap.{$job->id}";
        $existing = $this->getExisting($candidate, $key);
        if ($existing) return $existing;

        $data = $this->provider->skillGapAnalysis($candidate->toArray(), $job->toArray());
        $score = $data['readiness_score'] ?? null;

        return $this->store($candidate, $key, $data, $score);
    }

    private function resolveProvider(): AIProviderInterface
    {
        if (config('services.openai.api_key')) {
            return new OpenAIProvider;
        }
        return new MockProvider;
    }

    private function getExisting($model, string $type): ?AiInsight
    {
        return AiInsight::where('insightable_type', get_class($model))
            ->where('insightable_id', $model->id)
            ->where('type', $type)
            ->where('status', 'completed')
            ->first();
    }

    private function store($model, string $type, array $data, mixed $score): AiInsight
    {
        return AiInsight::updateOrCreate(
            [
                'insightable_type' => get_class($model),
                'insightable_id' => $model->id,
                'type' => $type,
            ],
            [
                'data' => $data,
                'score' => is_numeric($score) ? $score : null,
                'status' => isset($data['error']) ? 'failed' : 'completed',
                'error_message' => $data['error'] ?? null,
            ]
        );
    }

    public function clearCache($model, ?string $type = null): void
    {
        $query = AiInsight::where('insightable_type', get_class($model))
            ->where('insightable_id', $model->id);

        if ($type) {
            $query->where('type', $type);
        }

        $query->delete();
    }
}
