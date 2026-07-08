@extends('layouts.admin')

@section('title', 'Edit Geo-Fence')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <h2>Edit Geo-Fence: {{ $geoFence->name }}</h2>
            <a href="{{ route('geo-fences.index') }}" class="btn-secondary text-xs">Back</a>
        </div>
        <div class="content-card-body">
            <form method="POST" action="{{ route('geo-fences.update', $geoFence) }}" class="max-w-lg space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $geoFence->name) }}" class="form-input w-full" required>
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Latitude</label>
                        <input type="text" step="any" name="latitude" value="{{ old('latitude', $geoFence->latitude) }}" class="form-input w-full" required>
                        @error('latitude') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Longitude</label>
                        <input type="text" step="any" name="longitude" value="{{ old('longitude', $geoFence->longitude) }}" class="form-input w-full" required>
                        @error('longitude') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Radius (meters)</label>
                    <input type="number" name="radius_meters" value="{{ old('radius_meters', $geoFence->radius_meters) }}" class="form-input w-full" required min="10" max="10000">
                    @error('radius_meters') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Address (optional)</label>
                    <input type="text" name="address" value="{{ old('address', $geoFence->address) }}" class="form-input w-full">
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $geoFence->is_active) ? 'checked' : '' }}>
                        <span class="form-label mb-0">Active</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary">Update Geo-Fence</button>
                </div>
            </form>
        </div>
    </div>
@endsection
