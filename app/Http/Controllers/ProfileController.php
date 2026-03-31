<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateGlobal(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi input lengkap (Mengecualikan ID user ini untuk unique check)
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'        => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'     => 'nullable|string|min:8|confirmed', // 'confirmed' butuh 'password_confirmation'
        ]);

        // Update data dasar
        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->email = $request->email;

        // Jika form password diisi, enkripsi dan simpan password baru
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Profil dan akun berhasil diperbarui secara menyeluruh!');
    }
}