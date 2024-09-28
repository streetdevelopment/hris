<?php

namespace App\Models\profiling;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PI extends Model
{
    use HasFactory;
    protected $table = 'emp_pi';
    protected $primaryKey = 'pi_id';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'sex',
        'date_of_birth',
        'nationality',
        'photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
