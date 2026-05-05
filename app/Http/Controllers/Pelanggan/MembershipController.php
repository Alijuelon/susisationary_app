<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    /**
     * Tampilkan halaman keanggotaan pelanggan
     */
    public function index()
    {
        $membership = Membership::where('id_pelanggan', Auth::id())->first();
        $pengaturan = Pengaturan::first();

        return view('pelanggan.membership.index', compact('membership', 'pengaturan'));
    }

    /**
     * Daftar membership baru
     */
    public function store()
    {
        $userId = Auth::id();

        // Cek apakah sudah punya membership
        $existing = Membership::where('id_pelanggan', $userId)->first();
        if ($existing) {
            return redirect()->route('pelanggan.membership.index')
                ->with('error', 'Anda sudah memiliki permohonan/keanggotaan membership.');
        }

        // Cek apakah fitur membership aktif
        $pengaturan = Pengaturan::first();
        if (!$pengaturan || !$pengaturan->membership_aktif) {
            return redirect()->route('pelanggan.membership.index')
                ->with('error', 'Program membership sedang tidak aktif. Silakan coba lagi nanti.');
        }

        Membership::create([
            'id_pelanggan' => $userId,
            'no_kartu' => Membership::generateNoKartu(),
            'status' => 'menunggu',
        ]);

        return redirect()->route('pelanggan.membership.index')
            ->with('success', 'Permohonan membership berhasil dikirim! Menunggu persetujuan admin/kasir.');
    }
}
