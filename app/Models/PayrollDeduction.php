<?php

namespace App\Models;

use App\Models\payroll\Run;
use App\Models\company\Deductions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollDeduction extends Model
{
    use HasFactory;
    protected $table = 'emp_payslip_deduction';
    protected $primaryKey = 'payslip_ded_id';
    public $incrementing = true;
    protected $fillable = [
        'payroll_run_id',
        'ded_id'
    ];
    public function payroll_run()
    {
        return $this->belongsTo(Run::class, 'payroll_run_id');
    }
    public function deduction()
    {
        return $this->belongsTo(Deductions::class, 'ded_id');
    }
}
