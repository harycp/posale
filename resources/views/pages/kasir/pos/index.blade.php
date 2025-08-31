<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kasir') }}
        </h2>
    </x-slot>

    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="posManager({{ json_encode($products) }})">

                    <form action="{{ route('cashier.cart.add') }}" method="POST">
                        @csrf
                        {{-- KONTROL ATAS --}}
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-1/3">
                                <input type="text" x-model.debounce.300ms="search"
                                    placeholder="Cari nama atau kode produk..."
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <button type="submit" :disabled="itemsInCart === 0"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-50 disabled:cursor-not-allowed transition ease-in-out duration-150">
                                <span>Tambahkan ke List</span>
                                <span x-show="itemsInCart > 0"
                                    class="ml-2 bg-white text-indigo-600 rounded-full px-2 py-0.5 text-xs font-bold"
                                    x-text="itemsInCart"></span>
                            </button>
                        </div>

                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th @click="sortBy('product_code')"
                                            class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <span>ID Produk</span>
                                                <span class="ml-2">
                                                    <svg x-show="sortColumn === 'product_code' && sortDirection === 'asc'"
                                                        class="w-3 h-3 text-gray-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                    <svg x-show="sortColumn === 'product_code' && sortDirection === 'desc'"
                                                        class="w-3 h-3 text-gray-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Gambar
                                        </th>
                                        <th @click="sortBy('name')"
                                            class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <span>Nama Barang</span>
                                                <span class="ml-2">
                                                    <svg x-show="sortColumn === 'name' && sortDirection === 'asc'"
                                                        class="w-3 h-3 text-gray-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                    <svg x-show="sortColumn === 'name' && sortDirection === 'desc'"
                                                        class="w-3 h-3 text-gray-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Satuan
                                        </th>
                                        <th @click="sortBy('selling_price')"
                                            class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <span>Harga</span>
                                                <span class="ml-2">
                                                    <svg x-show="sortColumn === 'selling_price' && sortDirection === 'asc'"
                                                        class="w-3 h-3 text-gray-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                    <svg x-show="sortColumn === 'selling_price' && sortDirection === 'desc'"
                                                        class="w-3 h-3 text-gray-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kuantitas
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="product in paginatedProducts" :key="product.id">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"
                                                x-text="product.product_code"></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img :src="product.image_url" :alt="product.name"
                                                    class="w-12 h-12 object-cover rounded-md">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                x-text="product.name"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"
                                                x-text="product.unit.short_name"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"
                                                x-text="`Rp ${formatCurrency(product.selling_price)}`"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <button type="button" @click="decrement(product.id)"
                                                        class="p-1 rounded-md bg-red-100 text-red-600 hover:bg-red-200">-</button>
                                                    <input type="text" x-model.number="product.quantity"
                                                        @change="updateQuantity(product.id, $event.target.value)"
                                                        class="w-16 text-center border-gray-300 rounded-md shadow-sm">
                                                    <button type="button" @click="increment(product.id)"
                                                        class="p-1 rounded-md bg-green-100 text-green-600 hover:bg-green-200">+</button>
                                                </div>
                                                <input type="hidden" :name="`products[${product.id}][quantity]`"
                                                    :value="product.quantity">
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="paginatedProducts.length === 0">
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">Produk
                                                tidak
                                                ditemukan.</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-sm text-gray-700">
                            Menampilkan <span x-text="startRecord" class="font-medium"></span> sampai <span
                                x-text="endRecord" class="font-medium"></span> dari <span
                                x-text="filteredProducts.length" class="font-medium"></span> hasil
                        </span>
                        <div class="flex items-center space-x-1">
                            <button @click="prevPage" :disabled="currentPage === 1"
                                class="px-3 py-1 rounded-md bg-white border border-gray-300 text-sm hover:bg-gray-50 disabled:opacity-50">&laquo;
                                Prev</button>
                            <template x-for="page in pages" :key="page">
                                <button @click="currentPage = page"
                                    :class="{
                                        'bg-indigo-600 text-white border-indigo-600': currentPage ===
                                            page,
                                        'bg-white border-gray-300': currentPage !== page
                                    }"
                                    class="px-3 py-1 rounded-md border text-sm" x-text="page"></button>
                            </template>
                            <button @click="nextPage" :disabled="currentPage === totalPages"
                                class="px-3 py-1 rounded-md bg-white border border-gray-300 text-sm hover:bg-gray-50 disabled:opacity-50">Next
                                &raquo;</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            function posManager(products) {
                return {
                    products: products.map(p => ({
                        ...p,
                        quantity: 0,
                        image_url: p.image ? `{{ asset('storage') }}/${p.image}` :
                            'https://placehold.co/48x48/e2e8f0/a0aec0?text=N/A'
                    })),
                    search: '',
                    itemsPerPage: 10,
                    currentPage: 1,
                    sortColumn: 'name',
                    sortDirection: 'asc',

                    formatCurrency(number) {
                        return new Intl.NumberFormat('id-ID').format(number);
                    },

                    increment(productId) {
                        const product = this.products.find(p => p.id === productId);
                        if (product && product.quantity < product.stock) {
                            product.quantity++;
                        }
                    },

                    decrement(productId) {
                        const product = this.products.find(p => p.id === productId);
                        if (product && product.quantity > 0) {
                            product.quantity--;
                        }
                    },

                    updateQuantity(productId, value) {
                        const product = this.products.find(p => p.id === productId);
                        const qty = parseInt(value, 10);
                        if (!product) return;

                        if (isNaN(qty) || qty < 0) {
                            product.quantity = 0;
                        } else if (qty > product.stock) {
                            product.quantity = product.stock;
                            alert('Kuantitas melebihi stok yang tersedia.');
                        } else {
                            product.quantity = qty;
                        }
                    },

                    sortBy(column) {
                        if (this.sortColumn === column) {
                            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.sortColumn = column;
                            this.sortDirection = 'asc';
                        }
                    },

                    get itemsInCart() {
                        return this.products.reduce((total, p) => total + (p.quantity > 0 ? 1 : 0), 0);
                    },

                    get filteredProducts() {
                        this.currentPage = 1;
                        if (!this.search) return this.products;
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
                        if (this.filteredProducts.length === 0) return 0;
                        return (this.currentPage - 1) * this.itemsPerPage + 1;
                    },

                    get endRecord() {
                        return Math.min(this.currentPage * this.itemsPerPage, this.filteredProducts.length);
                    },

                    get pages() {
                        const range = [];
                        for (let i = 1; i <= this.totalPages; i++) {
                            range.push(i);
                        }
                        return range;
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
