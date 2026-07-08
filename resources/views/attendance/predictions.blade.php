@extends('layouts.admin')

@section('title', 'Attendance Prediction')

@section('content')
    <div class="mb-6">
        <a href="{{ route('attendance.employee-log', $employee) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Back to {{ $employee->full_name }}</a>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <div class="flex items-center gap-3">
                <h2>AI Attendance Prediction — {{ $employee->full_name }}</h2>
                <span class="badge-info">AI Powered</span>
            </div>
            <span class="text-xs text-slate-500">Next 7 days</span>
        </div>
        <div class="content-card-body">
            <div class="space-y-3">
                @forelse ($predictions as $pred)
                    <div class="flex items-center gap-4 p-4 border border-slate-100 rounded-lg {{ $pred['is_weekend'] ? 'bg-slate-50' : '' }}">
                        <div class="w-12 text-center">
                            <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($pred['date'])->format('d') }}</p>
                            <p class="text-xs text-slate-500">{{ $pred['day'] }}</p>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-medium text-slate-900">{{ $pred['shift_name'] }}</span>
                                @if ($pred['is_weekend'])
                                    <span class="badge-warning">Weekend</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 text-xs text-slate-600">
                                <span>Expected in: <strong>{{ \Carbon\Carbon::parse($pred['predicted_check_in'])->format('h:i A') }}</strong></span>
                                <span>Expected out: <strong>{{ \Carbon\Carbon::parse($pred['predicted_check_out'])->format('h:i A') }}</strong></span>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="flex items-center gap-1">
                                <div class="w-16 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $pred['attendance_probability'] >= 80 ? 'bg-emerald-500' : ($pred['attendance_probability'] >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                         style="width: {{ $pred['attendance_probability'] }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-700">{{ $pred['attendance_probability'] }}%</span>
                            </div>
                            <p class="text-[10px] text-slate-400">probability</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-sm text-slate-500">No predictions available.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
