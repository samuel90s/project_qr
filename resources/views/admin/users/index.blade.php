<x-app-layout>
    <h1 class="text-xl font-semibold">Unconfirmed Users</h1>

    @if(session('success'))
        <div class="mt-2 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full border-collapse border border-gray-200">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">Name</th>
                <th class="border border-gray-300 px-4 py-2">Email</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <form action="{{ route('admin.users.confirm', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-2 py-1">Confirm</button>
                        </form>
                        <form action="{{ route('admin.users.reject', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-2 py-1">Reject</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>
