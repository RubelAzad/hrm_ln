@extends('layouts.admin')

@section('title', 'Check In')

@section('content')
    <div class="max-w-lg mx-auto">
        <div class="content-card">
            <div class="content-card-header">
                <h2>Check In / Check Out</h2>
            </div>
            <div class="content-card-body">
                <form method="POST" action="{{ route('attendance.check-in.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="form-label">Employee</label>
                        <select name="employee_id" required class="form-select">
                            <option value="">Select employee...</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Method</label>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                            @foreach (['qr' => 'QR Code', 'gps' => 'GPS', 'face' => 'Face', 'fingerprint' => 'Fingerprint', 'manual' => 'Manual'] as $val => $label)
                                <label class="flex items-center gap-2 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="method" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }} class="text-indigo-600">
                                    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Location Name (optional)</label>
                        <input type="text" name="location_name" class="form-input" placeholder="e.g. Main Office">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Latitude (optional)</label>
                            <input type="number" step="any" name="latitude" class="form-input" placeholder="40.7128" id="lat">
                        </div>
                        <div>
                            <label class="form-label">Longitude (optional)</label>
                            <input type="number" step="any" name="longitude" class="form-input" placeholder="-74.0060" id="lng">
                        </div>
                    </div>

                    <button type="button" onclick="getLocation()" class="btn-secondary text-sm w-full">Use My Location</button>

                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary flex-1">Check In</button>
                    </div>
                </form>

                <hr class="my-6 border-slate-200">

                <h3 class="text-sm font-semibold text-slate-900 mb-3">Quick Check Out</h3>
                <form method="POST" action="{{ route('attendance.check-in.store') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="method" value="manual">
                    <div>
                        <select name="employee_id" required class="form-select">
                            <option value="">Select employee...</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-secondary w-full">Check Out</button>
                </form>
            </div>
        </div>

        @if ($fences->count() > 0)
            <div class="content-card mt-4">
                <div class="content-card-header"><h2>Active Geo-Fences</h2></div>
                <div class="content-card-body">
                    <div class="space-y-2">
                        @foreach ($fences as $fence)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-700">{{ $fence->name }}</span>
                                <span class="text-slate-500">{{ $fence->radius_meters }}m radius</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
function getLocation() {
    if (!navigator.geolocation) { alert('Geolocation not supported.'); return; }
    navigator.geolocation.getCurrentPosition(
        (pos) => { document.getElementById('lat').value = pos.coords.latitude.toFixed(7); document.getElementById('lng').value = pos.coords.longitude.toFixed(7); },
        () => { alert('Could not get location. Enter manually.'); }
    );
}
</script>
@endpush
