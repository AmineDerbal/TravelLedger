<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

Class TransactionService {
  
  public function createTransaction(array $data) {

    return DB::transaction(function () use ($data) {
      $transaction = Transaction::create($data);
      
      if(!$transaction) {
        return response()->json(['message' => 'Transaction creation failed'], 500);
      }

             $ledger = Ledger::find($data['ledger_id']);
             if (!$ledger) {
                 $transaction->delete();
                 return response()->json(['message' => 'Ledger not found'], 404);
             }
            
             $ledger->updateAmount($data['amount'], $data['type']);
             return response()->json(['message' => 'Transaction created successfully'], 201);
    });
  }
}