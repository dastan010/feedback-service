<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('user');
Route::get('/admin', function(){
  return view('admin');
})->name('admin');
Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'index']);
Route::get('/admin/users/getTickets/{id}', [App\Http\Controllers\AdminController::class, 'getUserTickets']);
Route::get('/admin/users/fileDownload', [App\Http\Controllers\AdminController::class, 'downloadFile']);
Route::put('/admin/users/ticketResponse/{id}', [App\Http\Controllers\AdminController::class, 'update']);

Route::get('/add', [App\Http\Controllers\HomeController::class, 'index']);
Route::resource('/tickets', TicketController::class);
Auth::routes();
