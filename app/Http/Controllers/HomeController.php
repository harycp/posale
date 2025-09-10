<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class HomeController extends Controller{
    public function index()
    {
        $completedTransactionsToday = Transaction::where('status', 'completed')
            ->whereDate('created_at', today());

        $salesTodayCount = $completedTransactionsToday->count();

        $grossSalesToday = $completedTransactionsToday->clone()->sum('total_amount');
        
        $totalItemsSoldToday = TransactionDetail::whereHas('transaction', function ($query) {
            $query->where('status', 'completed')->whereDate('created_at', today());
        })->sum('quantity');

        $cogsToday = 0; // Cost of Goods Sold / HPP
        $detailsToday = TransactionDetail::with('product')
            ->whereHas('transaction', function ($query) {
                $query->where('status', 'completed')->whereDate('created_at', today());
            })->get();

        foreach ($detailsToday as $detail) {
            if ($detail->product) {
                $cogsToday += $detail->quantity * $detail->product->purchase_price;
            }
        }

        $netSalesToday = $grossSalesToday - $cogsToday;

        $expensesToday = Expense::whereDate('created_at', today())->sum('total_cost');

        $lowStockProducts = Product::where('stock', '<=', 20)
            ->whereNot('stock', '=', 0)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();
            
        $todayDate = Carbon::now()->translatedFormat('l, j F Y');

        return view('home', compact(
            'salesTodayCount',
            'totalItemsSoldToday',
            'netSalesToday',
            'grossSalesToday',
            'expensesToday',
            'lowStockProducts',
            'todayDate'
        ));
    }
}