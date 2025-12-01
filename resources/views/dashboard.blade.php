<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Vehicles</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $totalVehicles }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Bookings</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $totalBookings }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-yellow-600">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $pendingBookings }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-green-600">Approved</div>
                    <div class="text-3xl font-bold text-green-600">{{ $approvedBookings }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-red-600">Rejected</div>
                    <div class="text-3xl font-bold text-red-600">{{ $rejectedBookings }}</div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Vehicle Usage Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Vehicle Usage (Last 6 Months)</h3>
                    <canvas id="vehicleUsageChart" height="200"></canvas>
                </div>

                <!-- Vehicle Type Usage -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Vehicle Type Usage</h3>
                    <canvas id="vehicleTypeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Vehicle Usage Chart
        const vehicleUsageCtx = document.getElementById('vehicleUsageChart').getContext('2d');
        new Chart(vehicleUsageCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($vehicleUsage->pluck('month')) !!},
                datasets: [{
                    label: 'Bookings',
                    data: {!! json_encode($vehicleUsage->pluck('count')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Vehicle Type Chart
        const vehicleTypeCtx = document.getElementById('vehicleTypeChart').getContext('2d');
        new Chart(vehicleTypeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($vehicleTypeUsage->pluck('type')) !!},
                datasets: [{
                    data: {!! json_encode($vehicleTypeUsage->pluck('count')) !!},
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
    @endpush
</x-app-layout>
