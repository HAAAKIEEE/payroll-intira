<?php

namespace App\Imports;

use App\Models\UserBranche;
use App\Models\PayrollPiece;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class PayrollPieceImport implements ToCollection, WithHeadingRow
{
    private int $imported = 0;
    private int $skipped  = 0;
    private array $errors = [];
    private string $periode;

    public function __construct(string $periode)
    {
        $this->periode = $periode;
    }

    public function headingRow(): int
    {
        return 1;
    }

    private function normalize($value)
    {
        if ($value === null || $value === '-' || $value === '') {
            return 0;
        }

        return (float) str_replace([','], '', $value);
    }

    private function parseTanggal($value)
    {
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            $rowNumber = $i + 2;

            try {
                $nama   = trim($row['nama'] ?? '');
                $cabang = trim($row['cabang'] ?? '');

                if (!$nama || !$cabang) {
                    throw new \Exception("Nama atau Cabang kosong");
                }

                // 🔎 CARI USER + CABANG
                $userBranche = UserBranche::whereHas('user', fn ($q) =>
                        $q->whereRaw('LOWER(name) = ?', [strtolower($nama)])
                    )
                    ->whereHas('branch', fn ($q) =>
                        $q->whereRaw('LOWER(name) = ?', [strtolower($cabang)])
                    )
                    ->first();

                if (!$userBranche) {
                    throw new \Exception("User '$nama' di cabang '$cabang' tidak ditemukan");
                }

                $tanggal = $this->parseTanggal($row['tanggal'] ?? null);

                // 🔁 CEK DUPLIKAT
                $exists = PayrollPiece::where('user_branche_id', $userBranche->id)
                    ->where('periode', $this->periode)
                    ->where('kategori', trim($row['kategori'] ?? ''))
                    ->where('tanggal', $tanggal)
                    ->exists();

                if ($exists) {
                    throw new \Exception("Duplikat data");
                }

                // 💾 SIMPAN
                PayrollPiece::create([
                    'user_branche_id' => $userBranche->id,
                    'periode'        => $this->periode,
                    'jabatan'        => trim($row['jabatan'] ?? '-'),
                    'kesejahteraan'  => $this->normalize($row['kesejahteraan'] ?? 0),
                    'komunikasi'     => $this->normalize($row['komunikasi'] ?? 0),
                    'tunjangan'      => $this->normalize($row['tunjangan'] ?? 0),
                    'potongan'       => $this->normalize($row['potongan'] ?? 0),
                    'kategori'       => trim($row['kategori'] ?? ''),
                    'keterangan'     => trim($row['keterangan'] ?? null),
                    'tanggal'        => $tanggal,
                ]);

                $this->imported++;

            } catch (\Exception $e) {
                $this->skipped++;
                $this->errors[] = "Baris $rowNumber: " . $e->getMessage();
            }
        }

        session()->flash(
            'success',
            "✅ Import selesai. Berhasil: {$this->imported}, Dilewati: {$this->skipped}"
        );

        if ($this->errors) {
            session()->flash('errors_import', $this->errors);
        }
    }
}
