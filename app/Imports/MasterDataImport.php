<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;

class MasterDataImport implements WithMultipleSheets
{
    /**
    
     */
    public function sheets(): array
    {
        return [
            'MASTER CABANG'   => new BranchImport(),
            'MASTER KARYAWAN' => new EmployeeUserImport(),
        ];
    }
}
