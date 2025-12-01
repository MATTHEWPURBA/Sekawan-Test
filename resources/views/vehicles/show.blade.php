<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vehicle Details') }} - {{ $vehicle->license_plate }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">License Plate</h3>
                        <p>{{ $vehicle->license_plate }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Brand & Model</h3>
                        <p>{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Type</h3>
                        <p>{{ ucfirst(str_replace('_', ' ', $vehicle->type)) }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Ownership</h3>
                        <p>{{ ucfirst($vehicle->ownership) }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Status</h3>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($vehicle->is_active) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $vehicle->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    @if($vehicle->region)
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Region</h3>
                            <p>{{ $vehicle->region }}</p>
                        </div>
                    @endif
                    @if($vehicle->location)
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Location</h3>
                            <p>{{ $vehicle->location }}</p>
                        </div>
                    @endif
                    @if($vehicle->notes)
                        <div class="md:col-span-2">
                            <h3 class="font-semibold text-gray-700 mb-2">Notes</h3>
                            <p>{{ $vehicle->notes }}</p>
                        </div>
                    @endif
                </div>

                @if($vehicle->bookings->count() > 0)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-4">Bookings ({{ $vehicle->bookings->count() }})</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($vehicle->bookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $booking->booking_number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $booking->driver->name }} ({{ $booking->driver->license_number }})
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
                    <a href="{{ route('vehicles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to List
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
