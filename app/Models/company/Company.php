<?php

namespace App\Models\company;

use stdClass;
use App\Models\User;
use App\Models\payroll\Run;
use App\Models\company\JobTypes;
use App\Models\company\Policies;
use App\Models\company\LeaveType;
use App\Models\company\Department;
use App\Models\company\Document as DT;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;
    protected $table = 'company';
    protected $primaryKey = 'co_id';
    public $incrementing = true;
    protected $fillable = [
        'co_name',
        'address',
        'city',
        'province',
        'postal_code',
        'industry',
        'tin'
    ];
    public function departments()
    {
        return $this->hasMany(Department::class, 'co_id');
    }
    public function jobtypes()
    {
        return $this->hasMany(JobTypes::class, 'co_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'co_id');
    }
    public function leavetypes()
    {
        return $this->hasMany(LeaveType::class, 'co_id');
    }
    public function documents()
    {
        return $this->hasMany(DT::class, 'co_id');
    }
    public function policies()
    {
        return $this->hasOne(Policies::class, 'co_id');
    }
    public function deductions()
    {
        return $this->hasMany(Deductions::class, 'co_id');
    }
    public function totalDeductions()
    {
        $total = 0;
        foreach ($this->deductions as $deduction) {
            $total += $deduction->value;
        }
        return $total;
    }
    public function payrollRuns()
    {
        return $this->hasMany(Run::class, 'co_id');
    }
    public function setup($type)
    {
        $result = new stdClass();
        if ($type == 'jobtype') {
            $result->status = $this->jobtypes->count() === 1 ? '1/2 Created' : ($this->jobtypes->count() > 1 ? 'Completed' : 'Waiting');
            $result->checked = $this->jobtypes->count() > 1 ? 'checked' : '';
            $result->badge = $this->jobtypes->count() === 1 ? 'badge-soft-warning' : ($this->jobtypes->count() > 1 ? 'badge-soft-success' : 'badge-soft-secondary');
        } else if ($type == 'leavetypes') {
            $result->status = $this->leavetypes->count() === 1 ? '1/2 Created' : ($this->leavetypes->count() > 1 ? 'Completed' : 'Waiting');
            $result->checked = $this->leavetypes->count() > 1 ? 'checked' : '';
            $result->badge = $this->leavetypes->count() === 1 ? 'badge-soft-warning' : ($this->leavetypes->count() > 1 ? 'badge-soft-success' : 'badge-soft-secondary');
        } else if ($type == 'documents') {
            $result->status = $this->documents->count() === 1 ? '1/2 Created' : ($this->documents->count() > 1 ? 'Completed' : 'Waiting');
            $result->checked = $this->documents->count() > 1 ? 'checked' : '';
            $result->badge = $this->documents->count() === 1 ? 'badge-soft-warning' : ($this->documents->count() > 1 ? 'badge-soft-success' : 'badge-soft-secondary');
        } else if ($type == 'policies') {
            $result->status = $this->policies ? 'Completed' : 'Waiting';
            $result->checked = $this->policies ? 'checked' : '';
            $result->badge = $this->policies ? 'badge-soft-success' : 'badge-soft-secondary';
        } else if ($type == 'profile') {
            $result->status = Auth()->user()->userPSI ? 'Completed' : 'Waiting';
            $result->checked = Auth()->user()->userPSI ? 'checked' : '';
            $result->badge = Auth()->user()->userPSI ? 'badge-soft-success' : 'badge-soft-secondary';
        } else if ($type == 'departments') {
            $result->status = $this->departments->count() === 1 ? '1/2 Created' : ($this->departments->count() > 1 ? 'Completed' : 'Waiting');
            $result->checked = $this->departments->count() > 1 ? 'checked' : '';
            $result->badge = $this->departments->count() === 1 ? 'badge-soft-warning' : ($this->departments->count() > 1 ? 'badge-soft-success' : 'badge-soft-secondary');
        } else {
            $result->status = $this->setup('jobtype')->status == 'Completed' && $this->setup('leavetypes')->status == 'Completed' && $this->setup('documents')->status == 'Completed' && $this->setup('policies')->status == 'Completed' && $this->setup('profile')->status == 'Completed' && $this->setup('departments')->status == 'Completed' ? 1 : 0;
        }
        return $result;
    }
}
