<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="mb-0">List Product</h2>
                        <a href="{{ route('admin/products/create') }}" class="btn btn-primary">Add Product</a>
                    </div>
                    <hr>
                    @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Title</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Serial</th>
                                <th>Certificate</th>
                                <th>Code Manufactur</th> <!-- Menambahkan kolom code_manufactur -->
                                <th>Actions</th>
                                <th>QR Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td> <!-- Menggunakan $loop->iteration untuk nomor urut -->
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->category }}</td>
                                    <td>{{ number_format($product->price, 2, ',', '.') }} IDR</td> <!-- Memformat harga -->
                                    <td>{{ $product->serial }}</td>
                                    <td>{{ $product->certificate }}</td>
                                    <td>{{ $product->code_manufactur }}</td> <!-- Menampilkan code_manufactur -->
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                                        <a href="{{ route('admin.products.delete', $product->id) }}" class="btn btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                    </td>
                                    <td>
                                        @if($product->qr_code_path) <!-- Cek apakah QR code sudah di-generate -->
                                            <img src="{{ asset($product->qr_code_path) }}" alt="QR Code" width="50" height="50"> <!-- Menampilkan gambar QR -->
                                        @else
                                            <a href="{{ route('admin.products.qrcode', $product->id) }}" class="btn btn-secondary">
                                                Generate QR Code
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
