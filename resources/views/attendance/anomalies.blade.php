@extends('layouts.admin')

@section('title', 'Attendance Anomalies')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <div class="flex items-center gap-3">
                <h2>AI-Detected Anomalies</h2>
                <span class="badge-info">AI Powered</span>
            </div>
        </div>
        <div class="content-card-body">
            <form method="GET" class="flex flex-wrap gap-3 mb-4">
                <select name="severity" class="form-select text-sm w-32">
                    <option value="">All Severity</option>
                    <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Low</option>
                </select>
                <select name="is_resolved" class="form-select text-sm w-32">
                    <option value="">All</option>
                    <option value="0" {{ request('is_resolved') === '0' ? 'selected' : '' }}>Open</option>
                    <option value="1" {{ request('is_resolved') === '1' ? 'selected' : '' }}>Resolved</option>
                </select>
                <button type="submit" class="btn-primary text-xs">Filter</button>
                <a href="{{ route('attendance.anomalies') }}" class="btn-secondary text-xs">Clear</a>
            </form>

            <div class="space-y-3">
                @forelse ($anomalies as $anomaly)
                    <div class="border border-slate-200 rounded-lg p-4 {{ $anomaly->is_resolved ? 'opacity-60' : ($anomaly->severity === 'high' ? 'border-l-4 border-l-rose-500' : 'border-l-4 border-l-amber-500') }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="badge-{{ $anomaly->severity === 'high' ? 'danger' : 'warning' }}">{{ ucfirst($anomaly->severity) }}</span>
                                    <span class="badge-neutral">{{ str_replace('_', ' ', ucfirst($anomaly->anomaly_type)) }}</span>
                                    <span class="text-xs text-slate-500">{{ $anomaly->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-700">{{ $anomaly->description }}</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Employee: <strong>{{ $anomaly->employee?->full_name }}</strong>
                                    @if ($anomaly->attendanceRecord)
                                        &middot; {{ $anomaly->attendanceRecord->date->format('M d, Y') }}
                                    @endif
                                    &middot; Detected by: <span class="capitalize">{{ $anomaly->detected_by }}</span>
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0 ml-4">
                                @if (!$anomaly->is_resolved)
                                    <form method="POST" action="{{ route('attendance.anomalies.resolve', $anomaly) }}">
                                        @csrf
                                        <button type="submit" class="btn-success text-xs px-2 py-1">Resolve</button>
                                    </form>
                                @else
                                    <span class="badge-success">Resolved</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-sm text-slate-500">No anomalies detected.</div>
                @endforelse
            </div>
            <div class="mt-4">{{ $anomalies->links() }}</div>
        </div>
    </div>
@endsection
