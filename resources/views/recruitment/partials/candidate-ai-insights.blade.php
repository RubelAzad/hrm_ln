<div class="space-y-6" x-data="{
    screening: null,
    matching: null,
    skillGap: null,
    questions: null,
    selectedJob: null,
    questionType: 'general',
    loading: { screening: false, matching: false, skillGap: false, questions: false },
    init() {
        this.loadScreening();
    },
    async loadScreening() {
        this.loading.screening = true;
        const res = await fetch('{{ route('ai.screening', $candidate) }}');
        this.screening = await res.json();
        this.loading.screening = false;
    },
    async loadMatching(jobId) {
        if (!jobId) return;
        this.loading.matching = true;
        const res = await fetch(`/ai/candidates/{{ $candidate->id }}/matching/${jobId}`);
        this.matching = await res.json();
        this.loading.matching = false;
    },
    async loadSkillGap(jobId) {
        if (!jobId) return;
        this.loading.skillGap = true;
        const res = await fetch(`/ai/candidates/{{ $candidate->id }}/skill-gap/${jobId}`);
        this.skillGap = await res.json();
        this.loading.skillGap = false;
    },
    async loadQuestions(jobId, type) {
        if (!jobId) return;
        this.loading.questions = true;
        const res = await fetch(`/ai/candidates/{{ $candidate->id }}/questions/${jobId}?type=${type}`);
        this.questions = await res.json();
        this.loading.questions = false;
    },
    onJobChange(jobId) {
        this.selectedJob = jobId;
        this.matching = null;
        this.skillGap = null;
        this.questions = null;
        if (jobId) {
            this.loadMatching(jobId);
            this.loadSkillGap(jobId);
        }
    }
}">
    {{-- Job Selector --}}
    <div class="content-card">
        <div class="content-card-header">
            <h2>AI Analysis</h2>
            <span class="badge-info">Powered by AI</span>
        </div>
        <div class="content-card-body">
            <div class="mb-4">
                <label class="form-label">Compare against job posting</label>
                <select x-model="selectedJob" @change="onJobChange($el.value)"
                        class="form-select max-w-md">
                    <option value="">-- Select a job for comparison --</option>
                    @foreach ($jobs as $job)
                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Resume Screening --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-slate-900">Resume Screening</h3>
                    <template x-if="!loading.screening">
                        <button @click="loadScreening()" class="text-xs text-indigo-600 hover:text-indigo-500">Refresh</button>
                    </template>
                </div>
                <template x-if="loading.screening">
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Analyzing resume...
                    </div>
                </template>
                <template x-if="screening && !loading.screening">
                    <div class="bg-slate-50 rounded-lg p-4 space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Summary</p>
                            <p class="text-sm text-slate-800" x-text="screening.data.summary"></p>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-slate-500">Experience</p>
                                <p class="text-sm font-semibold text-slate-900" x-text="screening.data.years_experience + ' yrs'"></p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Education</p>
                                <p class="text-sm font-semibold text-slate-900" x-text="screening.data.education_level"></p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Skills Found</p>
                                <p class="text-sm font-semibold text-slate-900" x-text="screening.data.extracted_skills?.length || 0"></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Extracted Skills</p>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="skill in (screening.data.extracted_skills || [])" :key="skill">
                                    <span class="badge-info" x-text="skill"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Matching Score --}}
            <template x-if="selectedJob">
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-slate-900">Job Matching Score</h3>
                        <template x-if="!loading.matching">
                            <button @click="loadMatching(selectedJob)" class="text-xs text-indigo-600 hover:text-indigo-500">Refresh</button>
                        </template>
                    </div>
                    <template x-if="loading.matching">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Calculating match...
                        </div>
                    </template>
                    <template x-if="matching && !loading.matching">
                        <div class="bg-slate-50 rounded-lg p-4 space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="relative w-16 h-16">
                                    <svg class="w-16 h-16 -rotate-90" viewBox="0 0 36 36">
                                        <circle cx="18" cy="18" r="16" fill="none" stroke="#e2e8f0" stroke-width="3"></circle>
                                        <circle cx="18" cy="18" r="16" fill="none"
                                                :stroke="(matching.data.overall_score || 0) >= 80 ? '#059669' : (matching.data.overall_score || 0) >= 60 ? '#6366f1' : '#e11d48'"
                                                stroke-width="3"
                                                stroke-dasharray="100"
                                                :stroke-dasharray="(matching.data.overall_score || 0) + ' ' + (100 - (matching.data.overall_score || 0))"
                                                stroke-linecap="round"></circle>
                                    </svg>
                                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold" x-text="Math.round(matching.data.overall_score || 0) + '%'"></span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900" x-text="matching.data.summary"></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-slate-500">Skills Match</p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <div class="flex-1 h-1.5 bg-slate-200 rounded-full">
                                            <div class="h-full rounded-full bg-indigo-500" :style="'width:' + (matching.data.breakdown?.skills_match || 0) + '%'"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700" x-text="Math.round(matching.data.breakdown?.skills_match || 0) + '%'"></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Experience</p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <div class="flex-1 h-1.5 bg-slate-200 rounded-full">
                                            <div class="h-full rounded-full bg-emerald-500" :style="'width:' + (matching.data.breakdown?.experience_match || 0) + '%'"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700" x-text="Math.round(matching.data.breakdown?.experience_match || 0) + '%'"></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Education</p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <div class="flex-1 h-1.5 bg-slate-200 rounded-full">
                                            <div class="h-full rounded-full bg-amber-500" :style="'width:' + (matching.data.breakdown?.education_match || 0) + '%'"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700" x-text="Math.round(matching.data.breakdown?.education_match || 0) + '%'"></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Culture Fit</p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <div class="flex-1 h-1.5 bg-slate-200 rounded-full">
                                            <div class="h-full rounded-full bg-sky-500" :style="'width:' + (matching.data.breakdown?.culture_fit || 0) + '%'"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700" x-text="Math.round(matching.data.breakdown?.culture_fit || 0) + '%'"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="skill in (matching.data.matched_skills || [])" :key="skill">
                                    <span class="badge-success" x-text="skill"></span>
                                </template>
                                <template x-for="skill in (matching.data.missing_skills || [])" :key="skill">
                                    <span class="badge-danger" x-text="skill"></span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Skill Gap --}}
            <template x-if="selectedJob && skillGap">
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-slate-900">Skill Gap Analysis</h3>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 space-y-3">
                        <p class="text-sm text-slate-700" x-text="skillGap.data.summary"></p>
                        <template x-if="skillGap.data.gaps">
                            <div class="space-y-2">
                                <template x-for="gap in skillGap.data.gaps" :key="gap.skill">
                                    <div class="flex items-center justify-between py-1.5 border-b border-slate-200 last:border-0">
                                        <span class="text-sm text-slate-800" x-text="gap.skill"></span>
                                        <span :class="gap.gap_severity === 'none' ? 'badge-success' : 'badge-warning'"
                                              x-text="gap.gap_severity === 'none' ? 'Met' : 'Gap: ' + gap.gap_severity"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Interview Questions --}}
            <template x-if="selectedJob">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-slate-900">Interview Questions</h3>
                        <div class="flex items-center gap-2">
                            <select x-model="questionType" class="form-select text-xs py-1 px-2 w-auto">
                                <option value="general">General</option>
                                <option value="technical">Technical</option>
                                <option value="hr">HR</option>
                                <option value="managerial">Managerial</option>
                            </select>
                            <button @click="loadQuestions(selectedJob, questionType)" class="btn-primary text-xs px-3 py-1">
                                Generate
                            </button>
                        </div>
                    </div>
                    <template x-if="loading.questions">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Generating questions...
                        </div>
                    </template>
                    <template x-if="questions && !loading.questions">
                        <div class="space-y-3">
                            <template x-for="(q, i) in (questions.data.questions || [])" :key="i">
                                <div class="border border-slate-200 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="badge-neutral" x-text="q.category"></span>
                                        <span :class="q.difficulty === 'hard' ? 'badge-danger' : q.difficulty === 'medium' ? 'badge-warning' : 'badge-success'"
                                              x-text="q.difficulty"></span>
                                        <span class="text-xs text-slate-400" x-text="q.suggested_duration_minutes + ' min'"></span>
                                    </div>
                                    <p class="text-sm text-slate-800 font-medium" x-text="q.question"></p>
                                    <p class="text-xs text-slate-500 mt-1" x-text="'Focus: ' + q.focus_area"></p>
                                </div>
                            </template>
                            <template x-if="questions.data.tips">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                    <p class="text-xs font-semibold text-amber-800 mb-1">Tips</p>
                                    <ul class="space-y-0.5">
                                        <template x-for="(tip, i) in questions.data.tips" :key="i">
                                            <li class="text-xs text-amber-700" x-text="'• ' + tip"></li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>
