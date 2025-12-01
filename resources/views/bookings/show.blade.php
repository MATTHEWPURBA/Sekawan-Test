<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Details') }} - {{ $booking->booking_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Vehicle</h3>
                        <p>{{ $booking->vehicle->license_plate }} - {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Driver</h3>
                        <p>{{ $booking->driver->name }} ({{ $booking->driver->license_number }})</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Start Date & Time</h3>
                        <p>{{ $booking->start_date->format('Y-m-d') }} at {{ $booking->start_time }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">End Date & Time</h3>
                        <p>{{ $booking->end_date->format('Y-m-d') }} at {{ $booking->end_time }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Purpose</h3>
                        <p>{{ $booking->purpose }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Destination</h3>
                        <p>{{ $booking->destination }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Status</h3>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($booking->status === 'approved') bg-green-100 text-green-800
                            @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Created By</h3>
                        <p>{{ $booking->creator->name }}</p>
                    </div>
                </div>

                @if($booking->notes)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">Notes</h3>
                        <p>{{ $booking->notes }}</p>
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="font-semibold text-gray-700 mb-4">Approval Status</h3>
                    <div class="space-y-2">
                        @foreach($booking->approvals as $approval)
                            <div class="border rounded p-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium">Level {{ $approval->level }}: {{ $approval->approver->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $approval->approver->email }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($approval->status === 'approved') bg-green-100 text-green-800
                                        @elseif($approval->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($approval->status) }}
                                    </span>
                                </div>
                                @if($approval->comments)
                                    <p class="text-sm text-gray-600 mt-2">{{ $approval->comments }}</p>
                                @endif
                                @if($approval->approved_at)
                                    <p class="text-xs text-gray-400 mt-1">{{ $approval->approved_at->format('Y-m-d H:i:s') }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('bookings.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

