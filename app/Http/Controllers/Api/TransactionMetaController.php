<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\TransactionType;
use App\Enums\TransactionCategory;

class TransactionMetaController extends Controller
{
    public function categories()
    {
        return response()->json(TransactionCategory::values());
    }

    public function types()
    {
        return response()->json(TransactionType::values());
    }
}
