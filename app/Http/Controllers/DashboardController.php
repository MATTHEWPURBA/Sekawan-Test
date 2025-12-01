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

        // Vehicle type usage
        $vehicleTypeUsage = Booking::join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->select('vehicles.type', DB::raw('COUNT(*) as count'))
            ->groupBy('vehicles.type')
            ->get();

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
