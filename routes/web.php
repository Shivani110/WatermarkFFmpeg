<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

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

Route::get('/', function () {
    return redirect('/watermark-image');
});

Route::get('/watermark-image',[TestController::class,'watermarkImage']);
// Route::post('/add_watermark',[TestController::class,'addWatermark']);
// Route::post('/add_watermark',[TestController::class,'addTextwatermark']);
// Route::post('/add_watermark',[TestController::class,'addRepeatedWatermark']);
// Route::post('/add_watermark',[TestController::class,'addOverlayImage']);
Route::post('/add_watermark',[TestController::class,'addStaticImage']);
