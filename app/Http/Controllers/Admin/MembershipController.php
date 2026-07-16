<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Daftar semua membership (semua status)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterStatus = $request->input('status');
        $tglMulai = $request->input('tgl_mulai');
        $tglAkhir = $request->input('tgl_akhir');

        $query = Membership::with('pelanggan', 'processor')
            ->orderByRaw("FIELD(status, 'menunggu', 'aktif', 'nonaktif')")
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_kartu', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        if ($filterStatus && $filterStatus !== 'semua') {
            $query->where('status', $filterStatus);
        }

        if ($tglMulai && $tglAkhir) {
            $query->whereBetween('created_at', [$tglMulai . ' 00:00:00', $tglAkhir . ' 23:59:59']);
        }

        $memberships = $query->paginate(15)->withQueryString();
        $pengaturan = Pengaturan::first();

        return view('admin.membership.index', compact('memberships', 'search', 'filterStatus', 'tglMulai', 'tglAkhir', 'pengaturan'));
    }

    /**
     * Approve membership
     */
    public function approve($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->update([
            'status' => 'aktif',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.membership.index')
            ->with('success', 'Membership ' . $membership->pelanggan->nama_lengkap . ' berhasil disetujui!');
    }

    /**
     * Reject / nonaktifkan membership
     */
    public function reject($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->update([
            'status' => 'nonaktif',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.membership.index')
            ->with('success', 'Membership ' . $membership->pelanggan->nama_lengkap . ' ditolak/dinonaktifkan.');
    }

    /**
     * Menghapus (Menolak) Membership Tunggal
     */
    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->delete();
        return redirect()->route('admin.membership.index')->with('success', 'Data membership berhasil dihapus.');
    }

    /**
     * Menghapus Massal (Bulk Delete)
     */
    public function destroyBulk(Request $request)
    {
        $ids = $request->input('selected_ids');
        
        if (!$ids || count($ids) == 0) {
            return redirect()->route('admin.membership.index')->with('error', 'Belum ada data membership yang dipilih untuk dihapus.');
        }

        Membership::whereIn('id', $ids)->delete();

        return redirect()->route('admin.membership.index')->with('success', count($ids) . ' data membership berhasil dihapus sekaligus.');
    }

    /**
     * Halaman pengaturan membership
     */
    public function settings()
    {
        $pengaturan = Pengaturan::first();
        return view('admin.membership.settings', compact('pengaturan'));
    }

    /**
     * Update pengaturan membership
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'membership_aktif' => 'required|boolean',
        ]);

        $pengaturan = Pengaturan::first();
        $pengaturan->update([
            'membership_aktif' => $request->membership_aktif,
        ]);

        return redirect()->route('admin.membership.settings')
            ->with('success', 'Pengaturan membership berhasil diperbarui!');
    }
}
