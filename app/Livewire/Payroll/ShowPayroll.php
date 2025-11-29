<?php

namespace App\Livewire\Payroll;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Payroll;
use App\Models\PayrollPiece;

class ShowPayroll extends Component
{
    public $payroll;
    public $pieces;
    public $totalKesejahteraan = 0;
    public $totalKomunikasi = 0;
    public $totalTunjangan = 0;
    public $totalPotongan = 0;
    public $grandTotal = 0;

    public function mount()
    {
        $user = Auth::user();

        // Ambil payroll terbaru milik user yg login
        $this->payroll = Payroll::whereHas('userBranche', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['userBranche.user', 'userBranche.branch']) // Eager load
            ->latest()
            ->first();

        // ========================
        // PERBAIKAN: Ambil pieces berdasarkan user_branche_id dan periode
        // Karena PayrollPiece tidak lagi punya payroll_id
        // ========================
        if ($this->payroll) {
            $this->pieces = PayrollPiece::where('user_branche_id', $this->payroll->user_branche_id)
                ->where('periode', $this->payroll->periode)
                ->orderBy('tanggal', 'asc')
                ->orderBy('kategori', 'asc')
                ->get();

            // Hitung total
            $this->calculateTotals();
        }else {
        // ← PERBAIKAN: kalau payroll tidak ada, set sebagai Collection kosong
        $this->pieces = collect();}
    }

    /**
     * Hitung total dari semua pieces
     */
    private function calculateTotals()
    {
        $this->totalKesejahteraan = $this->pieces->sum('kesejahteraan');
        $this->totalKomunikasi = $this->pieces->sum('komunikasi');
        $this->totalTunjangan = $this->pieces->sum('tunjangan');
        $this->totalPotongan = $this->pieces->sum('potongan');
        
        // Grand total = (kesejahteraan + komunikasi + tunjangan) - potongan
        $this->grandTotal = ($this->totalKesejahteraan + $this->totalKomunikasi + $this->totalTunjangan) - abs($this->totalPotongan);
    }

    /**
     * Group pieces by kategori untuk tampilan yang lebih rapi
     */
    public function getPiecesByKategori()
    {
        return $this->pieces->groupBy('kategori');
    }

    public function render()
    {
        return view('livewire.payroll.show-payroll', [
            'piecesByKategori' => $this->getPiecesByKategori()
        ]);
    }
}