<?php

namespace App\Livewire\Payroll;

use App\Imports\PayrollsImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ManagePayroll extends Component
{
    use WithFileUploads;

    public $file;
public function import()
{
    $this->validate([
        'file' => 'required|mimes:xlsx,xls',
    ]);

    try {
        $import = new PayrollsImport;
        Excel::import($import, $this->file);

        // Jika ada error baris gagal
        if (!empty($import->errors)) {
            session()->flash('errors_import', $import->errors);
            session()->flash('error', 'Sebagian baris gagal diimport. Silakan cek detail di bawah.');
        } else {
            session()->flash('message', 'Payroll data imported successfully.');
        }

        $this->reset('file');

    } catch (\Throwable $e) {
        session()->flash('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
    }
}

    // public function import()
    // {
    //     $this->validate([
    //         'file' => 'required|mimes:xlsx,xls',
    //     ]);

    //     try {
    //         Excel::import(new PayrollsImport, $this->file);
    //         session()->flash('message', 'Payroll data imported successfully.');
    //         $this->reset('file');
    //     } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
    //         $failures = $e->failures();
    //         // You can handle validation failures here, e.g., flash them to the session
    //         session()->flash('error', 'There was an error with the import: ' . $failures[0]->errors()[0]);
    //     }
    // }

    public function render()
    {
        return view('livewire.payroll.manage-payroll');
    }
}
