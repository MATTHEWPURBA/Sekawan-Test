<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVehicles = Vehicle::whereRaw('is_active = true')->count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $rejectedBookings = Booking::where('status', 'rejected')->count();

        // Vehicle usage chart data (last 6 months)
        $vehicleUsage = Booking::select(
            DB::raw("to_char(created_at, 'YYYY-MM') as month"),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy(DB::raw("to_char(created_at, 'YYYY-MM')"))
        ->orderBy('month')
        ->get();

        // Vehicle type usage - show all vehicle types even if they have no bookings
        // Get all distinct vehicle types from active vehicles
        $allVehicleTypes = Vehicle::whereRaw('is_active = true')
            ->distinct()
            ->pluck('type')
            ->toArray();
        
        // Count bookings for each vehicle type
        $vehicleTypeUsage = collect($allVehicleTypes)->map(function ($type) {
            $count = Booking::join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
                ->where('vehicles.type', $type)
                ->whereRaw('vehicles.is_active = true')
                ->count();
            
            // Format the type name for display (angkutan_orang -> Angkutan Orang)
            $formattedType = str_replace('_', ' ', $type);
            $formattedType = ucwords($formattedType);
            
            return (object) [
                'type' => $formattedType,
                'count' => (int) $count
            ];
        });

        // Monthly booking trends
        $monthlyTrends = Booking::select(
            DB::raw("to_char(start_date, 'YYYY-MM') as month"),
            DB::raw('COUNT(*) as count')
        )
        ->where('start_date', '>=', now()->subMonths(6))
        ->groupBy(DB::raw("to_char(start_date, 'YYYY-MM')"))
        ->orderBy('month')
        ->get();

        return view('dashboard', compact(
            'totalVehicles',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'rejectedBookings',
            'vehicleUsage',
            'vehicleTypeUsage',
            'monthlyTrends'
        ));
    }
}
