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
                                <th>Actions</th>
                                <th>QR Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->category }}</td>
                                    <td>{{ number_format($product->price, 2, ',', '.') }} IDR</td>
                                    <!-- Format the price -->
                                    <td>{{ $product->serial }}</td>
                                    <td>{{ $product->certificate }}</td>
                                    <td>
                                        <a href="{{ route('admin/products/edit', $product->id) }}"
                                            class="btn btn-primary">Edit</a>
                                        <a href="{{ route('admin/products/delete', $product->id) }}"
                                            class="btn btn-danger">Delete</a>
                                        {{-- <a href="{{ route('admin/products/detail', $product->id) }}"
                                            class="btn btn-info">Detail</a> --}}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin/products/qrcode', $product->id) }}"
                                            class="btn btn-secondary">Download QR Code</a>
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
