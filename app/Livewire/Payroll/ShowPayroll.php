<?php

namespace App\Livewire\Payroll;

use Livewire\Component;
use App\Models\Payroll;
use App\Models\PayrollPiece;
use Illuminate\Support\Facades\Auth;

class ShowPayroll extends Component
{
    public $payroll;
    public $pieces;
    public $selectedPeriode;
    public $availablePeriodes = [];
    
    // Totals
    public $totalKesejahteraan = 0;
    public $totalKomunikasi = 0;
    public $totalTunjangan = 0;
    public $totalPotongan = 0;
    public $grandTotal = 0;

    public function mount()
    {
        $user = Auth::user();
        
        // Ambil semua periode yang tersedia untuk user ini
        $this->availablePeriodes = Payroll::whereHas('userBranche', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->select('periode')
        ->distinct()
        ->orderBy('periode', 'desc')
        ->pluck('periode')
        ->toArray();
        
        // Set periode default ke periode terbaru
        $this->selectedPeriode = !empty($this->availablePeriodes) ? $this->availablePeriodes[0] : null;
        
        // Load data payroll
        $this->loadPayrollData();
    }

    public function updatedSelectedPeriode()
    {
        // Ketika periode berubah, reload data
        $this->loadPayrollData();
    }

    private function loadPayrollData()
    {
        $user = Auth::user();
        
        if (!$this->selectedPeriode) {
            $this->payroll = null;
            $this->pieces = collect();
            return;
        }
        
        // Ambil payroll berdasarkan user dan periode yang dipilih
        $this->payroll = Payroll::whereHas('userBranche', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('periode', $this->selectedPeriode)
        ->with(['userBranche.user', 'userBranche.branch'])
        ->first();
        
        // Ambil pieces berdasarkan user_branche_id dan periode
        if ($this->payroll) {
            $this->pieces = PayrollPiece::where('user_branche_id', $this->payroll->user_branche_id)
                ->where('periode', $this->payroll->periode)
                ->orderBy('tanggal', 'asc')
                ->orderBy('kategori', 'asc')
                ->get();
            
            // Hitung total
            $this->calculateTotals();
        } else {
            $this->pieces = collect();
        }
    }

    private function calculateTotals()
    {
        $this->totalKesejahteraan = $this->pieces->sum('kesejahteraan');
        $this->totalKomunikasi = $this->pieces->sum('komunikasi');
        $this->totalTunjangan = $this->pieces->sum('tunjangan');
        $this->totalPotongan = $this->pieces->sum('potongan');
        
        // Grand total = (kesejahteraan + komunikasi + tunjangan) - potongan
        $this->grandTotal = ($this->totalKesejahteraan + $this->totalKomunikasi + $this->totalTunjangan) - abs($this->totalPotongan);
    }

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