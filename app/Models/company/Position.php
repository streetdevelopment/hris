<?php

namespace App\Models\company;

use App\Models\profiling\PSI;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\company\JobTypes;

class Position extends Model
{
    use HasFactory;
    protected $table = 'position';
    protected $primaryKey = 'pos_id';
    public $incrementing = true;
    protected $fillable = [
        'dep_id',
        'jt_id',
        'title',
        'description',
        'salary',
        'status'
    ];
    public function department()
    {
        return $this->belongsTo(Department::class, 'dep_id');
    }
    public function PSI()
    {
        return $this->hasOne(PSI::class, 'pos_id');
    }
    public function JT()
    {
        return $this->belongsTo(JobTypes::class, 'jt_id');
    }
}
