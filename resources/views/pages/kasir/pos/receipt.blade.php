<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $transaction->transaction_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 10px;
            }

            .receipt-container {
                box-shadow: none;
                border: none;
                max-width: 100%;
            }
        }

        @page {
            size: 80mm auto;
            /* Typical thermal printer paper width */
            margin: 0;
        }
    </style>
</head>

<body class="bg-gray-100 font-mono">
    <div class="container mx-auto p-4 flex flex-col items-center">
        <div class="receipt-container bg-white shadow-lg rounded-lg p-6 w-full max-w-sm">
            {{-- Header Struk --}}
            <div class="text-center mb-6">
                <h1 class="text-xl font-bold">NAMA TOKO ANDA</h1>
                <p class="text-xs">Jl. Alamat Toko Anda No. 123, Kota Anda</p>
                <p class="text-xs">Telp: 0812-3456-7890</p>
            </div>

            {{-- Info Transaksi --}}
            <div class="text-xs mb-4">
                <div class="flex justify-between">
                    <span>No. Transaksi:</span>
                    <span>{{ $transaction->transaction_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tanggal:</span>
                    <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir:</span>
                    <span>{{ $transaction->user->name }}</span>
                </div>
            </div>

            {{-- Daftar Item --}}
            <div class="border-t-2 border-dashed border-b-2 border-dashed py-4">
                <table class="w-full text-xs">
                    <thead>
                        <tr>
                            <th class="text-left">Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->transactionDetails as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-right">{{ number_format($detail->price_at_transaction, 0, ',', '.') }}
                                </td>
                                <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Total Belanja --}}
            <div class="text-xs mt-4">
                <div class="flex justify-between font-bold">
                    <span>Total</span>
                    <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                @if ($transaction->payment_method == 'cash')
                    <div class="flex justify-between">
                        <span>Bayar (Tunai)</span>
                        <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kembali</span>
                        <span>Rp
                            {{ number_format($transaction->payment_amount - $transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            {{-- Detail Pembayaran QRIS/Transfer --}}
            @if ($transaction->payment_method == 'qris' || $transaction->payment_method == 'transfer')
                <div class="mt-6 border-t pt-4 text-center">
                    <h3 class="font-bold mb-2">
                        {{ $transaction->payment_method == 'qris' ? 'Scan QRIS untuk Pembayaran' : 'Detail Transfer Bank' }}
                    </h3>
                    <p class="text-xs mb-2">Status: <span
                            class="font-bold uppercase {{ $transaction->status == 'completed' ? 'text-green-600' : 'text-orange-500' }}">{{ $transaction->status }}</span>
                    </p>

                    @if ($transaction->payment_method == 'qris')
                        {{-- Ganti dengan gambar QRIS asli Anda dari payment gateway --}}
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $transaction->transaction_code }}"
                            alt="QRIS Code" class="mx-auto my-2">
                        <p class="text-xs text-gray-600">Scan QR Code ini menggunakan aplikasi pembayaran Anda.</p>
                    @else
                        <div class="text-left bg-gray-100 p-3 rounded-md text-xs">
                            <p>Silakan transfer ke rekening berikut:</p>
                            <p class="font-bold">Bank BCA: <span class="tracking-widest">1234567890</span></p>
                            <p>a/n NAMA TOKO ANDA</p>
                            <p class="mt-2">Jumlah: <span class="font-bold text-base">Rp
                                    {{ number_format($transaction->total_amount, 0, ',', '.') }}</span></p>
                        </div>
                    @endif
                    {{-- Di aplikasi nyata, Anda perlu mekanisme untuk update status ini --}}
                    <p class="text-xs mt-2 text-red-500">PENTING: Halaman ini belum bisa mendeteksi pembayaran otomatis.
                    </p>
                </div>
            @endif


            {{-- Footer Struk --}}
            <div class="text-center mt-6 text-xs">
                <p>Terima kasih telah berbelanja!</p>
                <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="no-print mt-6 flex space-x-4">
            <a href="{{ route('cashier.pos.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Transaksi Baru
            </a>
            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Cetak Struk
            </button>
        </div>
    </div>
</body>

</html>
