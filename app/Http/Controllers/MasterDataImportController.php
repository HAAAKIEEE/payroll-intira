<?php

namespace App\Http\Controllers;

use App\Imports\BranchImport;
use App\Imports\EmployeeUserImport;
use App\Imports\MasterDataImport;
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
        'file' => 'required|mimes:xlsx,xls|max:10240'
    ], [
        'file.required' => 'File Excel wajib diupload',
        'file.mimes'    => 'Format harus .xlsx atau .xls',
        'file.max'      => 'Ukuran file maksimal 10MB',
    ]);

    try {

        $file = $request->file('file');

        // Jalankan import
        $import = new EmployeeUserImport;
        Excel::import($import, $file);

        // Ambil hasil dari import class
        $imported = $import->getImportedCount();
        $skipped  = $import->getSkippedCount();
        $errors   = $import->getErrors();

        // Selalu kirim session agar bisa ditampilkan di Blade
        return redirect()
            ->back()
            ->with('success', "✅ Import Cabang selesai! Berhasil: $imported, Dilewati: $skipped")
            ->with('import_errors', $errors);

    } catch (Exception $e) {

        return redirect()
            ->back()
            ->with('error', '❌ Import gagal: ' . $e->getMessage());
    }
}



    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        $filePath = public_path('templates/master-data-template.xlsx');
        
        if (!file_exists($filePath)) {
            return redirect()
                ->back()
                ->with('error', 'Template file tidak ditemukan');
        }

        return response()->download($filePath, 'Template-Master-Data.xlsx');
    }

    /**
     * Show import preview (optional feature)
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $file = $request->file('file');
            
            // Load Excel untuk preview (tanpa import ke database)
            $data = Excel::toArray(new MasterDataImport, $file);
            
            return view('master-data.preview', [
                'branches' => $data[0] ?? [], // Sheet MASTER CABANG
                'employees' => $data[1] ?? [], // Sheet MASTER KARYAWAN
            ]);
            
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Preview gagal: ' . $e->getMessage());
        }
    }
}