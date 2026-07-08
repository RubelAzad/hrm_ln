@extends('layouts.admin')

@section('title', 'Assign Employees')

@section('content')
    <div class="content-card mb-6">
        <div class="content-card-header">
            <h2>Assign Employees to {{ $shift->name }}</h2>
            <a href="{{ route('shifts.show', $shift) }}" class="btn-secondary text-xs">Back</a>
        </div>
        <div class="content-card-body">
            <form method="POST" action="{{ route('shifts.assign.store', $shift) }}" class="max-w-lg space-y-4 mb-6">
                @csrf
                <div>
                    <label class="form-label">Employee</label>
                    <select name="employee_id" class="form-select w-full" required>
                        <option value="">Select Employee</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Effective From</label>
                        <input type="date" name="effective_from" value="{{ old('effective_from', now()->format('Y-m-d')) }}" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="form-label">Effective To</label>
                        <input type="date" name="effective_to" value="{{ old('effective_to') }}" class="form-input w-full">
                    </div>
                </div>
                <div>
                    <label class="form-label">Day of Week (optional)</label>
                    <select name="day_of_week" class="form-select w-full">
                        <option value="">All Days</option>
                        <option value="monday">Monday</option>
                        <option value="tuesday">Tuesday</option>
                        <option value="wednesday">Wednesday</option>
                        <option value="thursday">Thursday</option>
                        <option value="friday">Friday</option>
                        <option value="saturday">Saturday</option>
                        <option value="sunday">Sunday</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Assign</button>
            </form>

            <h3 class="text-sm font-semibold text-slate-700 mb-2">Current Assignments</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Effective From</th>
                            <th>Effective To</th>
                            <th>Day</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $assignment)
                            <tr>
                                <td>{{ $assignment->employee?->first_name }} {{ $assignment->employee?->last_name }}</td>
                                <td>{{ $assignment->effective_from->format('M d, Y') }}</td>
                                <td>{{ $assignment->effective_to?->format('M d, Y') ?? 'Indefinite' }}</td>
                                <td>{{ ucfirst($assignment->day_of_week ?? 'All') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('shifts.assign.remove', [$shift, $assignment]) }}" onsubmit="return confirm('Remove this assignment?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-slate-500 py-4">No assignments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
