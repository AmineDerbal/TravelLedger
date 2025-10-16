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

    public function index()
    {
        $transactions = Transaction::with('ledgerCategory')->get();
        $transactionBalance = $this->transactionService->calculateTransactionTotal($transactions);
        return response()->json(['transactions' => BasicTransactionResource::collection($transactions), 'balance' => $transactionBalance], 201);
    }
    public function getTransactionsByDateRange(GetTransactionsByDateRangeRequest $request)
    {
        $data = $request->validated();
        $transactions = Transaction::with('ledgerCategory')->where('ledger_id', $data['ledger_id'])->where('is_active', 1)
        ->whereBetween('date', [$data['start_date'], $data['end_date']])
        ->get();

        $transactionBalance = $this->transactionService->calculateTransactionTotal($transactions);

        return response()->json(['transactions' => BasicTransactionResource::collection($transactions), 'balance' => $transactionBalance], 201);

    }

    public function toggleTransactionStatus($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return $this->jsonError('Transaction not found', 404);
        }
        $transactionStatus = $transaction['is_active'];
        $transaction->update(['is_active' => !$transactionStatus]);


        return $this->jsonResponse('Transaction status updated successfully', 200);


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
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        return $this->transactionService->destroyTransaction($transaction);
    }

    public function deactivate($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return $this->jsonError('Transaction not found', 404);
        }
        return $this->transactionService->deactivateTranscation($transaction);
    }
}
