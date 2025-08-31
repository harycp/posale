<?php

namespace App\Http\Controllers\Cashier;

use Exception;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CashierTransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0',
        ]);

        $cart = session()->get('cart', []);
        $transactionCode = session()->get('transaction_code');
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        if (empty($cart)) {
            return redirect()->route('cashier.pos.index')->with('error', 'Keranjang kosong, tidak ada transaksi untuk diproses.');
        }

        if ($request->payment_amount < $total) {
            return redirect()->back()->with('error', 'Jumlah pembayaran tidak mencukupi.');
        }

        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'user_id' => Auth::id(), // ID kasir yang sedang login
                'total_amount' => $total,
                'payment_amount' => $request->payment_amount,
                'status' => 'completed',
            ]);

            foreach ($cart as $productId => $details) {
                $transaction->transactionDetails()->create([
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price_at_transaction' => $details['price'],
                    'subtotal' => $details['price'] * $details['quantity'],
                ]);

                $product = Product::find($productId);
                $product->decrement('stock', $details['quantity']);
            }

            DB::commit();

            session()->forget(['cart', 'transaction_code']);

            return redirect()->route('cashier.pos.index')->with('success', 'Transaksi berhasil!');

        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses transaksi. Silakan coba lagi.');
        }
    }
}
