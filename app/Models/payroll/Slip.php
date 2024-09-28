<?php

namespace App\Models\payroll;

use App\Models\User;
use App\Models\company\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slip extends Model
{
    use HasFactory;
    protected $table = 'emp_payslip';
    protected $primaryKey = 'payslip_id';
    public $incrementing = true;
    protected $fillable = [
        'payroll_run_id',
        'user_id',
        'basic_salary',
        'overtime_hours',
        'deductions',
        'overtime_pay',
        'bonuses',
        'net_pay',
        'status',
        'jt_id'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'co_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function run()
    {
        return $this->belongsTo(Run::class, 'payroll_run_id');
    }
    public function badge()
    {
        return $this->status == 'Draft' ? 'bg-primary' : ($this->status == 'Pending' ? 'bg-warning' : ($this->status == 'Issued' ? 'bg-success' : 'bg-danger'));
    }
}
