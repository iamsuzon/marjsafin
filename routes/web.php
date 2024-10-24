<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DeveloperSettingsController;
use App\Http\Controllers\MedicalCenterAuthController;
use App\Http\Controllers\MedicalCenterManageController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserAuthController::class, 'index'])->name('home');
Route::post('/', [UserAuthController::class, 'login']);
Route::get('login', function () {
    return to_route('home');
})->name('login');

Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', [UserAuthController::class, 'dashboard'])->name('dashboard');

    Route::get('/customer-registration', [UserAuthController::class, 'userRegistration'])->name('user.registration');
    Route::post('/customer-registration', [UserAuthController::class, 'storeUserRegistration']);

    Route::get('/generate-pdf/{id}', [UserAuthController::class, 'generatePdf'])->name('user.generate.pdf');

    Route::get('/customer-application-list', [UserAuthController::class, 'userPanel'])->name('user.application.list');

    Route::get('/logout', [UserAuthController::class, 'logout'])->name('logout');
});


Route::get('/admin', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('/admin', [AdminAuthController::class, 'loginAction']);

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/application-list', [AdminAuthController::class, 'applicationList'])->name('admin.application.list');
    Route::post('/admin/application-update-result', [AdminAuthController::class, 'applicationUpdateResult'])->name('admin.application.result.update');

    Route::get('/admin/application-edit/{id}', [AdminAuthController::class, 'applicationEdit'])->name('admin.application.edit');
    Route::post('/admin/application-edit/{id}', [AdminAuthController::class, 'applicationUpdate']);

    Route::get('/admin/application-delete', [AdminAuthController::class, 'applicationDelete'])->name('admin.application.delete');

    Route::get('/admin/new-user', [AdminAuthController::class, 'newUser'])->name('admin.new.user');
    Route::post('/admin/new-user', [AdminAuthController::class, 'newUserCreate']);
    Route::get('/admin/user-list', [AdminAuthController::class, 'userList'])->name('admin.user.list');

    Route::get('/admin/new-medical-center', [MedicalCenterManageController::class, 'newMedicalCenter'])->name('admin.new.medical-center');
    Route::post('/admin/new-medical-center', [MedicalCenterManageController::class, 'newMedicalCenterCreate']);
    Route::get('/admin/medical-center-list', [MedicalCenterManageController::class, 'MedicalCenterList'])->name('admin.medical-center.list');

    Route::post('/admin/medical-center/change-password', [MedicalCenterManageController::class, 'MedicalCenterChangePassword'])->name('admin.medical-center.change.password');
    Route::post('/admin/medical-center/update', [MedicalCenterManageController::class, 'MedicalCenterUpdate'])->name('admin.medical-center.update');
    Route::get('/admin/medical-center/delete', [MedicalCenterManageController::class, 'MedicalCenterDelete'])->name('admin.medical-center.delete');

    Route::get('/admin/application-list/allocations', [AdminAuthController::class, 'allocatedMedicalCenterList'])->name('admin.application-list.allocations');
    Route::get('/admin/application-list/allocations/{id}', [AdminAuthController::class, 'allocatedMedicalCenterDetails'])->name('admin.application-list.allocations.details');
    Route::get('/admin/application-list/allocations/approve/{id}', [AdminAuthController::class, 'allocatedMedicalCenterApprove'])->name('admin.application-list.allocations.approve');
    Route::get('/admin/application-list/allocations/disapprove/{id}', [AdminAuthController::class, 'allocatedMedicalCenterDisapprove'])->name('admin.application-list.allocations.disapprove');

    Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/admin/upgrade-database', [DeveloperSettingsController::class, 'upgradeDatabase'])->name('admin.upgrade.database');
});


Route::get('/medical', [MedicalCenterAuthController::class, 'login'])->name('medical.login');
Route::post('/medical', [MedicalCenterAuthController::class, 'loginAction']);

Route::middleware('auth:medical_center')->prefix('medical')->group(function () {
    Route::get('/dashboard', [MedicalCenterAuthController::class, 'dashboard'])->name('medical.dashboard');
    Route::get('/application-list', [MedicalCenterAuthController::class, 'applicationList'])->name('medical.application.list');

    Route::post('/application-update-result', [MedicalCenterAuthController::class, 'applicationUpdateResult'])->name('medical.application.result.update');

    Route::get('/application-list/search', [MedicalCenterAuthController::class, 'searchApplication'])->name('medical.application.search');

    Route::get('/logout', [MedicalCenterAuthController::class, 'logout'])->name('medical.logout');
});
