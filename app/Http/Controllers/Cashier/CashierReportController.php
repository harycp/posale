<?php

namespace App\Http\Controllers\Cashier;

use Barryvdh\DomPDF\Facade\Pdf; 
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class CashierReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::now()->format('Y-m-d'));
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));
        $reportType = $request->input('report_type', 'daily');

        $query = Transaction::with('user')->where('status', 'completed');

        if ($reportType === 'daily') {
            $query->whereDate('created_at', $selectedDate);
            $period = Carbon::parse($selectedDate)->translatedFormat('l, j F Y');
        } elseif ($reportType === 'monthly') {
            $month = Carbon::parse($selectedMonth)->month;
            $year = Carbon::parse($selectedMonth)->year;
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
            $period = Carbon::parse($selectedMonth)->translatedFormat('F Y');
        } elseif ($reportType === 'yearly') {
            $query->whereYear('created_at', $selectedYear);
            $period = 'Tahun ' . $selectedYear;
        }

        $transactions = $query->latest()->get();

        $totalRevenue = $transactions->sum('total_amount');

        return view('pages.kasir.reports.index', compact(
            'transactions',
            'totalRevenue',
            'period',
            'reportType',
            'selectedDate',
            'selectedMonth',
            'selectedYear'
        ));
    }

    public function exportPDF(Request $request)
    {
        // Logika pengambilan data sama persis dengan method index()
        $selectedDate = $request->input('date', Carbon::now()->format('Y-m-d'));
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));
        $reportType = $request->input('report_type', 'daily');

        $query = Transaction::with('user')->where('status', 'completed');

        if ($reportType === 'daily') {
            $query->whereDate('created_at', $selectedDate);
            $period = Carbon::parse($selectedDate)->translatedFormat('l, j F Y');
        } elseif ($reportType === 'monthly') {
            $month = Carbon::parse($selectedMonth)->month;
            $year = Carbon::parse($selectedMonth)->year;
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
            $period = Carbon::parse($selectedMonth)->translatedFormat('F Y');
        } elseif ($reportType === 'yearly') {
            $query->whereYear('created_at', $selectedYear);
            $period = 'Tahun ' . $selectedYear;
        }

        $transactions = $query->latest()->get();
        $totalRevenue = $transactions->sum('total_amount');
        
        // Buat PDF
        $pdf = PDF::loadView('pages.kasir.reports.pdf', compact(
            'transactions',
            'totalRevenue',
            'period'
        ));

        // Tentukan nama file
        $fileName = 'laporan-penjualan-' . strtolower(str_replace(' ', '-', $period)) . '.pdf';

        // Unduh file PDF
        return $pdf->download($fileName);
    }
}
