<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\WilayahController;
use App\Models\Kabupaten;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route untuk login admin
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Semua route admin dilindungi oleh middleware auth:admin
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/maps', [WilayahController::class, 'map'])->name('admin.maps.index');
    Route::get('/admin/peta-wilayah', [WilayahController::class, 'map'])->name('admin.wilayah.map');
    Route::resource('/admin/wilayah', WilayahController::class);
    Route::get('/admin/peta-kecamatan', [KecamatanController::class, 'map'])->name('admin.kecamatan.map');
    Route::get('/admin/peta-kabupaten', [KabupatenController::class, 'map'])->name('admin.kabupaten.map');
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('kabupaten', KabupatenController::class);


});
Route::get('/get-polygon-kabupaten', [WilayahController::class, 'getPolygonKabupaten']);
Route::get('/get-kecamatan/{id}', [WilayahController::class, 'getKecamatan'])->name('get.kecamatan');
Route::get('/', [HomeController::class, 'indexs'])->name('home');

