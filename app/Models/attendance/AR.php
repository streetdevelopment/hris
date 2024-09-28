<?php

namespace App\Models\attendance;

use Carbon\Carbon;
use App\Models\User;
use App\Models\company\JobTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AR extends Model
{
    use HasFactory;
    protected $table = 'attendance_record';
    protected $primaryKey = 'att_rec_id';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_in_remark',
        'time_in_image',
        'time_in_coords',
        'time_out',
        'time_out_remark',
        'time_out_image',
        'time_in_coords',
        'worked_hours',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function workedHours($jt_id = false)
    {
        // Parse time_in and time_out as Carbon instances
        $timeIn = Carbon::parse($this->time_in);
        $timeOut = $this->time_out ? Carbon::parse($this->time_out) : null;

        // Define the start of the workday
        if ($jt_id) {
            $jt = JobTypes::find($jt_id);
            $start_time = $jt->start_time; // format is hh:mm:ss
        } else {
            $start_time = $this->user->userPSI->position->jt->start_time; // format is hh:mm:ss
        }
        list($start_hour, $start_minute, $start_second) = explode(':', $start_time);

        $lateGracePeriod = $this->user->company->policies->grace_period; // grace period in minutes (integer)

        $workStartTime = Carbon::create($timeIn->year, $timeIn->month, $timeIn->day, $start_hour, $start_minute, $start_second); // 09:00
        $comparisonTime = $workStartTime->copy();
        $comparisonTime->addMinutes($lateGracePeriod);

        // Adjust time_in if it's before 09:00
        if ($timeIn->lt($workStartTime)) {
            $timeIn->setTimeFrom($workStartTime);
        } elseif ($timeIn->gt($comparisonTime)) {
            // No adjustment needed if time_in is after 09:15
            $timeIn = $timeIn;
        } else {
            // Adjust time_in to 09:00 if it's between 09:00 and 09:15
            $timeIn->setTimeFrom($workStartTime);
        }

        // Define the end of the workday, matching the date of timeOut
        if ($timeOut) {
            if ($jt_id) {
                $jt = JobTypes::find($jt_id);
                $end_time = $jt->end_time;
            } else {
                $end_time = $this->user->userPSI->position->jt->end_time; // format is hh:mm:ss
            }
            list($end_hour, $end_minute, $end_second) = explode(':', $end_time);
            $workEndTime = Carbon::create($timeOut->year, $timeOut->month, $timeOut->day, $end_hour, $end_minute, $end_second);

            // Adjust time_out if it's after 17:00 (5:00 PM)
            if ($timeOut->gt($workEndTime)) {
                $timeOut = $workEndTime;
            }
        }

        // Define lunch break time
        $lunchStart = Carbon::create($timeIn->year, $timeIn->month, $timeIn->day, 12, 0, 0); // 12:00 PM
        $lunchEnd = Carbon::create($timeIn->year, $timeIn->month, $timeIn->day, 13, 0, 0); // 1:00 PM

        // Calculate the worked minutes
        $workedMinutes = $timeIn->diffInMinutes($timeOut);

        // Subtract lunch break time if it overlaps with working hours
        if ($timeIn->lt($lunchEnd) && $timeOut->gt($lunchStart)) {
            // Determine the overlap
            $lunchOverlapStart = $timeIn->lt($lunchStart) ? $lunchStart : $timeIn;
            $lunchOverlapEnd = $timeOut->gt($lunchEnd) ? $lunchEnd : $timeOut;
            $lunchMinutes = $lunchOverlapStart->diffInMinutes($lunchOverlapEnd);
            $workedMinutes -= $lunchMinutes;
        }

        return round($workedMinutes / 60, 2);
    }
}
