<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Daftar user dengan pagination, search, dan filter
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterRole = $request->input('role');
        $filterStatus = $request->input('status');

        $query = User::query()->orderBy('created_at', 'desc');

        // Search by nama, username, atau email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($filterRole && $filterRole !== 'semua') {
            $query->where('role', $filterRole);
        }

        // Filter by status aktif/nonaktif
        if ($filterStatus !== null && $filterStatus !== '' && $filterStatus !== 'semua') {
            $query->where('is_active', $filterStatus === 'aktif');
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'filterRole', 'filterStatus'));
    }

    /**
     * Buat user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:50|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:admin,kasir,pelanggan',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'is_active'    => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User baru berhasil ditambahkan!');
    }

    /**
     * Form edit user (return data untuk modal)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email'        => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'         => 'required|in:admin,kasir,pelanggan',
        ]);

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'role'         => $request->role,
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user ' . $user->nama_lengkap . ' berhasil diperbarui!');
    }

    /**
     * Toggle status aktif/nonaktif
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menonaktifkan dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.users.index')
            ->with('success', 'User ' . $user->nama_lengkap . ' berhasil ' . $status . '!');
    }

    /**
     * Menghapus user tunggal
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }

    /**
     * Menghapus massal (Bulk Delete)
     */
    public function destroyBulk(Request $request)
    {
        $ids = $request->input('selected_ids');
        
        if (!$ids || count($ids) == 0) {
            return redirect()->route('admin.users.index')->with('error', 'Belum ada user yang dipilih untuk dihapus.');
        }

        // Mencegah admin menghapus dirinya sendiri dalam bulk delete
        $authId = auth()->id();
        if (in_array($authId, $ids)) {
            // Hilangkan ID sendiri dari list delete
            $ids = array_diff($ids, [$authId]);
            $selfExcluded = true;
        } else {
            $selfExcluded = false;
        }

        if (count($ids) > 0) {
            User::whereIn('id', $ids)->delete();
        }

        $msg = count($ids) . ' user berhasil dihapus.';
        if ($selfExcluded) {
            $msg .= ' Akun Anda sendiri tidak ikut dihapus.';
        }

        return redirect()->route('admin.users.index')->with('success', $msg);
    }
}
