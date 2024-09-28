<?php

namespace App\Models\company;

use App\Models\PayrollDeduction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deductions extends Model
{
    use HasFactory;
    protected $table = 'deductions';
    protected $primaryKey = 'ded_id';
    public $incrementing = true;
    protected $fillable = [
        'co_id',
        'deduction_type',
        'description',
        'value',
        'unit'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'co_id');
    }
    public function payrollDeductions()
    {
        return $this->hasMany(PayrollDeduction::class, 'ded_id');
    }
}
