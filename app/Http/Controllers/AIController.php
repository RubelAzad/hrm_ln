<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Services\AI\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AIController extends Controller
{
    public function __construct(
        private readonly AIService $ai
    ) {}

    public function screening(Candidate $candidate): JsonResponse
    {
        $insight = $this->ai->screening($candidate);
        return response()->json($insight);
    }

    public function matchingScore(Candidate $candidate, JobPosting $job): JsonResponse
    {
        $insight = $this->ai->matchingScore($candidate, $job);
        return response()->json($insight);
    }

    public function interviewQuestions(Candidate $candidate, JobPosting $job, Request $request): JsonResponse
    {
        $type = $request->input('type', 'general');
        $insight = $this->ai->interviewQuestions($candidate, $job, $type);
        return response()->json($insight);
    }

    public function interviewSummary(Interview $interview): JsonResponse
    {
        $insight = $this->ai->interviewSummary($interview);
        return response()->json($insight);
    }

    public function ranking(JobPosting $job): JsonResponse
    {
        $insight = $this->ai->ranking($job);
        return response()->json($insight);
    }

    public function skillGap(Candidate $candidate, JobPosting $job): JsonResponse
    {
        $insight = $this->ai->skillGap($candidate, $job);
        return response()->json($insight);
    }

    public function regenerateScreening(Candidate $candidate): JsonResponse
    {
        $this->ai->clearCache($candidate, 'resume_screening');
        return $this->screening($candidate);
    }

    public function regenerateMatching(Candidate $candidate, JobPosting $job): JsonResponse
    {
        $this->ai->clearCache($candidate, "matching_score.{$job->id}");
        return $this->matchingScore($candidate, $job);
    }

    public function regenerateRanking(JobPosting $job): JsonResponse
    {
        $this->ai->clearCache($job, 'candidate_ranking');
        return $this->ranking($job);
    }

    public function regenerateSkillGap(Candidate $candidate, JobPosting $job): JsonResponse
    {
        $this->ai->clearCache($candidate, "skill_gap.{$job->id}");
        return $this->skillGap($candidate, $job);
    }

    public function allInsights(Candidate $candidate): View
    {
        return view('recruitment.partials.candidate-ai-insights', [
            'candidate' => $candidate,
            'jobs' => JobPosting::where('status', 'published')->get(),
        ]);
    }

    public function regenerateQuestions(Candidate $candidate, JobPosting $job, Request $request): JsonResponse
    {
        $type = $request->input('type', 'general');
        $key = "interview_questions.{$job->id}.{$type}";
        $this->ai->clearCache($candidate, $key);
        return $this->interviewQuestions($candidate, $job, $request);
    }
}
