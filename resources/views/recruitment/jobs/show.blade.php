@extends('layouts.admin')

@section('title', $job->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('recruitment.jobs.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Back to Jobs</a>
    </div>

    <div class="content-card mb-6">
        <div class="content-card-header">
            <div>
                <h2>{{ $job->title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $job->department ?? 'No Department' }} &middot; {{ $job->location ?? 'No Location' }} &middot; {{ ucfirst($job->employment_type ?? 'N/A') }}</p>
            </div>
            <span class="badge-{{ $job->status === 'published' ? 'success' : ($job->status === 'draft' ? 'neutral' : 'warning') }}">{{ ucfirst($job->status) }}</span>
        </div>
        <div class="content-card-body">
            <div class="flex items-center gap-4 text-sm text-slate-600">
                <span>Vacancies: <strong>{{ $job->vacancies }}</strong></span>
                @if ($job->salary_min)
                    <span>Salary: <strong>{{ $job->currency }} {{ number_format($job->salary_min, 0) }} - {{ number_format($job->salary_max, 0) }}</strong></span>
                @endif
                @if ($job->closing_at)
                    <span>Closes: <strong>{{ $job->closing_at->format('M d, Y') }}</strong></span>
                @endif
                <a href="{{ route('recruitment.jobs.edit', $job) }}" class="ml-auto btn-secondary text-xs">Edit</a>
            </div>
        </div>
    </div>

    <div x-data="{ activeTab: 'details' }">
        <div class="tab-bar">
            <button @click="activeTab = 'details'" :class="{ 'active': activeTab === 'details' }" class="tab-item">Details</button>
            <button @click="activeTab = 'pipeline'" :class="{ 'active': activeTab === 'pipeline' }" class="tab-item">Pipeline</button>
            <button @click="activeTab = 'ranking'" :class="{ 'active': activeTab === 'ranking' }" class="tab-item flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                AI Ranking
            </button>
        </div>

        <div x-show="activeTab === 'details'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="content-card">
                    <div class="content-card-header"><h2>Description</h2></div>
                    <div class="content-card-body">
                        <p class="text-sm text-slate-600 whitespace-pre-wrap">{{ $job->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
                <div class="content-card">
                    <div class="content-card-header"><h2>Requirements</h2></div>
                    <div class="content-card-body">
                        <p class="text-sm text-slate-600 whitespace-pre-wrap">{{ $job->requirements ?? 'No requirements listed.' }}</p>
                    </div>
                </div>
                <div class="content-card">
                    <div class="content-card-header"><h2>Responsibilities</h2></div>
                    <div class="content-card-body">
                        <p class="text-sm text-slate-600 whitespace-pre-wrap">{{ $job->responsibilities ?? 'No responsibilities listed.' }}</p>
                    </div>
                </div>
                <div class="content-card">
                    <div class="content-card-header"><h2>Candidates in Pipeline</h2></div>
                    <div class="content-card-body">
                        @if ($candidates->count() > 0)
                            <div class="space-y-2">
                                @foreach ($candidates as $candidate)
                                    @php $stage = $candidate->pipelineStages->first(); @endphp
                                    <div class="flex items-center justify-between p-2 border border-slate-200 rounded-lg">
                                        <a href="{{ route('recruitment.candidates.show', $candidate) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">{{ $candidate->full_name }}</a>
                                        <span class="badge-{{ $stage?->stage === 'hired' ? 'success' : ($stage?->stage === 'rejected' ? 'danger' : 'neutral') }}">
                                            {{ $stage ? ucfirst($stage->stage) : 'New' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-sm text-slate-500">No candidates in pipeline yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'pipeline'" x-cloak>
            <div class="content-card">
                <div class="content-card-header"><h2>Pipeline Stages</h2></div>
                <div class="content-card-body">
                    <div class="space-y-4">
                        @php
                            $stages = ['sourced', 'applied', 'screening', 'interview', 'offer', 'hired'];
                            $labels = ['Sourced', 'Applied', 'Screening', 'Interview', 'Offer', 'Hired'];
                            $stageCounts = [];
                            foreach ($stages as $s) {
                                $stageCounts[$s] = $candidates->filter(fn($c) => $c->pipelineStages->first()?->stage === $s)->count();
                            }
                            $maxStage = max($stageCounts) ?: 1;
                        @endphp
                        @foreach ($stages as $i => $stage)
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span class="font-medium text-slate-700">{{ $labels[$i] }}</span>
                                    <span class="text-sm font-semibold text-slate-900">{{ $stageCounts[$stage] }}</span>
                                </div>
                                <div class="pipeline-bar">
                                    <div class="pipeline-bar-fill {{ ['bg-slate-500', 'bg-blue-500', 'bg-amber-500', 'bg-indigo-500', 'bg-emerald-500', 'bg-emerald-600'][$i] }}"
                                         style="width: {{ ($stageCounts[$stage] / $maxStage) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'ranking'" x-cloak
             x-data="{
                ranking: null,
                loading: false,
                async load() {
                    this.loading = true;
                    const res = await fetch('{{ route('ai.ranking', $job) }}');
                    this.ranking = await res.json();
                    this.loading = false;
                }
             }"
             x-init="load()">
            <div class="content-card">
                <div class="content-card-header">
                    <h2>AI Candidate Ranking</h2>
                    <div class="flex items-center gap-2">
                        <span x-show="ranking" class="text-xs text-slate-500" x-text="'Avg: ' + Math.round(ranking.data.average_score || 0) + '%'"></span>
                        <button @click="load()" class="btn-secondary text-xs">Refresh</button>
                    </div>
                </div>
                <div class="content-card-body">
                    <template x-if="loading">
                        <div class="flex items-center justify-center gap-2 py-8 text-sm text-slate-500">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Analyzing candidates...
                        </div>
                    </template>
                    <template x-if="ranking && !loading">
                        <div>
                            <template x-if="(ranking.data.rankings || []).length === 0">
                                <div class="text-center py-8 text-sm text-slate-500">No candidates in pipeline for ranking.</div>
                            </template>
                            <template x-if="(ranking.data.rankings || []).length > 0">
                                <div class="space-y-3">
                                    <template x-for="(item, i) in ranking.data.rankings" :key="item.candidate_id">
                                        <div class="flex items-center gap-4 p-4 border border-slate-100 rounded-lg hover:bg-slate-50 transition-colors">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold shrink-0"
                                                 :class="i === 0 ? 'bg-amber-100 text-amber-700' : i === 1 ? 'bg-slate-100 text-slate-600' : i === 2 ? 'bg-amber-50 text-amber-600' : 'bg-slate-50 text-slate-500'"
                                                 x-text="'#' + (i + 1)"></div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-900 truncate" x-text="item.candidate_name"></p>
                                                <p class="text-xs text-slate-500 truncate" x-text="item.summary"></p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="flex items-center gap-1">
                                                    <span class="text-lg font-bold" :class="item.score >= 80 ? 'text-emerald-600' : item.score >= 60 ? 'text-indigo-600' : 'text-rose-600'" x-text="Math.round(item.score) + '%'"></span>
                                                </div>
                                                <div class="flex gap-1 mt-1">
                                                    <template x-for="skill in (item.matched_skills || []).slice(0, 2)" :key="skill">
                                                        <span class="badge-success text-[10px]" x-text="skill"></span>
                                                    </template>
                                                    <template x-if="(item.matched_skills || []).length > 2">
                                                        <span class="text-[10px] text-slate-400" x-text="'+' + ((item.matched_skills || []).length - 2)"></span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
@endsection
