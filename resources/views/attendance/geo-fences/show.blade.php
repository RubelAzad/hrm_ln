@extends('layouts.admin')

@section('title', $geoFence->name)

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <h2>{{ $geoFence->name }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('geo-fences.edit', $geoFence) }}" class="btn-primary text-xs">Edit</a>
                <form method="POST" action="{{ route('geo-fences.destroy', $geoFence) }}" onsubmit="return confirm('Delete this geo-fence?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger text-xs">Delete</button>
                </form>
                <a href="{{ route('geo-fences.index') }}" class="btn-secondary text-xs">Back</a>
            </div>
        </div>
        <div class="content-card-body">
            <div class="grid grid-cols-2 gap-4 max-w-lg">
                <div class="stat-card">
                    <p class="stat-label">Latitude</p>
                    <p class="stat-value">{{ number_format($geoFence->latitude, 6) }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Longitude</p>
                    <p class="stat-value">{{ number_format($geoFence->longitude, 6) }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Radius</p>
                    <p class="stat-value">{{ $geoFence->radius_meters }} meters</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Status</p>
                    <p class="stat-value">
                        <span class="badge-{{ $geoFence->is_active ? 'success' : 'neutral' }}">
                            {{ $geoFence->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
            @if ($geoFence->address)
                <div class="mt-4 p-3 bg-slate-50 rounded text-sm text-slate-600">
                    <strong>Address:</strong> {{ $geoFence->address }}
                </div>
            @endif
        </div>
    </div>
@endsection
