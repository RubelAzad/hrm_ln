<?php

namespace App\Services\AI;

class MockProvider implements AIProviderInterface
{
    public function resumeScreening(array $candidateData, ?string $resumeText = null): array
    {
        $skills = $candidateData['skills'] ?? [];
        $experienceYears = $candidateData['experience_years'] ?? 0;

        return [
            'extracted_skills' => $skills,
            'years_experience' => $experienceYears,
            'education_level' => $candidateData['education_level'] ?? 'Not specified',
            'key_findings' => $this->generateFindings($candidateData),
            'suggestions' => $this->generateSuggestions($skills),
            'summary' => sprintf(
                'Candidate has %d years of experience with expertise in %s. Education: %s.',
                $experienceYears,
                !empty($skills) ? implode(', ', array_slice($skills, 0, 4)) : 'various areas',
                $candidateData['education_level'] ?? 'Not specified'
            ),
        ];
    }

    public function candidateJobMatching(array $candidateData, array $jobData): array
    {
        $candidateSkills = array_map('strtolower', $candidateData['skills'] ?? []);
        $jobRequirements = strtolower($jobData['requirements'] ?? '');
        $jobTitle = strtolower($jobData['title'] ?? '');

        $requiredSkills = $this->extractKeywords($jobRequirements);
        $titleSkills = $this->extractKeywords($jobTitle);
        $allRequired = array_unique(array_merge($requiredSkills, $titleSkills));

        $matched = [];
        $missing = [];
        foreach ($allRequired as $skill) {
            if (in_array($skill, $candidateSkills)) {
                $matched[] = $skill;
            } else {
                $missing[] = $skill;
            }
        }

        $skillsScore = !empty($allRequired)
            ? round((count($matched) / count($allRequired)) * 100, 1)
            : 70;

        $expScore = $this->experienceScore($candidateData['experience_years'] ?? 0, $jobData);
        $overall = round(($skillsScore * 0.5 + $expScore * 0.3 + rand(60, 85) * 0.2), 1);

        return [
            'overall_score' => min(99, $overall),
            'breakdown' => [
                'skills_match' => min(99, $skillsScore),
                'experience_match' => min(99, $expScore),
                'education_match' => rand(60, 95),
                'culture_fit' => rand(65, 90),
            ],
            'matched_skills' => $matched,
            'missing_skills' => $missing,
            'summary' => sprintf(
                'Candidate matches %d of %d key requirements. %s',
                count($matched),
                count($allRequired),
                !empty($missing) ? 'Missing: ' . implode(', ', array_slice($missing, 0, 4)) . '.' : 'All key skills present.'
            ),
        ];
    }

    public function interviewQuestions(array $candidateData, array $jobData, string $interviewType): array
    {
        $categories = match ($interviewType) {
            'technical' => ['Technical Skills', 'Problem Solving', 'System Design', 'Code Review'],
            'hr' => ['Background & Motivation', 'Cultural Fit', 'Soft Skills', 'Career Goals'],
            'managerial' => ['Leadership', 'Strategic Thinking', 'Team Management', 'Decision Making'],
            default => ['General', 'Technical', 'Behavioral', 'Situational'],
        };

        $questions = [];
        $templates = $this->getQuestionTemplates($jobData['title'] ?? 'the role');

        foreach ($categories as $i => $category) {
            $qTemplates = $templates[$i] ?? $templates[array_rand($templates)];
            $questions[] = [
                'category' => $category,
                'question' => $qTemplates[array_rand($qTemplates)],
                'difficulty' => ['easy', 'medium', 'hard'][min($i, 2)],
                'suggested_duration_minutes' => rand(3, 8),
                'focus_area' => $this->getFocusArea($category, $jobData),
            ];
        }

        return [
            'questions' => $questions,
            'total_estimated_duration' => array_sum(array_column($questions, 'suggested_duration_minutes')),
            'interview_type' => $interviewType,
            'tips' => [
                'Start with warm-up questions to put the candidate at ease.',
                'Allow 2-3 minutes for candidate responses per question.',
                'Take notes on specific examples rather than general answers.',
            ],
        ];
    }

    public function interviewSummary(array $interviewData, array $feedbackData): array
    {
        $candidateName = $feedbackData['candidate_name'] ?? 'The candidate';
        $rating = $interviewData['rating'] ?? 3;

        return [
            'key_points' => [
                sprintf('%s demonstrated strong communication skills throughout the interview.', $candidateName),
                sprintf('Showed %s understanding of core concepts.', $rating >= 4 ? 'excellent' : ($rating >= 3 ? 'good' : 'adequate')),
                'Provided relevant examples from past experience.',
                ($rating >= 4 ? 'Asked insightful questions about the team and role.' : ''),
            ],
            'strengths' => [
                'Clear and structured communication',
                'Relevant industry experience',
                'Problem-solving approach',
            ],
            'areas_for_improvement' => $rating < 4 ? [
                'Could deepen technical expertise in specific areas',
                'Would benefit from more preparation on company background',
            ] : [
                'No significant areas identified',
            ],
            'sentiment' => $rating >= 4 ? 'positive' : ($rating >= 3 ? 'neutral' : 'constructive'),
            'recommendation' => match (true) {
                $rating >= 4 => 'Strongly recommend proceeding to next stage.',
                $rating >= 3 => 'Recommend proceeding with additional evaluation.',
                default => 'Consider for different role or gain more experience.',
            },
            'overall_assessment' => sprintf(
                '%s rates %d/5. %s',
                $candidateName,
                $rating,
                $rating >= 4 ? 'Excellent fit for the role.' : ($rating >= 3 ? 'Potential fit with some development areas.' : 'May need further assessment.')
            ),
        ];
    }

    public function candidateRanking(array $candidatesData, array $jobData): array
    {
        $ranked = [];
        foreach ($candidatesData as $candidate) {
            $matching = $this->candidateJobMatching($candidate, $jobData);
            $ranked[] = [
                'candidate_id' => $candidate['id'] ?? null,
                'candidate_name' => ($candidate['first_name'] ?? '') . ' ' . ($candidate['last_name'] ?? ''),
                'score' => $matching['overall_score'],
                'breakdown' => $matching['breakdown'],
                'matched_skills' => $matching['matched_skills'],
                'missing_skills' => $matching['missing_skills'],
                'summary' => $matching['summary'],
            ];
        }

        usort($ranked, fn($a, $b) => $b['score'] <=> $a['score']);

        return [
            'rankings' => $ranked,
            'job_title' => $jobData['title'] ?? '',
            'total_candidates' => count($ranked),
            'average_score' => !empty($ranked)
                ? round(array_sum(array_column($ranked, 'score')) / count($ranked), 1)
                : 0,
        ];
    }

    public function skillGapAnalysis(array $candidateData, array $jobData): array
    {
        $matching = $this->candidateJobMatching($candidateData, $jobData);

        $gaps = [];
        foreach ($matching['missing_skills'] as $skill) {
            $gaps[] = [
                'skill' => ucfirst($skill),
                'required_level' => 'intermediate',
                'current_level' => 'beginner',
                'gap_severity' => 'medium',
                'learning_resources' => [
                    'Online courses available on platforms like Coursera and Udemy',
                    sprintf('Consider internal training for %s', ucfirst($skill)),
                    sprintf('Pair programming or mentorship for %s', ucfirst($skill)),
                ],
            ];
        }

        $matchedSkills = array_map(fn($s) => [
            'skill' => ucfirst($s),
            'required_level' => 'intermediate',
            'current_level' => 'intermediate',
            'gap_severity' => 'none',
            'learning_resources' => [],
        ], $matching['matched_skills']);

        return [
            'gaps' => array_merge($matchedSkills, $gaps),
            'overall_readiness' => !empty($gaps) ? 'partial' : 'ready',
            'readiness_score' => $matching['overall_score'],
            'recommended_training' => !empty($gaps)
                ? sprintf('Recommended training in: %s', implode(', ', array_slice($matching['missing_skills'], 0, 4)))
                : 'No additional training required.',
            'summary' => sprintf(
                'Candidate meets %d of %d required skills. %s',
                count($matching['matched_skills']),
                count($matching['matched_skills']) + count($matching['missing_skills']),
                !empty($matching['missing_skills'])
                    ? 'Skill gap exists in ' . implode(', ', array_slice($matching['missing_skills'], 0, 3)) . '.'
                    : 'All required skills are covered.'
            ),
        ];
    }

    private function extractKeywords(string $text): array
    {
        $commonSkills = [
            'php', 'laravel', 'javascript', 'typescript', 'react', 'vue', 'angular',
            'python', 'java', 'go', 'rust', 'sql', 'mysql', 'postgresql', 'mongodb',
            'docker', 'kubernetes', 'aws', 'azure', 'gcp', 'git', 'ci/cd', 'redis',
            'html', 'css', 'sass', 'tailwind', 'bootstrap', 'node.js', 'express',
            'rest api', 'graphql', 'microservices', 'agile', 'scrum', 'devops',
            'machine learning', 'data analysis', 'project management', 'leadership',
            'communication', 'teamwork', 'problem solving', 'critical thinking',
        ];

        $found = [];
        foreach ($commonSkills as $skill) {
            if (str_contains($text, $skill)) {
                $found[] = $skill;
            }
        }
        return $found;
    }

    private function experienceScore(int $candidateYears, array $jobData): float
    {
        $requiredYears = match ($jobData['experience_level'] ?? '') {
            'entry' => 1,
            'junior' => 2,
            'mid' => 4,
            'senior' => 6,
            'lead' => 8,
            default => 3,
        };

        if ($candidateYears >= $requiredYears) {
            return min(99, 70 + ($candidateYears - $requiredYears) * 5);
        }
        return max(30, 70 - ($requiredYears - $candidateYears) * 10);
    }

    private function generateFindings(array $data): array
    {
        $findings = [];
        if (!empty($data['current_company'])) {
            $findings[] = sprintf('Currently at %s as %s', $data['current_company'], $data['current_position'] ?? 'unknown role');
        }
        if (!empty($data['experience_years'])) {
            $findings[] = sprintf('%d years of professional experience', $data['experience_years']);
        }
        if (!empty($data['education_level'])) {
            $findings[] = sprintf('Education: %s', $data['education_level']);
        }
        return $findings;
    }

    private function generateSuggestions(array $skills): array
    {
        if (empty($skills)) {
            return ['Consider adding more skills to profile'];
        }
        return [
            'Profile has good skill diversity',
            'Consider getting certifications for key skills',
            'Highlight measurable achievements for each skill',
        ];
    }

    private function getQuestionTemplates(string $role): array
    {
        return [
            [
                sprintf('What interests you about the %s role?', $role),
                sprintf('How does your experience align with the %s position?', $role),
                sprintf('What do you know about our company and this %s role?', $role),
            ],
            [
                sprintf('What technical skills make you a strong fit for %s?', $role),
                sprintf('Describe a complex technical problem you solved in your last role.'),
                sprintf('How do you stay current with industry trends for %s?', $role),
            ],
            [
                'Tell me about a time you had to collaborate with a difficult team member.',
                'Describe a situation where you had to meet a tight deadline.',
                'How do you handle feedback and constructive criticism?',
            ],
            [
                sprintf('Where do you see your career in the next 3-5 years as a %s professional?', $role),
                'What type of work environment helps you perform your best?',
                'Describe a project you are most proud of and why.',
            ],
        ];
    }

    private function getFocusArea(string $category, array $jobData): string
    {
        $areas = [
            'Technical Skills' => $jobData['requirements'] ?? 'Core technical competencies',
            'Problem Solving' => 'Analytical and critical thinking abilities',
            'System Design' => 'Architecture and scalability understanding',
            'Background & Motivation' => 'Career trajectory and intrinsic motivation',
            'Cultural Fit' => 'Alignment with company values and work style',
            'Soft Skills' => 'Interpersonal and communication abilities',
            'Leadership' => 'Team guidance and mentorship capability',
            'Strategic Thinking' => 'Long-term planning and vision alignment',
        ];
        return $areas[$category] ?? 'General competency assessment';
    }
}
