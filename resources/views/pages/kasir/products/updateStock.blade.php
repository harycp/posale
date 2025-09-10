<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Stok Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('cashier.products.updateStock') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">

                            <!-- Product Selection -->
                            <div>
                                <label for="product_id" class="block font-medium text-sm text-gray-700">Pilih
                                    Produk</label>
                                <select name="product_id" id="product_id"
                                    class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (Stok saat ini: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block font-medium text-sm text-gray-700">Jumlah Stok
                                    Ditambahkan</label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}"
                                    class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    min="1" required>
                                @error('quantity')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cost per Item -->
                            <div>
                                <label for="cost_per_item" class="block font-medium text-sm text-gray-700">Harga Beli
                                    per Satuan (Rp)</label>
                                <input type="number" name="cost_per_item" id="cost_per_item"
                                    value="{{ old('cost_per_item') }}"
                                    class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    min="0" required>
                                <p class="text-xs text-gray-500 mt-1">Ini adalah harga beli/modal Anda untuk stok baru
                                    ini.</p>
                                @error('cost_per_item')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi
                                    (Opsional)</label>
                                <textarea name="description" id="description" rows="3"
                                    class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Contoh: Pembelian dari Supplier A.</p>
                                @error('description')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('cashier.products.index') }}"
                                class="text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
