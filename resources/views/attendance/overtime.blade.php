@extends('layouts.admin')

@section('title', 'Overtime')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <h2>Overtime Records</h2>
        </div>
        <div class="content-card-body">
            <form method="GET" class="flex flex-wrap gap-3 mb-4">
                <select name="employee_id" class="form-select text-sm w-48">
                    <option value="">All Employees</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                    @endforeach
                </select>
                <select name="is_approved" class="form-select text-sm w-32">
                    <option value="">All</option>
                    <option value="1" {{ request('is_approved') === '1' ? 'selected' : '' }}>Approved</option>
                    <option value="0" {{ request('is_approved') === '0' ? 'selected' : '' }}>Pending</option>
                </select>
                <button type="submit" class="btn-primary text-xs">Filter</button>
                <a href="{{ route('attendance.overtime') }}" class="btn-secondary text-xs">Clear</a>
            </form>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Hours</th>
                            <th>Type</th>
                            <th>Rate</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $ot)
                            <tr>
                                <td class="font-medium">{{ $ot->employee?->full_name }}</td>
                                <td>{{ $ot->date->format('M d, Y') }}</td>
                                <td><strong>{{ $ot->overtime_hours }}h</strong></td>
                                <td><span class="badge-neutral capitalize">{{ $ot->overtime_type }}</span></td>
                                <td>{{ $ot->rate_multiplier }}x</td>
                                <td>
                                    @if ($ot->is_approved)
                                        <span class="badge-success">Approved</span>
                                    @else
                                        <span class="badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!$ot->is_approved)
                                        <form method="POST" action="{{ route('attendance.overtime.approve', $ot) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-success text-xs px-2 py-1">Approve</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-slate-500 py-8">No overtime records.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $records->links() }}</div>
        </div>
    </div>
@endsection
