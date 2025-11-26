<?php

namespace App\Livewire\Master;

use App\Models\Branch;
use App\Models\Region;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class BrancheManage extends Component
{
    use WithPagination;

    // Branch fields
    public $branchId;
    public $region_id;
    public $name;
    public $address;
    public $isBranchEditMode = false;

    // Region fields
    public $createNewRegion = false;
    public $regionId;
    public $region_name;
    public $isRegionEditMode = false;

    // Search
    public $search = '';
    public $regionSearch = '';

    protected function branchRules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ];

        if ($this->createNewRegion) {
            $rules['region_name'] = 'required|string|max:255|unique:regions,name';
        } else {
            $rules['region_id'] = 'required|exists:regions,id';
        }

        return $rules;
    }

    protected $regionRules = [
        'region_name' => 'required|string|max:255|unique:regions,name',
    ];

    public function render()
    {
        $branches = Branch::with('region')
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('region', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        $regions = Region::withCount('branches')
            ->when($this->regionSearch, function($query) {
                $query->where('name', 'like', '%' . $this->regionSearch . '%');
            })
            ->orderBy('name')
            ->get();

        return view('livewire.master.branche-manage', [
            'branches' => $branches,
            'regions' => $regions,
        ]);
    }

    // Branch Methods
    public function createBranch()
    {
        $this->reset(['branchId', 'region_id', 'name', 'address', 'createNewRegion', 'region_name']);
        $this->isBranchEditMode = false;
        $this->resetValidation();
    }

    public function editBranch($id)
    {
        $branch = Branch::findOrFail($id);
        $this->branchId = $id;
        $this->region_id = $branch->region_id;
        $this->name = $branch->name;
        $this->address = $branch->address;
        $this->isBranchEditMode = true;
        $this->createNewRegion = false;
        $this->resetValidation();
    }

    public function saveBranch()
    {
        $this->validate($this->branchRules());

        DB::transaction(function () {
            $regionId = $this->region_id;

            // Create new region if needed
            if ($this->createNewRegion) {
                $region = Region::create([
                    'name' => $this->region_name,
                ]);
                $regionId = $region->id;
            }

            // Create or update branch
            Branch::updateOrCreate(
                ['id' => $this->branchId],
                [
                    'region_id' => $regionId,
                    'name' => $this->name,
                    'address' => $this->address,
                ]
            );

            session()->flash('message', $this->isBranchEditMode ? 'Branch updated successfully.' : 'Branch created successfully.');
            $this->createBranch();
        });
    }

    public function deleteBranch($id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->employees()->count()) {
            session()->flash('error', 'Cannot delete branch with associated employees.');
            return;
        }
        $branch->delete();
        session()->flash('message', 'Branch deleted successfully.');
    }

    // Region Methods
    public function createRegion()
    {
        $this->reset(['regionId', 'region_name']);
        $this->isRegionEditMode = false;
        $this->resetValidation();
    }

    public function editRegion($id)
    {
        $region = Region::findOrFail($id);
        $this->regionId = $region->id;
        $this->region_name = $region->name;
        $this->isRegionEditMode = true;
        $this->resetValidation();
    }

    public function saveRegion()
    {
        if ($this->isRegionEditMode) {
            $this->validate([
                'region_name' => 'required|string|max:255|unique:regions,name,' . $this->regionId,
            ]);
        } else {
            $this->validate($this->regionRules);
        }

        Region::updateOrCreate(
            ['id' => $this->regionId],
            ['name' => $this->region_name]
        );

        session()->flash('message', $this->isRegionEditMode ? 'Region updated successfully.' : 'Region created successfully.');
        $this->createRegion();
    }

    public function deleteRegion($id)
    {
        $region = Region::findOrFail($id);
        
        if ($region->branches()->count() > 0) {
            session()->flash('error', 'Cannot delete region with associated branches.');
            return;
        }

        $region->delete();
        session()->flash('message', 'Region deleted successfully.');
    }

    public function toggleRegionForm()
    {
        $this->createNewRegion = !$this->createNewRegion;
        if (!$this->createNewRegion) {
            $this->reset(['region_name']);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRegionSearch()
    {
        $this->resetPage();
    }
}