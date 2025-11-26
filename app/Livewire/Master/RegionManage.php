<?php

namespace App\Livewire\Master;

use App\Models\Region;
use Livewire\Component;
use Livewire\WithPagination;

class RegionManage extends Component
{
     use WithPagination;

    public $showModal = false;
    public $regionId;
    public $name;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:regions,name,' . $this->regionId,
        ];
    }

    public function create()
    {
        $this->reset(['regionId', 'name']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $region = Region::findOrFail($id);
        $this->regionId = $id;
        $this->name = $region->name;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        Region::updateOrCreate(
            ['id' => $this->regionId],
            ['name' => $this->name]
        );

        session()->flash('message', 'Region saved successfully.');

        $this->showModal = false;
    }

    public function delete($id)
    {
        $region = Region::findOrFail($id);
        if ($region->branches()->count()) {
            session()->flash('error', 'Cannot delete region with associated branches.');
            return;
        }
        $region->delete();
        session()->flash('message', 'Region deleted successfully.');
    }

    public function render()
    {
        return view('livewire.master.region-manage', [
            'regions' => Region::withCount('branches')->latest()->paginate(10),
        ]);
    }
}
