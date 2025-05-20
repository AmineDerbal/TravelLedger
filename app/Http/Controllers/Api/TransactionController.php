<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Http\Requests\Transaction\storeTransactionRequest;
use App\Http\Requests\Transaction\getTransactionsByDateRangeRequest;    
use App\Http\Resources\Transaction\BasicTransactionResource;
use App\Services\TransactionService;



class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService) {
        $this->transactionService = $transactionService;
    }
    public function getTransactionsByDateRange(getTransactionsByDateRangeRequest $request) {
        $data = $request->validated();
        $transactions = Transaction::where('ledger_id', $data['ledger_id'])->whereBetween('date', [$data['start_date'], $data['end_date']])->get();
        return response()->json(BasicTransactionResource::collection($transactions), 201);
        
    }
    public function store(storeTransactionRequest $request) {
        $data = $request->validated();

        return $this->transactionService->createTransaction($data);

       
    }
}