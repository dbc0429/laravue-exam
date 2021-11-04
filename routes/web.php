<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::resource('product', ProductController::class);
Route::get('/', function () {
    return view('layouts.app');
});
Route::prefix('api')->group(function () {
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
    Route::get('/search', [App\Http\Controllers\ProductController::class, 'search'])->name('search');
    Route::get('/searchAvailabily', [App\Http\Controllers\ProductController::class, 'searchAvailabily'])->name('searchAvailabily'); 
    Route::get('/searchMinPrice', [App\Http\Controllers\ProductController::class, 'searchMinPrice'])->name('searchMinPrice');

});
