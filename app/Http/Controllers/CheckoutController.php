<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // 1. Tampilkan form checkout
    public function index()
    {
        $products = Product::all();
        return view('index', compact('products'));
    }

    // 2. Proses checkout tanpa Tripay
    public function checkout(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'required|string',
            'product_id' => 'required|exists:products,id',
        ]);

        $customer = Customer::create([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $product = Product::findOrFail($request->product_id);
        $reference = 'REAND-' . strtoupper(Str::random(10));

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'product_id'  => $product->id,
            'reference'   => $reference,
            'status'      => 'UNPAID',
        ]);

        // Data dummy pembayaran manual tanpa Tripay
        $tripayDummy = [
            'payment_name' => 'Pembayaran Manual',
            'qr_url'       => null,
            'pay_code'     => null,
            'checkout_url' => '#',
        ];

        // Tampilkan view rincian pembayaran sederhana tanpa Tripay
        return view('payment-details', [
            'customer'    => $customer,
            'product'     => $product,
            'transaction' => $transaction,
            'tripay'      => $tripayDummy
        ]);
    }

    // 3. Status pembayaran (bisa tetap ada atau dikosongkan)
    public function paymentStatus($reference)
    {
        $transaction = Transaction::where('reference', $reference)->firstOrFail();

        // Tidak ada cek status Tripay
        // Bisa kembalikan view dengan status transaksi saja
        return view('payment-status', compact('transaction'));
    }

    // 4. Checkout AJAX tanpa Tripay, pakai data dummy juga
    public function checkoutAjax(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'required|string',
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        $customer = Customer::create([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $tripayDummy = [
            'payment_name' => 'Pembayaran Manual',
            'qr_url'       => null,
            'pay_code'     => null,
            'checkout_url' => '#',
        ];

        return response()->json([
            'success'  => true,
            'tripay'   => $tripayDummy,
            'product'  => $product,
            'customer' => $customer,
        ]);
    }
}
