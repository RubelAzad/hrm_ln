<?php

namespace App\Services;

use App\Models\AttendanceAnomaly;
use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\GeoFence;
use App\Models\OvertimeRecord;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceService
{
    public function checkIn(Employee $employee, array $data): AttendanceRecord
    {
        $shift = $this->getCurrentShift($employee);
        $now = now();

        $record = AttendanceRecord::updateOrCreate(
            ['employee_id' => $employee->id, 'date' => $now->toDateString()],
            [
                'shift_id' => $shift?->id,
                'check_in' => $now,
                'method' => $data['method'] ?? 'manual',
                'ip_address' => $data['ip_address'] ?? request()->ip(),
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'location_name' => $data['location_name'] ?? null,
                'photo_path' => $data['photo_path'] ?? null,
                'status' => $this->determineStatus($shift, $now),
            ]
        );

        $this->detectAnomalies($record);

        return $record;
    }

    public function checkOut(Employee $employee, array $data = []): ?AttendanceRecord
    {
        $today = now()->toDateString();
        $record = AttendanceRecord::where('employee_id', $employee->id)
            ->where('date', $today)
            ->whereNotNull('check_in')
            ->first();

        if (!$record) return null;

        $now = now();
        $record->update([
            'check_out' => $now,
            'notes' => $data['notes'] ?? $record->notes,
            'method' => $data['method'] ?? $record->method,
        ]);

        $this->calculateOvertime($record);
        $this->detectAnomalies($record);

        return $record;
    }

    public function getCurrentShift(Employee $employee): ?\App\Models\Shift
    {
        $today = now()->toDateString();
        $dayOfWeek = now()->format('l');

        $assignment = EmployeeShift::where('employee_id', $employee->id)
            ->where('effective_from', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('effective_to')->orWhere('effective_to', '>=', $today);
            })
            ->where(function ($q) use ($dayOfWeek) {
                $q->whereNull('day_of_week')->orWhere('day_of_week', $dayOfWeek);
            })
            ->latest('effective_from')
            ->first();

        return $assignment?->shift;
    }

    public function determineStatus(?\App\Models\Shift $shift, Carbon $checkIn): string
    {
        if (!$shift) return 'present';

        $shiftStart = Carbon::parse($shift->start_time);
        $lateThreshold = $shiftStart->copy()->addMinutes($shift->late_threshold_minutes);

        if ($checkIn->gt($lateThreshold)) return 'late';
        if ($checkIn->gt($shiftStart->copy()->addMinutes($shift->grace_minutes))) return 'late';

        return 'present';
    }

    public function getTodayStatus(Employee $employee): array
    {
        $today = now()->toDateString();
        $record = AttendanceRecord::with('shift', 'breaks')
            ->where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if (!$record) {
            return ['status' => 'absent', 'checked_in' => false, 'checked_out' => false, 'record' => null];
        }

        $workedMinutes = $this->calculateWorkedMinutes($record);
        $breakMinutes = $record->breaks->sum('duration_minutes');
        $netMinutes = max(0, $workedMinutes - $breakMinutes);

        return [
            'status' => $record->status,
            'checked_in' => !is_null($record->check_in),
            'checked_out' => !is_null($record->check_out),
            'check_in' => $record->check_in,
            'check_out' => $record->check_out,
            'worked_hours' => round($netMinutes / 60, 1),
            'break_minutes' => $breakMinutes,
            'record' => $record,
        ];
    }

    public function calculateOvertime(AttendanceRecord $record): ?OvertimeRecord
    {
        if (!$record->shift || !$record->shift->overtime_allowed) return null;
        if (!$record->check_in || !$record->check_out) return null;

        $workedMinutes = $this->calculateWorkedMinutes($record);
        $breakMinutes = $record->breaks->sum('duration_minutes');
        $netMinutes = max(0, $workedMinutes - $breakMinutes);
        $scheduledMinutes = $record->shift->work_hours * 60;

        $overtimeMinutes = max(0, $netMinutes - $scheduledMinutes);
        if ($overtimeMinutes < 30) return null;

        $overtimeHours = round($overtimeMinutes / 60, 2);
        $isWeekend = in_array($record->date->dayOfWeek, [0, 6]);

        return OvertimeRecord::updateOrCreate(
            ['employee_id' => $record->employee_id, 'date' => $record->date],
            [
                'shift_id' => $record->shift_id,
                'overtime_hours' => $overtimeHours,
                'overtime_type' => $isWeekend ? 'weekend' : 'weekday',
                'rate_multiplier' => $isWeekend ? 2.0 : ($record->shift->overtime_rate ?? 1.5),
            ]
        );
    }

    public function calculateWorkedMinutes(AttendanceRecord $record): int
    {
        if (!$record->check_in || !$record->check_out) return 0;
        return $record->check_in->diffInMinutes($record->check_out);
    }

    public function validateGeoFence(Employee $employee, float $lat, float $lng): array
    {
        $fences = GeoFence::where('is_active', true)->get();
        foreach ($fences as $fence) {
            if ($fence->isWithinFence($lat, $lng)) {
                return ['valid' => true, 'fence' => $fence];
            }
        }
        return ['valid' => false, 'fence' => null, 'message' => 'No active geo-fence covers this location.'];
    }

    public function detectAnomalies(AttendanceRecord $record): void
    {
        $anomalies = [];

        if ($record->check_in && $record->check_out) {
            $worked = $this->calculateWorkedMinutes($record);
            if ($worked < 60) {
                $anomalies[] = ['type' => 'very_short_shift', 'severity' => 'high',
                    'desc' => sprintf('Worked only %d minutes.', $worked)];
            }
            if ($worked > 720) {
                $anomalies[] = ['type' => 'excessive_hours', 'severity' => 'medium',
                    'desc' => sprintf('Worked %d minutes, exceeds 12 hours.', $worked)];
            }
        }

        if ($record->latitude && $record->longitude) {
            $fenceCheck = $this->validateGeoFence($record->employee, $record->latitude, $record->longitude);
            if (!$fenceCheck['valid']) {
                $anomalies[] = ['type' => 'outside_geo_fence', 'severity' => 'medium',
                    'desc' => 'Check-in location is outside all active geo-fences.'];
            }
        }

        $today = now()->toDateString();
        $existingCount = AttendanceRecord::where('employee_id', $record->employee_id)
            ->where('date', $today)
            ->where('id', '!=', $record->id)
            ->count();
        if ($existingCount > 0 || $record->wasRecentlyCreated === false) {
            $dupCheck = AttendanceRecord::where('employee_id', $record->employee_id)
                ->where('date', $today)
                ->where('check_in', $record->check_in)
                ->where('id', '!=', $record->id)
                ->exists();
            if ($dupCheck) {
                $anomalies[] = ['type' => 'duplicate_check_in', 'severity' => 'high',
                    'desc' => 'Multiple check-ins detected at same time.'];
            }
        }

        $previous = AttendanceRecord::where('employee_id', $record->employee_id)
            ->where('id', '<', $record->id)
            ->latest('id')
            ->first();
        if ($previous && $record->check_in && $previous->check_out) {
            $gap = $previous->check_out->diffInHours($record->check_in);
            if ($gap < 6) {
                $anomalies[] = ['type' => 'short_rest_period', 'severity' => 'medium',
                    'desc' => sprintf('Only %d hours since last check-out.', $gap)];
            }
        }

        foreach ($anomalies as $a) {
            AttendanceAnomaly::updateOrCreate(
                [
                    'attendance_record_id' => $record->id,
                    'anomaly_type' => $a['type'],
                ],
                [
                    'employee_id' => $record->employee_id,
                    'severity' => $a['severity'],
                    'description' => $a['desc'],
                    'detected_by' => 'ai',
                ]
            );
        }
    }

    public function getAttendanceSummary(Employee $employee, string $month): array
    {
        $records = AttendanceRecord::where('employee_id', $employee->id)
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->get();

        $totalDays = $records->count();
        $present = $records->whereIn('status', ['present', 'late'])->count();
        $late = $records->where('status', 'late')->count();
        $absent = $records->where('status', 'absent')->count();
        $totalWorkedMinutes = $records->sum(fn($r) => $this->calculateWorkedMinutes($r));
        $totalOvertime = OvertimeRecord::where('employee_id', $employee->id)
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->sum('overtime_hours');

        return [
            'total_days' => $totalDays,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'total_hours' => round($totalWorkedMinutes / 60, 1),
            'overtime_hours' => round($totalOvertime, 2),
            'attendance_rate' => $totalDays > 0 ? round(($present / $totalDays) * 100, 1) : 0,
        ];
    }

    public function predictAttendance(Employee $employee, int $daysAhead = 7): Collection
    {
        $history = AttendanceRecord::where('employee_id', $employee->id)
            ->where('date', '>=', now()->subMonth())
            ->get();

        $avgCheckIn = $history->filter(fn($r) => $r->check_in)
            ->avg(fn($r) => (int)$r->check_in->format('Hi'));
        $avgCheckOut = $history->filter(fn($r) => $r->check_out)
            ->avg(fn($r) => (int)$r->check_out->format('Hi'));
        $attendanceRate = $history->count() > 0
            ? $history->whereIn('status', ['present', 'late'])->count() / $history->count()
            : 0.85;

        $predictions = collect();
        for ($i = 1; $i <= $daysAhead; $i++) {
            $date = now()->addDays($i);
            $dayOfWeek = $date->format('l');
            $isWeekend = $date->isWeekend();
            $shift = $this->getCurrentShift($employee);

            $probability = $isWeekend ? min(0.1, $attendanceRate * 0.05) : $attendanceRate;
            $predictedCheckIn = $avgCheckIn > 0
                ? $date->format('Y-m-d') . ' ' . str_pad(floor($avgCheckIn / 100), 2, '0', STR_PAD_LEFT) . ':' . str_pad($avgCheckIn % 100, 2, '0', STR_PAD_LEFT)
                : ($shift ? $date->format('Y-m-d') . ' ' . $shift->start_time : $date->format('Y-m-d') . ' 09:00');

            $predictions->push([
                'date' => $date->format('Y-m-d'),
                'day' => $dayOfWeek,
                'is_weekend' => $isWeekend,
                'predicted_check_in' => $predictedCheckIn,
                'predicted_check_out' => $avgCheckOut > 0
                    ? $date->format('Y-m-d') . ' ' . str_pad(floor($avgCheckOut / 100), 2, '0', STR_PAD_LEFT) . ':' . str_pad($avgCheckOut % 100, 2, '0', STR_PAD_LEFT)
                    : ($shift ? $date->format('Y-m-d') . ' ' . $shift->end_time : $date->format('Y-m-d') . ' 18:00'),
                'attendance_probability' => round($probability * 100, 1),
                'shift_name' => $shift?->name ?? 'No shift',
            ]);
        }

        return $predictions;
    }

    public function missingPunchSuggestions(Employee $employee): Collection
    {
        $records = AttendanceRecord::where('employee_id', $employee->id)
            ->where(function ($q) {
                $q->whereNull('check_in')->orWhereNull('check_out');
            })
            ->where('date', '>=', now()->subWeek())
            ->orderBy('date', 'desc')
            ->get();

        return $records->map(fn($r) => [
            'date' => $r->date->format('Y-m-d'),
            'missing' => is_null($r->check_in) ? 'check_in' : 'check_out',
            'suggested_time' => $this->suggestMissingTime($r),
            'record_id' => $r->id,
        ]);
    }

    private function suggestMissingTime(AttendanceRecord $record): ?string
    {
        if (is_null($record->check_in) && $record->shift) {
            return $record->date->format('Y-m-d') . ' ' . $record->shift->start_time;
        }
        if (is_null($record->check_out) && $record->shift) {
            return $record->date->format('Y-m-d') . ' ' . $record->shift->end_time;
        }
        return null;
    }
}
