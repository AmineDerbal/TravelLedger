<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exports\TransactionsWithBalanceExport;
use App\Http\Requests\Transaction\TransactionExportRequest;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TransactionExportController extends Controller
{
        public function export(TransactionExportRequest $request)
    {
        $data = $request->validated();
   $transactions = $data['transactions'];
    $balance = $data['balance'];

    if (!$transactions || !$balance) {
        return response()->json(['error' => 'Invalid request data'], 400);
    }

   

    return Excel::download(new TransactionsWithBalanceExport($transactions, $balance), 'transactions_with_balance.xlsx');
    }
}