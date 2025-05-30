<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ledger;

class LedgerController extends Controller
{
    public function index()
    {
        $ledgers = Ledger::all();
        return response()->json($ledgers);
    }
    public function getFirstLedger()
    {
        $ledger = Ledger::first();
        return response()->json($ledger);
    }

    public function getLedgerAmount($id)
    {
        $ledger = Ledger::find($id);
        return response()->json($ledger->amount);
    }


}