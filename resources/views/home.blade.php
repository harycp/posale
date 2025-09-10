<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Point Of Sale</title>
    @vite('resources/css/app.css')
    <style>
        .kotak {
            clip-path: polygon(0 0, 100% 0, 95% 100%, 0% 100%);
            display: inline-block;
        }
    </style>
</head>

<body class="bg-gray-200 font-sans">

    <div class="">
        <div class="bg-blue-400">
        </div>
        <div class="bg-blue-500">
            <h1 class="text-3xl font-bold text-white bg-blue-600 px-4 py-2 kotak">Point Of Sale</h1>
        </div>
    </div>

    <div class="min-h-screen p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <p class="text-lg text-gray-700 flex items-center">
                    <img src="{{ asset('images/kalender.svg') }}" style="width:24px" class="mr-2">
                    {{-- REVISI: Tanggal dinamis --}}
                    {{ $todayDate }}
                </p>
            </div>
            <div class="flex items-center space-x-2">
                {{-- REVISI: Tombol akan berubah tergantung status login --}}
                @auth
                    {{-- Jika sudah login, tampilkan tombol Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded font-semibold hover:bg-blue-600 transition">
                        Dashboard
                    </a>
                @else
                    {{-- Jika belum login, tampilkan tombol Register dan Login --}}
                    <a href="{{ route('register') }}"
                        class="bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
                        Register
                    </a>
                    <a href="{{ route('login') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded font-semibold hover:bg-blue-600 transition">
                        Login
                    </a>
                @endauth
            </div>
        </div>

        <!-- Card Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Penjualan Hari Ini (Total Item) -->
            <div class="bg-blue-400 text-white p-4 rounded">
                <div class="flex items-center">
                    <img src="{{ asset('images/troli.svg') }}" style="width:40px">
                    <div class="ml-2">
                        <p class="text-sm">Penjualan Hari Ini</p>
                        {{-- REVISI: Menggunakan total item terjual --}}
                        <p class="text-lg font-bold">{{ $totalItemsSoldToday ?? 0 }} ITEM</p>
                    </div>
                </div>
            </div>

            <!-- Barang Terjual (Total Transaksi) -->
            <div class="bg-blue-400 text-white p-4 rounded">
                <div class="flex items-center">
                    <img src="{{ asset('images/ceklis.svg') }}" style="width:40px">
                    <div class="ml-2">
                        <p class="text-sm">Transaksi Selesai</p>
                        {{-- REVISI: Menggunakan jumlah transaksi --}}
                        <p class="text-lg font-bold">{{ $salesTodayCount }} TRANSAKSI</p>
                    </div>
                </div>
            </div>

            <!-- Penjualan Bersih -->
            <div class="bg-blue-400 text-white p-4 rounded">
                <div class="flex items-center">
                    <img src="{{ asset('images/check.svg') }}" style="width:40px">
                    <div class="ml-2">
                        <p class="text-sm">Penjualan Bersih</p>
                        {{-- REVISI: Data dinamis --}}
                        <p class="text-lg font-bold">Rp {{ number_format($netSalesToday, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Penjualan Kotor -->
            <div class="bg-green-400 text-white p-4 rounded">
                <div class="flex items-center">
                    <img src="{{ asset('images/neraca.svg') }}" style="width:40px">
                    <div class="ml-2">
                        <p class="text-sm">Penjualan Kotor</p>
                        {{-- REVISI: Data dinamis --}}
                        <p class="text-lg font-bold">Rp {{ number_format($grossSalesToday, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Pengeluaran Hari Ini -->
            <div class="bg-green-400 text-white p-4 rounded">
                <div class="flex items-center">
                    <img src="{{ asset('images/money.svg') }}" style="width:40px">
                    <div class="ml-2">
                        <p class="text-sm">Pengeluaran Hari Ini</p>
                        {{-- REVISI: Data dinamis --}}
                        <p class="text-lg font-bold">Rp {{ number_format($expensesToday, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Hampir Habis -->
        <div class="bg-green-400 text-white p-4 rounded">
            <p class="text-lg font-bold mb-2">Produk Hampir Habis</p>
            <div class="space-y-1 text-sm">
                {{-- REVISI: Loop dinamis --}}
                @forelse ($lowStockProducts as $product)
                    <div class="flex justify-between items-center">
                        <span>{{ Str::limit($product->name, 30) }}</span>
                        <span
                            class="font-bold bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ $product->stock }}</span>
                    </div>
                @empty
                    <p class="text-white/70 italic">Tidak ada produk yang stoknya menipis.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>

</html>
