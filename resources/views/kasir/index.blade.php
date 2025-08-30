@extends('layouts.app')
@section('content')
<div class="flex">
    <!-- Sidebar -->
    <div class="w-1/5 bg-gray-800 text-white h-screen p-4">
        <div class="flex items-center space-x-2 mb-6">
            <div class="bg-white rounded-full w-10 h-10"></div>
            <div>
                <div class="font-semibold">Helmus kaluasa</div>
                <div class="text-green-400 text-sm">● Online</div>
            </div>
        </div>
        <nav class="space-y-2">
            <a href="#" class="block bg-blue-600 text-white px-4 py-2 rounded">Kasir</a>
            <a href="#" class="block bg-white text-black px-4 py-2 rounded">Data Produk</a>
            <a href="#" class="block bg-white text-black px-4 py-2 rounded">Riwayat Transaksi</a>
            <a href="#" class="block bg-white text-black px-4 py-2 rounded flex items-center"><span>↩</span> Log out</a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="w-4/5 p-6">
        <div class="flex justify-between items-center mb-4">
            <input type="text" placeholder="Search.." class="border p-2 rounded w-1/3">
            <a href="{{ route('kasir.keranjang') }}" class="bg-purple-600 text-white px-4 py-2 rounded">Tambahkan ke list</a>
        </div>

        <table class="w-full text-left table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Id Produk</th>
                    <th class="px-4 py-2">Gambar</th>
                    <th class="px-4 py-2">Nama Barang</th>
                    <th class="px-4 py-2">Satuan</th>
                    <th class="px-4 py-2">Harga Satuan</th>
                    <th class="px-4 py-2">Kuantitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produk as $item)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $item->kode_produk }}</td>
                    <td class="px-4 py-2"><img src="{{ asset('img/produk/' . $item->kode_produk . '.png') }}" class="w-8"></td>
                    <td class="px-4 py-2">{{ $item->nama }}</td>
                    <td class="px-4 py-2">{{ $item->satuan }}</td>
                    <td class="px-4 py-2">{{ number_format($item->harga) }}</td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('kasir.tambah') }}" class="flex items-center">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button class="bg-green-500 text-white px-2">+</button>
                            <span class="mx-2">1</span>
                            <button class="bg-red-500 text-white px-2">-</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $produk->links() }}
        </div>
    </div>
</div>
@endsection