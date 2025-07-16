<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LedgerController;
use App\Http\Controllers\Api\LedgerCategoryController;
use App\Http\Controllers\Api\TransactionMetaController;
use App\Http\Controllers\Api\TransactionExportController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;

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
        Route::get('ledgers/for-select', 'forSelect')->name('ledgers.for-select');
        Route::get('ledgers-with-categories', 'withCategories')->name('ledgers.categories');
        Route::get('ledgers/{id}', 'show')->name('ledgers.show');
        Route::get('ledgers/{id}/balance', 'getLedgerBalance')->name('ledgers.balance');
    });

    Route::controller(TransactionMetaController::class)->group(function () {
        Route::get('transaction-categories', 'categories')->name('transaction-categories');
        Route::get('transaction-types', 'types')->name('transaction-types');
    });

   Route::controller(TransactionController::class)->group(function () {
        Route::middleware(['check.permission:create Transaction'])->group(function () {
            Route::post('transactions/store', 'store')->name('transactions.store');
    });
     
        Route::post('transactions/date-range', 'getTransactionsByDateRange')->name('transactions.date-range')->middleware('check.permission:view Transaction');
  
        Route::middleware(['check.ownership:edit Transaction'])->group(function () {
            Route::put('transactions/{id}/update', 'update')->name('transactions.update');
            Route::delete('transactions/{id}', 'destroy')->name('transactions.destroy');
        }); 
    });

    Route::controller(LedgerCategoryController::class)->group(function () {
        Route::get('/ledger-categories', 'index')->name('ledger-categories.index');
        Route::get('/ledger-categories/ledger-categories-options', 'ledgerCategoriesOptions')->name('ledger-categories.ledger-categories-options');

        Route::middleware(['check.permission:manage Ledger'])->group(function () {
            Route::post('/ledger-categories/store', 'store')->name('ledger-categories.store');
            Route::put('/ledger-categories/update/{id}', 'update')->name('ledger-categories.update');
            Route::delete('/ledger-categories/{id}', 'destroy')->name('ledger-categories.destroy');
        });   
    });

    Route::controller(UserController::class)->group(function () {

        Route::get('/users/{id}/permissions', 'getPermissions')->name('users.permissions')->middleware('validate.user');

    });

    Route::post('/export-transactions', [TransactionExportController::class, 'export']);
 


});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });