<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Ledger;
use App\Models\LedgerCategory;
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

            $ledgerCategory = LedgerCategory::with('ledger')->find($data['ledger_category_id']);
            $ledgerId = $ledgerCategory->ledger['id'];
            $type = $ledgerCategory->type['value'];

            $this->UpdateLedgerBalance($ledgerId, $type, $data['amount']) ;

            $profit = $data['profit'] ?? null;
            if($profit !== null && $profit > 0) {
                $newData = [
                    'user_id' => $data['user_id'],
                    'ledger_category_id' => '2',
                    'amount' => $data['profit'],
                    'date' => $data['date'],
                    'description' => $data['description'],
                ];

               return $this->createTransaction($newData);
            }

            return $this->jsonSuccess('Transaction created successfully', 201);
        });
    }

    public function updateTransaction(array $data)
    {
        $transaction = Transaction::find($data['id']);
        if (!$transaction) {
            return $this->jsonError('Transaction not found', 404);
        }



        $amountDifference = $data['amount'] - $transaction->amount;
        $transaction->update($data);

        if ($amountDifference !== 0) {
            $this->UpdateLedgerBalance($data['ledger_id'], $data['type'], $amountDifference);
        }

        return $this->jsonSuccess('Transaction updated successfully', 200);
    }

    public function destroyTransaction($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return $this->jsonError('Transaction not found', 404);
        }
        $amount = -$transaction->amount;
        $type = $transaction->type;
        $ledgerId = $transaction->ledger_id;
        $transaction->delete();
        $this->UpdateLedgerBalance($ledgerId, $type, $amount);

        return $this->jsonSuccess('Transaction deleted successfully', 200);
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

    private function jsonSuccess(string $message, int $satus = 200): JsonResponse
    {
        return response()->json(['message' => $message], $satus);
    }
    private function jsonError(string $message, int $status): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }
}