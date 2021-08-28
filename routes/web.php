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
Route::get('/icon', [App\Http\Controllers\Controller::class, 'icon'])->name('icon');
Route::get('/ui-general', [App\Http\Controllers\Controller::class, 'uiGeneral'])->name('ui-general');
// Route::get('/depositCash', [App\Http\Controllers\Controller::class, 'depositCash'])->name('depositCash');
// Route::get('/depositCashWithValidation', [App\Http\Controllers\Controller::class, 'depositCashWithValidation'])->name('depositCash');
Route::get('/withdrawCash', [App\Http\Controllers\Controller::class, 'withdrawCash'])->name('withdrawCash');

// user
Route::get('/member', [App\Http\Controllers\User\UserController::class, 'index'])->name('memberView');
// Route::get('/member/items', [App\Http\Controllers\User\UserController::class, 'listItem'])->name('itemView');
Route::get('/member/items/{upline_id}/{position}', [App\Http\Controllers\User\UserController::class, 'listItem'])->name('itemView');
Route::post('/member/list', [App\Http\Controllers\User\UserController::class, 'indexUserList'])->name('memberUserList');
Route::get('/member/{product_id}/{upline_id}/{position}/create', [App\Http\Controllers\User\UserController::class, 'create'])->name('createView');
Route::post('/member/create', [App\Http\Controllers\User\UserController::class, 'createUser'])->name('createUser');
Route::post('/member/create/search', [App\Http\Controllers\User\UserController::class, 'createUserFindInvite'])->name('create.user.find.invite');

// org
Route::get('/upline', [App\Http\Controllers\User\OrgController::class, 'index'])->name('orgView');
Route::post('/upline', [App\Http\Controllers\User\OrgController::class, 'uplineList'])->name('orgUplineList');
Route::post('/upline/info', [App\Http\Controllers\User\OrgController::class, 'uplineListInfo'])->name('orgUplineList.info');

//Update User Details
//Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');
Route::get('/profile', [App\Http\Controllers\AccountController::class, 'index'])->name('accountProfile');
Route::get('/profile/change-password', [App\Http\Controllers\AccountController::class, 'changePassIndex'])->name('changePassword');
Route::post('/profile/update-password', [App\Http\Controllers\AccountController::class, 'changePassword'])->name('updatePass');
Route::get('/profile/update', [App\Http\Controllers\AccountController::class, 'updateIndex'])->name('updateProfile');
Route::put('/profile/edit', [App\Http\Controllers\AccountController::class, 'accountProfileUpdate'])->name('accountProfileUpdate');
Route::get('/start-change-password', [App\Http\Controllers\StartUserController::class, 'startChangePassIndex'])->name('startChangePassIndex');
Route::post('/start-update-password', [App\Http\Controllers\StartUserController::class, 'changePassword'])->name('startChangePass');

// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//Language Translation
// Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);


// //Manage Package
// Route::get('/package', [App\Http\Controllers\PackageController::class, 'index'])->name('package.index');
// Route::post('/package/show', [App\Http\Controllers\PackageController::class, 'show'])->name('package.show');
// Route::post('/package/store', [App\Http\Controllers\PackageController::class, 'store'])->name('package.store');
// Route::post('/package/delete', [App\Http\Controllers\PackageController::class, 'delete'])->name('package.delete');

//wallets
Route::get('/wallet/index', [App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');
Route::get('/wallet/cash-wallet/create', [App\Http\Controllers\WalletController::class, 'createCashWallet'])->name('wallet.cash-wallet.create');
Route::get('/wallet/coin-wallet/create', [App\Http\Controllers\WalletController::class, 'createCoinWallet'])->name('wallet.coin-wallet.create');

//Manage Cash wallet
Route::get('/wallet/cash-wallet', [App\Http\Controllers\CashWalletController::class, 'index'])->name('wallet.cash-wallet.index');
Route::post('/wallet/cash-wallet/search', [App\Http\Controllers\CashWalletController::class, 'search'])->name('wallet.cash-wallet.search');

//Manage Coin wallet
Route::get('/wallet/coin-wallet', [App\Http\Controllers\CoinWalletController::class, 'index'])->name('wallet.coin-wallet.index');
Route::post('/wallet/coin-wallet/search', [App\Http\Controllers\CoinWalletController::class, 'search'])->name('wallet.coin-wallet.search');

//User Withdraw
Route::get('/withdraw', [App\Http\Controllers\WithdrawController::class, 'index'])->name('withdraw.index');
Route::get('/withdraw/edit-bank/store', [App\Http\Controllers\WithdrawController::class, 'editBankIndex'])->name('withdraw.edit-bank');
Route::post('/withdraw/edit-bank', [App\Http\Controllers\WithdrawController::class, 'storeBank'])->name('withdraw.edit-bank.store');
Route::post('/withdraw/store', [App\Http\Controllers\WithdrawController::class, 'store'])->name('withdraw.store');
Route::post('/withdraw/show', [App\Http\Controllers\WithdrawController::class, 'show'])->name('withdraw.show');
Route::post('/withdraw/get-balance', [App\Http\Controllers\WithdrawController::class, 'getBalance'])->name('withdraw.get-balance');

//User Deposit
Route::get('/deposit', [App\Http\Controllers\DepositController::class, 'index'])->name('deposit.index');
Route::post('/deposit/store', [App\Http\Controllers\DepositController::class, 'store'])->name('deposit.store');
Route::post('/deposit/show', [App\Http\Controllers\DepositController::class, 'show'])->name('deposit.show');
Route::post('/deposit/get-balance', [App\Http\Controllers\DepositController::class, 'getBalance'])->name('deposit.get-balance');

// //Admin Deposit
// Route::get('/admin/deposit', [App\Http\Controllers\AdminDepositController::class, 'index'])->name('admin.deposit.index');
// Route::post('/admin/deposit/store', [App\Http\Controllers\AdminDepositController::class, 'store'])->name('admin.deposit.store');
// Route::post('/admin/deposit/show', [App\Http\Controllers\AdminDepositController::class, 'show'])->name('admin.deposit.show');

// //Admin Withdraw
// Route::get('/admin/withdraw', [App\Http\Controllers\AdminWithdrawController::class, 'index'])->name('admin.withdraw.index');
// Route::post('/admin/withdraw/store', [App\Http\Controllers\AdminWithdrawController::class, 'store'])->name('admin.withdraw.store');
// Route::post('/admin/withdraw/show', [App\Http\Controllers\AdminWithdrawController::class, 'show'])->name('admin.withdraw.show');

// //Admin Report
// Route::get('/admin/report/summary-in-out', [App\Http\Controllers\ReportController::class, 'summaryInOut'])->name('admin.report.summary-in-out.index');
// Route::post('/admin/report/summary-in-out/show', [App\Http\Controllers\ReportController::class, 'showSummaryInOut'])->name('admin.report.summary-in-out.show');


//sponsor
Route::get('/sponsor', [App\Http\Controllers\Sponsor\SponsorController::class, 'index'])->name('sponsor.index');
Route::post('/sponsor/list', [App\Http\Controllers\Sponsor\SponsorController::class, 'getList'])->name('sponsor.list');

Route::get('/member-upgrade', [App\Http\Controllers\Upgrade\UpgradeController::class, 'memberUpgrade'])->name('member.upgrade');
Route::post('/member-upgrade/product-list', [App\Http\Controllers\Upgrade\UpgradeController::class, 'productList'])->name('member.product-list');

// MLM Debug
Route::get('/mlm/basic/{id}', [App\Http\Controllers\MLM\BasicController::class, 'computeFee'])->name('getReferral');
Route::get('/mlm/rollup/{id}-{pairId}-{type}', [App\Http\Controllers\MLM\RollUpController::class, 'index'])->name('getDealer');
Route::get('/mlm/logs', [App\Http\Controllers\MLM\LogsController::class, 'getLogs']);
Route::get('/mlm/logs/{id}-{pairId}-{type}', [App\Http\Controllers\MLM\LogsController::class, 'index'])->name("getLogs");
// MLM Insert Functions
Route::get('/mlm/test/insert-fee/{id}', [App\Http\Controllers\MLM\BasicController::class, 'insertFee'])->name('insertFee');
Route::get('/mlm/test/insert-rollup/{id}', [App\Http\Controllers\MLM\BasicController::class, 'insertRollUp'])->name('insertRollUp');
Route::get('/mlm/test/insert-key/{id}/{childUser}', [App\Http\Controllers\MLM\LogsController::class, 'insertKey'])->name('insertKey');
Route::get('/mlm/test/insert-couple/{id}', [App\Http\Controllers\MLM\LogsController::class, 'insertCouple'])->name('insertCouple');
