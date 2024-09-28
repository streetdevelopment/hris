<?php

namespace App\Models\profiling;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CI extends Model
{
    use HasFactory;
    protected $table = 'emp_ci';
    protected $primaryKey = 'ci_id';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'contact_number',
        'email_address',
        'permanent_address',
        'current_address',
        'ec_name',
        'ec_relation',
        'ec_contact_number'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
