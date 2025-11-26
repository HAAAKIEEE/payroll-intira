<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Region;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BranchImport implements ToCollection, SkipsEmptyRows, WithHeadingRow
{
    private $importedCount = 0;
    private $skippedCount  = 0;
    private $errors        = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $rowNumber = $index + 2; // Row Excel asli

            try {
                // ================================
                // Ambil berdasarkan nama kolom
                // ================================
                $branchName = trim($row['kcp'] ?? '');
                $regionName = trim($row['wilayah'] ?? '');

                // ================================
                // Validasi minimal
                // ================================
                if (!$branchName) {

                    $this->skippedCount++;

                    $this->errors[] = [
                        'row'    => $rowNumber,
                        'reason' => 'Nama Cabang (KCP) kosong',
                        'data'   => $row->toArray()
                    ];

                    continue;
                }

                if (!$regionName) {
                    $regionName = 'TANPA WILAYAH';
                }

                // ================================
                // Buat atau cari Region
                // ================================
                $region = Region::firstOrCreate([
                    'name' => $regionName
                ]);

                // ================================
                // Cek sudah ada atau belum
                // ================================
                $existing = Branch::where('name', $branchName)->first();

                if ($existing) {
                    $this->skippedCount++;

                    $this->errors[] = [
                        'row'    => $rowNumber,
                        'reason' => "Cabang '$branchName' sudah ada",
                        'data'   => $row->toArray()
                    ];

                    continue;
                }

                // ================================
                // Simpan cabang baru
                // ================================
                Branch::create([
                    'name'      => $branchName,
                    'region_id' => $region->id,
                    'is_active' => true,
                ]);

                $this->importedCount++;

            } catch (\Exception $e) {

                $this->skippedCount++;

                $this->errors[] = [
                    'row'    => $rowNumber,
                    'reason' => $e->getMessage(),
                    'data'   => $row->toArray()
                ];

                Log::error("Error importing row $rowNumber", [
                    'error' => $e->getMessage(),
                    'row'   => $row->toArray()
                ]);
            }
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
