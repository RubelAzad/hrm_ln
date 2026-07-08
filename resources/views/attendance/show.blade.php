@extends('layouts.admin')

@section('title', 'Attendance Detail')

@section('content')
    <div class="mb-6">
        <a href="{{ route('attendance.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 content-card">
            <div class="content-card-header"><h2>Record Details</h2></div>
            <div class="content-card-body">
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-slate-500">Employee</dt><dd class="font-medium text-slate-900">{{ $record->employee?->full_name }}</dd></div>
                    <div><dt class="text-slate-500">Date</dt><dd class="font-medium text-slate-900">{{ $record->date->format('l, M d, Y') }}</dd></div>
                    <div><dt class="text-slate-500">Check In</dt><dd class="font-medium text-slate-900">{{ $record->check_in?->format('h:i:s A') ?? '--' }}</dd></div>
                    <div><dt class="text-slate-500">Check Out</dt><dd class="font-medium text-slate-900">{{ $record->check_out?->format('h:i:s A') ?? '--' }}</dd></div>
                    <div><dt class="text-slate-500">Status</dt><dd><span class="badge-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning' : 'danger') }}">{{ ucfirst($record->status) }}</span></dd></div>
                    <div><dt class="text-slate-500">Method</dt><dd><span class="badge-neutral">{{ ucfirst($record->method) }}</span></dd></div>
                    <div><dt class="text-slate-500">Shift</dt><dd class="font-medium text-slate-900">{{ $record->shift?->name ?? '--' }}</dd></div>
                    <div><dt class="text-slate-500">Location</dt><dd class="font-medium text-slate-900">{{ $record->location_name ?? ($record->latitude ? "$record->latitude, $record->longitude" : '--') }}</dd></div>
                    <div><dt class="text-slate-500">IP Address</dt><dd class="font-medium text-slate-900">{{ $record->ip_address ?? '--' }}</dd></div>
                    <div><dt class="text-slate-500">Verified By</dt><dd class="font-medium text-slate-900">{{ $record->verifiedBy?->name ?? '--' }}</dd></div>
                </dl>
                @if ($record->notes)
                    <div class="mt-4 p-3 bg-slate-50 rounded-lg"><p class="text-sm text-slate-700">{{ $record->notes }}</p></div>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="content-card">
                <div class="content-card-header"><h2>Breaks</h2></div>
                <div class="content-card-body">
                    @forelse ($record->breaks as $break)
                        <div class="flex items-center justify-between py-1.5 text-sm border-b border-slate-100 last:border-0">
                            <span class="capitalize text-slate-700">{{ $break->type }}</span>
                            <span class="text-slate-500">{{ $break->break_start->format('h:i A') }} - {{ $break->break_end?->format('h:i A') ?? 'ongoing' }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No breaks recorded.</p>
                    @endforelse
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header"><h2>Quick Actions</h2></div>
                <div class="content-card-body space-y-2">
                    <form method="POST" action="{{ route('attendance.check-out', $record->employee) }}">
                        @csrf
                        <input type="hidden" name="method" value="manual">
                        <button type="submit" class="btn-secondary w-full text-sm">Check Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
