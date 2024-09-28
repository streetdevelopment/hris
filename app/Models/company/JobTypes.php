<?php

namespace App\Models\company;

use stdClass;
use Carbon\Carbon;
use InvalidArgumentException;
use App\Models\company\Company;
use App\Models\company\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobTypes extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'jt';
    protected $primaryKey = 'jt_id';
    public $incrementing = true;
    protected $fillable = [
        'co_id',
        'jt_name',
        'fixed_schedule',
        'work_on_sat',
        'work_on_sun',
        'start_time',
        'end_time',
        'status',
        'pay_rate'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function totalWorkHours()
    {
        $start_time = Carbon::parse($this->start_time);
        $end_time = Carbon::parse($this->end_time);
        $workHours = $start_time->diffInHours($end_time);

        $lunchStart = Carbon::createFromTime(12, 0, 0);
        $lunchEnd = Carbon::createFromTime(13, 0, 0);

        if ($start_time->lt($lunchEnd) && $end_time->gt($lunchStart)) {
            $workHours -= 1;
        }

        return $workHours;
    }
    public function getStatus($type)
    {
        $result = new stdClass();
        switch ($type) {
            case 'fs':
                $status = $this->fixed_schedule;
                break;
            case 'sat':
                $status = $this->work_on_sat;
                break;
            case 'sun':
                $status = $this->work_on_sun;
                break;
            case 'status':
                $status = $this->status;
                break;
            default:
                throw new InvalidArgumentException('Invalid type provided');
        }
        if ($status === 1) {
            $result->fs = 'Yes';
            $result->badge = 'badge-soft-success';
        } else if ($status == 'Active') {
            $result->badge = 'bg-success';
        } else if ($status == 'Inactive') {
            $result->badge = 'bg-danger';
        } else if ($status == 'Pending') {
            $result->badge = 'bg-warning';
        } else {
            $result->fs = 'No';
            $result->badge = 'badge-soft-danger';
        }
        return $result;
    }
    public function position()
    {
        return $this->belongsToMany(Position::class);
    }
}
