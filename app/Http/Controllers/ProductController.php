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
use Illuminate\Support\Facades\File;

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

        // If user is admin, provide options to select branches
        $branches = $user->role === 'admin' ? \App\Models\Branch::all() : [];

        return view('admin.product.create', compact('branches'));
    }

    public function save(Request $request)
    {
        $user = Auth::user(); // Get the currently authenticated user

        // Validate product data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            'serial' => 'required|string|max:255',
            'certificate' => 'required|string|max:255',
            'code_manufactur' => 'required|string|max:255|unique:products,code_manufactur',
            'branch_id' => 'required_if:role,admin|exists:branches,id', // Only admin can select a branch
        ]);

        // Determine branch_id based on user role
        $branchId = $user->role === 'admin' ? $request->input('branch_id') : $user->branch_id; // Branch admin uses their own branch

        // Create new product
        $product = Product::create($validatedData + [
            'branch_id' => $branchId,
            'created_by' => $user->id, // Save the ID of the user who created the product
        ]);

        // Generate QR code link and save to product, including the code
        $product->qr_code_link = $this->generateQrCodeLink($product->id, $product->code_manufactur);
        $product->save();

        // Generate and save QR code image
        $this->generateQrCode($product->qr_code_link);

        return redirect()->route('admin.products')->with('success', 'Product created and QR code generated!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        // Admin can select branches when editing a product
        $branches = $user->role === 'admin' ? \App\Models\Branch::all() : [];

        return view('admin.product.edit', compact('product', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user(); // Get the currently authenticated user

        // Validate product data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            'serial' => 'required|string|max:255',
            'certificate' => 'nullable|string|max:255',
            'branch_id' => 'required_if:role,admin|exists:branches,id', // Admin can select branches
            'image' => 'nullable|image|max:2048',
        ]);

        // Determine branch_id based on user role
        if ($user->role === 'admin') {
            $product->branch_id = $request->input('branch_id'); // Admin can change branch
        }

        // Check if branch admin has already edited this product
        if ($user->role === 'branch_admin') {
            if ($product->is_edited) {
                return redirect()->route('admin.products')->with('error', 'You have already edited this product once.');
            }
            $product->is_edited = 1; // Mark product as edited
        }

        // Update product data
        $product->fill($validatedData);
        $product->edited_by = $user->id; // Record who edited the product

        // If a new image is uploaded
        if ($request->hasFile('image')) {
            $this->handleImageUpload($product, $request->file('image'));
        }

        // Generate QR code if it doesn't exist
        if (!$product->qr_code_link) {
            $product->qr_code_link = $this->generateQrCodeLink($product->id, $product->code_manufactur);
            $this->generateQrCode($product->qr_code_link);
        }

        // Save the updated product
        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        if ($product->delete()) {
            return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
        }

        return redirect()->route('admin.products')->with('error', 'Failed to delete product.');
    }

    private function generateQrCode($text)
    {
        // Tentukan path folder untuk QR code
        $folderPath = public_path('qrcodes');

        // Periksa apakah folder qrcodes ada, jika tidak buat folder
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true); // Buat folder dengan izin yang sesuai
        }

        $options = new QROptions([
            'version' => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
        ]);

        $qrcode = new QRCode($options);
        $imageName = 'qrcode_' . Str::random(10) . '.png'; // Menyimpan QR code dengan nama acak
        $imagePath = $folderPath . '/' . $imageName; // Path lengkap untuk menyimpan gambar

        try {
            $qrcode->render($text, $imagePath); // Generate QR code dan simpan
        } catch (\Exception $e) {
            // Menangani kesalahan saat membuat QR code
            return response()->json(['error' => 'QR code generation failed: ' . $e->getMessage()]);
        }

        return $imageName; // Return nama file QR code
    }

    private function generateQrCodeLink($productId, $code)
    {
        // Generate a direct link to the product detail route including the code
        return route('public.products.detail', ['id' => $productId, 'code' => $code]);
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
        // Get product data by ID
        $product = Product::findOrFail($id);

        // Generate a unique URL for QR code with a unique code
        $qrCodeLink = $this->generateQrCodeLink($product->id, $product->code_manufactur);

        // Generate QR Code and save as image
        $qrCodeImageName = $this->generateQrCode($qrCodeLink);

        // Mengembalikan QR Code yang telah dihasilkan
        return response()->download(public_path('qrcodes/' . $qrCodeImageName))->deleteFileAfterSend(true);
    }

    public function detail($id, $code)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Validasi kode produk
        if ($product->code_manufactur !== $code) {
            return response()->json(['message' => 'Invalid product code'], 404);
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
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Import products from the uploaded Excel file
        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->route('admin.products')->with('success', 'Products imported successfully.');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
