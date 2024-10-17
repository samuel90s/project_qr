<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Menampilkan daftar pengguna yang belum dikonfirmasi atau detail pengguna
    public function index(Request $request)
    {
        // Jika ada parameter 'user', kita ambil pengguna tersebut dan tampilkan detailnya
        if ($request->has('user')) {
            $user = User::find($request->input('user'));

            // Jika pengguna tidak ditemukan, kembali ke daftar dengan pesan kesalahan
            if (!$user) {
                return redirect()->route('admin.users.index')->with('error', 'User not found.');
            }

            return view('admin.users.detail', compact('user')); // Pindahkan tampilan detail pengguna ke file blade `detail.blade.php`
        }

        // Jika tidak ada parameter 'user', tampilkan semua pengguna yang belum dikonfirmasi
        $users = User::where('is_confirmed', false)
                    ->whereIn('role', ['branch_admin', 'user'])
                    ->get();

        return view('admin.users.index', compact('users'));
    }

    // Menampilkan detail pengguna (opsional jika digunakan route terpisah)
    public function show(User $user)
    {
        return view('admin.users.detail', compact('user'));
    }

    // Mengonfirmasi pengguna
    public function confirm(User $user)
    {
        $user->is_confirmed = true; // Set is_confirmed menjadi true
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User confirmed successfully.');
    }

    // Menolak pengguna
    public function reject(User $user)
    {
        $user->is_confirmed = false; // Set is_confirmed menjadi false
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User registration rejected.');
    }
}
