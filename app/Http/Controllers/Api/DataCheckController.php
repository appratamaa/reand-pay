<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;

class DataCheckController extends Controller
{
    public function latestData()
    {
        $latestCustomer = Customer::latest()->first();
        $latestProduct = Product::latest()->first();
        $latestTransaction = Transaction::latest()->first();

        return response()->json([
            'customer'    => $latestCustomer,
            'product'     => $latestProduct,
            'transaction' => $latestTransaction,
        ]);
    }
}
