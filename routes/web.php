<?php
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminController; // Impor AdminControl
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BranchController; // Pastikan Anda mengimpor BranchController
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
    // Rute untuk manajemen produk
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products');
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products/save', [ProductController::class, 'save'])->name('admin.products.save');
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('/admin/products/{id}/update', [ProductController::class, 'update'])->name('admin.products.update');
    Route::get('/admin/products/{id}/delete', [ProductController::class, 'delete'])->name('admin.products.delete');
    Route::get('/admin/products/{id}/qrcode', [ProductController::class, 'showQrCode'])->name('admin.products.qrcode');
    Route::get('/admin/products/{id}/detail', [ProductController::class, 'detail'])->name('admin.products.detail');

    // Rute untuk manajemen cabang
    Route::get('/admin/branch', [BranchController::class, 'index'])->name('admin.branch.index');
    Route::get('/admin/branch/create', [BranchController::class, 'create'])->name('admin.branch.create');
    Route::post('/admin/branch/store', [BranchController::class, 'store'])->name('admin.branch.store');
    Route::get('/admin/branch/{branch}/edit', [BranchController::class, 'edit'])->name('admin.branch.edit');
    Route::put('/admin/branch/{branch}', [BranchController::class, 'update'])->name('admin.branch.update');
    Route::delete('/admin/branch/{id}', [BranchController::class, 'destroy'])->name('admin.branch.destroy');

    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{user}/confirm', [AdminController::class, 'confirm'])->name('admin.users.confirm');
    Route::post('/admin/users/{user}/reject', [AdminController::class, 'reject'])->name('admin.users.reject');

});

// Route untuk branch admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/branch/products/create', [ProductController::class, 'createBranchProduct'])->name('branch.products.create');
    Route::post('/branch/products/save', [ProductController::class, 'saveBranchProduct'])->name('branch.products.save');
});

require __DIR__.'/auth.php';
require __DIR__.'/../vendor/autoload.php';
