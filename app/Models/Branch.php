<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['region_id', 'name', 'address'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function userBranches()
    {
        return $this->hasMany(UserBranche::class, 'branches_id', 'id');
    }
}
