<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('is_confirmed', false)->get(); // Ambil pengguna yang belum dikonfirmasi
        return view('admin.users.index', compact('users'));
    }

    public function confirm(User $user)
    {
        $user->is_confirmed = true; // Set is_confirmed menjadi true
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User confirmed successfully.');
    }

    public function reject(User $user)
    {
        $user->delete(); // Atau Anda bisa menandai sebagai ditolak dengan kolom lain
        return redirect()->route('admin.users.index')->with('success', 'User registration rejected.');
    }
}
