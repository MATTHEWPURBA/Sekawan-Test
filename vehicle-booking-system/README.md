# Vehicle Booking System

A comprehensive web application for managing vehicle bookings with multi-level approval system, designed for a mining company.

## Features

1. **User Management**
   - Two user roles: Admin and Approver
   - Admin can create bookings and manage vehicles/drivers
   - Approvers can approve/reject bookings

2. **Booking Management**
   - Create, view, edit, and delete bookings
   - Assign vehicles and drivers to bookings
   - Multi-level approval system (minimum 2 levels)

3. **Multi-Level Approval**
   - Hierarchical approval process
   - Minimum 2 approval levels required
   - Track approval status at each level

4. **Dashboard**
   - Statistics overview
   - Vehicle usage charts
   - Booking status summary

5. **Reports**
   - Periodic booking reports
   - Excel export functionality

6. **Activity Logging**
   - Comprehensive logging of all system activities
   - Track user actions and changes

## Technology Stack

- **Framework**: Laravel 12.10.1
- **PHP Version**: 8.4.5
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **Frontend**: Blade Templates with Tailwind CSS
- **Charts**: Chart.js
- **Excel Export**: Maatwebsite Excel

## Installation

1. Clone the repository:
```bash
cd vehicle-booking-system
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env`:
```env
DB_CONNECTION=sqlite
# Or use MySQL/PostgreSQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=vehicle_booking
# DB_USERNAME=root
# DB_PASSWORD=
```

5. Run migrations and seeders:
```bash
php artisan migrate:fresh --seed
```

6. Build frontend assets:
```bash
npm run build
```

7. Start the development server:
```bash
php artisan serve
```

## Default User Credentials

### Admin User
- **Email**: admin@sekawan.com
- **Password**: password
- **Role**: Admin

### Approver Users
- **Email**: approver1@sekawan.com
- **Password**: password
- **Role**: Approver

- **Email**: approver2@sekawan.com
- **Password**: password
- **Role**: Approver

- **Email**: approver3@sekawan.com
- **Password**: password
- **Role**: Approver

## Usage Guide

### For Admin Users

1. **Login** with admin credentials
2. **Dashboard**: View statistics and charts
3. **Vehicles**: 
   - Add new vehicles
   - Edit vehicle information
   - View vehicle booking history
4. **Drivers**:
   - Add new drivers
   - Edit driver information
   - View driver booking history
5. **Bookings**:
   - Create new bookings
   - Assign vehicle and driver
   - Select approvers (minimum 2)
   - Edit pending bookings
   - Delete pending bookings
6. **Reports**:
   - Select date range
   - Export booking reports to Excel

### For Approver Users

1. **Login** with approver credentials
2. **Dashboard**: View statistics
3. **Approvals**: 
   - View pending approvals assigned to you
   - Approve or reject bookings
   - Add comments when approving/rejecting
4. **Bookings**: View all bookings (own and assigned)

### Booking Workflow

1. Admin creates a booking with:
   - Vehicle selection
   - Driver assignment
   - Date and time range
   - Purpose and destination
   - Minimum 2 approvers

2. Booking status becomes "Pending"

3. Approvers review and approve/reject:
   - Approvals are processed sequentially by level
   - All levels must approve for final approval
   - Any rejection rejects the entire booking

4. Once all approvals are received, booking status becomes "Approved"

## Database Schema

### Tables
- `users` - User accounts with roles
- `vehicles` - Vehicle information
- `drivers` - Driver information
- `bookings` - Booking records
- `approvals` - Multi-level approval records
- `activity_logs` - System activity logs

## Physical Data Model

See detailed [Physical Data Model Diagram](./docs/PHYSICAL_DATA_MODEL.md) for complete ERD and table structures.

The system uses the following relationships:
- User has many Bookings (created_by)
- User has many Approvals (approver_id)
- Vehicle has many Bookings
- Driver has many Bookings
- Booking belongs to Vehicle
- Booking belongs to Driver
- Booking belongs to User (creator)
- Booking has many Approvals
- Approval belongs to Booking
- Approval belongs to User (approver)

## Activity Diagram

See detailed [Activity Diagram](./docs/ACTIVITY_DIAGRAM.md) for complete process flows.

The booking process follows this flow:
1. Admin creates booking → Status: Pending
2. System creates approval records for each approver level
3. Level 1 approver reviews → Approve/Reject
4. If approved, Level 2 approver reviews → Approve/Reject
5. If all levels approved → Status: Approved
6. If any level rejected → Status: Rejected

## Logging

All system activities are logged in the `activity_logs` table, including:
- Booking creation, updates, deletion
- Approval actions
- Vehicle/Driver management
- User actions with timestamps and IP addresses

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

## License

This project is created for technical test purposes.

## Author

Created for Sekawan Media Technical Test
