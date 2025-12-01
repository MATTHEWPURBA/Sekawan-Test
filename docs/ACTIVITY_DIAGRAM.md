# Activity Diagram - Vehicle Booking Process

## Booking Process Flow

```mermaid
flowchart TD
    Start([Start: Admin wants to create booking]) --> CreateForm[Admin fills booking form]
    CreateForm --> Validate{Validate form data}
    Validate -->|Invalid| ShowErrors[Show validation errors]
    ShowErrors --> CreateForm
    Validate -->|Valid| CreateBooking[Create booking record]
    CreateBooking --> GenerateNumber[Generate booking number]
    GenerateNumber --> SetStatus[Set status = 'pending']
    SetStatus --> CreateApprovals[Create approval records for each approver level]
    CreateApprovals --> LogActivity1[Log: Booking created]
    LogActivity1 --> NotifyApprovers[Notify Level 1 approvers]
    NotifyApprovers --> WaitApproval1{Wait for Level 1 approval}
    
    WaitApproval1 --> CheckLevel1{Level 1 Approver reviews}
    CheckLevel1 -->|Approve| UpdateApproval1[Update approval status = 'approved']
    UpdateApproval1 --> LogActivity2[Log: Level 1 approved]
    LogActivity2 --> CheckAllLevels1{All levels approved?}
    CheckAllLevels1 -->|No| NotifyNextLevel[Notify next level approver]
    NotifyNextLevel --> WaitApproval2{Wait for next level approval}
    WaitApproval2 --> CheckLevel2{Next Level Approver reviews}
    CheckLevel2 -->|Approve| UpdateApproval2[Update approval status = 'approved']
    UpdateApproval2 --> LogActivity3[Log: Level N approved]
    LogActivity3 --> CheckAllLevels2{All levels approved?}
    CheckAllLevels2 -->|Yes| FinalApproval[Set booking status = 'approved']
    FinalApproval --> LogActivity4[Log: Booking fully approved]
    LogActivity4 --> EndSuccess([End: Booking approved])
    
    CheckLevel1 -->|Reject| RejectApproval1[Update approval status = 'rejected']
    RejectApproval1 --> RejectAll[Reject all pending approvals]
    RejectAll --> SetRejected[Set booking status = 'rejected']
    SetRejected --> LogActivity5[Log: Booking rejected]
    LogActivity5 --> EndRejected([End: Booking rejected])
    
    CheckLevel2 -->|Reject| RejectApproval2[Update approval status = 'rejected']
    RejectApproval2 --> RejectAll
    
    CheckAllLevels1 -->|Yes| FinalApproval
    CheckAllLevels2 -->|No| NotifyNextLevel
```

## Detailed Activity Diagram with Swimlanes

```mermaid
flowchart TB
    subgraph Admin["Admin Role"]
        A1[Login as Admin]
        A2[Access Dashboard]
        A3[Create New Booking]
        A4[Fill Booking Form:<br/>- Select Vehicle<br/>- Select Driver<br/>- Set Dates/Times<br/>- Enter Purpose<br/>- Select Approvers min 2]
        A5[Submit Booking]
        A6[View Booking Status]
        A7[Edit Pending Booking]
        A8[Delete Pending Booking]
    end
    
    subgraph System["System Process"]
        S1[Validate Form Data]
        S2[Generate Booking Number]
        S3[Create Booking Record]
        S4[Create Approval Records<br/>for each level]
        S5[Set Status: Pending]
        S6[Log Activity: Created]
        S7[Check Approval Status]
        S8[Update Booking Status]
        S9[Log Activity: Status Change]
    end
    
    subgraph Approver["Approver Role"]
        AP1[Login as Approver]
        AP2[View Pending Approvals]
        AP3[Review Booking Details]
        AP4{Decision}
        AP5[Approve with Comments]
        AP6[Reject with Reason]
    end
    
    A1 --> A2
    A2 --> A3
    A3 --> A4
    A4 --> A5
    A5 --> S1
    S1 -->|Valid| S2
    S2 --> S3
    S3 --> S4
    S4 --> S5
    S5 --> S6
    S6 --> A6
    
    A6 -->|Edit| A7
    A7 --> S1
    A6 -->|Delete| A8
    A8 --> S9
    
    S6 --> AP1
    AP1 --> AP2
    AP2 --> AP3
    AP3 --> AP4
    AP4 -->|Approve| AP5
    AP4 -->|Reject| AP6
    
    AP5 --> S7
    AP6 --> S7
    S7 -->|All Approved| S8
    S7 -->|Any Rejected| S8
    S8 --> S9
    S9 --> A6
```

## Sequential Approval Process

```mermaid
sequenceDiagram
    participant Admin
    participant System
    participant Approver1 as Level 1 Approver
    participant Approver2 as Level 2 Approver
    participant Approver3 as Level 3 Approver
    participant Database
    
    Admin->>System: Create Booking
    System->>Database: Save Booking (status: pending)
    System->>Database: Create Approval Level 1
    System->>Database: Create Approval Level 2
    System->>Database: Create Approval Level 3
    System->>Database: Log Activity: Booking Created
    System->>Approver1: Notify: Approval Required
    
    Approver1->>System: View Pending Approval
    System->>Database: Fetch Booking Details
    Database-->>System: Return Booking Data
    System-->>Approver1: Display Booking Form
    
    Approver1->>System: Approve (Level 1)
    System->>Database: Update Approval Level 1 (approved)
    System->>Database: Log Activity: Level 1 Approved
    System->>Database: Check: All levels approved?
    Database-->>System: No (Level 2 & 3 pending)
    System->>Approver2: Notify: Approval Required
    
    Approver2->>System: View Pending Approval
    System->>Database: Fetch Booking Details
    Database-->>System: Return Booking Data
    System-->>Approver2: Display Booking Form
    
    Approver2->>System: Approve (Level 2)
    System->>Database: Update Approval Level 2 (approved)
    System->>Database: Log Activity: Level 2 Approved
    System->>Database: Check: All levels approved?
    Database-->>System: No (Level 3 pending)
    System->>Approver3: Notify: Approval Required
    
    Approver3->>System: View Pending Approval
    System->>Database: Fetch Booking Details
    Database-->>System: Return Booking Data
    System-->>Approver3: Display Booking Form
    
    Approver3->>System: Approve (Level 3)
    System->>Database: Update Approval Level 3 (approved)
    System->>Database: Check: All levels approved?
    Database-->>System: Yes (All approved)
    System->>Database: Update Booking (status: approved)
    System->>Database: Log Activity: Booking Approved
    System->>Admin: Notify: Booking Approved
```

## Rejection Flow

```mermaid
flowchart TD
    Start([Approver Reviews Booking]) --> Decision{Approve or Reject?}
    Decision -->|Approve| ApproveFlow[Approve Process]
    Decision -->|Reject| RejectFlow[Reject Process]
    
    RejectFlow --> UpdateApproval[Update Current Approval: Rejected]
    UpdateApproval --> RejectAllPending[Reject All Pending Approvals]
    RejectAllPending --> UpdateBooking[Update Booking Status: Rejected]
    UpdateBooking --> LogRejection[Log Activity: Booking Rejected]
    LogRejection --> NotifyAdmin[Notify Admin: Booking Rejected]
    NotifyAdmin --> EndReject([End: Booking Rejected])
    
    ApproveFlow --> UpdateApprovalApprove[Update Current Approval: Approved]
    UpdateApprovalApprove --> CheckAll{All Levels Approved?}
    CheckAll -->|No| NotifyNext[Notify Next Level Approver]
    NotifyNext --> WaitNext[Wait for Next Approval]
    WaitNext --> Start
    CheckAll -->|Yes| FinalApprove[Update Booking Status: Approved]
    FinalApprove --> LogApproval[Log Activity: Booking Approved]
    LogApproval --> NotifyAdminApprove[Notify Admin: Booking Approved]
    NotifyAdminApprove --> EndApprove([End: Booking Approved])
```

## Key Process Steps

### 1. Booking Creation (Admin)
- **Actor**: Admin
- **Actions**:
  1. Fill booking form
  2. Select vehicle
  3. Select driver
  4. Set date/time range
  5. Enter purpose and destination
  6. Select minimum 2 approvers
  7. Submit form
- **System Actions**:
  1. Validate input
  2. Generate unique booking number
  3. Create booking record (status: pending)
  4. Create approval records for each selected approver
  5. Log activity
  6. Notify approvers

### 2. Approval Process (Multi-Level)
- **Actor**: Approvers (Level 1, 2, 3, ...)
- **Actions**:
  1. View pending approvals assigned to them
  2. Review booking details
  3. Make decision (Approve/Reject)
  4. Add comments (optional for approve, required for reject)
  5. Submit decision
- **System Actions**:
  1. Update approval record
  2. Log activity
  3. Check if all levels approved
  4. If all approved: Update booking status to "approved"
  5. If rejected: Update booking status to "rejected" and reject all pending approvals
  6. If not all approved: Notify next level approver

### 3. Status Transitions
- **Pending** → **Approved** (when all approval levels approve)
- **Pending** → **Rejected** (when any approval level rejects)
- **Pending** → **Pending** (can be edited by admin)
- **Approved/Rejected** → Cannot be edited or deleted

### 4. Activity Logging
All actions are logged with:
- User who performed action
- Action type (created, updated, approved, rejected, deleted)
- Model type and ID
- Old and new values (for updates)
- Timestamp
- IP address and user agent

## Business Rules

1. **Minimum Approvers**: At least 2 approvers must be selected
2. **Sequential Approval**: Approvals are processed by level (1, 2, 3, ...)
3. **All or Nothing**: All levels must approve for final approval
4. **Rejection Cascade**: Any rejection rejects the entire booking
5. **Edit Restriction**: Only pending bookings can be edited
6. **Delete Restriction**: Only pending bookings can be deleted
7. **Date Validation**: End date must be after or equal to start date
8. **Time Validation**: Start time and end time must be valid

