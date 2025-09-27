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

class CashierPOSController extends Controller
{
    public function index()
    {
        $products = Product::with('unit')->latest()->get();
        
        return view('pages.kasir.pos.index', compact('products'));
    }
    
    public function search(Request $request)
    {
        $searchTerm = $request->query('q');

        if (empty($searchTerm)) {
            $products = Product::with('unit')->orderBy('name', 'asc')->get();
        } else {
            $products = Product::with('unit')
                ->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('product_code', 'LIKE', "%{$searchTerm}%")
                ->orderBy('name', 'asc')
                ->get();
        }
        
        return response()->json($products);
    }
}
