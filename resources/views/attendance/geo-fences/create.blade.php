@extends('layouts.admin')

@section('title', 'Create Geo-Fence')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <h2>Create Geo-Fence</h2>
            <a href="{{ route('geo-fences.index') }}" class="btn-secondary text-xs">Back</a>
        </div>
        <div class="content-card-body">
            <form method="POST" action="{{ route('geo-fences.store') }}" class="max-w-lg space-y-4">
                @csrf

                <div>
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input w-full" required>
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Latitude</label>
                        <input type="text" step="any" name="latitude" value="{{ old('latitude') }}" class="form-input w-full" required placeholder="e.g. 40.7128">
                        @error('latitude') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Longitude</label>
                        <input type="text" step="any" name="longitude" value="{{ old('longitude') }}" class="form-input w-full" required placeholder="e.g. -74.0060">
                        @error('longitude') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Radius (meters)</label>
                    <input type="number" name="radius_meters" value="{{ old('radius_meters', 100) }}" class="form-input w-full" required min="10" max="10000">
                    @error('radius_meters') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Address (optional)</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="form-input w-full" placeholder="Office address">
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary">Create Geo-Fence</button>
                </div>
            </form>
        </div>
    </div>
@endsection
