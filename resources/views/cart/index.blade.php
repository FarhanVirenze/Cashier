<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-5xl mx-auto sm:px-4 lg:px-6">
            <div class="bg-white dark:bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100 text-sm">

                    {{-- Notifikasi --}}
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                            x-transition class="mb-4 p-3 text-green-700 font-semibold shadow">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                            x-transition class="mb-4 p-3 text-red-600 font-semibold shadow">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Isi Keranjang --}}
                    @if($items->isEmpty())
                        <p class="text-center text-gray-800 italic">Keranjang kosong.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($items as $item)
                                <div class="relative p-3 rounded-lg shadow hover:shadow-lg transition duration-300 group"
                                    style="background-image: url('{{ asset('images/card1.png') }}'); background-size: cover; background-position: center;">
                                    <div class="flex items-center justify-between gap-3">
                                        {{-- Gambar Produk --}}
                                        <div class="relative inline-block">
                                            <img src="{{ asset($item->product->foto) }}" alt="{{ $item->product->nama }}"
                                                class="w-14 h-14 object-cover rounded border border-gray-200 bg-blue-200 p-1" />

                                            {{-- Tombol Hapus --}}
                                            @if(Auth::user()->is_admin)
                                                <button type="button" onclick="openDeleteModal('{{ $item->id }}')"
                                                    class="absolute top-0 right-0 z-10 transform translate-x-1/2 -translate-y-1/2 bg-white border border-blue-200 text-red-600 hover:text-red-700 hover:scale-110 transition p-1 rounded-full shadow w-5 h-5 flex items-center justify-center">
                                                    <i class="fa fa-times text-xs"></i>
                                                </button>

                                                {{-- Modal Hapus --}}
                                                <div id="deleteModal{{ $item->id }}"
                                                    class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                                                    <div class="p-6 rounded-lg shadow-lg bg-cover bg-center relative"
                                                        style="background-image: url('{{ asset('images/card1.png') }}'); width: 320px;">
                                                        <h2 class="text-lg font-semibold text-white text-center mb-3">Konfirmasi
                                                            Hapus</h2>
                                                        <p class="text-white text-sm text-center mb-5">Apakah kamu yakin ingin
                                                            menghapus item ini dari keranjang?</p>

                                                        <div class="flex justify-center space-x-3">
                                                            <button type="button" onclick="closeDeleteModal('{{ $item->id }}')"
                                                                class="px-4 py-2 bg-gray-200 hover:bg-gray-400 text-gray-800 rounded-md font-medium transition">Batal</button>

                                                            <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="px-4 py-2 bg-red-600 hover:bg-red-800 text-white rounded-md font-medium transition">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Info Produk --}}
                                        <div class="flex-1 min-w-0">
                                            <h5 class="text-sm font-semibold text-white truncate">{{ $item->product->nama }}
                                            </h5>
                                            <p class="text-xs font-semibold text-white">
                                                Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                                            </p>

                                            @if(Auth::user()->is_admin)
                                                <p class="text-xs font-semibold text-white">
                                                    <small>User: {{ $item->user->name ?? 'Unknown' }}</small>
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Kontrol jumlah (user biasa) --}}
                                        @if(!Auth::user()->is_admin)
                                            <form action="{{ route('cart.update', $item) }}" method="POST"
                                                class="flex items-center gap-3 justify-center -ml-1">
                                                @csrf
                                                @method('PATCH')

                                                <button type="button"
                                                    onclick="let input=this.closest('form').querySelector('input[name=quantity]');let val=parseInt(input.value);if(val>1){input.value=val-1;input.form.submit();}"
                                                    class="w-8 h-8 bg-red-600 text-white font-bold rounded flex items-center justify-center hover:bg-red-700"
                                                    aria-label="Kurangi jumlah">−</button>

                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                    onchange="this.form.submit()"
                                                    class="w-16 px-3 py-1 text-center text-sm border border-gray-300 rounded bg-white text-black focus:outline-none focus:ring focus:ring-indigo-300" />

                                                <button type="button"
                                                    onclick="let input=this.closest('form').querySelector('input[name=quantity]');input.value=parseInt(input.value)+1;input.form.submit();"
                                                    class="w-8 h-8 bg-blue-600 text-white font-bold rounded flex items-center justify-center hover:bg-blue-700"
                                                    aria-label="Tambah jumlah">+</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total dan Checkout --}}
                        @if(!Auth::user()->is_admin)
                            @php
                                $total = $items->reduce(fn($carry, $item) => $carry + ($item->product->harga * $item->quantity), 0);
                            @endphp

                            <div class="mt-2 text-right text-sm font-semibold text-gray-900 pt-4">
                                Total: Rp {{ number_format($total, 0, ',', '.') }}
                            </div>

                            <form action="{{ route('cart.checkout') }}" method="POST" class="mt-4 space-y-4">
                                @csrf
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800">Nama Pelanggan</label>
                                        <input type="text" name="nama_pelanggan"
                                            class="mt-1 block w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            placeholder="Opsional">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800">Nomor Pelanggan</label>
                                        <input type="text" name="nomor_pelanggan"
                                            class="mt-1 block w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            placeholder="Opsional">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800">Metode Pembayaran</label>
                                        <select id="metode_pembayaran" name="metode_pembayaran" required
                                            class="mt-1 block w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="cash">Cash</option>
                                            <option value="qris">QRIS</option>
                                        </select>
                                    </div>

                                    <div id="jumlahBayarWrapper">
                                        <label class="block text-sm font-semibold text-gray-800">Jumlah Bayar</label>
                                        <input type="number" step="0.01" name="jumlah_bayar" required
                                            class="mt-1 block w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            placeholder="Contoh: 50000">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-white hover:bg-green-800 transition ease-in-out duration-150">
                                        Cetak Struk & Simpan
                                    </button>
                                </div>
                            </form>
                        @endif
                    @endif

                    {{-- POPUP QRIS --}}
                    <div id="qrisModal"
                        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded-lg p-6 w-96 max-w-full text-center relative">
                            <h3 class="text-lg font-semibold mb-4">Bayar QRIS</h3>
                            <img src="{{ asset('images/qris.png') }}" alt="QRIS"
                                class="mx-auto mb-6 w-72 h-72 object-contain">

                            <div class="flex justify-around gap-4">
                                <button id="cancelQris"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">Cancel</button>
                                <button id="confirmQris"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Cetak
                                    Struk & Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STYLE PRINT --}}
    <style>
        .hidden-print {
            visibility: hidden;
            position: absolute;
            z-index: -9999;
        }

        @media print {
            body *:not(#print-area):not(#print-area *) {
                visibility: hidden !important;
            }
        }

        #print-area,
        #print-area * {
            visibility: visible !important;
        }

        #print-area {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 320px;
            padding: 20px;
            background: #fff;
            font-family: monospace;
            font-size: 18px;
            line-height: 1.6;
            border: 1px solid #000;
        }

        #print-area h2 {
            font-size: 22px;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }

        #print-area table {
            width: 100%;
            border-collapse: collapse;
        }

        #print-area hr {
            border: none;
            border-top: 2px dashed #000;
            margin: 12px 0;
        }

        @page {
            margin: 0;
        }
    </style>

    {{-- AREA CETAK --}}
    @if(session('print_transaksi'))
        <div id="print-area" class="hidden-print">
            <div style="text-align: center; margin-bottom: 10px;">
                <h2>Warung Golpal</h2>
                <small style="font-size: 16px;">{{ session('print_transaksi')->tanggal }}</small>
            </div>

            <p style="margin-bottom: 12px;">
                <strong>Pelanggan:</strong> {{ session('print_transaksi')->nama_pelanggan ?? '-' }}<br>
                <strong>Kasir:</strong> {{ session('print_transaksi')->nama_user }}<br>
                <strong>Pembayaran:</strong> {{ strtoupper(session('print_transaksi')->metode_pembayaran) }}
            </p>

            <table style="margin-bottom: 12px;">
                @foreach(session('print_transaksi')->details as $item)
                    <tr>
                        <td colspan="2" style="border-bottom: 1px dashed #000; padding-bottom: 3px;">
                            <strong style="text-transform: uppercase;">{{ $item->nama_product }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 60%;">{{ $item->jumlah }} x Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td style="width: 40%; text-align: right;">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>

            <hr>

            <table style="font-weight: bold;">
                <tr>
                    <td>Total</td>
                    <td style="text-align: right;">Rp{{ number_format(session('print_transaksi')->total, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td style="text-align: right;">
                        Rp{{ number_format(session('print_transaksi')->jumlah_bayar, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td style="text-align: right;">Rp{{ number_format(session('print_transaksi')->kembalian, 0, ',', '.') }}
                    </td>
                </tr>
            </table>

            <p style="text-align: center; margin-top: 25px; text-transform: uppercase;">*** Terima kasih ***<br>Warung
                Golpal</p>
        </div>
    @endif

    {{-- SCRIPT FINAL --}}
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const checkoutButton = document.querySelector('form button[type="submit"]');
    const checkoutForm = checkoutButton ? checkoutButton.closest('form') : null;
    const qrisModal = document.getElementById('qrisModal');
    const cancelQris = document.getElementById('cancelQris');
    const confirmQris = document.getElementById('confirmQris');
    const metodeSelect = document.getElementById('metode_pembayaran');

    // Tampilkan popup jika metode QRIS
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function (e) {
            if (metodeSelect.value === 'qris') {
                e.preventDefault();
                qrisModal.classList.remove('hidden');
            }
        });
    }

    cancelQris.addEventListener('click', function () {
        qrisModal.classList.add('hidden');
    });

    confirmQris.addEventListener('click', function () {
        qrisModal.classList.add('hidden');
        if (checkoutForm) checkoutForm.submit();
    });

    // Cetak struk otomatis jika ada session
    const printArea = document.getElementById('print-area');
    if (printArea) {
        printArea.classList.remove('hidden-print');
        window.print();
        printArea.classList.add('hidden-print');

        fetch('{{ route('clear.print.session') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
    }
});

        // === Fungsi Modal Hapus ===
        function openDeleteModal(id) {
            const modal = document.getElementById('deleteModal' + id);
            if (modal) modal.classList.remove('hidden');
        }

        function closeDeleteModal(id) {
            const modal = document.getElementById('deleteModal' + id);
            if (modal) modal.classList.add('hidden');
        }
    </script>
</x-app-layout>