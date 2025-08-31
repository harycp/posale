<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Produk') }}
        </h2>
    </x-slot>

    {{-- Memuat Alpine.js dari CDN. Biasanya sudah ada di app.js jika Anda menggunakan Breeze/Jetstream --}}
    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="productsTable({{ json_encode($products) }})">

                    {{-- KONTROL ATAS - DIBUAT DENGAN FLEXBOX UNTUK KONTROL PENUH --}}
                    <div class="flex items-center mb-6 space-x-4">
                        <!-- Show Entries -->
                        <div class="flex items-center space-x-2">
                            <label for="itemsPerPage" class="text-sm text-gray-600">Show</label>
                            <select x-model="itemsPerPage" id="itemsPerPage" class="rounded-lg border-gray-300 text-sm">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                            <span class="text-sm text-gray-600">entries</span>
                        </div>

                        <!-- Search Bar (Tumbuh mengisi ruang) -->
                        <div class="flex-grow">
                            <input type="text" x-model.debounce.300ms="search" placeholder="Search..."
                                class="w-full rounded-lg border-gray-300 shadow-sm">
                        </div>

                        <!-- Tombol-Tombol -->
                        <div class="flex items-center space-x-2">
                            <a href="#"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2 whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Tambah Stok</span>
                            </a>
                            <a href="{{ route('cashier.products.create') }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2 whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Tambah Produk</span>
                            </a>
                        </div>
                    </div>

                    {{-- TABEL PRODUK --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full" style="width:100%">
                            <thead>
                                <tr>
                                    {{-- Header Kolom dengan Aksi Sort --}}
                                    <th @click="sortBy('product_code')"
                                        class="cursor-pointer text-left text-sm font-semibold text-gray-600 uppercase tracking-wider py-3 px-4">
                                        Kode Produk</th>
                                    <th
                                        class="text-left text-sm font-semibold text-gray-600 uppercase tracking-wider py-3 px-4">
                                        Gambar</th>
                                    <th @click="sortBy('name')"
                                        class="cursor-pointer text-left text-sm font-semibold text-gray-600 uppercase tracking-wider py-3 px-4">
                                        Nama Barang</th>
                                    <th
                                        class="text-left text-sm font-semibold text-gray-600 uppercase tracking-wider py-3 px-4">
                                        Satuan</th>
                                    <th @click="sortBy('selling_price')"
                                        class="cursor-pointer text-left text-sm font-semibold text-gray-600 uppercase tracking-wider py-3 px-4">
                                        Harga Jual</th>
                                    <th @click="sortBy('purchase_price')"
                                        class="cursor-pointer text-left text-sm font-semibold text-gray-600 uppercase tracking-wider py-3 px-4">
                                        Harga Beli</th>
                                    <th @click="sortBy('stock')"
                                        class="cursor-pointer text-left text-sm font-semibold text-gray-600 uppercase tracking-wider py-3 px-4">
                                        Stok Barang</th>
                                    <th
                                        class="text-left text-sm font-semibold text-gray-600 uppercase tracking-wider py-3 px-4">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop menggunakan data dari Alpine.js --}}
                                <template x-for="product in paginatedProducts" :key="product.id">
                                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                                        <td class="py-4 px-4 align-middle font-mono" x-text="product.product_code"></td>
                                        <td class="py-4 px-4 align-middle">
                                            <img :src="product.image_url" :alt="product.name"
                                                class="h-12 w-12 object-cover rounded-md">
                                        </td>
                                        <td class="py-4 px-4 align-middle font-medium text-gray-800"
                                            x-text="product.name"></td>
                                        <td class="py-4 px-4 align-middle" x-text="product.unit.name"></td>
                                        <td class="py-4 px-4 align-middle"
                                            x-text="`Rp ${formatCurrency(product.selling_price)}`"></td>
                                        <td class="py-4 px-4 align-middle"
                                            x-text="`Rp ${formatCurrency(product.purchase_price)}`"></td>
                                        <td class="py-4 px-4 align-middle" x-text="product.stock"></td>
                                        <td class="py-4 px-4 align-middle">
                                            <div class="flex items-center space-x-2">
                                                <a :href="`/cashier/products/${product.id}/edit`"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                        <path fill-rule="evenodd"
                                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                                <form :action="`/cashier/products/${product.id}`" method="POST"
                                                    class="delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- KONTROL PAGINASI --}}
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-sm text-gray-700">
                            Showing <span x-text="startRecord"></span> to <span x-text="endRecord"></span> of <span
                                x-text="filteredProducts.length"></span> entries
                        </span>
                        <div class="flex items-center space-x-1">
                            <button @click="prevPage" :disabled="currentPage === 1"
                                class="px-3 py-1 rounded-md bg-gray-200 disabled:opacity-50">Prev</button>
                            <template x-for="page in pages" :key="page">
                                <button @click="currentPage = page"
                                    :class="{ 'bg-indigo-600 text-white': currentPage === page, 'bg-gray-200': currentPage !==
                                            page }"
                                    class="px-3 py-1 rounded-md" x-text="page"></button>
                            </template>
                            <button @click="nextPage" :disabled="currentPage === totalPages"
                                class="px-3 py-1 rounded-md bg-gray-200 disabled:opacity-50">Next</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- LOGIKA ALPINE.JS --}}
    <x-slot name="script">
        <script>
            function productsTable(products) {
                return {
                    products: products.map(product => ({
                        ...product,
                        image_url: product.image ? `{{ asset('storage') }}/${product.image}` :
                            'https://placehold.co/48x48/e2e8f0/a0aec0?text=No+Img'
                    })),
                    search: '',
                    itemsPerPage: 10,
                    currentPage: 1,
                    sortColumn: 'product_code',
                    sortDirection: 'asc',

                    formatCurrency(number) {
                        return new Intl.NumberFormat('id-ID').format(number);
                    },

                    get filteredProducts() {
                        this.currentPage = 1; // Reset ke halaman 1 setiap kali search
                        if (!this.search) {
                            return this.products;
                        }
                        return this.products.filter(p =>
                            p.name.toLowerCase().includes(this.search.toLowerCase()) ||
                            p.product_code.toLowerCase().includes(this.search.toLowerCase())
                        );
                    },

                    get sortedProducts() {
                        return [...this.filteredProducts].sort((a, b) => {
                            let valA = a[this.sortColumn];
                            let valB = b[this.sortColumn];
                            if (typeof valA === 'string') {
                                return this.sortDirection === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(
                                    valA);
                            }
                            return this.sortDirection === 'asc' ? valA - valB : valB - valA;
                        });
                    },

                    get paginatedProducts() {
                        const start = (this.currentPage - 1) * this.itemsPerPage;
                        const end = start + Number(this.itemsPerPage);
                        return this.sortedProducts.slice(start, end);
                    },

                    get totalPages() {
                        return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
                    },

                    get startRecord() {
                        return (this.currentPage - 1) * this.itemsPerPage + 1;
                    },

                    get endRecord() {
                        return Math.min(this.currentPage * this.itemsPerPage, this.filteredProducts.length);
                    },

                    get pages() {
                        let from = Math.max(1, this.currentPage - 2);
                        let to = Math.min(this.totalPages, this.currentPage + 2);
                        if (to - from < 4) {
                            if (from === 1) to = Math.min(this.totalPages, 5);
                            else from = Math.max(1, this.totalPages - 4);
                        }
                        const pages = [];
                        for (let i = from; i <= to; i++) pages.push(i);
                        return pages;
                    },

                    sortBy(column) {
                        if (this.sortColumn === column) {
                            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.sortColumn = column;
                            this.sortDirection = 'asc';
                        }
                    },

                    nextPage() {
                        if (this.currentPage < this.totalPages) this.currentPage++;
                    },

                    prevPage() {
                        if (this.currentPage > 1) this.currentPage--;
                    },
                }
            }
        </script>
    </x-slot>

</x-app-layout>
