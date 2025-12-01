<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $bookings = Booking::with(['vehicle', 'driver', 'creator', 'approvals.approver'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get();

        $filename = 'bookings_report_' . $startDate . '_to_' . $endDate . '.xlsx';

        return Excel::download(new BookingsExport($bookings), $filename);
    }
}
