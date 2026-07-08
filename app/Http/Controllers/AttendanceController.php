<?php

namespace App\Http\Controllers;

use App\Models\AttendanceAnomaly;
use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\GeoFence;
use App\Models\OvertimeRecord;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService
    ) {}

    public function index(Request $request): View
    {
        $query = AttendanceRecord::with(['employee', 'shift', 'breaks']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $records = $query->latest('date')->latest('check_in')->paginate(20);
        $employees = Employee::all();

        return view('attendance.index', compact('records', 'employees'));
    }

    public function checkInView(): View
    {
        $employees = Employee::all();
        $fences = GeoFence::where('is_active', true)->get();
        return view('attendance.check-in', compact('employees', 'fences'));
    }

    public function storeCheckIn(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'method' => 'required|in:qr,gps,face,fingerprint,manual',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_name' => 'nullable|string|max:255',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        if ($request->filled(['latitude', 'longitude'])) {
            $check = $this->attendanceService->validateGeoFence(
                $employee, $request->latitude, $request->longitude
            );
            if (!$check['valid']) {
                return back()->with('error', 'Check-in location not within allowed geo-fence area.');
            }
        }

        $this->attendanceService->checkIn($employee, $validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Check-in recorded successfully.');
    }

    public function storeCheckOut(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'method' => 'nullable|in:qr,gps,face,fingerprint,manual',
            'notes' => 'nullable|string|max:500',
        ]);

        $result = $this->attendanceService->checkOut($employee, $validated);

        if (!$result) {
            return back()->with('error', 'No check-in found for today.');
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Check-out recorded successfully.');
    }

    public function show(AttendanceRecord $record): View
    {
        $record->load(['employee', 'shift', 'breaks', 'verifiedBy']);
        return view('attendance.show', compact('record'));
    }

    public function employeeLog(Employee $employee, Request $request): View
    {
        $query = AttendanceRecord::with('shift', 'breaks')
            ->where('employee_id', $employee->id);

        if ($request->filled('month')) {
            $query->whereYear('date', substr($request->month, 0, 4))
                  ->whereMonth('date', substr($request->month, 5, 2));
        }

        $records = $query->latest('date')->paginate(31);
        $summary = $this->attendanceService->getAttendanceSummary(
            $employee, $request->month ?? now()->format('Y-m')
        );

        return view('attendance.employee-log', compact('employee', 'records', 'summary'));
    }

    public function overtime(Request $request): View
    {
        $query = OvertimeRecord::with(['employee', 'shift', 'approvedBy']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('is_approved')) {
            $query->where('is_approved', $request->boolean('is_approved'));
        }

        $records = $query->latest('date')->paginate(20);
        $employees = Employee::all();

        return view('attendance.overtime', compact('records', 'employees'));
    }

    public function approveOvertime(OvertimeRecord $overtime): RedirectResponse
    {
        $overtime->update([
            'is_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Overtime approved.');
    }

    public function anomalies(Request $request): View
    {
        $query = AttendanceAnomaly::with(['employee', 'attendanceRecord', 'resolvedBy']);

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('is_resolved')) {
            $query->where('is_resolved', $request->boolean('is_resolved'));
        }

        $anomalies = $query->latest()->paginate(20);

        return view('attendance.anomalies', compact('anomalies'));
    }

    public function resolveAnomaly(AttendanceAnomaly $anomaly): RedirectResponse
    {
        $anomaly->update([
            'is_resolved' => true,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Anomaly resolved.');
    }

    public function predictAttendance(Employee $employee): View
    {
        $predictions = $this->attendanceService->predictAttendance($employee);
        return view('attendance.predictions', compact('employee', 'predictions'));
    }

    public function missingPunchSuggestions(): View
    {
        $employees = Employee::all();
        $suggestions = collect();

        foreach ($employees as $employee) {
            $employeeSuggestions = $this->attendanceService->missingPunchSuggestions($employee);
            if ($employeeSuggestions->isNotEmpty()) {
                $suggestions->push([
                    'employee' => $employee,
                    'missing' => $employeeSuggestions,
                ]);
            }
        }

        return view('attendance.missing-punches', compact('suggestions'));
    }
}
