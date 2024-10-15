<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2>Serial: {{ $product->serial }}</h2>
                    <p>Name: {{ $product->title }}</p>
                    <p>Category: {{ $product->category }}</p>
                    <p>Price: {{ number_format($product->price, 2, ',', '.') }} IDR</p> <!-- Format the price -->
                    <p>Certificate: {{ $product->certificate }}</p>
                    <p>Code Manufactur: {{ $product->code_manufactur }}</p> <!-- Menampilkan code_manufactur -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
