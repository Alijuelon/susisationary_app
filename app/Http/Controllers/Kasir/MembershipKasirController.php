<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipKasirController extends Controller
{
    /**
     * Daftar membership (menunggu + aktif)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $tglMulai = $request->input('tgl_mulai');
        $tglAkhir = $request->input('tgl_akhir');

        $query = Membership::with('pelanggan')
            ->whereIn('status', ['menunggu', 'aktif', 'ditolak']) // Optionally show all for better filtering
            ->orderByRaw("FIELD(status, 'menunggu', 'aktif', 'ditolak')")
            ->orderBy('created_at', 'desc');

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        if ($tglMulai && $tglAkhir) {
            $query->whereBetween('created_at', [$tglMulai . ' 00:00:00', $tglAkhir . ' 23:59:59']);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_kartu', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        $memberships = $query->paginate(15)->withQueryString();

        return view('kasir.membership.index', compact('memberships', 'search', 'status', 'tglMulai', 'tglAkhir'));
    }

    /**
     * Kasir menyetujui permohonan membership
     */
    public function approve($id)
    {
        $membership = Membership::findOrFail($id);
        
        if ($membership->status !== 'menunggu') {
            return redirect()->route('kasir.membership.index')
                ->with('error', 'Membership ini sudah diproses sebelumnya.');
        }

        $membership->update([
            'status' => 'aktif',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('kasir.membership.index')
            ->with('success', 'Membership ' . $membership->pelanggan->nama_lengkap . ' berhasil disetujui!');
    }

    /**
     * Menghapus (Menolak) Membership Tunggal
     */
    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->delete();
        return redirect()->route('kasir.membership.index')->with('success', 'Data membership berhasil dihapus.');
    }

    /**
     * Menghapus Massal (Bulk Delete)
     */
    public function destroyBulk(Request $request)
    {
        $ids = $request->input('selected_ids');
        
        if (!$ids || count($ids) == 0) {
            return redirect()->route('kasir.membership.index')->with('error', 'Belum ada data membership yang dipilih untuk dihapus.');
        }

        Membership::whereIn('id', $ids)->delete();

        return redirect()->route('kasir.membership.index')->with('success', count($ids) . ' data membership berhasil dihapus sekaligus.');
    }
}
