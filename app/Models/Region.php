<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
   protected $fillable = ['name'];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
