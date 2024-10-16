<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $product->title }}">
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
                            </div>
                            <div class="col-md-4">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" name="price" class="form-control" value="{{ $product->price }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="serial" class="form-label">Serial</label>
                                <input type="text" name="serial" class="form-control"
                                    value="{{ $product->serial }}">
                            </div>
                            <div class="col-md-4">
                                <label for="certificate" class="form-label">Certificate</label>
                                <input type="text" name="certificate" class="form-control"
                                    value="{{ $product->certificate }}">
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
