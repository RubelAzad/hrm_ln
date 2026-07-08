@extends('layouts.admin')

@section('title', 'Shifts')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <h2>Shift Management</h2>
            <a href="{{ route('shifts.create') }}" class="btn-primary text-xs">+ New Shift</a>
        </div>
        <div class="content-card-body">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Work Hours</th>
                            <th>Grace (min)</th>
                            <th>Overtime</th>
                            <th>Active</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shifts as $shift)
                            <tr>
                                <td class="font-medium">{{ $shift->name }}</td>
                                <td>{{ substr($shift->start_time, 0, 5) }}</td>
                                <td>{{ substr($shift->end_time, 0, 5) }}</td>
                                <td>{{ $shift->work_hours ?? '--' }}</td>
                                <td>{{ $shift->grace_minutes ?? 0 }}</td>
                                <td>
                                    <span class="badge-{{ $shift->overtime_allowed ? 'success' : 'neutral' }}">
                                        {{ $shift->overtime_allowed ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-{{ $shift->is_active ? 'success' : 'danger' }}">
                                        {{ $shift->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('shifts.show', $shift) }}" class="text-indigo-600 hover:text-indigo-800 text-sm mr-2">View</a>
                                    <a href="{{ route('shifts.edit', $shift) }}" class="text-indigo-600 hover:text-indigo-800 text-sm mr-2">Edit</a>
                                    <a href="{{ route('shifts.assign', $shift) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Assign</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-slate-500 py-8">No shifts defined.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $shifts->links() }}</div>
        </div>
    </div>
@endsection
