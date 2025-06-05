<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LedgerCategory;
use App\Models\Ledger;
use App\Enums\TransactionType;
use App\Http\Requests\LedgerCategory\storeLedgerCategoryRequest;
use App\Http\Resources\LedgerCategory\BasicLedgerCategoryResource;

class LedgerCategoryController extends Controller
{
    public function index()
    {
        $ledgerCategories = LedgerCategory::with('ledger')->get();
        return response()->json(BasicLedgerCategoryResource::collection($ledgerCategories));
    }

    public function ledgerCategoriesOptions()
    {
        $ledgerOptions = Ledger::select('id', 'name')->get();
        $typeOptions = TransactionType::values();
        return response()->json(['ledgerOptions' => $ledgerOptions, 'typeOptions' => $typeOptions]);
    }

    public function store(storeLedgerCategoryRequest $request)
    {
        $data = $request->validated();
        $ledgerCategory = LedgerCategory::create($data);
        if (!$ledgerCategory) {
            return response()->json(['message' => 'Ledger category creation failed'], 500);
        }
        return response()->json(['message' => 'Ledger category created successfully'], 201);

    }
}
