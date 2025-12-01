<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Booking;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $user = Auth::user();
        
        $pendingApprovals = Approval::with(['booking.vehicle', 'booking.driver', 'booking.creator'])
            ->where('approver_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('level')
            ->latest()
            ->paginate(15);

        return view('approvals.index', compact('pendingApprovals'));
    }

    public function approve(Request $request, Approval $approval)
    {
        if ($approval->approver_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'You are not authorized to approve this request.');
        }

        if ($approval->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This approval has already been processed.');
        }

        $approval->update([
            'status' => 'approved',
            'comments' => $request->comments,
            'approved_at' => now(),
        ]);

        // Check if all approvals are approved
        $booking = $approval->booking;
        $allApprovals = $booking->approvals;
        $allApproved = $allApprovals->every(function ($approval) {
            return $approval->status === 'approved';
        });

        if ($allApproved) {
            $booking->update(['status' => 'approved']);
        }

        // Check if we need to move to next level
        $nextLevel = $approval->level + 1;
        $nextApproval = $allApprovals->where('level', $nextLevel)->first();
        
        if ($nextApproval && $nextApproval->status === 'pending') {
            // Notify next approver (in a real app, you'd send email/notification)
        }

        // Log activity
        $this->logActivity('approved', $approval, "Approval level {$approval->level} approved for booking {$booking->booking_number}");

        return redirect()->route('approvals.index')
            ->with('success', 'Booking approved successfully.');
    }

    public function reject(Request $request, Approval $approval)
    {
        if ($approval->approver_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'You are not authorized to reject this request.');
        }

        if ($approval->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This approval has already been processed.');
        }

        $request->validate([
            'comments' => 'required|string|max:500',
        ]);

        $approval->update([
            'status' => 'rejected',
            'comments' => $request->comments,
            'approved_at' => now(),
        ]);

        // Reject the booking
        $booking = $approval->booking;
        $booking->update(['status' => 'rejected']);

        // Reject all pending approvals
        $booking->approvals()
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'comments' => 'Rejected due to rejection at level ' . $approval->level,
            ]);

        // Log activity
        $this->logActivity('rejected', $approval, "Approval level {$approval->level} rejected for booking {$booking->booking_number}");

        return redirect()->route('approvals.index')
            ->with('success', 'Booking rejected.');
    }
}
