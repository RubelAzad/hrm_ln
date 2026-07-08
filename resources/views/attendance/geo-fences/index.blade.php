@extends('layouts.admin')

@section('title', 'Geo-Fences')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <h2>Geo-Fence Management</h2>
            <a href="{{ route('geo-fences.create') }}" class="btn-primary text-xs">+ New Geo-Fence</a>
        </div>
        <div class="content-card-body">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Radius</th>
                            <th>Address</th>
                            <th>Active</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fences as $fence)
                            <tr>
                                <td class="font-medium">{{ $fence->name }}</td>
                                <td class="text-sm">{{ number_format($fence->latitude, 6) }}</td>
                                <td class="text-sm">{{ number_format($fence->longitude, 6) }}</td>
                                <td class="text-sm">{{ $fence->radius_meters }}m</td>
                                <td class="text-xs text-slate-500 max-w-[200px] truncate">{{ $fence->address ?? '--' }}</td>
                                <td>
                                    <span class="badge-{{ $fence->is_active ? 'success' : 'neutral' }}">
                                        {{ $fence->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('geo-fences.show', $fence) }}" class="text-indigo-600 hover:text-indigo-800 text-sm mr-2">View</a>
                                    <a href="{{ route('geo-fences.edit', $fence) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-slate-500 py-8">No geo-fences defined.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $fences->links() }}</div>
        </div>
    </div>
@endsection
