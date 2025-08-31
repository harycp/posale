<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CashierCartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (!empty($cart) && !session()->has('transaction_code')) {
            $transactionCode = 'TR-' . date('Ymd') . '-' . strtoupper(uniqid());
            session()->put('transaction_code', $transactionCode);
        }

        $transactionCode = session()->get('transaction_code');
        
        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('pages.kasir.pos.cart', compact('cart', 'total', 'transactionCode'));
    }

    public function add(Request $request)
    {
        $request->validate(['products' => 'present|array']);

        $productsToAdd = $request->products;
        $cart = session()->get('cart', []);
        $addedCount = 0;
        $warnings = [];

        foreach ($productsToAdd as $productId => $details) {
            $quantityToAdd = (int) $details['quantity'];

            if ($quantityToAdd > 0) {
                $product = Product::with('unit')->find($productId);
                if (!$product) continue;

                if (isset($cart[$productId])) {
                    $newQuantity = $cart[$productId]['quantity'] + $quantityToAdd;

                    if ($newQuantity > $product->stock) {
                        $warnings[] = "Stok '{$product->name}' tidak cukup. Kuantitas diatur ke maks: {$product->stock}.";
                        $cart[$productId]['quantity'] = $product->stock; // Set to max available stock
                    } else {
                        $cart[$productId]['quantity'] = $newQuantity;
                    }

                } else {
                     if ($quantityToAdd > $product->stock) {
                        $warnings[] = "Stok '{$product->name}' tidak cukup. Kuantitas diatur ke maks: {$product->stock}.";
                        $cart[$productId]['quantity'] = $product->stock;
                    } else {
                        $cart[$productId]['quantity'] = $quantityToAdd;
                    }
                    
                    $cart[$productId]['name'] = $product->name;
                    $cart[$productId]['price'] = $product->selling_price;
                    $cart[$productId]['image'] = $product->image;
                    $cart[$productId]['unit'] = $product->unit->short_name;
                }
                $addedCount++;
            }
        }
        
        session()->put('cart', $cart);

        $redirect = redirect()->route('cashier.cart.index');
        
        if ($addedCount > 0) {
            $redirect->with('success', "List barang berhasil diperbarui.");
        }
        
        if (!empty($warnings)) {
            $redirect->with('warning', implode('<br>', $warnings)); 
        }

        return $redirect;
    }
    
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            $product = Product::find($id);
            if ($request->quantity > $product->stock) {
                return redirect()->back()->with('error', 'Kuantitas melebihi stok yang tersedia!');
            }
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Kuantitas berhasil diupdate.');
    }

    public function destroy($id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        if (empty(session()->get('cart'))) {
            session()->forget('transaction_code');
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari list.');
    }
}
