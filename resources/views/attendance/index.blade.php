@extends('layouts.admin')

@section('title', 'Attendance')

@section('content')
    <div class="content-card mb-6">
        <div class="content-card-header">
            <h2>Attendance Records</h2>
            <a href="{{ route('attendance.check-in') }}" class="btn-primary text-xs">+ Check In</a>
        </div>
        <div class="content-card-body">
            <form method="GET" class="flex flex-wrap gap-3 mb-4">
                <select name="employee_id" class="form-select text-sm w-48">
                    <option value="">All Employees</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->first_name }} {{ $emp->last_name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="form-select text-sm w-32">
                    <option value="">All Status</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                    <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input text-sm w-40">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input text-sm w-40">
                <button type="submit" class="btn-primary text-xs">Filter</button>
                <a href="{{ route('attendance.index') }}" class="btn-secondary text-xs">Clear</a>
            </form>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td>
                                    <a href="{{ route('attendance.employee-log', $record->employee) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        {{ $record->employee?->first_name }} {{ $record->employee?->last_name }}
                                    </a>
                                </td>
                                <td>{{ $record->date->format('M d, Y') }}</td>
                                <td>{{ $record->check_in?->format('h:i A') ?? '--' }}</td>
                                <td>{{ $record->check_out?->format('h:i A') ?? '--' }}</td>
                                <td><span class="badge-neutral">{{ ucfirst($record->method) }}</span></td>
                                <td>
                                    <span class="badge-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                                <td class="text-xs text-slate-500 max-w-[150px] truncate">{{ $record->location_name ?? ($record->latitude ? 'GPS tracked' : '--') }}</td>
                                <td>
                                    <a href="{{ route('attendance.show', $record) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-slate-500 py-8">No attendance records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $records->links() }}</div>
        </div>
    </div>
@endsection
