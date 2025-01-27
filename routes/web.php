<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;

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
    return view('welcome');
});
Route::get('/message', function () {
    return view('message');
});

Route::post('/send-message', function () {
    broadcast(new \App\Events\MessageSent(request()->message));
});
Route::get('/send-signal', [VideoController::class, 'video']);
Route::post('/send-signal', [VideoController::class, 'sendSignal']);
Route::get('/live-stream', [VideoController::class, 'showStream']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
