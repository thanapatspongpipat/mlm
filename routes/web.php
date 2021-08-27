<?php

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

// user
Route::get('/upline', [App\Http\Controllers\User\UserController::class, 'index'])->name('memberView');
Route::post('/member/list', [App\Http\Controllers\User\UserController::class, 'indexUserList'])->name('memberUserList');
Route::get('/member/create', [App\Http\Controllers\User\UserController::class, 'create'])->name('createView');
Route::post('/member/create', [App\Http\Controllers\User\UserController::class, 'createUser'])->name('createUser');

// org
Route::get('/upline', [App\Http\Controllers\User\OrgController::class, 'index'])->name('orgView');


//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');


// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//Language Translation
// Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// Referral
Route::get('/mlm/basic/{id}', [App\Http\Controllers\MLM\BasicController::class, 'computeFee'])->name('getReferral');
Route::get('/mlm/rollup/{id}-{pairId}-{type}', [App\Http\Controllers\MLM\RollUpController::class, 'index'])->name('getDealer');
Route::get('/mlm/logs/{id}-{pairId}-{type}', [App\Http\Controllers\MLM\LogsController::class, 'index'])->name("getLogs");
Route::get('/mlm/test/insertfee/{id}', [App\Http\Controllers\MLM\BasicController::class, 'insertFee'])->name('insertFee');
Route::get('/mlm/test/insertrollup/{id}', [App\Http\Controllers\MLM\BasicController::class, 'insertRollUp'])->name('insertRollUp');
