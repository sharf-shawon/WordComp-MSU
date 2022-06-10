<?php

use App\Http\Controllers\Comparitor;
use App\Http\Controllers\Differentor;
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

Route::get('/', function(){
    return redirect('/view');
});
Route::get('/view', [Comparitor::class, 'view']);
Route::get('/json', [Comparitor::class, 'json']);
Route::get('/diff', [Differentor::class, 'view']);
Route::get('/findThis', [Differentor::class, 'findThis']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
