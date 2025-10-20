<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;

class TransaksiPenjualanController extends Controller
{
    // Tampilkan semua transaksi
    public function index(Request $request)
    {
        $query = TransaksiPenjualan::query();

        $start = $request->input('start_date');
        $end = $request->input('end_date');

        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        } elseif ($start) {
            $query->where('tanggal', '>=', $start);
        } elseif ($end) {
            $query->where('tanggal', '<=', $end);
        }

        $transaksi = $query->latest()->paginate(6)->withQueryString();

        // Cek apakah ada data
        if ($transaksi->count() === 0 && ($start || $end)) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Tidak ada data transaksi untuk rentang tanggal yang dipilih.');
        }

        // Jika ada data transaksi dan filter tanggal dipakai
        if ($transaksi->count() > 0 && ($start || $end)) {
            session()->flash('success', 'Menampilkan '.$transaksi->count().' transaksi dari tanggal yang dipilih.');
        }

        return view('transaksi.index', compact('transaksi'));
    }
    
    // Hapus transaksi dan detailnya
    public function destroy($id)
    {
        $user = auth()->user();

        if (! $user->is_admin) {
            abort(403, 'Hanya admin yang dapat menghapus transaksi.');
        }

        $transaksi = TransaksiPenjualan::findOrFail($id);
        $transaksi->detailPenjualan()->delete(); // hapus detail dulu
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    // Cetak / preview struk
    public function cetakTransaksi($id)
    {
        $transaksi = TransaksiPenjualan::with('detailPenjualan')->findOrFail($id);

        return view('transaksi.cetak', compact('transaksi'));
    }
}
