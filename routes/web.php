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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('auth')->group(function () {
    Route::resource('document', App\Http\Controllers\DocumentController::class);
    Route::get('/download/{document}', [App\Http\Controllers\DocumentController::class, 'download'])->name('download');
    Route::post('/download', [App\Http\Controllers\DocumentController::class, 'authDownload'])->name('auth.download');
    Route::get('/documentFind', [App\Http\Controllers\DocumentController::class, 'findDocument'])->name('document.find');
});
