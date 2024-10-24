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
        return view('admin.product.create');
    }

    public function save(Request $request)
    {
        // Validate product data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            'serial' => 'required|string|max:255',
            'certificate' => 'required|string|max:255',
            'code_manufactur' => 'required|string|max:255|unique:products,code_manufactur',
        ]);

        // Create new product
        $product = Product::create($validatedData + ['branch_id' => Auth::user()->branch_id, 'created_by' => Auth::id()]);

        // Generate QR code link and save it
        $product->qr_code_link = $this->generateQrCodeLink($product->id);
        $product->save();

        // Generate and save QR code image
        $this->generateQrCode($product->qr_code_link);

        return redirect()->route('admin.products')->with('success', 'Product created and QR code generated!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        // Find the product or fail with a 404 error
        $product = Product::findOrFail($id);

        // Validate request data
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            'serial' => 'required|string|max:255',
            'certificate' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // Update product attributes
        $product->fill($request->only('title', 'category', 'price', 'serial', 'certificate'));
        $product->edited_by = Auth::id(); // Record who edited the product

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $this->handleImageUpload($product, $request->file('image'));
        }

        // Check and generate QR code if it doesn't exist
        if (!$product->qr_code_link) {
            $product->qr_code_link = $this->generateQrCodeLink($product->id);
            $this->generateQrCode($product->qr_code_link);
        }

        // Save updated product
        $product->save();

        return redirect()->route('admin.products')->with(['success' => 'Product updated successfully']);
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
        // Validate the file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        // Import Excel file
        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->route('admin.products')->with('success', 'Products imported successfully.');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
