<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch; // Pastikan untuk mengimpor model Branch
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view with branches.
     */
    public function create(): View
    {
        $branches = Branch::all(); // Ambil semua data cabang
        return view('auth.register', compact('branches')); // Kirim data cabang ke view
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'alpha_num'],
            'branch_id' => ['required', 'exists:branches,id'], // Validasi branch_id
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'company' => $request->company,
            'username' => $request->username,
            'branch_id' => $request->branch_id, // Simpan branch_id
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Set role default ke 'user'
            'is_confirmed' => false, // Set is_confirmed default ke false
        ]);

        event(new Registered($user));

        // Jangan login otomatis pengguna baru
        // Auth::login($user);

        return redirect(route('login'))->with('success', 'Registration successful, please wait for confirmation.');
    }

}
