<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Ledger;
use App\Http\Requests\Transaction\storeTransactionRequest;
use App\Http\Requests\Transaction\getTransactionsByDateRangeRequest;    

use App\Enums\TransactionType;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function getTransactionsByDateRange(getTransactionsByDateRangeRequest $request) {
        $data = $request->validated();
        $transactions = Transaction::where('ledger_id', $data['ledger_id'])->whereBetween('date', [$data['start_date'], $data['end_date']])->get();
        return response()->json($transactions, 200);
        
    }
    public function store(storeTransactionRequest $request) {
        $data = $request->validated();

        $transaction = Transaction::create($data);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction creation failed'], 500);
        }

        $ledger = Ledger::find($data['ledger_id']);
        if (!$ledger) {
            $transaction->delete();
            return response()->json(['message' => 'Ledger not found'], 404);
        }
            if(TransactionType::labelFromValue($data['type']) == 'Credit') {
                $ledger->amount += $data['amount'];
            } else {
                $ledger->amount -= $data['amount'];
            }
            $ledger->save();
            return response()->json(['message' => 'Transaction created successfully'], 201);
       
    }
}