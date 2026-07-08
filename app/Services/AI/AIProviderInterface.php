<?php

namespace App\Services\AI;

interface AIProviderInterface
{
    public function resumeScreening(array $candidateData, ?string $resumeText = null): array;

    public function candidateJobMatching(array $candidateData, array $jobData): array;

    public function interviewQuestions(array $candidateData, array $jobData, string $interviewType): array;

    public function interviewSummary(array $interviewData, array $feedbackData): array;

    public function candidateRanking(array $candidatesData, array $jobData): array;

    public function skillGapAnalysis(array $candidateData, array $jobData): array;
}
