@extends('layouts.admin')

@section('title', $employee->full_name . ' - Attendance')

@section('content')
    <div class="mb-6">
        <a href="{{ route('attendance.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Back</a>
    </div>

    <div class="content-card mb-6">
        <div class="content-card-header">
            <h2>{{ $employee->full_name }} <span class="text-sm text-slate-500 font-normal">({{ $employee->employee_id }})</span></h2>
            <form method="GET" class="flex gap-2">
                <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" class="form-input text-sm w-44"
                       onchange="this.form.submit()">
            </form>
        </div>
        <div class="content-card-body">
            <div class="grid grid-cols-5 gap-4">
                <div class="text-center p-3 bg-slate-50 rounded-lg"><p class="text-xs text-slate-500">Total Days</p><p class="text-xl font-bold text-slate-900">{{ $summary['total_days'] }}</p></div>
                <div class="text-center p-3 bg-emerald-50 rounded-lg"><p class="text-xs text-emerald-600">Present</p><p class="text-xl font-bold text-emerald-700">{{ $summary['present'] }}</p></div>
                <div class="text-center p-3 bg-amber-50 rounded-lg"><p class="text-xs text-amber-600">Late</p><p class="text-xl font-bold text-amber-700">{{ $summary['late'] }}</p></div>
                <div class="text-center p-3 bg-rose-50 rounded-lg"><p class="text-xs text-rose-600">Absent</p><p class="text-xl font-bold text-rose-700">{{ $summary['absent'] }}</p></div>
                <div class="text-center p-3 bg-indigo-50 rounded-lg"><p class="text-xs text-indigo-600">Rate</p><p class="text-xl font-bold text-indigo-700">{{ $summary['attendance_rate'] }}%</p></div>
            </div>
            <div class="flex gap-4 mt-3 text-sm text-slate-600">
                <span>Total Hours: <strong>{{ $summary['total_hours'] }}h</strong></span>
                <span>Overtime: <strong>{{ $summary['overtime_hours'] }}h</strong></span>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header"><h2>Daily Log</h2></div>
        <div class="content-card-body">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Shift</th>
                            <th>Status</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td>{{ $record->date->format('M d, Y') }}</td>
                                <td>{{ $record->date->format('D') }}</td>
                                <td>{{ $record->check_in?->format('h:i A') ?? '--' }}</td>
                                <td>{{ $record->check_out?->format('h:i A') ?? '--' }}</td>
                                <td class="text-xs">{{ $record->shift?->name ?? '--' }}</td>
                                <td><span class="badge-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning' : 'danger') }}">{{ ucfirst($record->status) }}</span></td>
                                <td><span class="badge-neutral">{{ ucfirst($record->method) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-slate-500 py-8">No records for this month.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $records->links() }}</div>
        </div>
    </div>
@endsection
