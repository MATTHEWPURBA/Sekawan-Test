<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $bookings;

    public function __construct($bookings)
    {
        $this->bookings = $bookings;
    }

    public function collection()
    {
        return $this->bookings;
    }

    public function headings(): array
    {
        return [
            'Booking Number',
            'Vehicle',
            'Driver',
            'Start Date',
            'End Date',
            'Start Time',
            'End Time',
            'Purpose',
            'Destination',
            'Status',
            'Created By',
            'Created At',
            'Approval Status',
        ];
    }

    public function map($booking): array
    {
        $approvalStatus = $booking->approvals->map(function($approval) {
            return "Level {$approval->level}: " . ucfirst($approval->status) . 
                   ($approval->approver ? " by {$approval->approver->name}" : '');
        })->implode(' | ');

        return [
            $booking->booking_number,
            $booking->vehicle->license_plate . ' - ' . $booking->vehicle->brand . ' ' . $booking->vehicle->model,
            $booking->driver->name,
            $booking->start_date->format('Y-m-d'),
            $booking->end_date->format('Y-m-d'),
            $booking->start_time,
            $booking->end_time,
            $booking->purpose,
            $booking->destination,
            ucfirst($booking->status),
            $booking->creator->name,
            $booking->created_at->format('Y-m-d H:i:s'),
            $approvalStatus ?: 'No approvals',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
