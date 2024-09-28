<?php

namespace App\Models\company;

use App\Models\company\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Policies extends Model
{
    use HasFactory;
    protected $table = 'attendance_policies';
    protected $primaryKey = 'att_pol_id';
    public $incrementing = true;
    protected $fillable = [
        'co_id',
        'grace_period',
        'enable_auto_time_out',
        'enable_camera',
        'enable_gps',
        'yearly_leave_credit',
        'enable_overtime',
        'enable_time_correction_request',
        'late_deduction'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'co_id');
    }
}
