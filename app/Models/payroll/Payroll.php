<?php

namespace App\Models\payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $table = 'payroll';
    protected $primaryKey = 'payroll_id';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'co_id',
        'pay_period_start',
        'pay_period_end',
        'basic_salary',
        'overtime_hours',
        'overtime_pay',
        'bonuses',
        'deductions',
        'net_pay',
        'status'
    ];
}
