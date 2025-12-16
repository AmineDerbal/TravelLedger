<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LedgerCategory;
use App\Models\Ledger;
use App\Enums\TransactionType;
use App\Http\Requests\LedgerCategory\storeLedgerCategoryRequest;
use App\Http\Requests\LedgerCategory\updateLedgerCategoryRequest;
use App\Http\Resources\LedgerCategory\BasicLedgerCategoryResource;

class LedgerCategoryController extends Controller
{
    public function index()
    {
        $ledgerCategories = LedgerCategory::all();
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
        try {
            LedgerCategory::create($data);
            return response()->json(['message' => 'Ledger category created successfully'], 201);
        } catch (\Exception) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function update(updateLedgerCategoryRequest $request, $id)
    {
        $data = $request->validated();
        try {
            LedgerCategory::find($id)->update($data);
            return response()->json(['message' => 'Ledger category updated successfully'], 200);
        } catch (\Exception) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            LedgerCategory::find($id)->delete();
            return response()->json(['message' => 'Ledger category deleted successfully'], 200);
        } catch (\Exception) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

}