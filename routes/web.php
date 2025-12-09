<?php

use App\Http\Controllers\MasterDataImportController;
use App\Livewire\Master\BrancheManage;
use App\Livewire\Master\ManageBranches;
use App\Livewire\Master\ManageRegions;
use App\Livewire\Master\PayrollManage;
use App\Livewire\Master\RegionManage;
use App\Livewire\Master\RolePermissionManagement;
use App\Livewire\Master\UserManage;
use App\Livewire\Payroll\ManagePayroll;
use App\Livewire\Payroll\PiecesManage;
use App\Livewire\Payroll\ShowPayroll;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('livewire.auth.login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Halaman form import
    Route::get('/master-data/import-branch', [MasterDataImportController::class, 'indexBranch'])
        ->name('master-data.import-branch.index')->middleware('can:branches:import');
    Route::post('/master-data/import-branch', [MasterDataImportController::class, 'importBranch'])
        ->name('master-data.import')->middleware('can:branches:import');
    Route::get('/master-data/import-employee-user', [MasterDataImportController::class, 'indexEmployeeUser'])
        ->name('master-data.import-employee-user.index')->middleware('can:users:import');
    Route::post('/master-data/import-employee-user', [MasterDataImportController::class, 'importEmployeeUser'])
        ->name('master-data.import-employee-user.import')->middleware('can:users:import');
   
        Route::get('/master-data/import-payroll-am', [MasterDataImportController::class, 'payrollAm'])
        ->name('master-data.import-payroll-am.index')->middleware('can:branches:import');

        Route::post('/master-data/import-employee-user', [MasterDataImportController::class, 'importpayrollAm'])
        ->name('master-data.import-payroll-am')->middleware('can:users:import');
   
        Route::get('/download-template', [MasterDataImportController::class, 'downloadTemplateImportPayrollAm'])->name('download-template');


    Route::get('/master-data/download-template', [MasterDataImportController::class, 'downloadTemplate'])
        ->name('master-data.download-template')->middleware('can:master:download template');

    // Preview data (optional)
    Route::post('/master-data/preview', [MasterDataImportController::class, 'preview'])
        ->name('master-data.preview');

    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('/manage-role-permissions', RolePermissionManagement::class)
        ->name('manage.role-permissions')->middleware('can:authorization:manage roles');


    Route::get('/regions', RegionManage::class)->name('regions.manage')->middleware('can:regions:manage');
    Route::get('/branches', BrancheManage::class)->name('branches.manage')->middleware('can:branches:manage');
    Route::get('/users', UserManage::class)->name('user.manage')->middleware('can:users:manage');
    Route::get('settings/password', action: Password::class)->name('user-password.edit');
    Route::get('/manage-payroll', PayrollManage::class)->name('payroll.manage')->middleware('can:payroll:manage');
    Route::get('/show-payroll', ShowPayroll::class)->name('payroll.show');
    Route::get('/payroll', ManagePayroll::class)->name('payroll.import')->middleware('can:payroll:import');
    Route::get('/manage-pieces', PiecesManage::class)->name('pieces.manage')->middleware('can:pieces:manage');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
