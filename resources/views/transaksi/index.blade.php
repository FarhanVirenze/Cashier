<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
            <div class="space-y-4">

                {{-- Notifikasi --}}
                @if (session('success'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-green-600 dark:text-green-600 font-medium">
                        {{ session('success') }}
                    </p>
                @endif

                @if (session('error'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-red-600 dark:text-red-600 font-medium">
                        {{ session('error') }}
                    </p>
                @endif

                {{-- Filter Laporan Penjualan --}}
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Filter Laporan Penjualan</h3>

                    <form action="{{ route('transaksi.index') }}" method="GET"
                        class="flex flex-col sm:flex-row items-start sm:items-end gap-4">

                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <div class="flex flex-col">
                                <label for="start_date" class="text-sm text-gray-600 mb-1">Dari Tanggal</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition w-full sm:w-48">
                            </div>

                            <div class="flex flex-col">
                                <label for="end_date" class="text-sm text-gray-600 mb-1">Sampai Tanggal</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition w-full sm:w-48">
                            </div>
                        </div>

                        <div class="flex gap-2 mt-4 sm:mt-0">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-lg shadow-md transition flex items-center gap-2">
                                <i class="fa fa-filter"></i> Tampilkan
                            </button>
                            <a href="{{ route('transaksi.index') }}"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-5 py-2 rounded-lg shadow-md transition flex items-center gap-2">
                                <i class="fa fa-undo"></i> Reset
                            </a>
                        </div>

                    </form>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block">
                    <table class="min-w-full bg-white text-gray-900 shadow-lg rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-sm">
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">No. Invoice</th>
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Kasir</th>
                                <th class="px-4 py-2">Pelanggan</th>
                                <th class="px-4 py-2">Nomor</th>
                                <th class="px-4 py-2">Metode</th>
                                <th class="px-4 py-2">Sub Total</th>
                                <th class="px-4 py-2">Total Bayar</th>
                                <th class="px-4 py-2">Total Modal</th>
                                <th class="px-4 py-2">Profit</th>
                                <th class="px-4 py-2">Kembalian</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksi as $i => $trx)
                                <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors duration-200">
                                    <td class="px-4 py-2 font-medium">{{ $i + 1 }}</td>
                                    <td class="px-4 py-2 font-semibold text-gray-800">{{ $trx->no_invoice }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="px-4 py-2">{{ $trx->nama_user }}</td>
                                    <td class="px-4 py-2">{{ $trx->nama_pelanggan ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $trx->nomor_pelanggan ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $trx->metode_pembayaran }}</td>
                                    <td class="px-4 py-2 font-semibold text-gray-800">
                                        Rp{{ number_format($trx->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 font-semibold text-gray-800">
                                        Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 font-semibold text-gray-800">
                                        Rp{{ number_format($trx->total_modal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 font-semibold text-gray-800">
                                        Rp{{ number_format($trx->profit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 font-semibold text-gray-800">
                                        Rp{{ number_format($trx->kembalian, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 flex space-x-2">
                                        <a href="{{ route('transaksi.cetak', $trx->id) }}" target="_blank"
                                            class="text-amber-600 hover:amber-800 font-semibold text-sm flex items-center">
                                            <i class="fa-solid fa-print mr-1"></i> Cetak Struk
                                        </a>

                                        <button type="button" onclick="openDetailModal({{ $trx->id }})"
                                            class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center">
                                            <i class="fa-solid fa-eye mr-1"></i> Detail
                                        </button>

                                        @auth
                                            @if(auth()->user()->is_admin)
                                                <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 font-semibold text-sm flex items-center">
                                                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4 text-gray-500 font-medium">
                                        Tidak ada data transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Modal Detail Transaksi Desktop --}}
                @foreach ($transaksi as $trx)
                    <div id="detailModal{{ $trx->id }}"
                        class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 p-4">

                        <div
                            class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-y-auto max-h-[80vh] p-0 border-t-8 border-blue-600">
                            {{-- Header Modal --}}
                            <div class="bg-blue-600 text-white rounded-t-2xl px-6 py-4 flex justify-between items-center">
                                <h3 class="text-lg font-semibold">Detail Transaksi</h3>
                                <button onclick="closeDetailModal({{ $trx->id }})"
                                    class="text-white hover:text-gray-200 font-bold text-xl">&times;</button>
                            </div>

                            {{-- Nomor Invoice --}}
                            <div class="px-6 py-3 border-b border-gray-200 bg-blue-50">
                                <span class="text-sm text-gray-700 font-medium">No. Invoice: </span>
                                <span class="text-gray-900 font-semibold">{{ $trx->no_invoice }}</span>
                            </div>

                            {{-- Ringkasan Transaksi --}}
                            <div class="px-6 py-3 bg-blue-50 border-b border-gray-200 space-y-1">
                                <div class="flex justify-between text-gray-700">
                                    <span>Sub Total:</span>
                                    <span class="font-semibold">Rp{{ number_format($trx->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Total Bayar:</span>
                                    <span class="font-semibold">Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Kembalian:</span>
                                    <span class="font-semibold">Rp{{ number_format($trx->kembalian, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Total Modal:</span>
                                    <span class="font-semibold">Rp{{ number_format($trx->total_modal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Profit:</span>
                                    <span class="font-semibold">Rp{{ number_format($trx->profit, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Tabel Produk --}}
                            <div class="px-6 py-4">
                                <table class="min-w-full text-left border border-gray-200">
                                    <thead class="bg-blue-600">
                                        <tr>
                                            <th class="px-3 py-2 text-white border">No</th>
                                            <th class="px-3 py-2 text-white border">Foto</th>
                                            <th class="px-3 py-2 text-white border">Nama Produk</th>
                                            <th class="px-3 py-2 text-white border">Harga</th>
                                            <th class="px-3 py-2 text-white border">Jumlah</th>
                                            <th class="px-3 py-2 text-white border">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($trx->details as $i => $detail)
                                            <tr class="border-b hover:bg-blue-50 transition">
                                                <td class="px-3 py-2 border">{{ $i + 1 }}</td>
                                                <td class="px-3 py-2 border">
                                                    @if($detail->foto_product)
                                                        <img src="{{ asset($detail->foto_product) }}"
                                                            class="w-12 h-12 object-cover rounded"
                                                            alt="{{ $detail->nama_product }}">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-200 flex items-center justify-center rounded text-gray-400 text-xs">
                                                            No Img
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 border font-semibold text-gray-800">{{ $detail->nama_product }}</td>
                                                <td class="px-3 py-2 border text-gray-700">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                <td class="px-3 py-2 border text-gray-700">{{ $detail->jumlah }}</td>
                                                <td class="px-3 py-2 border font-semibold text-gray-800">Rp{{ number_format($detail->total, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Footer Modal --}}
                            <div class="px-6 py-4 flex justify-end border-t border-gray-200 bg-blue-50">
                                <button type="button" onclick="closeDetailModal({{ $trx->id }})"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Script Modal Desktop --}}
                <script>
                    function openDetailModal(id) {
                        document.getElementById('detailModal' + id).classList.remove('hidden');
                    }
                    function closeDetailModal(id) {
                        document.getElementById('detailModal' + id).classList.add('hidden');
                    }
                </script>

                {{-- Mobile Card --}}
                <div class="md:hidden">
                    @forelse ($transaksi as $trx)
                        <div class="relative shadow-lg p-4 flex flex-col space-y-3 text-gray-700 dark:text-gray-300 text-sm overflow-hidden"
                            style="background-image: url('{{ asset('images/card1.png') }}'); background-size: cover; background-position: center;">

                            {{-- Header: No. Invoice & Tanggal --}}
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-gray-800 font-semibold text-xs bg-white px-2 py-1 rounded">
                                    No. Invoice: {{ $trx->no_invoice }}
                                </div>
                                <div class="flex items-center space-x-1 text-white text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d F Y') }}</span>
                                </div>
                            </div>

                            {{-- Kasir --}}
                            <div class="flex items-center space-x-2 text-white font-medium">
                                <i class="fa-solid fa-cash-register"></i>
                                <span>Kasir: {{ $trx->nama_user }}</span>
                            </div>

                            {{-- Ringkasan --}}
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2 mt-2">
                                <div class="flex items-center space-x-2 text-white">
                                    <i class="fa-solid fa-user"></i>
                                    <span><strong>Pelanggan:</strong> {{ $trx->nama_pelanggan ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-white">
                                    <i class="fa-solid fa-phone"></i>
                                    <span><strong>Nomor:</strong> {{ $trx->nomor_pelanggan ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-white">
                                    <i class="fa-solid fa-credit-card"></i>
                                    <span><strong>Metode:</strong> {{ $trx->metode_pembayaran }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-white">
                                    <i class="fa-solid fa-box"></i>
                                    <span><strong>Total Modal:</strong> Rp{{ number_format($trx->total_modal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-white">
                                    <i class="fa-solid fa-dollar-sign"></i>
                                    <span><strong>Profit:</strong> Rp{{ number_format($trx->profit, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-white">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    <span><strong>Total:</strong> Rp{{ number_format($trx->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-white">
                                    <i class="fa-solid fa-hand-holding-dollar"></i>
                                    <span><strong>Bayar:</strong> Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-white">
                                    <i class="fa-solid fa-coins"></i>
                                    <span><strong>Kembalian:</strong> Rp{{ number_format($trx->kembalian, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="flex space-x-2 mt-3">
                                <a href="{{ route('transaksi.cetak', $trx->id) }}" target="_blank"
                                    class="flex items-center bg-white text-amber-600 px-3 py-1 rounded-lg font-semibold text-sm hover:bg-blue-50 hover:text-amber-700 transition">
                                    <i class="fa-solid fa-print mr-1"></i> Cetak Struk
                                </a>

                                <button type="button" onclick="openDetailModalMobile({{ $trx->id }})"
                                    class="flex items-center bg-white text-blue-600 px-3 py-1 rounded-lg font-semibold text-sm hover:bg-green-50 hover:text-blue-700 transition">
                                    <i class="fa-solid fa-eye mr-1"></i> Detail
                                </button>
                            </div>

                            @auth
                                @if (auth()->user()->is_admin)
                                    {{-- Tombol Hapus --}}
                                    <button type="button" onclick="openDeleteModal({{ $trx->id }})"
                                        class="flex items-center ml-2 bg-gray-200 text-red-600 px-3 py-1 rounded font-semibold text-sm hover:bg-gray-300 hover:text-red-700 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
                                        </svg>
                                        Hapus
                                    </button>

                                    {{-- Modal Konfirmasi Hapus --}}
                                    <div id="deleteModal{{ $trx->id }}" class="hidden fixed inset-0 flex items-center justify-center z-50">
                                        <div class="modal-content bg-white bg-cover bg-center rounded-xl shadow-2xl p-6 w-80"
                                            style="background-image: url('{{ asset('images/card1.png') }}');">
                                            <h2 class="text-lg font-semibold text-white mb-3 text-center">Konfirmasi Hapus</h2>
                                            <p class="text-sm text-white text-center mb-5">
                                                Apakah kamu yakin ingin menghapus transaksi ini?
                                            </p>

                                            <div class="flex justify-center space-x-3">
                                                <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition duration-200">
                                                        Hapus
                                                    </button>
                                                </form>

                                                <button type="button" onclick="closeDeleteModal({{ $trx->id }})"
                                                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-400 transition duration-200">
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endauth

                            {{-- Modal Detail Mobile --}}
                            <div id="detailModalMobile{{ $trx->id }}"
                                class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 p-4 md:hidden">

                                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-y-auto max-h-[80vh] p-0 border-t-8 border-blue-600">
                                    {{-- Header Modal --}}
                                    <div class="bg-blue-600 text-white rounded-t-2xl px-6 py-4 flex justify-between items-center">
                                        <h3 class="text-lg font-semibold">Detail Transaksi</h3>
                                        <button onclick="closeDetailModalMobile({{ $trx->id }})" class="text-white hover:text-gray-200 font-bold text-xl">&times;</button>
                                    </div>

                                    {{-- Nomor Invoice --}}
                                    <div class="px-6 py-3 border-b border-gray-200">
                                        <span class="text-sm text-gray-700 font-medium">No. Invoice: </span>
                                        <span class="text-gray-900 font-semibold">{{ $trx->no_invoice }}</span>
                                    </div>

                                    {{-- Ringkasan Transaksi --}}
                                    <div class="px-6 py-3 bg-blue-50 border-b border-gray-200 space-y-1">
                                        <div class="flex justify-between text-gray-700">
                                            <span>Sub Total:</span>
                                            <span class="font-semibold">Rp{{ number_format($trx->total, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between text-gray-700">
                                            <span>Total Bayar:</span>
                                            <span class="font-semibold">Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between text-gray-700">
                                            <span>Kembalian:</span>
                                            <span class="font-semibold">Rp{{ number_format($trx->kembalian, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between text-gray-700">
                                            <span>Total Modal:</span>
                                            <span class="font-semibold">Rp{{ number_format($trx->total_modal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between text-gray-700">
                                            <span>Profit:</span>
                                            <span class="font-semibold">Rp{{ number_format($trx->profit, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    {{-- List Produk --}}
                                    <div class="px-4 py-3 space-y-3">
                                        @foreach($trx->details as $i => $detail)
                                            <div class="flex space-x-3 items-center p-3 border rounded-lg hover:bg-blue-50 transition">
                                                <div>
                                                    @if($detail->foto_product)
                                                        <img src="{{ asset($detail->foto_product) }}" class="w-14 h-14 object-cover rounded" alt="{{ $detail->nama_product }}">
                                                    @else
                                                        <div class="w-14 h-14 bg-gray-200 flex items-center justify-center rounded text-gray-400 text-xs">
                                                            No Img
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="flex-1 text-sm text-gray-700">
                                                    <div class="font-semibold text-gray-800">{{ $detail->nama_product }}</div>
                                                    <div class="flex justify-between mt-1 text-gray-600">
                                                        <span>Harga: Rp{{ number_format($detail->harga, 0, ',', '.') }}</span>
                                                        <span>Jumlah: {{ $detail->jumlah }}</span>
                                                    </div>
                                                    <div class="mt-1 font-semibold text-gray-800">Total: Rp{{ number_format($detail->total, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Footer Modal --}}
                                    <div class="px-6 py-4 flex justify-end border-t border-gray-200">
                                        <button type="button" onclick="closeDetailModalMobile({{ $trx->id }})" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Script Modal Mobile --}}
                            <script>
                                function openDetailModalMobile(id) {
                                    document.getElementById('detailModalMobile' + id).classList.remove('hidden');
                                }
                                function closeDetailModalMobile(id) {
                                    document.getElementById('detailModalMobile' + id).classList.add('hidden');
                                }
                                function openDeleteModal(id) {
                                    document.getElementById('deleteModal' + id).classList.remove('hidden');
                                }
                                function closeDeleteModal(id) {
                                    document.getElementById('deleteModal' + id).classList.add('hidden');
                                }
                            </script>

                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 text-sm font-medium">
                            Tidak ada data transaksi.
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $transaksi->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
