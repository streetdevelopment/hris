<?php

namespace App\Models\profiling;

use App\Models\User;
use App\Models\company\Position;
use App\Models\company\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PSI extends Model
{
    use HasFactory;
    protected $table = 'emp_psi';
    protected $primaryKey = 'psi_id';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'pos_id',
        'date_hired',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class, 'pos_id');
    }
    public function badge()
    {
        return $this->status == 'Active' ? 'bg-success' : ($this->status == 'Inactive' ? 'bg-danger' : 'bg-warning');
    }
}
