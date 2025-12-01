<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Drivers') }}
            </h2>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('drivers.create') }}" 
                       class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded no-underline"
                       style="background-color: #3b82f6 !important; color: #ffffff !important; padding: 0.5rem 1rem !important; border-radius: 0.25rem !important; text-decoration: none !important; font-weight: 700 !important; display: inline-block !important;">
                        ➕ Create Driver
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">License Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($drivers as $driver)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $driver->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $driver->license_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $driver->phone ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $driver->email ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($driver->is_active) bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $driver->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('drivers.show', $driver) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            @if(auth()->user()->isAdmin())
                                                <a href="{{ route('drivers.edit', $driver) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Edit</a>
                                                <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-4" onclick="return confirm('Are you sure you want to delete this driver?')">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No drivers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $drivers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
