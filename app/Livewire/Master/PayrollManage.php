<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PayrollsImport;
use App\Models\Payroll;
class PayrollManage extends Component
{
    use WithFileUploads;

    public $file;
    public $periode; // ✅ tambahkan

    public function mount()
    {
        // default ke bulan sekarang
        $this->periode = now()->format('Y-m');
    }

    public function import()
    {
        $this->validate([
            'periode' => 'required|date_format:Y-m',
            'file'    => 'required|mimes:xlsx,xls',
        ]);

        try {
            // ✅ inject periode ke import
            $import = new PayrollsImport($this->periode);
            Excel::import($import, $this->file);

            if (!empty($import->errors)) {
                session()->flash('errors_import', $import->errors);
                session()->flash(
                    'error',
                    "Sebagian baris gagal diimport untuk periode {$this->periode}"
                );
            } else {
                session()->flash(
                    'success',
                    "✅ Import Payroll periode $this->periode berhasil! 
                Berhasil: {$import->getImportedCount()}, 
                Dilewati: {$import->getSkippedCount()}"
                );
              

            }

            $this->reset('file');

        } catch (\Throwable $e) {
            session()->flash(
                'error',
                'Terjadi kesalahan saat import: ' . $e->getMessage()
            );
        }
    }

    public function downloadTemplateImportPayroll()
{
    $this->dispatch('download-payroll-template');
}


    public function render()
    {
        return view('livewire.master.payroll-manage');
    }
}