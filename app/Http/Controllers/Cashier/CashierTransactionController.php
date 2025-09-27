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
            'payment_method' => 'required',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        $cart = session()->get('cart', []);
        $transactionCode = session()->get('transaction_code');

        if (empty($cart)) {
            return redirect()->route('cashier.pos.index')->with('error', 'Keranjang kosong, tidak ada transaksi untuk diproses.');
        }

        // Hitung total belanja dari session
        $totalAmount = 0;
        foreach ($cart as $details) {
            $totalAmount += $details['price'] * $details['quantity'];
        }

        if ($request->payment_method === 'cash' && $request->payment_amount < $totalAmount) {
            return back()->with('error', 'Jumlah pembayaran kurang dari total tagihan.');
        }


        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'payment_amount' => $request->payment_amount,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'cash' ? 'completed' : 'pending',
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

            session()->forget(['cart', 'transaction_code']);

            DB::commit();

            return redirect()->route('cashier.transactions.receipt', $transaction->id)
                             ->with('success', 'Transaksi berhasil disimpan.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage());
        }
    }

    public function showReceipt(Transaction $transaction)
    {
        // Load relasi yang dibutuhkan untuk struk
        $transaction->load('user', 'transactionDetails.product.unit');

        return view('pages.kasir.pos.receipt', compact('transaction'));
    }

    public function history(Request $request)
    {
        // Query dasar untuk transaksi, diurutkan dari yang terbaru
        $query = Transaction::with('user')->latest();

        // Terapkan filter pencarian jika ada input 'search'
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('transaction_code', 'like', "%{$searchTerm}%");
        }

        // Ambil data dengan paginasi (15 item per halaman)
        $transactions = $query->paginate(15)->withQueryString();

        // Kirim data ke view
        return view('pages.kasir.pos.history', [
            'transactions' => $transactions,
            'search' => $request->input('search', '')
        ]);
    }
}
