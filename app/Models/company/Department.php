<?php

namespace App\Models\company;

use App\Models\User;
use App\Models\profiling\PI;
use App\Models\company\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
    protected $table = 'department';
    protected $primaryKey = 'dep_id';
    public $incrementing = true;
    protected $fillable = [
        'co_id',
        'dep_name',
        'location',
        'phone_number',
        'email_address',
        'status'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'co_id');
    }
    public function positions()
    {
        return $this->hasMany(Position::class, 'dep_id');
    }
    public function badge()
    {
        return $this->status == 'Active' ? 'bg-success' : ($this->status == 'Inactive' ? 'bg-danger' : 'bg-warning');
    }
}
