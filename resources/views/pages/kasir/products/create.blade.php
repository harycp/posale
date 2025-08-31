<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- Menampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                            <ul class="mt-3 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cashier.products.store') }}" enctype="multipart/form-data"
                        x-data="{ imagePreview: null }">
                        @csrf

                        <!-- Tata Letak Vertikal -->
                        <div class="space-y-6">

                            <!-- Kode Produk -->
                            <div>
                                <label for="product_code" class="block text-sm font-medium text-gray-700">Kode
                                    Produk</label>
                                <input type="text" name="product_code" id="product_code"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value="{{ old('product_code') }}" required>
                            </div>

                            <!-- Nama Barang -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama
                                    Barang</label>
                                <input type="text" name="name" id="name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value="{{ old('name') }}" required>
                            </div>

                            <!-- Satuan -->
                            <div>
                                <label for="unit_id" class="block text-sm font-medium text-gray-700">Satuan</label>
                                <select id="unit_id" name="unit_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required>
                                    <option value="">Pilih Satuan</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}
                                            ({{ $unit->short_name }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Harga Beli -->
                            <div>
                                <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli
                                    (Rp)</label>
                                <input type="number" name="purchase_price" id="purchase_price"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value="{{ old('purchase_price') }}" required>
                            </div>

                            <!-- Harga Jual -->
                            <div>
                                <label for="selling_price" class="block text-sm font-medium text-gray-700">Harga Jual
                                    (Rp)</label>
                                <input type="number" name="selling_price" id="selling_price"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value="{{ old('selling_price') }}" required>
                            </div>

                            <!-- Stok Awal -->
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stok Awal</label>
                                <input type="number" name="stock" id="stock"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value="{{ old('stock') }}" required>
                            </div>

                            <!-- Upload Gambar -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Gambar
                                    Produk</label>
                                <!-- Image Preview -->
                                <div x-show="imagePreview" class="mt-2">
                                    <img :src="imagePreview" class="h-40 w-40 object-cover rounded-md">
                                </div>
                                <input type="file" name="image" id="image"
                                    class="mt-2 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100"
                                    @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                            </div>

                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('cashier.products.index') }}"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                                Simpan Produk
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
