<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="mb-0">Add Product</h2>
                        <hr>
                        @if (session()->has('error'))
                            <div>
                                {{ session('error') }}
                            </div>
                        @endif
                        <p><a href="{{ route('admin/products') }}" class="btn btn-primary">Go Back</a></p>
                    </div>
                    <form action="{{ route('admin/products/save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title">
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="smoke signal">Smoke Signal</option>
                                    <option value="redhandflare">Red Hand Flare</option>
                                    <option value="rocket parachute">Rocket Parachute</option>
                                    <option value="manoverboard">Man Overboard</option>
                                    <option value="linetrhowing">Line Throwing</option>
                                </select>
                                @error('category')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" name="price" class="form-control" placeholder="Price">
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="serial" class="form-label">Serial</label>
                                <input type="text" name="serial" class="form-control" placeholder="Serial">
                                @error('serial')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="certificate" class="form-label">Certificate</label>
                                <input type="text" name="certificate" class="form-control" placeholder="Certificate">
                                @error('certificate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-grid">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
