<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/pos');
});

// POS Routes
Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [PosController::class, 'index'])->name('index');
    Route::post('/sale', [PosController::class, 'storeSale'])->name('sale.store');
    Route::post('/client', [PosController::class, 'storeClient'])->name('client.store');
    Route::get('/clients/search', [PosController::class, 'searchClient'])->name('client.search');
    Route::get('/receipt/{sale}', [PosController::class, 'receipt'])->name('receipt');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.products.index');
    });

    // Products CRUD
    Route::resource('products', ProductController::class);

    // Clients CRUD
    Route::resource('clients', ClientController::class);

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});
