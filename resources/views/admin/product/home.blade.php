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
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h2 class="mb-0">List Product</h2>
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
                        @endif
                    </div>
                    <hr>

                    @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('error') }}
                        </div>
                    @endif

                    <!-- Menampilkan Total Produk -->
                    <div class="mb-4">
                        <strong>Total Products: </strong> {{ $total }}
                    </div>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Title</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Serial</th>
                                <th>Certificate</th>
                                <th>Code Manufacturer</th>
                                <th>Actions</th>
                                <th>QR Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->category }}</td>
                                    <td>{{ number_format($product->price, 2, ',', '.') }} IDR</td>
                                    <td>{{ $product->serial }}</td>
                                    <td>{{ $product->certificate }}</td>
                                    <td>{{ $product->code_manufactur }}</td>
                                    <td>
                                        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'branch_admin')
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                                            <a href="{{ route('admin.products.delete', $product->id) }}" class="btn btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                        @else
                                            <span class="text-muted">No actions available</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->qr_code_path)
                                            <img src="{{ asset($product->qr_code_path) }}" alt="QR Code" width="50" height="50">
                                        @else
                                            <a href="{{ route('admin.products.qrcode', $product->id) }}" class="btn btn-secondary">
                                                Generate QR Code
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No products available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
