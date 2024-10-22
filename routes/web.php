<?php

use App\Http\Controllers\AdminAuthController;
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
    Route::post('/admin/application-update', [AdminAuthController::class, 'applicationUpdate'])->name('admin.application.update');

    Route::get('/admin/new-user', [AdminAuthController::class, 'newUser'])->name('admin.new.user');
    Route::post('/admin/new-user', [AdminAuthController::class, 'newUserCreate']);

    Route::get('/admin/user-list', [AdminAuthController::class, 'userList'])->name('admin.user.list');

    Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});
