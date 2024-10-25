<!-- resources/views/public/product/detail.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Judul Produk -->
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $product->title }}</h1>

                    <!-- Detail Produk -->
                    <div class="text-gray-700">
                        <p><strong>Category:</strong> {{ $product->category }}</p>
                        <p><strong>Price:</strong> ${{ number_format($product->price, 2, ',', '.') }}</p>
                        <p><strong>Serial Number:</strong> {{ $product->serial }}</p>
                        <p><strong>Certificate:</strong> {{ $product->certificate }}</p>
                    </div>

                    <!-- Gambar Produk -->
                    @if($product->image)
                        <div class="mt-6">
                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-auto rounded-lg shadow-md">
                        </div>
                    @endif

                    <!-- Tombol Kembali -->
                    <div class="mt-6">
                        <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 underline">Back to Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
