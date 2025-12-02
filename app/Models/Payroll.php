<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
   
    protected $fillable = [
        'user_branche_id',
        'periode',
        'hari_kerja',

        // Komponen Gaji
        'gaji_pokok',
        'transportasi',
        'makan',
        'tunjangan',

        // Revenue & bonus
        'bonus_revenue',
        'revenue_persentase',
        'total_revenue',

        // Potongan & simpanan
        'simpanan',
        'potongan',

        // KPI
        'kpi_persentase',
        'kpi',
        'total_kpi',

        // Total akhir
        'grand_total',
        'take_home_pay',
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
        ->where('periode', $this->periode)
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
        ->where('periode', $this->periode);
}


    /**
     * Hitung total kesejahteraan dari pieces
     */
 

}
