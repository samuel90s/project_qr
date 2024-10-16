<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Branch') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Form untuk menambah cabang baru -->
                    <form action="{{ route('admin.branch.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-medium mb-2">Branch Name</label>
                            <input type="text" name="name" id="name" required
                                   class="border border-gray-300 rounded-lg w-full py-2 px-3
                                   focus:outline-none focus:ring focus:ring-blue-300
                                   @error('name') border-red-500 @enderror"
                                   value="{{ old('name') }}">
                            @error('name')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="location" class="block text-gray-700 font-medium mb-2">Location</label>
                            <input type="text" name="location" id="location" required
                                   class="border border-gray-300 rounded-lg w-full py-2 px-3
                                   focus:outline-none focus:ring focus:ring-blue-300
                                   @error('location') border-red-500 @enderror"
                                   value="{{ old('location') }}">
                            @error('location')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="contact_number" class="block text-gray-700 font-medium mb-2">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" required
                                   class="border border-gray-300 rounded-lg w-full py-2 px-3
                                   focus:outline-none focus:ring focus:ring-blue-300
                                   @error('contact_number') border-red-500 @enderror"
                                   value="{{ old('contact_number') }}">
                            @error('contact_number')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                                Add Branch
                            </button>
                            <a href="{{ route('admin.branch.index') }}" class="text-gray-600 hover:text-blue-600 transition duration-200">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
