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

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);


        $import = new PayrollsImport;
        Excel::import($import, $this->file);

        if (!empty($import->errors)) {
            session()->flash('errors_import', $import->errors);
        } else {
            session()->flash('success', 'Payroll berhasil diimport!');
        }

        if (!empty($import->errors)) {
            session()->flash('errors_import', $import->errors);
        } else {
            session()->flash('success', 'Payroll berhasil diimport!');
        }

        $this->reset('file');
    }

   public function render()
{
    $payrolls = Payroll::with(['userBranche.user', 'userBranche.branch'])
        ->orderBy('id', 'desc')
        ->paginate(20);

    return view('livewire.master.payroll-manage', [
        'payrolls' => $payrolls
    ]);
}

}
