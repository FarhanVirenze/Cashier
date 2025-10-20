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
        $search = $request->input('search');

        // === Filter Berdasarkan Tanggal ===
        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        } elseif ($start) {
            $query->where('tanggal', '>=', $start);
        } elseif ($end) {
            $query->where('tanggal', '<=', $end);
        }

        // === Fitur Search (berdasarkan kolom yang valid) ===
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_invoice', 'like', "%{$search}%")
                    ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('nomor_pelanggan', 'like', "%{$search}%")
                    ->orWhere('nama_user', 'like', "%{$search}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$search}%")
                    ->orWhere('total', 'like', "%{$search}%")
                    ->orWhere('profit', 'like', "%{$search}%")
                    ->orWhere('tanggal', 'like', "%{$search}%");
            });
        }

        $transaksi = $query->latest()->paginate(6)->withQueryString();

        // === Notifikasi ===
        if ($transaksi->count() === 0 && ($start || $end || $search)) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Tidak ada data transaksi sesuai filter yang dipilih.');
        }

        if ($transaksi->count() > 0 && ($start || $end || $search)) {
            session()->flash('success', 'Menampilkan '.$transaksi->count().' transaksi hasil pencarian/filter.');
        }

        return view('transaksi.index', compact('transaksi', 'start', 'end', 'search'));
    }

    // Hapus transaksi dan detailnya
    public function destroy($id)
    {
        $user = auth()->user();

        if (! $user->is_admin) {
            abort(403, 'Hanya admin yang dapat menghapus transaksi.');
        }

        $transaksi = TransaksiPenjualan::findOrFail($id);
        $transaksi->detailPenjualan()->delete();
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    // Cetak / preview struk
    public function cetakTransaksi($id)
    {
        $transaksi = TransaksiPenjualan::with('detailPenjualan')->findOrFail($id);

        return view('transaksi.cetak', compact('transaksi'));
    }

    // Export ke PDF (pastikan ini ada)
    public function exportPdf(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $transaksi = TransaksiPenjualan::query()
            ->when($start && $end, fn ($q) => $q->whereBetween('tanggal', [$start, $end]))
            ->get();

        if ($transaksi->isEmpty()) {
            return redirect()->route('transaksi.index')->with('error', 'Tidak ada data untuk diekspor.');
        }

        $pdf = \PDF::loadView('transaksi.pdf', compact('transaksi', 'start', 'end'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-'.$start.'-sd-'.$end.'.pdf');
    }
}
