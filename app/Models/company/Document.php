<?php

namespace App\Models\company;

use App\Models\profiling\PD;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;
    protected $table = 'dt';
    protected $primaryKey = 'dt_id';
    public $incrementing = true;
    protected $fillable = [
        'co_id',
        'dt_name'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function ltr()
    {
        return $this->hasMany(LeaveTypeReq::class, 'dt_id');
    }
    public function pds()
    {
        return $this->belongsToMany(PD::class, 'dt_id');
    }
}
