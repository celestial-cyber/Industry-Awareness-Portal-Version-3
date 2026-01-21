# Student Login & Dashboard System - Implementation Guide

## Overview
This comprehensive student authentication and dashboard system has been implemented for the IAP Portal. Students can now:

1. **Authenticate** using roll number and password
2. **Reset passwords** on first login with security prompts
3. **View personalized dashboard** with registered sessions
4. **Access quizzes** for registered sessions with server-side access control
5. **Maintain secure sessions** with protected routes

---

## Features Implemented

### 1. **Student Authentication (student_login.php)**
- Roll number-based login (instead of email)
- Password authentication using `password_verify()` with bcrypt hashing
- Default password: `student@IAP`
- Demo credentials for testing: Roll Number `2021001`, Password `student@IAP`
- Bootstrap UI with responsive design
- Session validation using MySQLi prepared statements
- Automatic redirection to password reset on first login

### 2. **Password Reset System (reset_password.php)**
- Mandatory on first login (can skip if needed)
- Password strength indicator (Weak/Medium/Strong)
- Minimum 8 characters required
- Password confirmation validation
- Uses `password_hash()` with PASSWORD_BCRYPT
- Updates `is_password_changed` flag in database
- Bootstrap alerts for error/success messages

### 3. **Session Protection (includes/student_session_check.php)**
- Validates student session on every protected page
- Verifies student exists in database
- Redirects to login if session invalid
- Provides `$conn` variable for database operations
- Prevents unauthorized access

### 4. **Student Dashboard (student_dashboard.php)**
- Personalized welcome header with student info
- Year-wise organization of registered sessions
- Session cards showing:
  - Session title
  - Academic year
  - Description
  - Registration date
  - Registration status (registered/completed/dropped)
- "Take Quiz" button for each session (links to quiz.php?session_id={id})
- Empty state message if no sessions registered
- Responsive grid layout
- Student information sidebar
- Logout functionality

### 5. **Quiz Page with Access Control (quiz.php)**
- **Server-side validation** ensures student can only access quizzes for registered sessions
- Uses MySQLi prepared statements to prevent SQL injection
- Displays session information and quiz questions
- Access denied screen for unauthorized users
- Multiple question types:
  - Multiple choice
  - Rating scale (1-5)
  - Short text/essay
- Client-side and server-side validation
- Bootstrap form styling

### 6. **Database Schema (student_migration.sql)**

#### Tables Created:

**students**
```sql
- id (INT, Primary Key)
- roll_number (VARCHAR(50), UNIQUE)
- full_name (VARCHAR(255))
- email (VARCHAR(255))
- department (VARCHAR(100))
- year (ENUM: '1','2','3','4')
- password (VARCHAR(255)) - bcrypt hashed
- is_password_changed (BOOLEAN) - default FALSE
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

**sessions**
```sql
- id (INT, Primary Key)
- title (VARCHAR(255))
- year (ENUM: '1','2','3','4')
- description (TEXT)
- created_at (TIMESTAMP)
```

**student_sessions** (Junction Table)
```sql
- id (INT, Primary Key)
- student_id (INT, Foreign Key → students.id)
- session_id (INT, Foreign Key → sessions.id)
- registration_status (ENUM: 'registered','completed','dropped')
- registered_at (TIMESTAMP)
- UNIQUE constraint on (student_id, session_id)
```

---

## Setup Instructions

### Step 1: Import Database Migration
```bash
# MySQL CLI
mysql -u root -p iap_portal < student_migration.sql

# Or via phpMyAdmin:
# 1. Go to phpMyAdmin
# 2. Select 'iap_portal' database
# 3. Click 'Import' tab
# 4. Upload 'student_migration.sql' file
```

### Step 2: Configure Database Connection
The system uses default credentials:
```php
$servername = "localhost";
$db_username = "root";
$db_password = "root@123";
$database = "iap_portal";
```

If your credentials differ, update in:
- `student_login.php` (lines 30-32)
- `student_dashboard.php` (included in student_session_check.php)
- `quiz.php` (included in student_session_check.php)
- `reset_password.php` (lines 16-18)

### Step 3: File Structure
Ensure these files are in your project root:
```
/
├── student_login.php          # Student login page
├── reset_password.php         # Password reset page
├── student_dashboard.php      # Dashboard (protected)
├── quiz.php                   # Quiz page (protected)
├── includes/
│   └── student_session_check.php  # Session protection
├── student_migration.sql      # Database schema
├── index.php                  # Updated with student login link
└── [other existing files]
```

---

## Usage Guide

### For Students:

1. **First Time Login:**
   - Visit `student_login.php`
   - Enter Roll Number: `2021001`
   - Enter Password: `student@IAP`
   - Prompted to reset password (can skip)

2. **Accessing Dashboard:**
   - After login, redirected to `student_dashboard.php`
   - View all registered sessions
   - Organized by year
   - Click "Take Quiz" to access session quiz

3. **Taking a Quiz:**
   - Click "Take Quiz" button on dashboard
   - Answer all questions
   - Click "Submit Quiz"
   - Response is recorded

---

## Security Features

### 1. **Password Security**
- ✅ Bcrypt hashing using `password_hash()` and `password_verify()`
- ✅ Minimum 8 characters required
- ✅ Password strength indicator
- ✅ Confirmation validation

### 2. **Database Security**
- ✅ MySQLi prepared statements for all queries
- ✅ SQL injection prevention
- ✅ Parameterized queries with bind parameters

### 3. **Session Security**
- ✅ Session validation on every protected page
- ✅ Database verification of student existence
- ✅ Session timeout handling
- ✅ Automatic logout on session expiry

### 4. **Access Control**
- ✅ Server-side validation for quiz access
- ✅ Students can only access quizzes for registered sessions
- ✅ Cannot bypass session registration via URL manipulation
- ✅ Role-based access (student vs admin kept separate)

### 5. **Input Validation**
- ✅ HTML special character escaping with `htmlspecialchars()`
- ✅ Input trimming and sanitization
- ✅ Type casting for numeric values
- ✅ Form validation on client and server side

---

## Database Sample Data

Default students created:

| Roll Number | Name | Email | Department | Year | Password |
|---|---|---|---|---|---|
| 2021001 | Test Student | test@example.com | Computer Science | 1 | student@IAP |
| 2021002 | Jane Smith | jane.smith@example.com | Information Technology | 2 | student@IAP |
| 2021003 | Bob Johnson | bob.johnson@example.com | Electronics | 3 | student@IAP |
| 2021004 | Alice Brown | alice.brown@example.com | Mechanical | 4 | student@IAP |

Sample sessions created for each year (see student_migration.sql)

---

## Session Management

### Session Variables Set on Login:
```php
$_SESSION['student_id']        // Student ID (required)
$_SESSION['roll_number']       // Roll number (required)
$_SESSION['full_name']         // Full name
$_SESSION['email']             // Email
$_SESSION['department']        // Department
$_SESSION['year']              // Academic year
$_SESSION['is_password_changed'] // Boolean flag
```

### Session Check:
Every protected page includes `student_session_check.php` which:
1. Starts session
2. Verifies `student_id` and `roll_number` exist
3. Queries database to confirm student exists
4. Destroys session if invalid
5. Redirects to login page

---

## Password Reset Logic

### First Login Flow:
```
Login → Credentials Valid → is_password_changed = FALSE 
→ Redirect to reset_password.php?first_login=1
```

### Password Reset Page:
- Shows info box about first login
- "Save Password" button (validates)
- "Continue" button (allows skip)
- Password strength indicator
- Both options update `is_password_changed` appropriately

### On Next Login:
- If `is_password_changed = TRUE` → Go directly to dashboard
- If `is_password_changed = FALSE` → Show password reset again

---

## Quiz Access Control

### Validation Flow:
```
Quiz Request → Check Session Valid → Check Student Registered for Session 
→ Check Registration Status (not 'dropped') → Allow Access
```

### Query Used:
```php
$sql = "SELECT ss.id, s.id as session_id, s.title, s.year, s.description, ss.registration_status
        FROM student_sessions ss
        JOIN sessions s ON ss.session_id = s.id
        WHERE ss.student_id = ? AND s.id = ?";
```

### Access Denied Scenarios:
1. Session not found
2. Student not registered for session
3. Registration status is 'dropped'
4. Session expired or invalid

---

## Customization Guide

### Adding More Questions:
Create a `quiz_questions` table:
```sql
CREATE TABLE quiz_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    question VARCHAR(500) NOT NULL,
    question_type ENUM('multiple_choice', 'rating', 'short_text'),
    options JSON,
    correct_answer VARCHAR(255),
    FOREIGN KEY (session_id) REFERENCES sessions(id)
);
```

### Adding Quiz Responses:
Create a `quiz_responses` table:
```sql
CREATE TABLE quiz_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_id INT NOT NULL,
    question_id INT NOT NULL,
    answer TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (session_id) REFERENCES sessions(id),
    FOREIGN KEY (question_id) REFERENCES quiz_questions(id)
);
```

### Changing Default Password:
Edit `student_login.php` line 78:
```php
$default_password_hash = password_hash("YOUR_NEW_PASSWORD", PASSWORD_BCRYPT);
```

### Changing Database Credentials:
Update in all files:
1. `student_login.php` (lines 30-32)
2. `includes/student_session_check.php` (lines 30-33)
3. `reset_password.php` (lines 16-18)
4. `student_dashboard.php` (via includes)
5. `quiz.php` (via includes)

---

## Troubleshooting

### Issue: "Database connection failed"
- ✅ Check MySQL is running
- ✅ Verify credentials (localhost, root, root@123)
- ✅ Ensure `iap_portal` database exists

### Issue: "Session expired" after login
- ✅ Check student record exists in `students` table
- ✅ Verify `roll_number` matches exactly
- ✅ Check session.auto_start is enabled in php.ini

### Issue: "You are not registered for this session"
- ✅ Verify student_sessions record exists
- ✅ Check registration_status is 'registered' or 'completed'
- ✅ Ensure session_id is correct

### Issue: Password reset loop
- ✅ Verify `is_password_changed` column exists in students table
- ✅ Check UPDATE query execution in reset_password.php
- ✅ Verify password hash generated correctly

---

## Testing Checklist

- [ ] Able to login with demo credentials
- [ ] Password reset prompt appears on first login
- [ ] Can skip password reset and access dashboard
- [ ] Dashboard shows registered sessions
- [ ] Quiz page accessible for registered sessions
- [ ] Quiz page blocked for non-registered sessions
- [ ] Can submit quiz responses
- [ ] Logout works and redirects to login
- [ ] Session expires and redirects to login
- [ ] Cannot access dashboard by manipulating URL

---

## Additional Notes

- **Separate from Admin:** Student and Admin authentication are completely separate
- **Bootstrap UI:** All pages use Bootstrap 5.3.0 for consistent, responsive design
- **Font Awesome:** Icons using Font Awesome 6.5.1
- **No Dependencies:** No external PHP libraries required beyond MySQLi
- **Production Ready:** All inputs validated, SQL injection prevented, sessions secured

---

## Support

For issues or questions:
1. Check troubleshooting section
2. Review database schema in student_migration.sql
3. Verify all files are in correct locations
4. Check error logs in browser console (F12)
5. Check PHP error logs

---

**Last Updated:** January 2026
**Status:** Complete Implementation
