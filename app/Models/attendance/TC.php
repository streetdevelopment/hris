<?php

namespace App\Models\attendance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TC extends Model
{
    use HasFactory;
    protected $table = 'tc_req';
    protected $primaryKey = 'tc_req_id';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'approver_id',
        's_approver_id',
        'original_clock_in',
        'corrected_clock_in',
        'original_clock_out',
        'corrected_clock_out',
        'status',
        'date_approved',
        'reason'
    ];
}
