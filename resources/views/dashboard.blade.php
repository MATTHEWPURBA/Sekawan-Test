<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 hover:shadow-md transition-shadow">
                    <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Total Vehicles</div>
                    <div class="text-3xl font-bold text-gray-900 pl-1">{{ $totalVehicles }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 hover:shadow-md transition-shadow">
                    <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Total Bookings</div>
                    <div class="text-3xl font-bold text-gray-900 pl-1">{{ $totalBookings }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 hover:shadow-md transition-shadow">
                    <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600 pl-1">{{ $pendingBookings }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 hover:shadow-md transition-shadow">
                    <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Approved</div>
                    <div class="text-3xl font-bold text-green-600 pl-1">{{ $approvedBookings }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 hover:shadow-md transition-shadow">
                    <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Rejected</div>
                    <div class="text-3xl font-bold text-red-600 pl-1">{{ $rejectedBookings }}</div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Vehicle Usage Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Vehicle Usage (Last 6 Months)</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="vehicleUsageChart"></canvas>
                    </div>
                </div>

                <!-- Vehicle Type Usage -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Vehicle Type Usage</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="vehicleTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Prepare data with fallbacks for empty collections
            const vehicleUsageData = {!! json_encode($vehicleUsage->pluck('month')) !!} || [];
            const vehicleUsageCounts = {!! json_encode($vehicleUsage->pluck('count')) !!} || [];
            const vehicleTypeLabels = {!! json_encode($vehicleTypeUsage->pluck('type')) !!} || [];
            const vehicleTypeCounts = {!! json_encode($vehicleTypeUsage->pluck('count')) !!} || [];

            // Vehicle Usage Chart
            const vehicleUsageCanvas = document.getElementById('vehicleUsageChart');
            if (vehicleUsageCanvas) {
                const vehicleUsageCtx = vehicleUsageCanvas.getContext('2d');
                
                // If no data, show empty chart with message
                if (vehicleUsageData.length === 0) {
                    vehicleUsageData.push('No Data');
                    vehicleUsageCounts.push(0);
                }

                new Chart(vehicleUsageCtx, {
                    type: 'line',
                    data: {
                        labels: vehicleUsageData,
                        datasets: [{
                            label: 'Bookings',
                            data: vehicleUsageCounts,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                enabled: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Vehicle Type Chart
            const vehicleTypeCanvas = document.getElementById('vehicleTypeChart');
            if (vehicleTypeCanvas) {
                const vehicleTypeCtx = vehicleTypeCanvas.getContext('2d');
                
                // If no data, show empty chart with message
                if (vehicleTypeLabels.length === 0) {
                    vehicleTypeLabels.push('No Data');
                    vehicleTypeCounts.push(1);
                }

                // Generate more colors if needed
                const colors = [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                ];
                
                const backgroundColors = vehicleTypeLabels.map((_, index) => 
                    colors[index % colors.length]
                );

                new Chart(vehicleTypeCtx, {
                    type: 'doughnut',
                    data: {
                        labels: vehicleTypeLabels,
                        datasets: [{
                            data: vehicleTypeCounts,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                display: true,
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                enabled: true
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
