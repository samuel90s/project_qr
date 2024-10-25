<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the currently authenticated user

        // Fetch products based on user role
        if ($user->role === 'admin') {
            $products = Product::orderBy('id', 'desc')->get();
        } elseif ($user->role === 'branch_admin') {
            $products = Product::where('branch_id', $user->branch_id)->orderBy('id', 'desc')->get();
        } else {
            $products = collect(); // Return an empty collection for unrecognized roles
        }

        $total = $products->count(); // Count total products

        return view('admin.product.home', compact('products', 'total'));
    }

    public function create()
    {
        $user = Auth::user(); // Get the currently authenticated user

        // Jika pengguna adalah admin, berikan opsi untuk memilih cabang (branch)
        $branches = [];
        if ($user->role === 'admin') {
            $branches = \App\Models\Branch::all(); // Ambil semua data cabang
        }

        return view('admin.product.create', compact('branches'));
    }

    public function save(Request $request)
    {
        $user = Auth::user(); // Get the currently authenticated user

        // Validasi data produk
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            'serial' => 'required|string|max:255',
            'certificate' => 'required|string|max:255',
            'code_manufactur' => 'required|string|max:255|unique:products,code_manufactur',
            'branch_id' => 'required_if:role,admin|exists:branches,id' // Hanya admin yang bisa memilih cabang
        ]);

        // Tentukan branch_id berdasarkan peran pengguna
        $branchId = $user->role === 'admin'
            ? $request->input('branch_id')  // Admin memilih cabang
            : $user->branch_id;             // Branch admin menggunakan cabangnya sendiri

        // Tambahkan data produk baru
        $product = Product::create($validatedData + [
            'branch_id' => $branchId,     // Menggunakan branch_id yang ditentukan
            'created_by' => $user->id     // Menyimpan ID pengguna yang membuat produk
        ]);

        // Generate QR code link dan simpan ke produk
        $product->qr_code_link = $this->generateQrCodeLink($product->id);
        $product->save();

        // Generate dan simpan gambar QR code
        $this->generateQrCode($product->qr_code_link);

        return redirect()->route('admin.products')->with('success', 'Product created and QR code generated!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        // Admin bisa memilih cabang saat mengedit produk
        $branches = [];
        if ($user->role === 'admin') {
            $branches = \App\Models\Branch::all(); // Ambil semua cabang
        }

        return view('admin.product.edit', compact('product', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user(); // Get the currently authenticated user

        // Validasi data produk
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            'serial' => 'required|string|max:255',
            'certificate' => 'nullable|string|max:255',
            'branch_id' => 'required_if:role,admin|exists:branches,id', // Admin bisa memilih cabang
            'image' => 'nullable|image|max:2048',
        ]);

        // Tentukan branch_id berdasarkan peran pengguna
        if ($user->role === 'admin') {
            $product->branch_id = $request->input('branch_id'); // Admin bisa mengubah cabang
        }

        // Cek jika branch admin sudah mengedit produk
        if ($user->role === 'branch_admin') {
            if ($product->is_edited == 1) {
                return redirect()->route('admin.products')->with('error', 'You have already edited this product once.');
            }
            $product->is_edited = 1; // Tandai produk sudah diedit
        }

        // Update data produk
        $product->fill($validatedData);
        $product->edited_by = $user->id; // Catat siapa yang mengedit produk

        // Jika ada gambar baru yang diunggah
        if ($request->hasFile('image')) {
            $this->handleImageUpload($product, $request->file('image'));
        }

        // Generate QR code jika belum ada
        if (!$product->qr_code_link) {
            $product->qr_code_link = $this->generateQrCodeLink($product->id);
            $this->generateQrCode($product->qr_code_link);
        }

        // Simpan produk yang telah diupdate
        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        if ($product->delete()) {
            return redirect()->route('admin.products')->with(['success' => 'Product deleted successfully']);
        }

        return redirect()->route('admin.products')->with(['error' => 'Failed to delete product']);
    }

    private function generateQrCode($text)
    {
        $options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L,
        ]);

        $qrcode = new QRCode($options);
        $imagePath = 'qrcode_' . Str::random(10) . '.png';
        $qrcode->render($text, public_path($imagePath));

        return $imagePath; // Return QR code image path
    }

    private function generateQrCodeLink($productId)
    {
        $uniqueCode = Str::random(10);
        return route('public.products.detail', ['id' => $productId, 'code' => $uniqueCode]);
    }

    private function handleImageUpload($product, $image)
    {
        // Optionally delete the old image
        if ($product->image) {
            $oldImagePath = public_path('images/products/' . $product->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Save the new image
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('images/products'), $filename);
        $product->image = $filename;
    }

    public function showQrCode($id)
    {
        // Ambil data produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Buat URL untuk QR code
        $qrCodeLink = url('/public/products/' . $product->id . '/detail/' . $product->qr_code_link);

        // Opsi untuk QR Code
        $options = new QROptions([
            'version'      => 5,
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => QRCode::ECC_L, // Level error correction
            'scale'        => 5,
        ]);

        // Hasilkan QR Code
        $qrCode = new QRCode($options);
        $imageData = base64_encode($qrCode->render($qrCodeLink));

        // Kirimkan image data dan produk ke view
        return view('admin.product.qrcode', ['imageData' => $imageData, 'product' => $product]);
    }

    public function detail($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return view('admin.product.detail', compact('product'));
    }

    public function createBranchProduct()
    {
        return view('branch.products.create'); // View for adding products
    }

    public function saveBranchProduct(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            // Additional validations as needed
        ]);

        // Get branch_id from the authenticated user
        $branchId = Auth::user()->branch_id;

        // Create new product linked to the branch
        Product::create($request->only('title', 'category', 'price') + [
            'branch_id' => $branchId,
            'created_by' => Auth::id(), // Save who created the product
        ]);

        return redirect()->route('branch.products.index')->with('success', 'Product added successfully.');
    }

    public function importExcel(Request $request)
    {
        // Validate the file upload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        // Import products from the uploaded Excel file
        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->route('admin.products')->with('success', 'Products imported successfully.');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx'); // Download products as Excel file
    }
}
