<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Approvals') }}
        </h2>
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

                    <div class="space-y-4">
                        @forelse($pendingApprovals as $approval)
                            <div class="border rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold">Booking: {{ $approval->booking->booking_number }}</h3>
                                        <p class="text-sm text-gray-500">Level {{ $approval->level }} Approval Required</p>
                                    </div>
                                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Vehicle</p>
                                        <p class="font-medium">{{ $approval->booking->vehicle->license_plate }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Driver</p>
                                        <p class="font-medium">{{ $approval->booking->driver->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Date Range</p>
                                        <p class="font-medium">{{ $approval->booking->start_date->format('Y-m-d') }} to {{ $approval->booking->end_date->format('Y-m-d') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Purpose</p>
                                        <p class="font-medium">{{ $approval->booking->purpose }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <form action="{{ route('approvals.approve', $approval) }}" method="POST" class="flex-1">
                                        @csrf
                                        <textarea name="comments" placeholder="Comments (optional)" class="w-full border-gray-300 rounded-md mb-2" rows="2"></textarea>
                                        <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('approvals.reject', $approval) }}" method="POST" class="flex-1">
                                        @csrf
                                        <textarea name="comments" placeholder="Rejection reason (required)" class="w-full border-gray-300 rounded-md mb-2" rows="2" required></textarea>
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-gray-500">
                                <p>No pending approvals</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $pendingApprovals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

