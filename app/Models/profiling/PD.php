<?php

namespace App\Models\profiling;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\company\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PD extends Model
{
    use HasFactory;
    protected $table = 'emp_pd';
    protected $primaryKey = 'pd_id';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'dt_id',
        'filepath',
        'filesize',
        'leave_req'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function details()
    {
        return $this->belongsTo(Document::class, 'dt_id');
    }
}
