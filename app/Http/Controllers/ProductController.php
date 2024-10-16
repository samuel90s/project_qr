<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        $total = Product::count();

        return view('admin.product.home', compact('products', 'total'));
    }

    public function create()
    {
        return view('admin.product.create');
    }

    public function save(Request $request)
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['message' => 'You need to be logged in.']);
        }

        // Validasi data yang diterima
        $validation = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            'serial' => 'required|string|max:255',
            'certificate' => 'required|string|max:255',
            'code_manufactur' => 'required|string|max:255|unique:products,code_manufactur',
        ]);

        // Tambahkan ID pengguna yang sedang login ke data produk
        $validation['created_by'] = Auth::id(); // Menggunakan Auth::id()

        // Buat produk baru
        try {
            $data = Product::create($validation);

            // Cek apakah data berhasil disimpan
            if ($data) {
                session()->flash('success', 'Product Added Successfully');
                return redirect(route('admin.products'));
            }
        } catch (\Exception $e) {
            // Jika terjadi kesalahan saat menyimpan data
            session()->flash('error', 'Some Problem Occurred: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }

        // Jika tidak berhasil, kembalikan ke form dengan error
        session()->flash('error', 'Some Problem Occurred');
        return redirect(route('admin.products.create'))->withInput();
    }



    public function edit($id)
{
    $product = Product::find($id);
    return view('admin.product.edit', compact('product'));
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    // Validasi data jika diperlukan
    $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'price' => 'required|string|max:255',
        'serial' => 'required|string|max:255',
        'certificate' => 'nullable|string|max:255',
        'image' => 'nullable|image|max:2048',
    ]);

    // Update atribut produk
    $product->title = $request->title;
    $product->category = $request->category;
    $product->price = $request->price;
    $product->serial = $request->serial;
    $product->certificate = $request->certificate;

    // Tambahkan ID pengguna yang mengedit produk
    $product->edited_by = Auth::id(); // Menggunakan Auth untuk mendapatkan ID pengguna

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = $image->getClientOriginalName();
        $image->move(public_path('images/products'), $filename);
        $product->image = $filename; // Jika ada kolom gambar di tabel
    }

    $product->save();

    return redirect()->route('admin.products')->with(['success' => 'Product updated successfully']);
}


    public function delete(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->delete()) {
            return redirect()->route('admin.products')->with(['success' => 'Product deleted successfully']);
        } else {
            return redirect()->route('admin.products')->with(['error' => 'Failed to delete product']);
        }
    }

    public function generateQrCode($text)
    {
        $options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L,
        ]);

        $qrcode = new QRCode($options);
        $imagePath = public_path('qrcode.png'); // Save the image to a file
        $qrcode->render($text, $imagePath);

        return $imagePath; // Return the file path
    }

    public function showQrCode($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $data = route('admin.products.detail', ['id' => $product->id]); // Link to the detail page
        $qrCodePath = $this->generateQrCode($data); // Generate the QR code and get the file path

        return response()->download($qrCodePath, 'qrcode.png', [
            'Content-Type' => 'image/png'
        ]);
    }


    public function detail($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('admin.products')->with(['error' => 'Product not found']);
        }

        return view('admin.product.detail', compact('product'));
    }

}
