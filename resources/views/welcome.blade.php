<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <title>Point Of Sale</title>
    @vite('resources/css/app.css') <!-- pastikan Tailwind terhubung -->
</head>
<body class="bg-gray-200">

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
                   <img src="{{asset('images/kalender.svg')}}" style="width:24px"class="mr-2">
                    Minggu, 01 Juni 2025
                </p>
        </div>
    <div class="flex space-x-2">
        <form action="{{ route('register') }}" method="GET">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                 Register
            </button>
        </form>

        <form action="{{ route('login') }}" method="GET">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Login
            </button>
        </form>
    </div>
    </div>

        

        <!-- Card Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Penjualan Hari Ini -->
            <div class="bg-blue-400 text-white p-4 rounded">
                <div class="flex items-center">
                  <img src="{{asset('images/troli.svg')}}" style="width:40px">
                    <div class="ml-2">
                        <p class="text-sm">Penjualan Hari Ini</p>
                        <p class="text-lg font-bold">0 ITEM</p>
                    </div>
                </div>
            </div>

            <!-- Barang Terjual -->
            <div class="bg-blue-400 text-white p-4 rounded">
                <div class="flex items-center">
                   <img src="{{asset('images/ceklis.svg')}}" style="width:40px">
                    <div class="ml-2">
                        <p class="text-sm">Barang Terjual</p>
                        <p class="text-lg font-bold">0 ITEM</p>
                    </div>
                </div>
            </div>

            <!-- Penjualan Bersih -->
            <div class="bg-blue-400 text-white p-4 rounded">
                <div class="flex items-center">
                      <img src="{{asset('images/check.svg')}}" style="width:40px">
                    <div class="ml-2" >
                        <p class="text-sm">Penjualan Bersih</p>
                        <p class="text-lg font-bold">Rp -</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Penjualan Kotor -->
            <div class="bg-green-400 text-white p-4 rounded">
                <div class="flex items-center">
                     <img src="{{asset('images/neraca.svg')}}" style="width:40px">
                    <div class="ml-2">
                        <p class="text-sm">Penjualan Kotor</p>
                        <p class="text-lg font-bold">Rp -</p>
                    </div>
                </div>
            </div>

            <!-- Pengeluaran Hari Ini -->
            <div class="bg-green-400 text-white p-4 rounded">
                <div class="flex items-center">
                     <img src="{{asset('images/money.svg')}}" style="width:40px">
                    <div class="ml-2">
                        <p class="text-sm">Pengeluaran Hari Ini</p>
                        <p class="text-lg font-bold">Rp -</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Hampir Habis -->
        <div class="bg-green-400 text-white p-4 rounded">
            <p class="text-lg font-bold mb-2">Produk Hampir Habis</p>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <p>Nama Produk</p>
                <p>Nama Produk</p>
                <hr class="col-span-2 border-white opacity-40">
                <p class="col-span-2 text-white">–</p>
                <p class="col-span-2 text-white">–</p>
                <p class="col-span-2 text-white">–</p>
            </div>
        </div>
    </div>
</body>
</html>