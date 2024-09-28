<?php

namespace App\Models\company;

use App\Models\attendance\Leave;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends Model
{
    use HasFactory;
    protected $table = 'lt';
    protected $primaryKey = 'lt_id';
    public $incrementing = true;
    protected $fillable = [
        'co_id',
        'name',
        'description',
    ];
    public function ltr()
    {
        return $this->hasMany(LeaveTypeReq::class, 'lt_id');
    }
    public function applications()
    {
        return $this->hasMany(Leave::class, 'lt_id');
    }
}
