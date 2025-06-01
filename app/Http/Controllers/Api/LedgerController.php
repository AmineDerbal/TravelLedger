<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Http\Resources\Ledger\BasicLedgerResource;

class LedgerController extends Controller
{
    public function index()
    {
        $ledgers = Ledger::all();
        return response()->json(BasicLedgerResource::collection($ledgers));
    }
    public function getFirstLedger()
    {
        $ledger = Ledger::first();
        return response()->json(new BasicLedgerResource($ledger));
    }

    public function getLedgerAmount($id)
    {
        $ledger = Ledger::find($id);
        return response()->json($ledger->amount);
    }


}