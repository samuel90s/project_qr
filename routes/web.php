<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Rute untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Rute untuk manajemen profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk dashboard
Route::get('/dashboard', function () {
    return redirect()->route('admin.products'); // Mengarahkan ke rute admin.products
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products'); // Rute ini harus ada
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products/save', [ProductController::class, 'save'])->name('admin.products.save');
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('/admin/products/{id}/update', [ProductController::class, 'update'])->name('admin.products.update');
    Route::get('/admin/products/{id}/delete', [ProductController::class, 'delete'])->name('admin.products.delete');
    Route::get('/admin/products/{id}/qrcode', [ProductController::class, 'showQrCode'])->name('admin.products.qrcode');
    Route::get('/admin/products/{id}/detail', [ProductController::class, 'detail'])->name('admin.products.detail');
});

require __DIR__.'/auth.php';
require __DIR__.'/../vendor/autoload.php';

