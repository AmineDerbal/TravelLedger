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

            $this->UpdateLedgerBalance($data['ledger_id'], $data['type'], $data['amount']) ;

            $profit = $data['profit'] ?? null;
            if ($profit !== null && $profit > 0) {
                $newData = [
                    'linked_transaction_id' => $transaction->id,
                    'user_id' => $data['user_id'],
                    'ledger_category_id' => '2',
                    'ledger_id' => '1',
                    'type' => '1',
                    'amount' => $data['profit'],
                    'date' => $data['date'],
                    'description' => $data['description'],
                ];

                return $this->createTransaction($newData);
            }

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
            $this->UpdateLedgerBalance($data['ledger_id'], $data['type'], $amountDifference);
        }

        return $this->jsonResponse('Transaction updated successfully', 200);
    }

    public function destroyTransaction($transaction)
    {

        $amount = -$transaction->amount;
        $type = $transaction->type;
        $ledgerId = $transaction->ledger_id;
        $linkdedId = $this->getLinkedTransaction($transaction);

        $transaction->delete();
        $this->UpdateLedgerBalance($ledgerId, $type, $amount);

        if ($linkdedId !== null) {
            $this->destroyTransaction(Transaction::find($linkdedId));
        }

        return $this->jsonResponse('Transaction deleted successfully', 200);
    }

    public function deactivateTranscation($transaction)
    {
        $amount = -$transaction->amount;
        $type = $transaction->type;
        $ledgerId = $transaction->ledger_id;
        $linkdedId = $this->getLinkedTransaction($transaction);

        $transaction->update(['is_active' => 0]);
        $this->UpdateLedgerBalance($ledgerId, $type, $amount);

        if ($linkdedId !== null) {
            $this->deactivateTranscation(Transaction::find($linkdedId));
        }

        return $this->jsonResponse('Transaction deactivated successfully', 200);

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



    private function UpdateLedgerBalance($ledgerId, $type, $amount): void
    {
        $ledger = Ledger::find($ledgerId);
        $ledger->updateAmount($amount, $type);
    }

    private function getLinkedTransaction($transaction): ?int
    {
        $linkdedId = null;
        if ($transaction->linkedTransaction !== null || $transaction->reverseLinkedTransaction !== null) {
            $linkdedId = $transaction->linkedTransaction->id ?? $transaction->reverseLinkedTransaction->id;
        }

        return $linkdedId;
    }

    private function jsonResponse(string $message, int $satus = 200): JsonResponse
    {
        return response()->json(['message' => $message], $satus);
    }

}
