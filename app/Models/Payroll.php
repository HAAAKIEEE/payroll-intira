<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
       protected $fillable = [
        'user_branche_id',
        'period',
        'hari_kerja',
        'gaji_pokok',
        'transportasi',
        'tunjangan',
        'bonus_revenue',
        'simpanan',
        'potongan',
        'makan',
        'total',
    ];

    public function userBranche()
    {
        return $this->belongsTo(UserBranche::class, 'user_branche_id');
    }



    // public function pieces()
    // {
    //     return $this->hasMany(PayrollPiece::class);
    // }

  
    public function pieces()
    {
        return PayrollPiece::where('user_branche_id', $this->user_branche_id)
            ->where('period', $this->period)
            ->orderBy('tanggal', 'asc')
            ->orderBy('kategori', 'asc')
            ->get();
    }

    /**
     * Atau jika ingin menggunakan Query Builder untuk chaining
     */
    public function piecesQuery()
    {
        return PayrollPiece::where('user_branche_id', $this->user_branche_id)
            ->where('period', $this->period);
    }

    /**
     * Hitung total kesejahteraan dari pieces
     */
    public function getTotalKesejahteraanAttribute()
    {
        return $this->pieces()->sum('kesejahteraan');
    }

    /**
     * Hitung total komunikasi dari pieces
     */
    public function getTotalKomunikasiAttribute()
    {
        return $this->pieces()->sum('komunikasi');
    }

    /**
     * Hitung total tunjangan dari pieces
     */
    public function getTotalTunjanganAttribute()
    {
        return $this->pieces()->sum('tunjangan');
    }

    /**
     * Hitung total potongan dari pieces
     */
    public function getTotalPotonganAttribute()
    {
        return $this->pieces()->sum('potongan');
    }

    /**
     * Hitung grand total
     */
    public function getGrandTotalAttribute()
    {
        $pieces = $this->pieces();
        $income = $pieces->sum('kesejahteraan') + 
                  $pieces->sum('komunikasi') + 
                  $pieces->sum('tunjangan');
        $deduction = abs($pieces->sum('potongan'));
        
        return $income - $deduction;
    }

}
