@extends('layouts.admin')

@section('title', $shift->name)

@section('content')
    <div class="content-card mb-6">
        <div class="content-card-header">
            <h2>{{ $shift->name }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('shifts.edit', $shift) }}" class="btn-primary text-xs">Edit</a>
                <a href="{{ route('shifts.assign', $shift) }}" class="btn-secondary text-xs">Assign Employees</a>
                <a href="{{ route('shifts.index') }}" class="btn-secondary text-xs">Back</a>
            </div>
        </div>
        <div class="content-card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="stat-card">
                    <p class="stat-label">Start Time</p>
                    <p class="stat-value">{{ substr($shift->start_time, 0, 5) }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">End Time</p>
                    <p class="stat-value">{{ substr($shift->end_time, 0, 5) }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Work Hours</p>
                    <p class="stat-value">{{ $shift->work_hours ?? '--' }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Grace Period</p>
                    <p class="stat-value">{{ $shift->grace_minutes ?? 0 }} min</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="stat-card">
                    <p class="stat-label">Late Threshold</p>
                    <p class="stat-value">{{ $shift->late_threshold_minutes ?? 15 }} min</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Early Leave Threshold</p>
                    <p class="stat-value">{{ $shift->early_leave_threshold_minutes ?? 15 }} min</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Overtime</p>
                    <p class="stat-value">{{ $shift->overtime_allowed ? 'Yes (x'.$shift->overtime_rate.')' : 'No' }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Break Duration</p>
                    <p class="stat-value">{{ $shift->break_duration_minutes ?? 60 }} min</p>
                </div>
            </div>

            @if ($shift->description)
                <div class="mt-4 p-3 bg-slate-50 rounded text-sm text-slate-600">{{ $shift->description }}</div>
            @endif

            <div class="mt-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-2">Assigned Employees ({{ $shift->employeeShifts->count() }})</h3>
                @if ($shift->employeeShifts->isNotEmpty())
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Day of Week</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shift->employeeShifts as $assignment)
                                    <tr>
                                        <td>{{ $assignment->employee?->first_name }} {{ $assignment->employee?->last_name }}</td>
                                        <td>{{ $assignment->effective_from->format('M d, Y') }}</td>
                                        <td>{{ $assignment->effective_to?->format('M d, Y') ?? 'Indefinite' }}</td>
                                        <td>{{ $assignment->day_of_week ?? 'All' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-slate-500">No employees assigned to this shift.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
