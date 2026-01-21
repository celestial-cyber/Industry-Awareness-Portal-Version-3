# IAP Portal - Student System Complete Setup Guide

## 📋 System Overview

This is a complete Student Registration, Login, and Dashboard system with the following features:

✅ **Student Registration** - Students register with roll number, email, and personal details
✅ **Secure Login** - Roll number-based authentication with password hashing (bcrypt)
✅ **Default Password** - Auto-assigned default password `student@IAP`
✅ **Mandatory Password Reset** - First-login password reset requirement
✅ **Protected Dashboard** - Personalized, session-authenticated student dashboard
✅ **Session Management** - Students see only their registered sessions
✅ **Quiz System** - Server-side validation prevents unauthorized quiz access
✅ **Session Protection** - All student pages require authentication
✅ **Bootstrap UI** - Responsive, modern interface

---

## 🗄️ Database Schema

### Tables Created:

#### 1. **students**
Stores all student accounts and authentication data.

```sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    department VARCHAR(100),
    year ENUM('1', '2', '3', '4') NOT NULL,
    password VARCHAR(255) NOT NULL,          -- Bcrypt hashed
    is_password_changed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_roll_number (roll_number),
    INDEX idx_created_at (created_at)
);
```

**Key Fields:**
- `roll_number`: Unique identifier, used for login
- `password`: Bcrypt hashed password
- `is_password_changed`: Set to 0 (false) on registration, set to 1 (true) after password reset

#### 2. **sessions**
Stores IAP sessions organized by academic year.

```sql
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    year ENUM('1', '2', '3', '4') NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_year (year),
    INDEX idx_created_at (created_at)
);
```

#### 3. **student_sessions**
Junction table linking students to their registered sessions (many-to-many).

```sql
CREATE TABLE student_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_id INT NOT NULL,
    registration_status ENUM('registered', 'completed', 'dropped') DEFAULT 'registered',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_session (student_id, session_id),
    INDEX idx_student_id (student_id),
    INDEX idx_session_id (session_id)
);
```

---

## 📁 Files Created/Updated

### Core Student System Files:

| File | Purpose | Status |
|------|---------|--------|
| `student_register.php` | Student registration page | ✅ Created |
| `student_login.php` | Student login with roll number | ✅ Existing (Enhanced) |
| `student_dashboard.php` | Protected dashboard showing registered sessions | ✅ Existing (Enhanced) |
| `reset_password.php` | Mandatory password reset on first login | ✅ Existing (Enhanced) |
| `quiz.php` | Quiz page with server-side access control | ✅ Existing (Enhanced) |
| `includes/student_session_check.php` | Session protection middleware | ✅ Existing |

---

## 🔒 Security Features

### Authentication & Authorization:
- ✅ MySQLi prepared statements for all database queries
- ✅ Password hashing with PHP's `password_hash()` (BCRYPT algorithm)
- ✅ `password_verify()` for secure password comparison
- ✅ Session-based authentication with `$_SESSION['student_id']` and `$_SESSION['roll_number']`
- ✅ Separate student and admin authentication systems
- ✅ Server-side validation on all sensitive operations

### Input Validation:
- ✅ Email validation using `filter_var()`
- ✅ Roll number format validation (alphanumeric, 3-20 chars)
- ✅ Password strength requirements (minimum 8 characters)
- ✅ Input sanitization with `trim()` and `htmlspecialchars()`
- ✅ Prevention of SQL injection via prepared statements

### Access Control:
- ✅ Protected student pages require session check
- ✅ Quiz access validated server-side (students can only access registered sessions)
- ✅ Session persistence and validation on every protected page
- ✅ Logout functionality to destroy sessions

---

## 🚀 Quick Start Guide

### Step 1: Database Setup
Run the SQL commands in your MySQL client:

```sql
CREATE DATABASE IF NOT EXISTS iap_portal;
USE iap_portal;

-- Create tables (see schema above)
-- Insert sample data (see SQL file below)
```

Or use the provided SQL file: `COMPLETE_SETUP_SQL.sql`

### Step 2: Access the System

1. **Register a new student:**
   - Go to: `http://localhost/IAP%20Portal/student_register.php`
   - Enter: Roll Number, Email, Full Name, Department, Year
   - Default password is auto-assigned: `student@IAP`

2. **Login as student:**
   - Go to: `http://localhost/IAP%20Portal/student_login.php`
   - Enter: Roll Number and Password (`student@IAP` for new students)
   - You'll be prompted to reset your password on first login

3. **Reset Password:**
   - You'll be redirected to password reset page automatically
   - Enter a new password (minimum 8 characters)
   - Confirm the password
   - Click "Reset Password" to proceed

4. **View Dashboard:**
   - After password reset, you'll be redirected to your personal dashboard
   - See all sessions registered to you, organized by year
   - See your registration status for each session
   - Click "Take Quiz" for registered sessions

5. **Take a Quiz:**
   - Click "Take Quiz" on a registered session
   - System validates that you're registered for this session
   - If not registered: "You are not registered for this session" error
   - Quiz content is displayed only for authorized students

### Step 3: Database Connection
Edit database credentials in your files if needed:
```php
$servername = "localhost";
$db_username = "root";
$db_password = "root@123";
$database = "iap_portal";
```

---

## 📊 Test Credentials

### Sample Students (Default Password: `student@IAP`):
- **Roll Number:** 2021001 | **Name:** Test Student | **Year:** 1
- **Roll Number:** 2021002 | **Name:** Jane Smith | **Year:** 2
- **Roll Number:** 2021003 | **Name:** Bob Johnson | **Year:** 3
- **Roll Number:** 2021004 | **Name:** Alice Brown | **Year:** 4

### Sample Sessions:
- **Year 1:** Introduction to Engineering Careers, How to Ace Ideathons
- **Year 2:** Resume Building and Career Positioning, Interview Preparation
- **Year 3:** Internship Readiness Program, Advanced System Design
- **Year 4:** Startup Ecosystem and Entrepreneurship, Leadership and Management

---

## 🔄 Complete User Flow

```
1. Student goes to student_register.php
   ↓
2. Fills registration form (roll_number, email, name, dept, year)
   ↓
3. System creates account with:
   - password = bcrypt("student@IAP")
   - is_password_changed = 0
   ↓
4. Success message shown, redirected to student_login.php
   ↓
5. Student logs in with roll_number + "student@IAP"
   ↓
6. System checks is_password_changed:
   - If 0: Redirect to reset_password.php?first_login=1
   - If 1: Redirect to student_dashboard.php
   ↓
7. On reset_password.php:
   - Student enters new password (min 8 chars)
   - System hashes and updates password
   - Sets is_password_changed = 1
   ↓
8. Redirect to student_dashboard.php
   ↓
9. Dashboard displays:
   - Student name and info
   - Sessions organized by year
   - For each session: title, year, registration status, "Take Quiz" button
   ↓
10. Click "Take Quiz" button
    ↓
11. System validates student is registered for this session (server-side)
    ↓
12. If authorized: Display quiz
    If not authorized: Show error message
```

---

## 🛡️ Access Control Flow

### Protected Pages (require student session):
- `student_dashboard.php`
- `quiz.php`

### Access Check Code:
```php
// Include at top of protected pages
require_once 'includes/student_session_check.php';

// This checks:
// 1. session_start() called
// 2. $_SESSION['student_id'] exists
// 3. $_SESSION['roll_number'] exists
// 4. Student record still exists in database
// 5. If any check fails: redirect to student_login.php
```

---

## 📋 File Descriptions

### student_register.php
- **Purpose:** Student registration with roll number and email
- **Method:** POST
- **Validation:** Roll number format, email format, all fields required
- **Database:** Creates tables and inserts student with default password
- **Security:** Prepared statements, input sanitization
- **Response:** Success message with login redirect or error alerts

### student_login.php
- **Purpose:** Authenticate students using roll number and password
- **Method:** POST
- **Authentication:** Roll number lookup + password_verify()
- **Session Setup:** Sets $_SESSION['student_id'], $_SESSION['roll_number'], etc.
- **First Login:** If is_password_changed = 0, redirect to reset_password.php?first_login=1
- **Security:** Prepared statements, password verification
- **Response:** Redirect to dashboard or reset page on success, error alerts on failure

### reset_password.php
- **Purpose:** Mandatory password reset on first login
- **Method:** POST
- **Validation:** Password minimum 8 characters, password confirmation match
- **Database Update:** Sets password hash and is_password_changed = 1
- **Optional:** Can skip on first login (option available)
- **Security:** Prepared statements, bcrypt hashing
- **Response:** Success message with dashboard redirect

### student_dashboard.php
- **Purpose:** Personalized dashboard showing student's registered sessions
- **Access:** Requires student_session_check.php (protected)
- **Data:** Fetches student's sessions from student_sessions table
- **Display:** Sessions grouped by year, with cards showing title, status, "Take Quiz" button
- **Security:** MySQLi prepared statements, session validation

### quiz.php
- **Purpose:** Display quiz for a specific session
- **Access:** Requires student_session_check.php (protected)
- **Validation:** Server-side check that student is registered for this session_id
- **Query:** "SELECT * FROM student_sessions WHERE student_id = ? AND session_id = ?"
- **If Not Registered:** "You are not registered for this session" error
- **If Registered:** Display quiz questions
- **Security:** Prepared statements, MySQLi, validates session_id parameter

### includes/student_session_check.php
- **Purpose:** Session protection middleware for student pages
- **Checks:**
  1. Session started
  2. $_SESSION['student_id'] exists
  3. $_SESSION['roll_number'] exists
  4. Student record still exists in database
- **On Failure:** Redirect to student_login.php
- **Database:** Validates student still exists
- **Security:** Prepared statement for verification

---

## 🔑 Key Implementation Details

### Default Password Assignment:
```php
$default_password = "student@IAP";
$password_hash = password_hash($default_password, PASSWORD_BCRYPT);
// Insert into database with is_password_changed = 0
```

### First Login Password Reset Check:
```php
if (!$student['is_password_changed']) {
    header("Location: reset_password.php?first_login=1");
    exit();
}
```

### Quiz Access Validation:
```php
$sql = "SELECT ss.id FROM student_sessions ss
        WHERE ss.student_id = ? AND ss.session_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $_SESSION['student_id'], $session_id);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    die("You are not registered for this session");
}
```

---

## ✅ Verification Checklist

After setup, verify:

- [ ] Database `iap_portal` created with all 3 tables
- [ ] Sample students inserted with is_password_changed = 0
- [ ] Sample sessions inserted
- [ ] Sample student_sessions records created
- [ ] Registration form works (creates new student)
- [ ] Login works with default password
- [ ] First login redirects to password reset
- [ ] Password reset updates is_password_changed = 1
- [ ] After reset, redirects to dashboard
- [ ] Dashboard shows only student's registered sessions
- [ ] "Take Quiz" button links to quiz.php?session_id={id}
- [ ] Quiz page validates student is registered
- [ ] Logout clears session and redirects to login
- [ ] All pages use prepared statements

---

## 🐛 Troubleshooting

### "Database connection failed"
- Check MySQL is running
- Verify credentials in `student_login.php` and other files
- Check `iap_portal` database exists

### "Roll number not found"
- Verify student was created during registration
- Check roll number exists in `students` table
- Check for case sensitivity issues

### "Invalid password"
- Verify password is correct (case-sensitive)
- Check password was hashed correctly
- For new students, use default: `student@IAP`

### "You are not registered for this session"
- Verify student is linked in `student_sessions` table
- Check `student_id` and `session_id` match
- Verify `registration_status` is 'registered' or 'completed'

---

## 📞 Support

For issues or questions, check:
1. Database credentials in files
2. Tables exist with correct schema
3. Sample data was inserted
4. All files are in correct directory
5. Error messages and logs in browser console

---

**Setup Status: ✅ COMPLETE**
**Last Updated:** January 21, 2026
**Version:** 1.0
