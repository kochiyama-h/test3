<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;

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
    Route::get('/mypage', [AuthController::class, 'profile']);
    Route::get('/sell', [AuthController::class, 'exhibit']);
    Route::post('item/{id}/comment', [ItemController::class, 'comment']);
    Route::post('/item/{itemId}/like', [ItemController::class, 'likeItem'])->name('item.like');
    

});

Route::get('/mypage/profile/setup', function () {
    return view('edit');
});


Route::get('/mypage/profile', [AuthController::class, 'profileUpdate']);
Route::post('/mypage/profile', [AuthController::class, 'edit']);

Route::post('purchase/item/{id}', [AuthController::class, 'purchase'])->name('purchase.item');




