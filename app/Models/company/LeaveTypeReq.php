<?php

namespace App\Models\company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveTypeReq extends Model
{
    use HasFactory;
    protected $table = 'ltr';
    protected $primaryKey = 'ltr_id';
    public $incrementing = true;
    protected $fillable = [
        'ltr_id',
        'lt_id',
        'dt_id',
    ];
    public function lt()
    {
        return $this->belongsTo(LeaveType::class, 'lt_id');
    }
    public function dt()
    {
        return $this->belongsTo(Document::class, 'dt_id');
    }
}
