# Diagrams Summary

## Quick Reference

### Physical Data Model - Key Points

```
┌─────────┐         ┌──────────┐         ┌─────────┐
│  USERS  │────────▶│ BOOKINGS│◀────────│ VEHICLES│
│         │ creates │          │ assigned│         │
└────┬────┘         └────┬─────┘         └─────────┘
     │                  │
     │                  │
     │                  ▼
     │            ┌──────────┐
     │            │ APPROVALS│
     │            │          │
     └───────────▶│          │
      approves   └──────────┘
```

**Key Relationships:**
- 1 User → Many Bookings (created_by)
- 1 User → Many Approvals (approver_id)
- 1 Vehicle → Many Bookings
- 1 Driver → Many Bookings
- 1 Booking → Many Approvals (multi-level)

### Activity Diagram - Key Flow

```
Admin Creates Booking
        │
        ▼
System: Generate Booking Number
        │
        ▼
System: Create Approval Records (Level 1, 2, 3...)
        │
        ▼
Notify Level 1 Approver
        │
        ▼
    ┌───┴───┐
    │       │
Approve  Reject
    │       │
    │       └───▶ Booking Status: REJECTED
    │
    ▼
Notify Level 2 Approver
    │
    ▼
    ┌───┴───┐
    │       │
Approve  Reject
    │       │
    │       └───▶ Booking Status: REJECTED
    │
    ▼
... Continue for all levels ...
    │
    ▼
All Approved? ──YES──▶ Booking Status: APPROVED
    │
    NO
    │
    ▼
Wait for Next Approval
```

## Table Structure Summary

| Table | Primary Key | Foreign Keys | Unique Fields |
|-------|-----------|--------------|---------------|
| users | id | - | email |
| vehicles | id | - | license_plate |
| drivers | id | - | license_number |
| bookings | id | vehicle_id, driver_id, created_by | booking_number |
| approvals | id | booking_id, approver_id | - |
| activity_logs | id | user_id (nullable) | - |

## Process States

### Booking Status Flow
```
PENDING → APPROVED (all levels approve)
PENDING → REJECTED (any level rejects)
```

### Approval Status Flow
```
PENDING → APPROVED (approver approves)
PENDING → REJECTED (approver rejects OR cascade from earlier rejection)
```

## Viewing Full Diagrams

For complete visual diagrams with all details, see:
- [Physical Data Model](./PHYSICAL_DATA_MODEL.md) - Full ERD with all fields
- [Activity Diagram](./ACTIVITY_DIAGRAM.md) - Complete process flows with swimlanes

