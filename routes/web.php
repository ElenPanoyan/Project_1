<?php

use App\Http\Controllers\BulkLinkController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShortLinkController;
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
Auth::routes();

//export csv
Route::get('/shorten-links', [ShortLinkController::class, 'getAllData']);
Route::get('/exportCSV', [ShortLinkController::class, 'exportCSV'])->name('links');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

//generate shorten links Routes
    Route::get('/generate-shorten-link', [ShortLinkController::class, 'index']);
    Route::post('/generate-shorten-link', [ShortLinkController::class, 'generateShortLink'])->name('generate-shorten-link-post');
    Route::get('/{code}', [ShortLinkController::class, 'getShortenLink'])->name('shorten.link');

//bulk links csv upload
    Route::get('/bulk-links/import-csv', [BulkLinkController::class, 'view']);
    Route::post('/bulk-links/import-csv', [BulkLinkController::class, 'getBulkLinksCSV'])->name('bulk-import-csv');
});
