<?php

namespace App\Livewire\Payroll;

use App\Models\Payroll;
use Livewire\Component;

class TabelPayrollShow extends Component
{
    public Payroll $payroll;

    public function mount(Payroll $payroll)
    {
        $this->payroll = $payroll->load([
            'userBranche.user',
            'userBranche.branch'
        ]);
    }
    public function render()
    {
        return view('livewire.payroll.tabel-payroll-show');
    }
}
