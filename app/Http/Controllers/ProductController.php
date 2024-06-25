<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Chillerlan\QRCode\QRCode;
use Chillerlan\QRCode\QROptions;

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
        $validation = $request->validate([
            'title' => 'required',
            'price' => 'required',
            'category' => 'required',
            'serial' => 'required',
            'certificate' => 'required',
        ]);
        $data = Product::create($validation);
        if ($data) {
            session()->flash('success', 'Product Added Successfully');
            return redirect(route('admin/products'));
        } else {
            session()->flash('error', 'Some Problem Occurred');
            return redirect(route('admin/products/create'));
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('admin.product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->title = $request->title;
        $product->category = $request->category;
        $product->price = $request->price;
        $product->serial = $request->serial;
        $product->certificate = $request->certificate;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $image->move(public_path('images/products'), $filename);
            $product->image = $filename;
        }

        $product->save();

        return redirect()->route('admin/products')->with(['success' => 'Product updated successfully']);
    }

    public function delete(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->delete()) {
            return redirect()->route('admin/products')->with(['success' => 'Product deleted successfully']);
        } else {
            return redirect()->route('admin/products')->with(['error' => 'Failed to delete product']);
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
        return $qrcode->render($text);
    }

    public function showQrCode($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $data = route('admin/products/edit', ['id' => $product->id]);
        $qrCodeImage = $this->generateQrCode($data);

        return response($qrCodeImage)->header('Content-Type', 'image/png');
    }
}
