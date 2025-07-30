<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Ledger;
use App\Enums\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class TransactionService
{
    public function createTransaction(array $data)
    {

        return DB::transaction(function () use ($data) {

            $transaction = Transaction::create($data);

            if (!$transaction) {
                return response()->json(['message' => 'Transaction creation failed'], 500);
            }

            $this->updateLedgerBalance($data['ledger_id'], $data['type'], $data['amount']) ;
            return $this->jsonResponse('Transaction created successfully', 201);
        });
    }


    public function updateTransaction(array $data)
    {
        $transaction = Transaction::find($data['id']);
        if (!$transaction) {
            return $this->jsonResponse('Transaction not found', 404);
        }


        $amountDifference = $data['amount'] - $transaction->amount;
        $transaction->update($data);

        if ($amountDifference !== 0) {
            $this->updateLedgerBalance($data['ledger_id'], $data['type'], $amountDifference);
        }

        return $this->jsonResponse('Transaction updated successfully', 200);
    }

    public function destroyTransaction($transaction)
    {

        $transaction->delete();
        return $this->jsonResponse('Transaction deleted successfully', 200);
    }

    public function deactivateTranscation($transaction)
    {
        ['amount' => $amount, 'type' => $type, 'ledgerId' => $ledgerId] =
    $this->extractTransactionDetails($transaction);

        $transaction->update(['is_active' => 0]);
        $this->updateLedgerBalance($ledgerId, $type, $amount);

        return $this->jsonResponse('Transaction deleted successfully', 200);

    }

    public function calculateTransactionTotal(Collection $transactions): array
    {

        $totalDebit = $transactions->where('type', TransactionType::Debit->value)->sum('amount');
        $totalCredit = $transactions->where('type', TransactionType::Credit->value)->sum('amount');
        $totalBalance = $totalCredit - $totalDebit;

        return [
         'totalDebit' => number_format($totalDebit, 2),
        'totalCredit' => number_format($totalCredit, 2),
        'totalBalance' => number_format($totalBalance, 2),
        ];

    }



    private function updateLedgerBalance($ledgerId, $type, $amount): void
    {
        $ledger = Ledger::find($ledgerId);
        $ledger->updateAmount($amount, $type);
    }

    private function jsonResponse(string $message, int $status = 200): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }

    private function extractTransactionDetails(Transaction $transaction): array
    {
        return [
            'amount'   => -$transaction->amount,
            'type'     => $transaction->type,
            'ledgerId' => $transaction->ledger_id,

        ];
    }

}