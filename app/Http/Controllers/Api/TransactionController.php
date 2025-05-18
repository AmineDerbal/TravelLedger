<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Ledger;
use App\Http\Requests\Transaction\storeTransactionRequest;
use App\Enums\TransactionType;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
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