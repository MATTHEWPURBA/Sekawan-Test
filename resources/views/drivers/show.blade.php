<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Driver Details') }} - {{ $driver->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Name</h3>
                        <p>{{ $driver->name }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">License Number</h3>
                        <p>{{ $driver->license_number }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Phone</h3>
                        <p>{{ $driver->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Email</h3>
                        <p>{{ $driver->email ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Status</h3>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($driver->is_active) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $driver->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    @if($driver->address)
                        <div class="md:col-span-2">
                            <h3 class="font-semibold text-gray-700 mb-2">Address</h3>
                            <p>{{ $driver->address }}</p>
                        </div>
                    @endif
                </div>

                @if($driver->bookings->count() > 0)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-4">Bookings ({{ $driver->bookings->count() }})</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($driver->bookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $booking->booking_number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $booking->vehicle->license_plate }} - {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->start_date->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->end_date->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    @if($booking->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end gap-2">
                    <a href="{{ route('drivers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to List
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('drivers.edit', $driver) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
