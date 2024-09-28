<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use App\Models\payroll\Slip;
use App\Models\profiling\CI;
use App\Models\profiling\PD;
use App\Models\profiling\PI;
use App\Models\attendance\AR;
use App\Models\attendance\OT;
use App\Models\profiling\PSI;
use App\Models\company\Company;
use App\Models\attendance\Leave;
use App\Models\company\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'co_id',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'co_id');
    }
    public function fullname()
    {
        $target = DB::SELECT('SELECT * FROM emp_pi WHERE user_id = ?', [$this->id]);
        $full = $target[0]->first_name . ' ' . $target[0]->last_name;
        return $full;
    }
    public function userPI()
    {
        $target = $this->hasOne(PI::class);
        return $target;
    }
    public function userCI()
    {
        return $this->hasOne(CI::class);
    }
    public function userPSI()
    {
        return $this->hasOne(PSI::class);
    }
    public function userPD()
    {
        return $this->hasMany(PD::class);
    }
    public function getARs()
    {
        return $this->hasMany(AR::class, 'user_id');
    }
    public function getARbetween($start_date, $end_date)
    {
        $records = $this->getARs()
            ->whereBetween('date', [$start_date, $end_date])
            ->orderBy('date', 'asc') // Order by date in ascending order (oldest to latest)
            ->get();

        return $records;
    }

    public function leaveRequests()
    {
        return $this->hasMany(Leave::class, 'user_id');
    }
    public function leaveRequestsBetween($start_date, $end_date)
    {
        $records = $this->leaveRequests()
            ->whereBetween('start_date', [$start_date, $end_date])
            ->whereBetween('end_date', [$start_date, $end_date])
            ->where('status', 'Approved')
            ->get();
        return $records;
    }
    public function overtimeRequests()
    {
        return $this->hasMany(OT::class, 'user_id');
    }
    public function overtimeRequestsBetween($start_date, $end_date)
    {
        $records = $this->overtimeRequests()
            ->whereBetween('start', [$start_date, $end_date])
            ->whereBetween('end', [$start_date, $end_date])
            ->where('status', 'Approved')
            ->get();
        return $records;
    }
    public function assignedLeaveApplications()
    {
        return $this->hasMany(Leave::class, 'approver_id');
    }
    public function subAssignedLeaveApplications()
    {
        return $this->hasMany(Leave::class, 's_approver_id');
    }
    public function assignedOT()
    {
        return $this->hasMany(OT::class, 'approver_id');
    }
    public function subAssignedOT()
    {
        return $this->hasMany(OT::class, 's_approver_id');
    }
    public function payslips()
    {
        return $this->hasMany(Slip::class, 'user_id');
    }
    public function hasRecordToday()
    {
        $today = Carbon::today();

        return $this->getARs()
            ->whereDate('time_in', $today)
            ->exists();
    }
    public function latestRecordToday()
    {
        return $this->getARs()
            ->whereDate('time_in', Carbon::today())
            ->orderBy('time_in', 'desc')
            ->first();
    }
    public function getRecordsToday()
    {
        return $this->getARs()
            ->whereDate('time_in', Carbon::today())
            ->orderBy('time_in', 'desc')
            ->get();
    }
    public function hasTimedIn()
    {
        return $this->hasRecordToday() && $this->latestRecordToday()->time_out == null ? 1 : 0;
    }
    public function hasTimedOut()
    {
        return $this->hasRecordToday() && $this->latestRecordToday()->time_out !== null ? 1 : 0;
    }
    public function renderTime($mode)
    {
        if ($mode == 'in') {
            if ($this->hasTimedIn()) {
                return Carbon::parse($this->latestRecordToday()->time_in)->format('g:i A');
            } else {
                if ($this->getRecordsToday()->count() > 0) {
                    return Carbon::parse($this->latestRecordToday()->time_in)->format('g:i A');
                } else {
                    return '--:--';
                }
            }
        } else {
            if ($this->getRecordsToday()->count() > 0) {
                if ($this->getRecordsToday()->count() > 1) {
                    return Carbon::parse($this->latestRecordToday()->time_out)->format('g:i A');
                } else {
                    if ($this->latestRecordToday()->time_out !== null) {
                        return Carbon::parse($this->latestRecordToday()->time_out)->format('g:i A');
                    } else {
                        return '--:--';
                    }
                }
            } else {
                return '--:--';
            }
        }
    }
    function getHourlyRate($salary = false)
    {
        $jt = $this->userPSI->position->JT;
        if ($jt->pay_rate == 'Hourly') {
            return $this->userPSI->position->salary;
        } else {
            $dailyWorkHours = $this->userPSI->position->jt->totalWorkHours();
            $weeklyWorkHours = $dailyWorkHours * 5;
            if ($salary) {
                $montlyRate = $salary;
            } else {
                $montlyRate = $this->userPSI->position->salary;
            }
            if ($jt->pay_rate == 'Semi-Monthly') {
                $hourlyRate = $montlyRate / ($weeklyWorkHours * 2);
                return $hourlyRate;
            } else {
                $hourlyRate = $montlyRate / ($weeklyWorkHours * 4);
                return $hourlyRate;
            }
        }
    }
    function absenceReport($start, $end, $records, $leaveRecords)
    {
        // Convert start and end dates to Carbon instances
        $startDate = Carbon::createFromFormat('Y-m-d', $start);
        $endDate = Carbon::createFromFormat('Y-m-d', $end);

        // Initialize counters
        $totalWorkingDaysAbsent = 0;
        $workingDaysAbsentWithLeave = 0;
        $workingDaysAbsentWithoutLeave = 0;
        $totalLeaveDaysWithRecord = 0;

        // Convert records into an array of dates for quick lookup
        $recordDates = [];
        foreach ($records as $rec) {
            $recordDates[$rec->date] = $rec->status; // Assuming $rec->status is 'present' or 'leave'
        }

        // Convert leave records into an array of leave dates for quick lookup
        $leaveDates = [];
        foreach ($leaveRecords as $otRec) {
            $leaveStartDate = Carbon::createFromFormat('Y-m-d', $otRec->start_date);
            $leaveEndDate = Carbon::createFromFormat('Y-m-d', $otRec->end_date);
            for ($date = $leaveStartDate; $date->lte($leaveEndDate); $date->addDay()) {
                $leaveDates[$date->format('Y-m-d')] = true; // Store leave date in 'Y-m-d' format as the key
            }
        }

        // Iterate over each date in the range
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Check if the day is a weekday (Monday to Friday)
            if ($date->isWeekday()) {
                // Get the date string in 'Y-m-d' format
                $currentDate = $date->format('Y-m-d');

                // Check if there's no record for this date (meaning absent)
                if (!array_key_exists($currentDate, $recordDates)) {
                    $totalWorkingDaysAbsent++;

                    // Check if there is an approved leave record for this date
                    if (array_key_exists($currentDate, $leaveDates)) {
                        $workingDaysAbsentWithLeave++;
                    } else {
                        $workingDaysAbsentWithoutLeave++;
                    }
                }

                // Check if the person was recorded as present but also has a leave record
                if (array_key_exists($currentDate, $recordDates) && array_key_exists($currentDate, $leaveDates)) {
                    $totalLeaveDaysWithRecord++;
                }
            }
        }

        // Return the results as an associative array
        return [
            'total_working_days_absent' => $totalWorkingDaysAbsent,
            'working_days_absent_with_leave' => $workingDaysAbsentWithLeave,
            'working_days_absent_without_leave' => $workingDaysAbsentWithoutLeave,
            'total_leave_days_with_record' => $totalLeaveDaysWithRecord, // New field added for leave/record overlap
        ];
    }
}
