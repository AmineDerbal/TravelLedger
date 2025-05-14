<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\TransactionCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = TransactionCategory::values();
        return response()->json($categories);
    }
}