<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Debug route (remove after fixing)
    Route::get('/debug/user', function () {
        $user = auth()->user();
        return [
            'authenticated' => auth()->check(),
            'user_id' => $user->id ?? null,
            'user_email' => $user->email ?? null,
            'user_name' => $user->name ?? null,
            'user_role' => $user->role ?? 'NULL',
            'role_type' => gettype($user->role ?? null),
            'isAdmin()' => $user->isAdmin() ?? false,
            'role === "admin"' => ($user->role ?? null) === 'admin',
            'raw_user' => $user->toArray(),
        ];
    })->name('debug.user');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Bookings
    Route::resource('bookings', BookingController::class);
    
    // Vehicles (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('vehicles', VehicleController::class);
        Route::resource('drivers', DriverController::class);
    });
    
    // Approvals
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

require __DIR__.'/auth.php';
