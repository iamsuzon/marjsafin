<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDepositRequestHistoryController;
use App\Http\Controllers\BanUserManageController;
use App\Http\Controllers\DeveloperSettingsController;
use App\Http\Controllers\ExcelConverterController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\MedicalCenterAuthController;
use App\Http\Controllers\MedicalCenterManageController;
use App\Http\Controllers\PaymentLogController;
use App\Http\Controllers\UnionAccountManageController;
use App\Http\Controllers\UnionMedicalManageController;
use App\Http\Controllers\UnionUserManageController;
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
    Route::post('/customer-application-list/update', [UserAuthController::class, 'userPanelUpdate'])->name('user.application.list.update');

//    Route::get('/customer-deposit-request', [PaymentLogController::class, 'index'])->name('user.deposit.index');
//    Route::post('/customer-deposit-request', [PaymentLogController::class, 'deposit']);

    Route::get('/customer-score-request/{id}', [PaymentLogController::class, 'scoreRequest'])->name('user.score.request');
//
//    Route::get('/customer-deposit-history', [PaymentLogController::class, 'depositHistory'])
//        ->name('user.deposit.history');
    Route::get('/customer-transaction-history', [PaymentLogController::class, 'transactionHistory'])
        ->name('user.transaction.history');

    Route::get('/customer/pay-bil', [PaymentLogController::class, 'payBill'])->name('user.pay-bill');

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
    Route::post('/admin/application-update', [AdminAuthController::class, 'applicationRegDateUpdate'])->name('admin.application.update.reg-date');

    Route::get('/admin/application-delete', [AdminAuthController::class, 'applicationDelete'])
        ->name('admin.application.delete')
        ->can('delete-application');

    Route::get('/admin/new-user', [AdminAuthController::class, 'newUser'])
        ->name('admin.new.user')
        ->can('create-customer');
    Route::post('/admin/new-user', [AdminAuthController::class, 'newUserCreate'])
        ->can('create-customer');
    Route::get('/admin/user-list', [AdminAuthController::class, 'userList'])
        ->name('admin.user.list')
        ->can('view-customer');
    Route::get('/admin/user-ban/{id}', [BanUserManageController::class, 'banUser'])
        ->name('admin.user.ban');
    Route::post('/admin/user-balance/update', [AdminAuthController::class, 'updateBalance'])
        ->name('admin.user.balance.update');
    Route::get('/admin/user/pdf-generate', [AdminAuthController::class, 'generatePdf'])
        ->name('admin.user.pdf.generate');

    Route::get('/admin/new-medical-center', [MedicalCenterManageController::class, 'newMedicalCenter'])
        ->name('admin.new.medical-center')
        ->can('create-medical-center');
    Route::post('/admin/new-medical-center', [MedicalCenterManageController::class, 'newMedicalCenterCreate'])
        ->can('create-medical-center');
    Route::get('/admin/medical-center-list', [MedicalCenterManageController::class, 'MedicalCenterList'])
        ->name('admin.medical-center.list')
        ->can('view-medical-center');
    Route::get('/admin/medical-center-list/application', [MedicalCenterManageController::class, 'MedicalCenterListApplication'])
        ->name('admin.medical-center.list.application')
        ->can('view-medical-center');
    Route::get('/admin/medical/application-list', [MedicalCenterManageController::class, 'medicalApplicationList'])
        ->name('admin.medical.application.list');

    Route::get('admin/application-list/pdf', [MedicalCenterManageController::class, 'applicationListPdf'])
        ->name('admin.application.list.generate.pdf');
    Route::get('/admin/application-list/medical/pdf', [MedicalCenterManageController::class, 'medicalApplicationListPdf'])
        ->name('admin.medical.application.list.generate.pdf');

    Route::get('/admin/allocate-center-list', [MedicalCenterManageController::class, 'AllocateCenterList'])
        ->name('admin.allocate-center.list')
        ->can('view-allocation');
    Route::post('/admin/allocate-center-list/new', [MedicalCenterManageController::class, 'newAllocateCenter'])
        ->name('admin.allocate-center.new')
        ->can('create-allocation');
    Route::post('/admin/allocate-center-list/update', [MedicalCenterManageController::class, 'updateAllocateCenter'])
        ->name('admin.allocate-center.update')
        ->can('update-allocation');

    Route::post('/admin/medical-center/change-password', [MedicalCenterManageController::class, 'MedicalCenterChangePassword'])
        ->name('admin.medical-center.change.password')
        ->can('change-password-medical-center');
    Route::post('/admin/medical-center/update', [MedicalCenterManageController::class, 'MedicalCenterUpdate'])
        ->name('admin.medical-center.update')
        ->can('update-medical-center');
    Route::get('/admin/medical-center/delete', [MedicalCenterManageController::class, 'MedicalCenterDelete'])
        ->name('admin.medical-center.delete')
        ->can('delete-medical-center');

    Route::get('/admin/application-list/allocations', [AdminAuthController::class, 'allocatedMedicalCenterList'])->name('admin.application-list.allocations');
    Route::get('/admin/application-list/allocations/{id}', [AdminAuthController::class, 'allocatedMedicalCenterDetails'])->name('admin.application-list.allocations.details');
    Route::get('/admin/application-list/allocations/approve/{id}', [AdminAuthController::class, 'allocatedMedicalCenterApprove'])
        ->name('admin.application-list.allocations.approve')
        ->can('update-allocation');
    Route::get('/admin/application-list/allocations/disapprove/{id}', [AdminAuthController::class, 'allocatedMedicalCenterDisapprove'])
        ->name('admin.application-list.allocations.disapprove')
        ->can('update-allocation');

    Route::get('/admin/score-request-history', [AdminDepositRequestHistoryController::class, 'depositRequestHistory'])
        ->name('admin.score-request-history');
    Route::post('/admin/score-request-history/add-score', [AdminDepositRequestHistoryController::class, 'addScore'])
        ->name('admin.score-request-history.add-score');

    Route::get('admin/transaction-history', [AdminDepositRequestHistoryController::class, 'transactionHistory'])
        ->name('admin.transaction-history');

    Route::get('admin/union-accounts', [UnionAccountManageController::class, 'index'])
        ->name('admin.union-accounts');
    Route::get('admin/union-accounts/new', [UnionAccountManageController::class, 'create'])
        ->name('admin.new.union-accounts');
    Route::post('admin/union-accounts/new', [UnionAccountManageController::class, 'store']);
    Route::get('admin/union-accounts/edit/{id}', [UnionAccountManageController::class, 'edit'])
        ->name('admin.edit.union-accounts');
    Route::post('admin/union-accounts/edit/{id}', [UnionAccountManageController::class, 'update']);
    Route::get('admin/union-accounts/assign/{id}', [UnionAccountManageController::class, 'assign'])
        ->name('admin.assign.union-accounts');
    Route::post('admin/union-accounts/assign/{id}', [UnionAccountManageController::class, 'assignStore']);

    Route::get('admin/application-list-single/{id}', [AdminAuthController::class, 'applicationListSingle'])->name('admin.application.list.single');
    Route::get('admin/all-notifications', [AdminAuthController::class, 'allNotification'])->name('admin.notification.all');

    Route::post('admin/customer-application-list/update-medical-status', [AdminAuthController::class, 'updateMedicalStatus'])->name('admin.application.list.update.medical-status');

    Route::get('admin/report-excel-list', [ExcelConverterController::class, 'excelList'])->name('admin.report.excel.list');
    Route::post('admin/report-excel-list', [ExcelConverterController::class, 'excelDownload']);
    Route::post('admin/excel-report/set-medical', [ExcelConverterController::class, 'setMedicalCenter'])->name('admin.excel.report.set.medical');

    Route::get('/admin/change-password', [AdminAuthController::class, 'changePassword'])->name('admin.change.password');
    Route::post('/admin/change-password', [AdminAuthController::class, 'changePasswordAction']);

    Route::get('/admin/general-settings', [GeneralSettingsController::class, 'generalSettings'])->name('admin.general.settings');
    Route::post('/admin/general-settings', [GeneralSettingsController::class, 'generalSettingsUpdate']);

    Route::get('/admin/upgrade-database', [DeveloperSettingsController::class, 'upgradeDatabase'])->name('admin.upgrade.database');
    Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});


Route::get('/medical', [MedicalCenterAuthController::class, 'login'])->name('medical.login');
Route::post('/medical', [MedicalCenterAuthController::class, 'loginAction']);

Route::middleware('auth:medical_center')->prefix('medical')->group(function () {
    Route::get('/dashboard', [MedicalCenterAuthController::class, 'dashboard'])->name('medical.dashboard');
    Route::get('/application-list', [MedicalCenterAuthController::class, 'applicationList'])->name('medical.application.list');

    Route::get('/application-edit/{id}', [MedicalCenterAuthController::class, 'applicationEdit'])->name('medical.application.edit');
    Route::post('/application-edit/{id}', [MedicalCenterAuthController::class, 'applicationUpdate']);

    Route::post('/application-update-result', [MedicalCenterAuthController::class, 'applicationUpdateResult'])->name('medical.application.result.update');

    Route::get('/application-list/search', [MedicalCenterAuthController::class, 'searchApplication'])->name('medical.application.search');

    Route::get('/change-password', [MedicalCenterAuthController::class, 'changePassword'])->name('medical.change.password');
    Route::post('/change-password', [MedicalCenterAuthController::class, 'changePasswordAction']);

    Route::post('/qr/check-application-id', [MedicalCenterAuthController::class, 'checkApplicationId'])->name('medical.check.application-id');
    Route::post('/qr/submit-serial-number', [MedicalCenterAuthController::class, 'submitSerialNumber'])->name('medical.submit.serial-number');

    Route::get('/logout', [MedicalCenterAuthController::class, 'logout'])->name('medical.logout');
});

Route::middleware('auth:union_account')->prefix('medicals')->group(function () {
    Route::get('/dashboard', [UnionMedicalManageController::class, 'dashboard'])->name('union.dashboard');
    Route::get('/medical-list', [UnionMedicalManageController::class, 'medicalList'])->name('union.medical.list');
    Route::get('/application-list', [UnionMedicalManageController::class, 'applicationList'])->name('union.application.list');
    Route::get('/application-list/pdf', [UnionMedicalManageController::class, 'applicationListPdf'])->name('union.application.list.generate.pdf');
    Route::get('/application-list/medical/pdf', [UnionMedicalManageController::class, 'medicalApplicationListPdf'])->name('union.medical.application.list.generate.pdf');

    Route::post('/application-update-result', [UnionMedicalManageController::class, 'applicationUpdateResult'])->name('union.application.result.update');
    Route::post('/customer-application-list/update-medical-status', [UnionMedicalManageController::class, 'updateMedicalStatus'])->name('user.application.list.update.medical-status');

    Route::get('/application-list-single/{id}', [UnionMedicalManageController::class, 'applicationListSingle'])->name('union.application.list.single');
    Route::get('/all-notifications', [UnionMedicalManageController::class, 'allNotification'])->name('union.notification.all');

//    Route::get('/application-edit/{id}', [MedicalCenterAuthController::class, 'applicationEdit'])->name('medical.application.edit');
//    Route::post('/application-edit/{id}', [MedicalCenterAuthController::class, 'applicationUpdate']);
//
//    Route::post('/application-update-result', [MedicalCenterAuthController::class, 'applicationUpdateResult'])->name('medical.application.result.update');
//
//    Route::get('/application-list/search', [MedicalCenterAuthController::class, 'searchApplication'])->name('medical.application.search');
//
//    Route::get('/change-password', [MedicalCenterAuthController::class, 'changePassword'])->name('medical.change.password');
//    Route::post('/change-password', [MedicalCenterAuthController::class, 'changePasswordAction']);

    Route::get('/logout', [UnionMedicalManageController::class, 'logout'])->name('union.logout');
});

Route::middleware('auth:union_account')->prefix('user')->group(function () {
    Route::get('/dashboard', [UnionUserManageController::class, 'dashboard'])->name('union.user.dashboard');
    Route::get('/user-list', [UnionUserManageController::class, 'UserList'])->name('union.user.list');

    Route::get('/application-list', [UnionUserManageController::class, 'applicationList'])->name('union.user.application.list');
    Route::post('/application-list/update', [UnionUserManageController::class, 'applicationUpdate'])->name('union.user.application.list.update');
    Route::get('/generate-pdf/{id}', [UserAuthController::class, 'generatePdf'])->name('union.user.generate.pdf');

    Route::get('/score-request/{id}', [UnionUserManageController::class, 'scoreRequest'])->name('union.user.score.request');
    Route::get('/customer/pay-bil', [UnionUserManageController::class, 'payBill'])->name('union.user.pay-bill');

    Route::get('/logout', [UnionUserManageController::class, 'logout'])->name('union.logout');
});
