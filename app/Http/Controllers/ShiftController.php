<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\Shift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function index(): View
    {
        $shifts = Shift::latest()->paginate(15);
        return view('attendance.shifts.index', compact('shifts'));
    }

    public function create(): View
    {
        return view('attendance.shifts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'grace_minutes' => 'nullable|integer|min:0|max:120',
            'late_threshold_minutes' => 'nullable|integer|min:0|max:120',
            'early_leave_threshold_minutes' => 'nullable|integer|min:0|max:120',
            'break_duration_minutes' => 'nullable|integer|min:0|max:240',
            'work_hours' => 'nullable|numeric|min:0|max:24',
            'overtime_allowed' => 'boolean',
            'overtime_rate' => 'nullable|numeric|min:1|max:5',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        Shift::create($validated);

        return redirect()->route('shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    public function show(Shift $shift): View
    {
        $shift->load('employeeShifts.employee');
        return view('attendance.shifts.show', compact('shift'));
    }

    public function edit(Shift $shift): View
    {
        return view('attendance.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'grace_minutes' => 'nullable|integer|min:0|max:120',
            'late_threshold_minutes' => 'nullable|integer|min:0|max:120',
            'early_leave_threshold_minutes' => 'nullable|integer|min:0|max:120',
            'break_duration_minutes' => 'nullable|integer|min:0|max:240',
            'work_hours' => 'nullable|numeric|min:0|max:24',
            'overtime_allowed' => 'boolean',
            'overtime_rate' => 'nullable|numeric|min:1|max:5',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $shift->update($validated);

        return redirect()->route('shifts.index')
            ->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift): RedirectResponse
    {
        $shift->delete();
        return redirect()->route('shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }

    public function assignForm(Shift $shift): View
    {
        $employees = Employee::all();
        $assignments = $shift->employeeShifts;
        return view('attendance.shifts.assign', compact('shift', 'employees', 'assignments'));
    }

    public function assignStore(Request $request, Shift $shift): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'day_of_week' => 'nullable|string|max:20',
        ]);

        $shift->employeeShifts()->create($validated);

        return redirect()->route('shifts.assign', $shift)
            ->with('success', 'Employee assigned to shift.');
    }

    public function assignRemove(Shift $shift, EmployeeShift $assignment): RedirectResponse
    {
        $assignment->delete();
        return redirect()->route('shifts.assign', $shift)
            ->with('success', 'Assignment removed.');
    }
}
