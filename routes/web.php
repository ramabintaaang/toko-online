<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TokoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::prefix('api')->group(function () {
    Route::get('/allProduk', [ProdukController::class, 'allProduk'])->name('allProduk');
    Route::get('/getEditProduk/{id}', [ProdukController::class, 'getEditProduk'])->name('getEditProduk');
    Route::post('/addProduk', [ProdukController::class, 'addProduk'])->name('addProduk');
    Route::post('/updateProduk', [ProdukController::class, 'updateProduk'])->name('updt');

    Route::post('/deleteProduk', [ProdukController::class, 'deleteProduk2'])->name('deleteProduk');
    // Route::get('/cari', [ProdukController::class, 'cari'])->name('cari');
});
Route::get('/getKeranjang', [ProdukController::class, 'getKeranjang'])->name('getKeranjang');
Route::get('/getEditKeranjang/{id}', [ProdukController::class, 'getEditKeranjang'])->name('getEditKeranjang');
Route::post('/addKeranjang', [ProdukController::class, 'addKeranjang'])->name('addKeranjang');
Route::post('/updateKeranjang', [ProdukController::class, 'updateKeranjang'])->name('updateKeranjang');

///dashboard user 
Route::get('/dashboard', [HomeController::class, 'awal'])->name('dashboard');
Route::get('/dashboard/keranjang', [HomeController::class, 'keranjang'])->name('keranjang');



Route::get('/tokosephia', [TokoController::class, 'getProduk'])->name('tokosephia');



// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
