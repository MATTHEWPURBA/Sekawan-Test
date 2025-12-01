<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;
use App\Models\Approval;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $bookings = Booking::with(['vehicle', 'driver', 'creator', 'approvals.approver'])
                ->latest()
                ->paginate(15);
        } else {
            $bookings = Booking::with(['vehicle', 'driver', 'creator', 'approvals.approver'])
                ->where('created_by', $user->id)
                ->orWhereHas('approvals', function($query) use ($user) {
                    $query->where('approver_id', $user->id);
                })
                ->latest()
                ->paginate(15);
        }

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $vehicles = Vehicle::whereRaw('is_active = true')->get();
        $drivers = Driver::whereRaw('is_active = true')->get();
        $approvers = User::where('role', 'approver')->get();
        
        return view('bookings.create', compact('vehicles', 'drivers', 'approvers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'purpose' => 'required|string|max:500',
            'destination' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'approvers' => 'required|array|min:2',
            'approvers.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $booking = Booking::create([
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'created_by' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'destination' => $request->destination,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Create multi-level approvals (minimum 2 levels)
        foreach ($request->approvers as $index => $approverId) {
            Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $approverId,
                'level' => $index + 1,
                'status' => 'pending',
            ]);
        }

        // Log activity
        $this->logActivity('created', $booking, "Booking {$booking->booking_number} created");

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully. Waiting for approval.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['vehicle', 'driver', 'creator', 'approvals.approver']);
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Only pending bookings can be edited.');
        }

        $vehicles = Vehicle::whereRaw('is_active = true')->get();
        $drivers = Driver::whereRaw('is_active = true')->get();
        $approvers = User::where('role', 'approver')->get();
        
        return view('bookings.edit', compact('booking', 'vehicles', 'drivers', 'approvers'));
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Only pending bookings can be edited.');
        }

        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'purpose' => 'required|string|max:500',
            'destination' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldValues = $booking->toArray();
        $booking->update($request->all());
        $newValues = $booking->fresh()->toArray();

        // Log activity
        $this->logActivity('updated', $booking, "Booking {$booking->booking_number} updated", $oldValues, $newValues);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Only pending bookings can be deleted.');
        }

        $bookingNumber = $booking->booking_number;
        $booking->delete();

        // Log activity
        $this->logActivity('deleted', $booking, "Booking {$bookingNumber} deleted");

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}
