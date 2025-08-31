<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Barang') }}
        </h2>
    </x-slot>

    {{-- Penambahan Alpine.js jika belum ada di layout utama --}}
    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </x-slot>

    {{-- REVISI KUNCI: Menggunakan satu fungsi data utama 'pageManager' untuk stabilitas --}}
    <div class="py-12" x-data="pageManager({{ $total }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Kontainer Utama Kartu --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                <div class="p-6 md:p-8 space-y-8">

                    {{-- BAGIAN HEADER --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="bg-blue-800 text-white rounded-lg px-4 py-2 text-left w-full md:w-auto">
                            <p class="text-xs font-light">Kode Transaksi</p>
                            <p class="font-bold text-lg">{{ $transactionCode ?? 'N/A' }}</p>
                        </div>
                        <div class="flex-grow text-center">
                            <span class="bg-blue-800 text-white font-bold text-xl px-10 py-3 rounded-lg">
                                List Barang
                            </span>
                        </div>
                        <div class="text-right text-sm text-gray-600 w-full md:w-auto">
                            <p>Tanggal: <span
                                    class="font-semibold">{{ \Carbon\Carbon::now()->translatedFormat('j F Y') }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- TABEL LIST BARANG --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-300">
                                    <th class="py-3 pr-6 text-left text-sm font-semibold text-gray-700">No</th>
                                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-700">Nama Barang</th>
                                    <th class="py-3 px-6 text-center text-sm font-semibold text-gray-700">Kuantitas</th>
                                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-700">Satuan</th>
                                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-700">Harga Jual</th>
                                    <th class="py-3 pl-6 text-center text-sm font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cart as $id => $details)
                                    <tr class="border-b border-gray-200">
                                        <td class="py-4 pr-6 whitespace-nowrap text-sm text-gray-600 align-middle">
                                            {{ $loop->iteration }}</td>
                                        <td
                                            class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-800 align-middle">
                                            {{ $details['name'] }}</td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-600 align-middle">
                                            <template x-if="editingProductId !== {{ $id }}">
                                                <p class="text-center">{{ $details['quantity'] }}</p>
                                            </template>
                                            <template x-if="editingProductId === {{ $id }}">
                                                <form action="{{ route('cashier.cart.update', $id) }}" method="POST"
                                                    class="flex justify-center items-center space-x-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" @click.prevent="decrement()"
                                                        class="p-1 rounded-md bg-red-100 text-red-600 hover:bg-red-200">-</button>
                                                    <input type="number" name="quantity"
                                                        x-model.number="currentQuantity"
                                                        class="w-16 text-center border-gray-300 rounded-md shadow-sm">
                                                    <button type="button"
                                                        @click.prevent="increment({{ $details['stock'] }})"
                                                        class="p-1 rounded-md bg-green-100 text-green-600 hover:bg-green-200">+</button>
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-900 ml-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                    <button type="button" @click.prevent="cancelEditing()"
                                                        class="text-gray-600 hover:text-gray-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </template>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-600 align-middle">
                                            {{ $details['unit'] }}</td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-600 align-middle">Rp
                                            {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        <td class="py-4 pl-6 whitespace-nowrap text-center align-middle">
                                            <div x-show="editingProductId !== {{ $id }}"
                                                class="flex justify-center items-center space-x-4">
                                                <button
                                                    @click="startEditing({{ $id }}, {{ $details['quantity'] }})"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                        <path fill-rule="evenodd"
                                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <form action="{{ route('cashier.cart.destroy', $id) }}" method="POST"
                                                    class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
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
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-sm text-gray-500">
                                            List barang masih kosong.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- BAGIAN FOOTER --}}
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-4">
                        <a href="{{ route('cashier.pos.index') }}"
                            class="w-full md:w-auto px-6 py-3 bg-teal-500 text-white font-bold rounded-lg hover:bg-teal-600 text-center shadow-md transition-transform transform hover:scale-105">
                            Tambah barang
                        </a>
                        <div
                            class="flex-grow flex items-center justify-center bg-blue-800 text-white rounded-lg p-3 w-full md:w-auto">
                            <span class="text-md font-bold mr-4">Total</span>
                            <span class="text-xl font-extrabold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <button type="button" @click="openModal" @if (empty($cart)) disabled @endif
                            class="w-full md:w-auto px-6 py-3 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed shadow-md transition-transform transform hover:scale-105">
                            Lanjutkan Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Pembayaran --}}
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
            <div @click.away="isOpen = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Pembayaran</h3>
                <form action="{{ route('cashier.transactions.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Belanja</label>
                            <p class="mt-1 text-2xl font-bold text-gray-900"
                                x-text="`Rp ${formatCurrency(totalAmount)}`"></p>
                        </div>
                        <div>
                            <label for="payment_amount" class="block text-sm font-medium text-gray-700">Jumlah Uang
                                Dibayar</label>
                            <input type="number" id="payment_amount" name="payment_amount"
                                x-model.number="paymentAmount"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kembalian</label>
                            <p class="mt-1 text-2xl font-bold"
                                :class="isPaymentInsufficient ? 'text-red-500' : 'text-green-600'"
                                x-text="`Rp ${formatCurrency(change)}`"></p>
                        </div>
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" @click="isOpen = false"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                Batal
                            </button>
                            <button type="submit" :disabled="isPaymentInsufficient"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Konfirmasi Pembayaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            // REVISI: Semua logika digabung ke dalam satu fungsi 'pageManager'
            function pageManager(total) {
                return {
                    // --- Properti dari cartManager ---
                    editingProductId: null,
                    currentQuantity: 0,

                    // --- Properti dari paymentModalHandler ---
                    isOpen: false,
                    totalAmount: parseFloat(total) || 0,
                    paymentAmount: null,

                    // --- Method dari cartManager ---
                    startEditing(productId, initialQuantity) {
                        this.editingProductId = productId;
                        this.currentQuantity = initialQuantity;
                    },
                    cancelEditing() {
                        this.editingProductId = null;
                    },
                    increment(stockLimit) {
                        if (this.currentQuantity < stockLimit) {
                            this.currentQuantity++;
                        } else {
                            alert('Kuantitas tidak bisa melebihi stok!');
                        }
                    },
                    decrement() {
                        if (this.currentQuantity > 1) {
                            this.currentQuantity--;
                        }
                    },

                    // --- Method dari paymentModalHandler ---
                    openModal() {
                        this.paymentAmount = null;
                        this.isOpen = true;
                        this.$nextTick(() => {
                            document.getElementById('payment_amount').focus();
                        });
                    },
                    formatCurrency(number) {
                        const num = Number(number);
                        if (isNaN(num)) return '0';
                        return new Intl.NumberFormat('id-ID').format(num);
                    },

                    // --- Getter dari paymentModalHandler ---
                    get paidAmount() {
                        return parseFloat(this.paymentAmount) || 0;
                    },
                    get change() {
                        if (this.paidAmount < this.totalAmount) {
                            return 0;
                        }
                        return this.paidAmount - this.totalAmount;
                    },
                    get isPaymentInsufficient() {
                        return this.paidAmount < this.totalAmount;
                    },
                }
            }
        </script>
    </x-slot>
</x-app-layout>
