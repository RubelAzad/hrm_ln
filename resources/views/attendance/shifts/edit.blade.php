@extends('layouts.admin')

@section('title', 'Edit Shift')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <h2>Edit Shift: {{ $shift->name }}</h2>
            <a href="{{ route('shifts.index') }}" class="btn-secondary text-xs">Back</a>
        </div>
        <div class="content-card-body">
            <form method="POST" action="{{ route('shifts.update', $shift) }}" class="max-w-2xl space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label">Shift Name</label>
                    <input type="text" name="name" value="{{ old('name', $shift->name) }}" class="form-input w-full" required>
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" value="{{ old('start_time', $shift->start_time) }}" class="form-input w-full" required>
                        @error('start_time') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" value="{{ old('end_time', $shift->end_time) }}" class="form-input w-full" required>
                        @error('end_time') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Grace Period (min)</label>
                        <input type="number" name="grace_minutes" value="{{ old('grace_minutes', $shift->grace_minutes ?? 0) }}" class="form-input w-full" min="0" max="120">
                    </div>
                    <div>
                        <label class="form-label">Late Threshold (min)</label>
                        <input type="number" name="late_threshold_minutes" value="{{ old('late_threshold_minutes', $shift->late_threshold_minutes ?? 15) }}" class="form-input w-full" min="0" max="120">
                    </div>
                    <div>
                        <label class="form-label">Early Leave Threshold (min)</label>
                        <input type="number" name="early_leave_threshold_minutes" value="{{ old('early_leave_threshold_minutes', $shift->early_leave_threshold_minutes ?? 15) }}" class="form-input w-full" min="0" max="120">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Work Hours</label>
                        <input type="number" step="0.5" name="work_hours" value="{{ old('work_hours', $shift->work_hours) }}" class="form-input w-full" min="0" max="24">
                    </div>
                    <div>
                        <label class="form-label">Break Duration (min)</label>
                        <input type="number" name="break_duration_minutes" value="{{ old('break_duration_minutes', $shift->break_duration_minutes ?? 60) }}" class="form-input w-full" min="0" max="240">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="overtime_allowed" value="1" {{ old('overtime_allowed', $shift->overtime_allowed) ? 'checked' : '' }}>
                            <span class="form-label mb-0">Overtime Allowed</span>
                        </label>
                    </div>
                    <div>
                        <label class="form-label">Overtime Rate (x)</label>
                        <input type="number" step="0.1" name="overtime_rate" value="{{ old('overtime_rate', $shift->overtime_rate ?? 1.5) }}" class="form-input w-full" min="1" max="5">
                    </div>
                </div>

                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input w-full">{{ old('description', $shift->description) }}</textarea>
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $shift->is_active) ? 'checked' : '' }}>
                        <span class="form-label mb-0">Active</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary">Update Shift</button>
                </div>
            </form>
        </div>
    </div>
@endsection
