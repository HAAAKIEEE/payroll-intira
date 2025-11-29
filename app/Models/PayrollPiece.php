<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollPiece extends Model
{
    protected $fillable = [
        'periode',
        'user_branche_id',
        'jabatan',
        'kesejahteraan',
        'komunikasi',
        'tunjangan',
        'potongan',
        'kategori',
        'keterangan',
        'tanggal'
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
        public function userBranche()
    {
        return $this->belongsTo(UserBranche::class, 'user_branche_id');
    }

    /**
     * Akses User melalui UserBranche
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            UserBranche::class,
            'id', // Foreign key di UserBranche
            'id', // Foreign key di User
            'user_branche_id', // Local key di PayrollPiece
            'user_id' // Local key di UserBranche
        );
    }

}
