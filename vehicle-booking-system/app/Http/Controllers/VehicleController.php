<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $vehicles = Vehicle::latest()->paginate(15);
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|unique:vehicles|max:20',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'type' => 'required|in:angkutan_orang,angkutan_barang',
            'ownership' => 'required|in:perusahaan,sewa',
            'region' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $vehicle = Vehicle::create($validated);
        $this->logActivity('created', $vehicle, "Vehicle {$vehicle->license_plate} created");

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('bookings.driver', 'bookings.creator');
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicle->id,
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'type' => 'required|in:angkutan_orang,angkutan_barang',
            'ownership' => 'required|in:perusahaan,sewa',
            'region' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $oldValues = $vehicle->toArray();
        $vehicle->update($validated);
        $newValues = $vehicle->fresh()->toArray();

        $this->logActivity('updated', $vehicle, "Vehicle {$vehicle->license_plate} updated", $oldValues, $newValues);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $licensePlate = $vehicle->license_plate;
        $vehicle->delete();
        $this->logActivity('deleted', $vehicle, "Vehicle {$licensePlate} deleted");

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
