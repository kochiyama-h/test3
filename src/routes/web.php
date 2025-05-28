<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TransactionController;

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

Route::get('/', function () {
    return view('index');
});





Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [AuthController::class, 'index']);




Route::get('item/{id}', [ItemController::class, 'itemDetail'])->name('detail');
Route::get('purchase/item/{id}', [ItemController::class, 'purchase'])->name('purchase');
Route::get('purchase/address/item/{id}', [ItemController::class, 'address'])->name('address');
Route::put('purchase/address/item/{id}', [ItemController::class, 'addressUpdate'])->name('purchase.address.update');
Route::post('/', [ItemController::class, 'exhibit']);


Route::middleware('auth')->group(function () {
    Route::get('/mypage', [AuthController::class, 'profile'])->name('profile');
    Route::get('/sell', [AuthController::class, 'exhibit']);
    Route::post('item/{id}/comment', [ItemController::class, 'comment']);
    Route::post('/item/{itemId}/like', [ItemController::class, 'likeItem'])->name('item.like');
    

});

Route::get('/mypage/profile/{user}/setup', function (App\Models\User $user) {
    return view('edit', compact('user'));
})->name('profile.setup');


Route::get('/mypage/profile', [AuthController::class, 'profileUpdate']);
Route::post('/mypage/profile', [AuthController::class, 'edit']);

Route::post('purchase/item/{id}', [AuthController::class, 'purchase'])->name('purchase.item');

Route::get('/chat/{item}', [ChatController::class, 'show'])->name('chat');
Route::post('/transaction/complete', [TransactionController::class, 'complete'])->name('transaction.complete');


Route::prefix('chat')->group(function () {
    Route::post('send', [ChatController::class, 'send'])->name('chat.send');
    Route::put('messages/{id}/update', [ChatController::class, 'update'])->name('chat.update');
    Route::delete('messages/{id}/delete', [ChatController::class, 'destroy'])->name('chat.delete');
});