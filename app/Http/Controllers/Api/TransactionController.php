<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTranactionRequest;
use App\Http\Requests\Transaction\GetTransactionsByDateRangeRequest;
use App\Http\Resources\Transaction\BasicTransactionResource;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function getTransactionsByDateRange(GetTransactionsByDateRangeRequest $request)
    {
        $data = $request->validated();
        $transactions = Transaction::where('ledger_id', $data['ledger_id'])->whereBetween('date', [$data['start_date'], $data['end_date']])->get();
        return response()->json(BasicTransactionResource::collection($transactions), 201);

    }
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();

        return $this->transactionService->createTransaction($data);


    }

    public function update(UpdateTranactionRequest $request)
    {
        $data = $request->validated();


        return $this->transactionService->updateTransaction($data);

    }

    public function destroy($id)
    {

        return $this->transactionService->destroyTransaction($id);
    }
}
