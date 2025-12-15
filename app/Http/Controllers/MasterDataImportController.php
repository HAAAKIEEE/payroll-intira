<?php

namespace App\Http\Controllers;

use App\Imports\BranchImport;
use App\Imports\EmployeeUserImport;
use App\Imports\MasterDataImport;
use App\Imports\PayrollAmImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Exception;

class MasterDataImportController extends Controller
{
    /**
     * Display the import form
     */
    public function indexBranch()
    {
        return view('master.import-branch');
    }

    /**
     * Proses import branch dari Excel
     */
    public function importBranch(Request $request)
    {
        // Validasi file upload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ], [
            'file.required' => 'File Excel wajib diupload',
            'file.mimes' => 'File harus berformat .xlsx atau .xls',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        try {
            $file = $request->file('file');
            
            // Log aktivitas import
            Log::info('Starting branch import', [
                'filename' => $file->getClientOriginalName(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            // Buat instance importer
            $importer = new BranchImport;
            
            // Proses import
            Excel::import($importer, $file);

            // Ambil hasil import
            $imported = $importer->getImportedCount();
            $skipped = $importer->getSkippedCount();
            $errors = $importer->getErrors();

            // Log hasil
            Log::info('Branch import completed', [
                'imported' => $imported,
                'skipped' => $skipped,
                'errors' => count($errors)
            ]);

            // Hitung total data
            $totalBranches = \App\Models\Branch::count();
            $totalRegions = \App\Models\Region::count();

            // Buat pesan sukses
            $message = "✅ Import Cabang selesai! Berhasil: {$imported}, Dilewati: {$skipped}";
            
            if (count($errors) > 0) {
                $message .= " | ⚠️ " . count($errors) . " baris bermasalah";
            }

            return redirect()
                ->back()
                ->with('success', $message)
                ->with('import_stats', [
                    'type' => 'branch',
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'errors' => $errors,
                    'total_branches' => $totalBranches,
                    'total_regions' => $totalRegions,
                ]);
                
        } catch (\Exception $e) {
            // Log error lengkap
            Log::error('Branch import failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Tampilkan error sesuai environment
            $errorMessage = app()->environment('local') 
                ? $e->getMessage() . ' (Line: ' . $e->getLine() . ')'
                : 'Terjadi kesalahan saat import data cabang';

            return redirect()
                ->back()
                ->with('error', '❌ Import gagal: ' . $errorMessage)
                ->withInput();
        }
    }


      public function indexEmployeeUser()
    {
        return view('master.import-employee-user');
    }

    /**
     * Handle the import process
     */
 public function importEmployeeUser(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);
        // dd($request->file);


        try {
            $import = new EmployeeUserImport;
            Excel::import($import, $request->file('file'));
            return redirect()
                ->back()
                ->with('success', 'Import sedang selesai.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            // Menangkap error validasi Excel
            $failures = $e->failures();
            $messages = collect($failures)->map(function ($failure) {
                return "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            })->implode(' | ');

            return redirect()
                ->back()
                ->with('error', "Import gagal karena kesalahan data. Detail: $messages");
        } catch (\Exception $e) {

            // Menangkap error selain validasi
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

  public function payrollAm()
    {
        return view('master.import-payroll-am');
    }

  public function importpayrollAm(Request $request)
{
    $request->validate([
        'periode' => 'required|date_format:Y-m',
        'file'    => 'required|mimes:xlsx,xls|max:10240',
    ], [
        'periode.required' => 'Periode wajib dipilih',
        'periode.date_format' => 'Format periode tidak valid',
        'file.required' => 'File Excel wajib diupload',
        'file.mimes'    => 'Format harus .xlsx atau .xls',
        'file.max'      => 'Ukuran file maksimal 10MB',
    ]);

    try {
        $periode = $request->periode; // ✅ dari input month
        $file    = $request->file('file');

        // ✅ Inject periode ke Import
        $import = new PayrollAmImport($periode);
        Excel::import($import, $file);

        return redirect()->back()
            ->with('success', "✅ Import Payroll AM periode $periode berhasil! 
                Berhasil: {$import->getImportedCount()}, 
                Dilewati: {$import->getSkippedCount()}")
            ->with('import_errors', $import->getErrors());

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', '❌ Import gagal: ' . $e->getMessage());
    }
}




public function downloadTemplateImportPayrollAm()
{
    $file = public_path('template/payroll-template.xlsx'); // lokasi file

    return response()->download($file, 'payroll-template.xlsx');
}

}