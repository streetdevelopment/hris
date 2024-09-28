<?php

namespace App\Models\attendance;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OT extends Model
{
    use HasFactory;
    protected $table = 'ot_req';
    protected $primaryKey = 'ot_req_id';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'approver_id',
        's_approver_id',
        'start',
        'end',
        'status',
        'date_approved',
        'reason'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function numberOfHours()
    {
        $start = Carbon::parse($this->start);
        $end = Carbon::parse($this->end);

        return $start->diffInHours($end);
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
    public function sApprover()
    {
        return $this->belongsTo(User::class, 's_approver_id');
    }
    public function badge()
    {
        return $this->status == 'Waiting Approval' ? 'bg-warning' : ($this->status == 'Approved' ? 'bg-success' : 'bg-danger');
    }
}
