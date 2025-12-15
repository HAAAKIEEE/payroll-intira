<?php

namespace App\Livewire\Payroll;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payroll;
use App\Models\Branch;

class TabelPayroll extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // INPUT FILTER
    public $search = '';
    public $branchId = '';
    public $periode = '';

    // VALUE YANG DIPAKAI QUERY
    public $filterSearch = '';
    public $filterBranchId = '';
    public $filterPeriode = '';

    protected $queryString = [
        'filterSearch'   => ['except' => ''],
        'filterBranchId' => ['except' => ''],
        'filterPeriode'  => ['except' => ''],
    ];

    public function applyFilter()
    {
        $this->filterSearch   = $this->search;
        $this->filterBranchId = $this->branchId;
        $this->filterPeriode  = $this->periode;

        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset([
            'search',
            'branchId',
            'periode',
            'filterSearch',
            'filterBranchId',
            'filterPeriode',
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $payrolls = Payroll::with([
                'userBranche.user',
                'userBranche.branch'
            ])
            ->when($this->filterSearch, function ($q) {
                $q->whereHas('userBranche.user', function ($u) {
                    $u->where('name', 'like', '%' . $this->filterSearch . '%');
                });
            })
            ->when($this->filterBranchId, function ($q) {
                $q->whereHas('userBranche', function ($b) {
                    $b->where('branches_id', $this->filterBranchId);
                });
            })
            ->when($this->filterPeriode, function ($q) {
                $q->where('periode', $this->filterPeriode);
            })
            ->orderBy('periode', 'desc')
            ->paginate(10);

        return view('livewire.payroll.tabel-payroll', [
            'payrolls' => $payrolls,
            'branches' => Branch::orderBy('name')->get(),
            'periodes' => Payroll::select('periode')
                ->distinct()
                ->orderBy('periode', 'desc')
                ->pluck('periode'),
        ]);
    }
}
