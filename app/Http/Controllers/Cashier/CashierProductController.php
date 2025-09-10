<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Unit;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CashierProductController extends Controller
{
    public function index()
    {
        $products = Product::with('unit')
                    ->where('stock', '>=', 1)
                    ->get();
        
        return view('pages.kasir.products.index', compact('products'));
    }

    public function create()
    {
        $units = Unit::orderBy('name')->get();
        return view('pages.kasir.products.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|max:255|unique:products,product_code',
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'product_code' => Str::upper($request->product_code),
            'name' => $request->name,
            'unit_id' => $request->unit_id,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return redirect()->route('cashier.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $units = Unit::orderBy('name')->get();
        return view('pages.kasir.products.edit', compact('product', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_code' => 'required|string|max:255|unique:products,product_code,'.$product->id,
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Update data disesuaikan dengan skema
        $product->update([
            'product_code' => Str::upper($request->product_code),
            'name' => $request->name,
            'unit_id' => $request->unit_id,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return redirect()->route('cashier.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('cashier.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function showUpdateStockForm()
    {
        $products = Product::orderBy('name')->get();
        return view('pages.kasir.products.updateStock', compact('products'));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'cost_per_item' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::findOrFail($request->product_id);
            $totalCost = $request->quantity * $request->cost_per_item;

            Expense::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'cost_per_item' => $request->cost_per_item,
                'total_cost' => $totalCost,
                'description' => $request->description ?: "Pembelian stok {$product->name}",
            ]);

            $product->increment('stock', $request->quantity);
        });

        return redirect()->route('cashier.products.index')->with('success', 'Stok produk berhasil diperbarui.');
    }
}
