<?php

namespace App\Http\Controllers;

use App\Models\GeoFence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeoFenceController extends Controller
{
    public function index(): View
    {
        $fences = GeoFence::latest()->paginate(15);
        return view('attendance.geo-fences.index', compact('fences'));
    }

    public function create(): View
    {
        return view('attendance.geo-fences.create');
    }

    public function show(GeoFence $geoFence): View
    {
        return view('attendance.geo-fences.show', compact('geoFence'));
    }

    public function edit(GeoFence $geoFence): View
    {
        return view('attendance.geo-fences.edit', compact('geoFence'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:10|max:10000',
            'address' => 'nullable|string|max:500',
        ]);

        GeoFence::create(array_merge($validated, ['created_by' => auth()->id()]));

        return redirect()->route('geo-fences.index')
            ->with('success', 'Geo-fence created successfully.');
    }

    public function update(Request $request, GeoFence $geoFence): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:10|max:10000',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $geoFence->update($validated);

        return redirect()->route('geo-fences.index')
            ->with('success', 'Geo-fence updated successfully.');
    }

    public function destroy(GeoFence $geoFence): RedirectResponse
    {
        $geoFence->delete();
        return redirect()->route('geo-fences.index')
            ->with('success', 'Geo-fence deleted successfully.');
    }
}
