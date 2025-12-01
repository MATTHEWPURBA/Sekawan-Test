<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $drivers = Driver::latest()->paginate(15);
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:drivers|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $driver = Driver::create($validated);
        $this->logActivity('created', $driver, "Driver {$driver->name} created");

        return redirect()->route('drivers.index')
            ->with('success', 'Driver created successfully.');
    }

    public function show(Driver $driver)
    {
        $driver->load('bookings.vehicle', 'bookings.creator');
        return view('drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'required|string|max:50|unique:drivers,license_number,' . $driver->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $oldValues = $driver->toArray();
        $driver->update($validated);
        $newValues = $driver->fresh()->toArray();

        $this->logActivity('updated', $driver, "Driver {$driver->name} updated", $oldValues, $newValues);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    public function destroy(Driver $driver)
    {
        $name = $driver->name;
        $driver->delete();
        $this->logActivity('deleted', $driver, "Driver {$name} deleted");

        return redirect()->route('drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }
}
