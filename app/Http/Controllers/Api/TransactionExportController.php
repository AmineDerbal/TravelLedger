<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exports\TransactionsWithBalanceExport;
use App\Http\Requests\Transaction\TransactionExportRequest;
use Maatwebsite\Excel\Facades\Excel;

class TransactionExportController extends Controller
{
    public function export(TransactionExportRequest $request)
    {
        $data = $request->validated();
        $transactions = $data['transactions'];
        $balance = $data['balance'];
        $exportData = $data['exportDate'];

        if (!$transactions || !$balance) {
            return response()->json(['error' => 'Invalid request data'], 400);
        }

        $fileName = 'transactions_From_' . $exportData['start_date'] . '_To_' . $exportData['end_date'] . '.xlsx';


        return Excel::download(new TransactionsWithBalanceExport($transactions, $balance), $fileName);
    }
}
