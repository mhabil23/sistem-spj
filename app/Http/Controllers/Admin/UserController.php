<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        return view('admin.pengguna', compact('users'));
    }

    public function create()
    {
        return view('admin.pengguna-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_wa' => 'nullable|string|max:25',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,teknis,umum,ppk,ppspm,bendahara',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_wa' => $validated['no_wa'] ?? null,
            'password' => $validated['password'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Pengguna '.$validated['name'].' berhasil ditambahkan.');
    }

    // Form edit pengguna
    public function edit(User $user)
    {
        return view('admin.pengguna-edit', compact('user'));
    }

    // Update pengguna
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'no_wa' => 'nullable|string|max:25',
            'role' => 'required|in:admin,teknis,umum,ppk,ppspm,bendahara',
            'status' => 'required|in:aktif,nonaktif',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->no_wa = $validated['no_wa'] ?? null;
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        // Password hanya diubah kalau diisi
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna '.$user->name.' berhasil diperbarui.');
    }

    // Hapus pengguna
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
