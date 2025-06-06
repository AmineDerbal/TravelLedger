<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LedgerController;
use App\Http\Controllers\Api\TransactionMetaController;
use App\Http\Controllers\Api\TransactionExportController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(AuthController::class)->group(function () {
    Route::post('auth/register', 'register')->name('auth.register');
    Route::post('auth/login', 'login')->name('auth.login');
});
Route::middleware(['token.cookie','auth:sanctum'])->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::controller(LedgerController::class)->group(function () {
        Route::get('ledgers', 'index')->name('ledgers.index');
        Route::get('ledgers/first-entry', 'getFirstLedger')->name('ledgers.first-entry');
        Route::get('ledgers/{id}/amount', 'getLedgerAmount')->name('ledgers.amount');
    });

    Route::controller(TransactionMetaController::class)->group(function () {
        Route::get('transaction-categories', 'categories')->name('transaction-categories');
        Route::get('transaction-types', 'types')->name('transaction-types');
    });

    Route::controller(TransactionController::class)->group(function () {
        Route::post('transactions/store', 'store')->name('transactions.store');
        Route::post('transactions/date-range', 'getTransactionsByDateRange')->name('transactions.date-range');
        Route::put('transactions/update', 'update')->name('transactions.update');
        Route::delete('transactions/{id}', 'destroy')->name('transactions.destroy');
    });

    Route::post('/export-transactions', [TransactionExportController::class, 'export']);


});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });