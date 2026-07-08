<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIProvider implements AIProviderInterface
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->model = config('services.openai.model', 'gpt-4o');
    }

    public function resumeScreening(array $candidateData, ?string $resumeText = null): array
    {
        $prompt = $this->buildPrompt('resume-screening', [
            'candidate' => $candidateData,
            'resume' => $resumeText,
        ]);

        return $this->ask($prompt);
    }

    public function candidateJobMatching(array $candidateData, array $jobData): array
    {
        $prompt = $this->buildPrompt('candidate-job-matching', [
            'candidate' => $candidateData,
            'job' => $jobData,
        ]);

        return $this->ask($prompt);
    }

    public function interviewQuestions(array $candidateData, array $jobData, string $interviewType): array
    {
        $prompt = $this->buildPrompt('interview-questions', [
            'candidate' => $candidateData,
            'job' => $jobData,
            'interview_type' => $interviewType,
        ]);

        return $this->ask($prompt);
    }

    public function interviewSummary(array $interviewData, array $feedbackData): array
    {
        $prompt = $this->buildPrompt('interview-summary', [
            'interview' => $interviewData,
            'feedback' => $feedbackData,
        ]);

        return $this->ask($prompt);
    }

    public function candidateRanking(array $candidatesData, array $jobData): array
    {
        $prompt = $this->buildPrompt('candidate-ranking', [
            'candidates' => $candidatesData,
            'job' => $jobData,
        ]);

        return $this->ask($prompt);
    }

    public function skillGapAnalysis(array $candidateData, array $jobData): array
    {
        $prompt = $this->buildPrompt('skill-gap-analysis', [
            'candidate' => $candidateData,
            'job' => $jobData,
        ]);

        return $this->ask($prompt);
    }

    private function buildPrompt(string $type, array $data): string
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);

        $prompts = [
            'resume-screening' => "Analyze this candidate's resume data and extract key information. Return a JSON object with: extracted_skills (array), years_experience (number), education_level (string), key_findings (array), suggestions (array), summary (string).\n\nData:\n$json",
            'candidate-job-matching' => "Compare this candidate with the job requirements and provide a match analysis. Return a JSON object with: overall_score (0-100), breakdown (object with skills_match, experience_match, education_match, culture_fit), matched_skills (array), missing_skills (array), summary (string).\n\nData:\n$json",
            'interview-questions' => "Generate interview questions for this candidate based on the job and interview type. Return a JSON object with: questions (array of objects with category, question, difficulty, suggested_duration_minutes, focus_area), total_estimated_duration (number), interview_type (string), tips (array).\n\nData:\n$json",
            'interview-summary' => "Summarize this interview feedback. Return a JSON object with: key_points (array), strengths (array), areas_for_improvement (array), sentiment (string), recommendation (string), overall_assessment (string).\n\nData:\n$json",
            'candidate-ranking' => "Rank these candidates for the job based on fit. Return a JSON object with: rankings (array of objects with candidate_id, candidate_name, score, breakdown, matched_skills, missing_skills, summary), job_title (string), total_candidates (number), average_score (number).\n\nData:\n$json",
            'skill-gap-analysis' => "Analyze skill gaps between this candidate and the job requirements. Return a JSON object with: gaps (array of objects with skill, required_level, current_level, gap_severity, learning_resources), overall_readiness (string), readiness_score (number), recommended_training (string), summary (string).\n\nData:\n$json",
        ];

        return $prompts[$type] ?? "Analyze this data and return insights as JSON:\n$json";
    }

    private function ask(string $prompt): array
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an AI HR assistant. Always respond with valid JSON only, no markdown.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 2000,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $parsed = json_decode($content, true);
                return $parsed ?? ['error' => 'Failed to parse AI response'];
            }

            Log::error('OpenAI API error', ['status' => $response->status(), 'body' => $response->body()]);
            return ['error' => 'AI service unavailable', 'details' => $response->body()];
        } catch (\Exception $e) {
            Log::error('OpenAI request failed', ['error' => $e->getMessage()]);
            return ['error' => 'AI service request failed'];
        }
    }
}
