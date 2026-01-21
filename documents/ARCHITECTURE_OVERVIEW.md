# Session Registration System - Architecture & Flow

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    IAP PORTAL - PUBLIC HOMEPAGE                 │
│                        (index.php)                              │
│                                                                 │
│  Year 1          Year 2          Year 3          Year 4        │
│  ┌────────┐     ┌────────┐     ┌────────┐     ┌────────┐      │
│  │Session1│ ... │Session5│ ... │Session9│ ... │Session13      │
│  │(RightClick)   │       │     │       │     │       │      │
│  │(Menu Icon⋮)   │       │     │       │     │       │      │
│  └────────┘     └────────┘     └────────┘     └────────┘      │
└────────────────┬──────────────────────────────────────────────┘
                 │
                 │ "Register for this Session"
                 ▼
┌─────────────────────────────────────────────────────────────────┐
│         SESSION_REGISTRATION.PHP                               │
│  - Validate session exists                                     │
│  - Verify session_id in database                               │
│  - Return JSON redirect URL                                    │
└────────────────┬──────────────────────────────────────────────┘
                 │
    ┌────────────┴────────────┐
    │                         │
    ▼                         ▼
NEW STUDENT?              EXISTING STUDENT?
    │                         │
    │                         ▼
    │              ┌─────────────────────────┐
    │              │  STUDENT_LOGIN.PHP      │
    │              │  - Email & Password     │
    │              │  - Verify credentials   │
    │              │  - Set sessions         │
    │              │  - Auto-register        │
    │              │  - Redirect to quiz     │
    │              └─────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────┐
│   STUDENT_REGISTER.PHP                  │
│   - Roll number                         │
│   - Full name                           │
│   - Email                               │
│   - Department & Year                   │
│   - Hash password (default: student@IAP)│
│   - Create student account              │
│   - Auto-login                          │
│   - Auto-register session               │
│   - Redirect to password reset          │
└──────────────┬──────────────────────────┘
               │
               ▼
┌──────────────────────────────────────────┐
│   RESET_PASSWORD.PHP                     │
│   - First login password change          │
│   - Can skip                             │
│   - Redirect to quiz (with session)      │
└──────────────┬──────────────────────────┘
               │
               ▼
┌──────────────────────────────────────────┐
│   QUIZ.PHP?session_id=X                  │
│   - Load quiz for session X              │
│   - Student registered & ready           │
│   - Can take quiz                        │
└──────────────────────────────────────────┘
```

---

## Database Relationships

```
┌──────────────────┐
│   STUDENTS       │
├──────────────────┤
│ id (PK)          │◄─────────────────┐
│ roll_number (U)  │                  │
│ full_name        │                  │
│ email (U)        │                  │
│ department       │                  │
│ year             │                  │
│ password         │                  │
│ is_password_..   │                  │
│ created_at       │                  │
└──────────────────┘                  │
                                      │
                    ┌─────────────────┴─────────────────┐
                    │                                   │
                    │                                   │
        ┌───────────▼────────────────┐      ┌──────────▼─────────────┐
        │  STUDENT_SESSIONS          │      │  SESSIONS              │
        ├────────────────────────────┤      ├────────────────────────┤
        │ id (PK)                    │      │ id (PK)                │
        │ student_id (FK) ───────────┼─────►│ topic                  │
        │ session_id (FK) ───────────┼─────►│ year                   │
        │ registration_status        │      │ created_at             │
        │ registered_at              │      └────────────────────────┘
        │ UNIQUE (student_id,        │
        │         session_id)        │
        └────────────────────────────┘
```

---

## Page Flow Sequences

### New Student Sequence
```
1. index.php [User on homepage]
   ↓ Right-click session
2. session_registration.php [Fetch session details]
   ↓ POST session_id → JSON response
3. student_login.php?session=1 [Page loads]
   ↓ User clicks "Register here"
4. student_register.php?session=1 [Registration form]
   ↓ Fill form → Submit
5. [Backend: Create student account + auto-login]
   ↓
6. reset_password.php?first_login=1&session=1 [Password reset]
   ↓ Set password or skip
7. [Backend: Update is_password_changed flag]
   ↓
8. quiz.php?session_id=1 [Quiz page loads]
   ↓ Student takes quiz
```

### Existing Student Sequence
```
1. index.php [User on homepage]
   ↓ Right-click session
2. session_registration.php [Fetch session details]
   ↓ POST session_id → JSON response
3. student_login.php?session=1 [Page loads]
   ↓ Enter email & password
4. [Backend: Verify credentials + Auto-register for session]
   ↓
5. quiz.php?session_id=1 [Quiz page loads]
   ↓ Student takes quiz
```

---

## Admin Dashboard Flow

```
┌─────────────────────────────────┐
│  Admin Login                    │
│  (admin_login.php)              │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│  Admin Dashboard                │
│  (Admin/admin_dashboard.php)    │
│                                 │
│  Menu Options:                  │
│  • Home                         │
│  • Create Session               │
│  • View Session Registrations   │
│  • View Registered Students ◄───┼─ NEW
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│  View Registered Students       │
│  - List all students            │
│  - Count sessions per student   │
│  - Show registered session list │
│  - Quiz taken count (dummy)     │
│  - Modules completed (dummy)    │
└─────────────────────────────────┘
```

---

## Data Flow: Registration Entry

```
Student Clicks "Register for Session"
    │
    ▼
┌─────────────────────────────────────────┐
│ session_registration.php                │
│ - Validate session_id                   │
│ - Store in $_SESSION                    │
│ - Return redirect URL                   │
└────────────┬────────────────────────────┘
             │
             ▼
Redirect to: student_login.php?session=1
OR           student_register.php?session=1
             │
             ▼
         LOGIN/REGISTER
             │
             ▼
┌─────────────────────────────────────────┐
│ Database Insert                         │
│                                         │
│ INSERT INTO student_sessions            │
│ (student_id, session_id, status)        │
│ VALUES (123, 1, 'registered')           │
│                                         │
│ Prevented: Duplicate entry (UNIQUE)     │
└─────────────────────────────────────────┘
             │
             ▼
Redirect to: quiz.php?session_id=1
```

---

## Security Checkpoints

```
Input Validation
    ↓
Session Verification ──► session_id valid?
    ↓
Student Verification ──► Student exists?
    ↓
Prepared Statements ──► All SQL parameterized
    ↓
Password Hashing ──► bcrypt with PASSWORD_BCRYPT
    ↓
Unique Constraints ──► No duplicate registrations
    ↓
HTML Escaping ──► htmlspecialchars() on output
    ↓
Session Checks ──► Server-side validation
```

---

## Code Structure

### Key Files
```
IAP Portal/
├── index.php
│   ├── Fetch sessions with IDs
│   ├── Context menu CSS
│   ├── Context menu JavaScript
│   └── Session item HTML with data attributes
│
├── session_registration.php (NEW)
│   ├── POST handler for session_id
│   ├── Session verification
│   └── JSON response with redirect
│
├── student_login.php
│   ├── ?session parameter handler
│   ├── Auto-session registration logic
│   └── Conditional redirect (quiz vs dashboard)
│
├── student_register.php
│   ├── ?session parameter handler
│   ├── Auto-session registration after account creation
│   ├── Auto-login logic
│   └── Redirect to password reset with session
│
├── reset_password.php
│   ├── ?session parameter handler
│   ├── Conditional redirect on skip/change
│   └── Quiz vs dashboard redirect
│
├── quiz.php
│   └── session_id required for registration check
│
├── student_dashboard.php
│   └── Shows registered sessions
│
└── Admin/
    └── admin_dashboard.php
        ├── "View Registered Students" option
        ├── Complex SQL query with JOINs
        └── Student registration table display
```

---

## Query Examples

### Fetch Sessions with IDs (index.php)
```sql
SELECT id, topic FROM sessions WHERE year = ? ORDER BY created_at DESC
```

### Register Student for Session (student_login.php)
```sql
INSERT IGNORE INTO student_sessions (student_id, session_id, registration_status)
VALUES (?, ?, 'registered')
```

### Fetch Registered Students (Admin/admin_dashboard.php)
```sql
SELECT DISTINCT 
    s.id, s.full_name, s.email, s.roll_number, s.department, s.year,
    COUNT(DISTINCT ss.session_id) as sessions_count,
    GROUP_CONCAT(DISTINCT sess.topic SEPARATOR ', ') as registered_sessions
FROM students s
LEFT JOIN student_sessions ss ON s.id = ss.student_id
LEFT JOIN sessions sess ON ss.session_id = sess.id
GROUP BY s.id
ORDER BY s.created_at DESC
```

---

## Error Handling

```
Context Menu Click
    │
    ├─► Session ID missing
    │   └─► Alert: "Session ID not provided"
    │
    ├─► Session not found
    │   └─► Alert: "Session not found"
    │
    └─► Success
        └─► Redirect with ?session parameter

Registration Process
    │
    ├─► Duplicate email/roll number
    │   └─► Display: "Email/Roll already exists"
    │
    ├─► Invalid inputs
    │   └─► Display: Specific validation errors
    │
    ├─► Database error
    │   └─► Display: "Connection failed"
    │
    └─► Success
        └─► Auto-login → Redirect

Login Process
    │
    ├─► Email not found
    │   └─► Offer: Register new account
    │
    ├─► Wrong password
    │   └─► Display: "Invalid password"
    │
    └─► Success
        └─► Auto-register session → Redirect
```

---

## Session Variables Used

```
$_SESSION['student_id']        ← Primary key in students table
$_SESSION['email']             ← Student email (login credential)
$_SESSION['full_name']         ← Student full name
$_SESSION['roll_number']       ← Student roll number
$_SESSION['department']        ← Student department
$_SESSION['year']              ← Student year (1-4)
$_SESSION['is_password_changed']  ← Boolean flag for first login
$_SESSION['selected_session_id']  ← Temporary session ID (optional)
```

---

## Future Enhancement Points

1. **Quiz Tracking**: Replace dummy values with real quiz_results table
2. **Module Tracking**: Replace dummy values with real modules table
3. **Email Notifications**: Send confirmation emails on registration
4. **Session Capacity**: Add max_capacity field to sessions table
5. **Waitlist**: Track interested students if session is full
6. **Unregistration**: Allow students to unregister from sessions
7. **Progress Reports**: Create student performance dashboards
8. **Analytics**: Track registration trends, popular sessions, etc.

---

**Architecture Version**: 1.0  
**Last Updated**: January 21, 2026  
**Status**: ✅ Complete
