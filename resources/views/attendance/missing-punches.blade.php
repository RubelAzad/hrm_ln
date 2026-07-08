@extends('layouts.admin')

@section('title', 'Missing Punches')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <div class="flex items-center gap-3">
                <h2>Missing Punch Suggestions</h2>
                <span class="badge-info">AI Powered</span>
            </div>
        </div>
        <div class="content-card-body">
            @forelse ($suggestions as $item)
                <div class="mb-4 last:mb-0">
                    <h3 class="text-sm font-semibold text-slate-900 mb-2">{{ $item['employee']->full_name }}</h3>
                    <div class="space-y-2">
                        @foreach ($item['missing'] as $miss)
                            <div class="flex items-center justify-between p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <span class="badge-warning">{{ $miss['date'] }}</span>
                                    <span class="text-sm text-slate-700">Missing: <strong>{{ str_replace('_', ' ', $miss['missing']) }}</strong></span>
                                </div>
                                <div class="text-right">
                                    @if ($miss['suggested_time'])
                                        <p class="text-xs text-slate-500">Suggested: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($miss['suggested_time'])->format('h:i A') }}</strong></p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-emerald-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-slate-500">No missing punches found. All records are complete.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
