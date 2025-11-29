<?php

namespace App\Livewire\Payroll;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PayrollPieceImport;
use App\Models\Payroll;

class PiecesManage extends Component
{
    use WithFileUploads;

    public $file;
    public $selectedPeriode = '';
    public $periodes = [];

    protected $messages = [
        'file.required' => 'File Excel wajib diupload.',
        'file.mimes' => 'File harus berformat .xlsx atau .xls',
        'file.max' => 'Ukuran file maksimal 10MB.',
        'selectedPeriode.required' => 'Periode payroll wajib dipilih.',
    ];

    public function mount()
    {
        // Ambil semua periode unik dari database, diurutkan terbaru
        $this->periodes = Payroll::select('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode')
            ->toArray();
    }

    // Validasi real-time ketika periode dipilih
    public function updatedSelectedPeriode($value)
    {
        $this->validateOnly('selectedPeriode', [
            'selectedPeriode' => 'required|string',
        ]);
    }

    public function import()
    {
        // Validasi lengkap sebelum import
        $validated = $this->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
            'selectedPeriode' => 'required|string',
        ]);

        try {
            // Pass periode yang dipilih ke import class
            Excel::import(new PayrollPieceImport($this->selectedPeriode), $this->file->getRealPath());

            // Jangan langsung flash message di sini, biarkan import class yang handle
            
            // Reset file dan periode setelah import
            $this->reset(['file']);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.payroll.pieces-manage');
    }
}