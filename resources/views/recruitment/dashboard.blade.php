@extends('layouts.admin')

@section('title', 'Recruitment Dashboard')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Total Jobs</p>
                    <p class="stat-value">{{ $jobCount }}</p>
                </div>
                <div class="stat-icon bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Active Jobs</p>
                    <p class="stat-value text-emerald-600">{{ $activeJobs }}</p>
                </div>
                <div class="stat-icon bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Total Candidates</p>
                    <p class="stat-value">{{ $candidateCount }}</p>
                </div>
                <div class="stat-icon bg-sky-50 text-sky-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Active Candidates</p>
                    <p class="stat-value text-indigo-600">{{ $activeCandidates }}</p>
                </div>
                <div class="stat-icon bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Pipeline & Interviews --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="content-card">
            <div class="content-card-header">
                <h2>Pipeline Overview</h2>
                <span class="badge-info">{{ array_sum(array_map(fn($s) => $pipelineStats[$s] ?? 0, ['sourced', 'applied', 'screening', 'interview', 'offer', 'hired'])) }} total</span>
            </div>
            <div class="content-card-body">
                @php
                    $stages = ['sourced', 'applied', 'screening', 'interview', 'offer', 'hired'];
                    $colors = ['bg-slate-500', 'bg-blue-500', 'bg-amber-500', 'bg-indigo-500', 'bg-emerald-500', 'bg-emerald-600'];
                    $labels = ['Sourced', 'Applied', 'Screening', 'Interview', 'Offer', 'Hired'];
                    $maxCount = max(array_map(fn($s) => $pipelineStats[$s] ?? 0, $stages)) ?: 1;
                @endphp
                <div class="space-y-4">
                    @foreach ($stages as $i => $stage)
                        @php $count = $pipelineStats[$stage] ?? 0; @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1.5">
                                <span class="font-medium text-slate-700">{{ $labels[$i] }}</span>
                                <span class="text-sm font-semibold text-slate-900">{{ $count }}</span>
                            </div>
                            <div class="pipeline-bar">
                                <div class="pipeline-bar-fill {{ $colors[$i] }}"
                                     style="width: {{ ($count / $maxCount) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h2>Upcoming Interviews</h2>
                <span class="badge-neutral">{{ $upcomingInterviews->count() }} scheduled</span>
            </div>
            <div class="content-card-body">
                @if ($upcomingInterviews->count() > 0)
                    <div class="space-y-3">
                        @foreach ($upcomingInterviews as $interview)
                            <div class="flex items-center gap-4 p-3 border border-slate-100 rounded-lg hover:bg-slate-50 transition-colors">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-sm font-semibold text-indigo-600 shrink-0">
                                    {{ substr($interview->candidate?->first_name ?? '?', 0, 1) }}{{ substr($interview->candidate?->last_name ?? '?', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $interview->candidate?->full_name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        {{ $interview->title }}
                                        <span class="mx-1">&middot;</span>
                                        <span class="capitalize">{{ $interview->interview_type }}</span>
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs font-semibold text-slate-900">{{ $interview->scheduled_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-slate-500">{{ $interview->scheduled_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-slate-500">No upcoming interviews scheduled.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
