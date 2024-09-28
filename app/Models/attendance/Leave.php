<?php

namespace App\Models\attendance;

use Carbon\Carbon;
use App\Models\User;
use App\Models\profiling\PD;
use App\Models\company\Document;
use App\Models\company\LeaveType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;
    protected $table = 'lv_req';
    protected $primaryKey = 'lv_req_id';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'lt_id',
        'approver_id',
        's_approver_id',
        'start_date',
        'end_date',
        'status',
        'date_approved',
        'message'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function lt()
    {
        return $this->belongsTo(LeaveType::class, 'lt_id');
    }
    public function numberOfDays()
    {
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        return $startDate->diffInDays($endDate) + 1;
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
    public function leaveDocuments()
    {
        $result = $this->lt;
        $docs = [];
        foreach ($result->ltr as $ltr) {
            $document = Document::find($ltr->dt_id);
        }
        foreach ($result->ltr as $ltr) {
            $pds = PD::where('user_id', $this->user_id)
                ->where('dt_id', $ltr->dt_id)
                ->where('leave_req', $this->lv_req_id)->get();
            array_push($docs, $pds);
        }
        return $docs;
    }
}
