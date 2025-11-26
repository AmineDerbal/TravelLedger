<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ledger;
use App\Http\Resources\Ledger\BasicLedgerResource;
use App\Http\Resources\Ledger\LegderWithCategoriesResource;
use App\Http\Requests\Ledger\StoreLedgerRequest;

class LedgerController extends Controller
{
    public function index()
    {
        $ledgers = Ledger::all();
        return response()->json(BasicLedgerResource::collection($ledgers));
    }

    public function store(StoreLedgerRequest $request)
    {
        $data = $request->validated();
        try {
            Ledger::create($data);
            return response()->json(['message' => 'Ledger created successfully'], 201);
        } catch (\Exception) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function show($id)
    {
        $ledger = Ledger::find($id);
        return response()->json(new BasicLedgerResource($ledger));
    }
    public function getFirstLedger()
    {
        $ledger = Ledger::first();
        return response()->json(new BasicLedgerResource($ledger));
    }

    public function forSelect()
    {
        $ledgers = Ledger::select('id', 'name')->get();
        return response()->json($ledgers);
    }

    public function withCategories()
    {

        $ledgers = Ledger::with('categories')->get();
        return response()->json(LegderWithCategoriesResource::collection($ledgers));
    }


    public function getLedgerBalance($id)
    {
        $ledger = Ledger::find($id);
        return response()->json(new BasicLedgerResource($ledger));
    }


}