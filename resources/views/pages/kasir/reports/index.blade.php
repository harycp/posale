<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="{ reportType: '{{ $reportType }}' }">

                    {{-- Form Filter --}}
                    <form action="{{ route('cashier.reports.index') }}" method="GET" class="mb-6 pb-6 border-b">
                        <div class="flex flex-wrap items-end gap-4">
                            <div>
                                <label for="report_type" class="block text-sm font-medium text-gray-700">Jenis
                                    Laporan</label>
                                <select name="report_type" id="report_type" x-model="reportType"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="daily">Harian</option>
                                    <option value="monthly">Bulanan</option>
                                    <option value="yearly">Tahunan</option>
                                </select>
                            </div>

                            <div x-show="reportType === 'daily'">
                                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="date" id="date" value="{{ $selectedDate }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div x-show="reportType === 'monthly'">
                                <label for="month" class="block text-sm font-medium text-gray-700">Bulan &
                                    Tahun</label>
                                <input type="month" name="month" id="month" value="{{ $selectedMonth }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div x-show="reportType === 'yearly'">
                                <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
                                <input type="number" name="year" id="year" value="{{ $selectedYear }}"
                                    placeholder="Contoh: 2024"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Tampilkan
                            </button>
                        </div>
                    </form>

                    {{-- Header Laporan & Tombol Cetak --}}
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Laporan Penjualan
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Periode: {{ $period }}
                            </p>
                        </div>
                        <a href="{{ route('cashier.reports.export', request()->query()) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Cetak PDF
                        </a>
                    </div>


                    {{-- Ringkasan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div class="bg-gray-50 p-5 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Total Omzet</h4>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">Rp
                                {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-5 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Jumlah Transaksi</h4>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $transactions->count() }}</p>
                        </div>
                    </div>

                    {{-- Tabel Detail Transaksi --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode
                                        Transaksi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->created_at->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <a href="{{ route('cashier.transactions.receipt', $transaction) }}"
                                                class="text-indigo-600 hover:text-indigo-900">{{ $transaction->transaction_code }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Rp
                                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">Tidak
                                            ada data transaksi untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
