<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ticket\TicketController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;

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

Route::get('/', [HomeController::class, 'index'])->name('user');


Route::prefix('admin')->group(function() {
  Route::get('/', function(){
    return view('admin');
  })->name('admin');
  
  Route::get('/users', [AdminController::class, 'index']);
  Route::get('/users/getTickets/{id}', [AdminController::class, 'getUserTickets']);
  Route::get('/users/{user_id}/tickets/{ticket_id}/fileDownload', [AdminController::class, 'downloadFile']);
  Route::put('/users/ticketResponse/{id}', [AdminController::class, 'update']);
});

Route::get('/add', [TicketController::class, 'store']);
Route::resource('/tickets', TicketController::class);
Auth::routes();
