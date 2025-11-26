<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBranche extends Model
{
    protected $table = 'user_branches'; // Pastikan nama tabel sesuai
   
    protected $fillable = [
        'branches_id',
        'user_id',
        'role',
        'is_active',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at' => 'date',
        'end_at' => 'date',
    ];

    // Relasi ke Branch (perbaikan nama relasi dan foreign key)
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branches_id', 'id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'user_branche_id');
    }

    // Scope untuk filter branch yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Scope untuk filter berdasarkan user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}