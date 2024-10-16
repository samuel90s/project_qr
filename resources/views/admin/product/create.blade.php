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
                        <p><a href="{{ route('admin.products') }}" class="btn btn-primary">Go Back</a></p>
                    </div>
                    <form action="{{ route('admin.products.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title" value="{{ old('title') }}">
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
                                <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') }}">
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="serial" class="form-label">Serial</label>
                                <input type="text" name="serial" class="form-control" placeholder="Serial" value="{{ old('serial') }}">
                                @error('serial')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="certificate" class="form-label">Certificate</label>
                                <input type="text" name="certificate" class="form-control" placeholder="Certificate" value="{{ old('certificate') }}">
                                @error('certificate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="code_manufactur" class="form-label">Code Manufactur</label>
                                <input type="text" name="code_manufactur" class="form-control" placeholder="Code Manufactur" value="{{ old('code_manufactur') }}">
                                @error('code_manufactur')
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
