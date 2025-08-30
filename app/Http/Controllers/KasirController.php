<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KasirController extends Controller
{
    public function index()
    {
        $produk = Produk::paginate(10);
        return view('kasir.index', compact('produk'));
    }

    public function tambahKeKeranjang(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;
        $produk = Produk::find($id);

        if (!$produk) return redirect()->back()->with('error', 'Produk tidak ditemukan');

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += 1;
        } else {
            $cart[$id] = [
                'id' => $produk->id,
                'nama' => $produk->nama,
                'harga' => $produk->harga,
                'qty' => 1
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Ditambahkan ke keranjang');
    }

    public function lihatKeranjang()
    {
        $cart = session()->get('cart', []);
        return view('kasir.keranjang', compact('cart'));
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->back()->with('error', 'Keranjang kosong');

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-' . Str::random(6),
            'total' => $total,
            'metode_pembayaran' => $request->metode ?? 'Tunai'
        ]);

        foreach ($cart as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item['id'],
                'qty' => $item['qty'],
                'harga_satuan' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);

            $produk = Produk::find($item['id']);
            $produk->decrement('stok', $item['qty']);
        }

        session()->forget('cart');
        return redirect()->route('kasir.index')->with('success', 'Transaksi berhasil');
    }
}