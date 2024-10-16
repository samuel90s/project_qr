<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    // Menampilkan daftar cabang
    public function index()
    {
        $branches = Branch::all(); // Ambil semua cabang
        return view('admin.branch.index', compact('branches'));
    }

    // Menampilkan form untuk membuat cabang baru
    public function create()
{
    return view('admin.branch.create'); // Tampilkan form pembuatan cabang
}

public function store(Request $request)
{
    // Validasi data input
    $request->validate([
        'name' => 'required|unique:branches|max:255',
        'location' => 'required|max:255',
        'contact_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
    ]);

    // Simpan cabang baru ke database
    Branch::create([
        'name' => $request->name,
        'location' => $request->location,
        'contact_number' => $request->contact_number,
    ]);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('admin.branch.index')->with('success', 'Branch created successfully.');
}

    // Menampilkan form untuk mengedit cabang
    // Menampilkan form untuk mengedit cabang
public function edit(Branch $branch)
{
    return view('admin.branch.edit', compact('branch')); // Tampilkan form edit cabang
}

// Memperbarui cabang yang ada
public function update(Request $request, Branch $branch)
{
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('branches')->ignore($branch->id), // This line should allow you to keep the same name
        ],
        'location' => 'required|string|max:255',
        'contact_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
    ]);

    // Update the branch with the validated data
    $branch->update($request->only('name', 'location', 'contact_number'));

    return redirect()->route('admin.branch.index')->with('success', 'Branch updated successfully.');
}



    // Menghapus cabang
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('admin.branch.index')->with('success', 'Branch deleted successfully.');
    }
}
