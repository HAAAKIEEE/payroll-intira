<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
protected $fillable = [
        'full_name',
        'grade',
        'address',
        'hire_date',
        'years_of_service',
        'education',
        'employee_code',
        'account_number',
        'npwp_number',
        'nik',
        'position',
        'is_active',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
