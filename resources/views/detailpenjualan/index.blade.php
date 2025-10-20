<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-5xl mx-auto sm:px-4 lg:px-6">
            <div class="space-y-4">

                {{-- Notifikasi --}}
                @if (session('success'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-green-600 dark:text-green-400 font-medium">
                        {{ session('success') }}
                    </p>
                @endif

                @forelse ($details as $detail)
                    <div class="shadow rounded border border-gray-200 dark:border-gray-700 p-4 flex flex-col space-y-3 text-gray-700 dark:text-gray-300 text-sm"
                        style="background-image: url('{{ asset('images/card1.png') }}'); background-size: cover; background-position: center;">

                        {{-- Header: Produk dan Jumlah --}}
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-2 font-semibold text-gray-800 dark:text-gray-200">
                                {{-- Box Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 5 9-5-9-5-9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l9 5 9-5M3 12l9 5 9-5" />
                                </svg>
                                <span>{{ $detail->nama_product }}</span>
                            </div>
                            <div class="text-white dark:text-white">
                                <span><strong>Qty:</strong> {{ $detail->jumlah }}</span>
                            </div>
                        </div>

                        {{-- Detail info --}}
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2">
                            <div class="flex items-center space-x-2 text-white dark:text-white">
                                <span><strong>Harga:</strong> Rp{{ number_format($detail->harga, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex items-center space-x-2 text-white dark:text-white">
                                <span><strong>Total:</strong> Rp{{ number_format($detail->total, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex items-center space-x-2 text-white dark:text-white">
                                <span><strong>ID Transaksi:</strong> {{ $detail->transaksi_penjualan_id }}</span>
                            </div>

                            <div class="flex items-center space-x-2 text-white dark:text-white">
                                <span><strong>ID Produk:</strong> {{ $detail->product_id }}</span>
                            </div>

                            <div class="flex items-center space-x-2 text-white dark:text-white">
                                <span><strong>Pelanggan:</strong>
                                    {{ $detail->transaksi->nama_pelanggan ?? '-' }}</span>
                            </div>

                            <div class="flex items-center space-x-2 text-white dark:text-white">
                                <span><strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($detail->transaksi->tanggal)->format('d M Y') }}</span>
                            </div>
                        </div>

                        {{-- Tombol Hapus dengan Modal --}}
                        <form action="{{ route('detailpenjualan.destroy', $detail->id) }}" method="POST"
                            class="inline-block mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="openDeleteModal({{ $detail->id }})"
                                class="flex items-center bg-white text-red-600 hover:bg-gray-300 hover:text-red-800 font-semibold text-sm transition-all duration-200 px-3 py-2 rounded-md shadow-sm border border-red-200">
                                {{-- Trash Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
                                </svg>
                                Hapus
                            </button>

                            {{-- Modal Konfirmasi Hapus --}}
                            <div id="delete-modal-{{ $detail->id }}"
                                class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                                <div class="bg-white rounded-lg shadow-lg w-80 p-5 relative text-center"
                                    style="background-image: url('{{ asset('images/card1.png') }}'); background-size: cover; background-position: center;">

                                    {{-- Isi Modal --}}
                                    <div class="text-white font-semibold mb-3">Konfirmasi Hapus</div>
                                    <p class="text-white text-sm mb-4">Apakah kamu yakin ingin menghapus data detail ini?
                                    </p>

                                    {{-- Tombol Aksi --}}
                                    <div class="flex justify-center space-x-3">
                                        <button type="button" onclick="closeDeleteModal({{ $detail->id }})"
                                            class="px-4 py-2 rounded-md text-sm font-semibold bg-gray-200 hover:bg-gray-300 text-gray-800 transition-all">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 rounded-md text-sm font-semibold bg-red-600 hover:bg-red-700 text-white transition-all">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <script>
                            function openDeleteModal(id) {
                                document.getElementById(`delete-modal-${id}`).classList.remove('hidden');
                            }

                            function closeDeleteModal(id) {
                                document.getElementById(`delete-modal-${id}`).classList.add('hidden');
                            }
                        </script>

                    </div>
                @empty
                    <div class="text-center text-gray-800 dark:text-gray-800 text-sm font-medium">
                        Tidak ada data detail penjualan.
                    </div>
                @endforelse
                {{-- Subtotal --}}
                @if ($details->count())
                    <div class="mt-6 p-4 rounded-lg shadow text-right text-white text-base font-semibold"
                        style="background-image: url('{{ asset('images/card1.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                        Subtotal: Rp{{ number_format($subtotal, 0, ',', '.') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>