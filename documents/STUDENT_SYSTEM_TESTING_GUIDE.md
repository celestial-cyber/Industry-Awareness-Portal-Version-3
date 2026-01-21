# IAP Portal Student System - Complete Testing & Verification Guide

## 🎯 Overview
This guide provides step-by-step instructions to verify that the entire Student Registration, Login, and Dashboard system is working correctly.

---

## 📋 Pre-Testing Checklist

Before testing, ensure:

- [ ] MySQL server is running
- [ ] `iap_portal` database exists (created by PHP files or manually)
- [ ] All PHP files are in the correct directory: `C:\Users\Dell\OneDrive\Desktop\IAP Portal\`
- [ ] Database credentials are correct in all files (localhost, root, root@123)
- [ ] PHP web server is running (Apache/XAMPP, Nginx, or built-in PHP server)
- [ ] Bootstrap and Font Awesome CDN links are accessible
- [ ] All three database tables exist: `students`, `sessions`, `student_sessions`

---

## 🗄️ Step 0: Database Initialization

### Option A: Auto-Create Tables (Recommended)
Tables are automatically created when you first visit the login or registration page.

### Option B: Manual SQL Setup
Run these SQL commands in MySQL:

```sql
CREATE DATABASE IF NOT EXISTS iap_portal;
USE iap_portal;

-- Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    department VARCHAR(100),
    year ENUM('1', '2', '3', '4') NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_password_changed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_roll_number (roll_number)
);

-- Create sessions table
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    year ENUM('1', '2', '3', '4') NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_year (year)
);

-- Create student_sessions table
CREATE TABLE IF NOT EXISTS student_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_id INT NOT NULL,
    registration_status ENUM('registered', 'completed', 'dropped') DEFAULT 'registered',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_session (student_id, session_id)
);

-- Insert sample students (default password: student@IAP)
INSERT IGNORE INTO students (roll_number, full_name, email, department, year, password, is_password_changed) VALUES 
('2021001', 'Test Student', 'test@example.com', 'Computer Science', '1', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/ECm', FALSE),
('2021002', 'Jane Smith', 'jane.smith@example.com', 'Information Technology', '2', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/ECm', FALSE),
('2021003', 'Bob Johnson', 'bob.johnson@example.com', 'Electronics', '3', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/ECm', FALSE),
('2021004', 'Alice Brown', 'alice.brown@example.com', 'Mechanical', '4', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/ECm', FALSE);

-- Insert sample sessions
INSERT IGNORE INTO sessions (id, title, year, description) VALUES 
(1, 'Introduction to Engineering Careers', '1', 'Career awareness and industry expectations'),
(2, 'How to Ace Ideathons', '1', 'Innovation and problem-solving skills'),
(3, 'Resume Building and Career Positioning', '2', 'Professional skills development'),
(4, 'Interview Preparation Fundamentals', '2', 'Interview techniques and tips'),
(5, 'Internship Readiness Program', '3', 'Preparing for internships'),
(6, 'Advanced System Design', '3', 'Technical depth and scalability'),
(7, 'Startup Ecosystem and Entrepreneurship', '4', 'Entrepreneurial pathways'),
(8, 'Leadership and Management Skills', '4', 'Leadership development');

-- Link sample students to sessions
INSERT IGNORE INTO student_sessions (student_id, session_id, registration_status) VALUES 
(1, 1, 'registered'),
(1, 2, 'registered'),
(2, 3, 'registered'),
(2, 4, 'completed'),
(3, 5, 'registered'),
(3, 6, 'registered'),
(4, 7, 'registered'),
(4, 8, 'registered');
```

---

## ✅ Test 1: Student Registration

### Objective
Verify that new students can register with a roll number and email, and receive a default password.

### Steps

1. **Open Registration Page**
   - Go to: `http://localhost/IAP%20Portal/student_register.php`
   - Expected: Registration form displays with fields for Full Name, Roll Number, Email, Department, Year
   - Expected: Info box says "You will be assigned a default password student@IAP"

2. **Fill Registration Form**
   - Full Name: `John Doe`
   - Roll Number: `2024001`
   - Email: `johndoe@example.com`
   - Department: `Computer Science`
   - Year: `1`

3. **Submit Form**
   - Click "Register" button
   - Expected: Success message displays: "Registration successful! Your account has been created with the default password: **student@IAP**"
   - Expected: "Go to Login" button appears

4. **Verify Database**
   - Check MySQL:
   ```sql
   SELECT roll_number, full_name, email, password, is_password_changed FROM students WHERE roll_number = '2024001';
   ```
   - Expected: 
     - `is_password_changed` = `0` (FALSE)
     - `password` is a bcrypt hash (60+ characters)

### Validation Tests

#### Test 1.1: Duplicate Roll Number
- Try registering with same roll number `2024001`
- Expected Error: "A student with this roll number or email already exists"

#### Test 1.2: Duplicate Email
- Try registering with same email `johndoe@example.com` but different roll number
- Expected Error: "A student with this roll number or email already exists"

#### Test 1.3: Invalid Email
- Try registering with invalid email: `notanemail`
- Expected Error: "Please enter a valid email address"

#### Test 1.4: Missing Fields
- Try submitting with empty Roll Number
- Expected Error: "Roll number is required"

#### Test 1.5: Invalid Roll Number Format
- Try roll number: `!@#$%` (special characters)
- Expected Error: "Roll number must be 3-20 alphanumeric characters"

---

## ✅ Test 2: Student Login

### Objective
Verify that students can log in with their roll number and password.

### Test 2.1: Login with Default Password

1. **Open Login Page**
   - Go to: `http://localhost/IAP%20Portal/student_login.php`
   - Expected: Login form with Roll Number and Password fields
   - Expected: Demo credentials box shows example (Roll Number: 2021001, Password: student@IAP)

2. **Enter Credentials**
   - Roll Number: `2021001`
   - Password: `student@IAP`
   - Click "Login"

3. **Expected Behavior**
   - Page redirects to: `reset_password.php?first_login=1`
   - Password reset page appears with message: "You are currently using the default password"
   - Password reset form is displayed
   - Both "Reset Password" and "Continue without changing" buttons are visible

### Test 2.2: Login with Different Student
- Roll Number: `2021002`
- Password: `student@IAP`
- Expected: Same redirect to password reset page

### Test 2.3: Invalid Password
- Roll Number: `2021001`
- Password: `wrongpassword`
- Expected Error: "Invalid password. Please try again."

### Test 2.4: Non-existent Roll Number
- Roll Number: `9999999`
- Password: `student@IAP`
- Expected Error: "Roll number not found. Please check and try again."

### Test 2.5: Empty Fields
- Roll Number: (empty)
- Password: `student@IAP`
- Expected Error: "Roll number is required"

---

## ✅ Test 3: Mandatory Password Reset (First Login)

### Objective
Verify that students are forced to reset their password on first login.

### Test 3.1: Successful Password Reset

1. **From Login, enter credentials of new student**
   - Roll Number: `2024001`
   - Password: `student@IAP`
   - Redirected to: `reset_password.php?first_login=1`

2. **Reset Password Page**
   - Expected: Info box says "You are currently using the default password"
   - Form shows: "New Password" and "Confirm Password" fields
   - Expected: "Reset Password" and "Continue without changing" buttons

3. **Enter New Password**
   - New Password: `SecurePass123!`
   - Confirm Password: `SecurePass123!`
   - Click "Reset Password"

4. **Verification**
   - Expected: Success message: "Password changed successfully! Redirecting to dashboard..."
   - Expected: Automatic redirect to `student_dashboard.php` after 2 seconds
   - Expected: Dashboard displays with student info

5. **Database Verification**
   ```sql
   SELECT roll_number, password, is_password_changed FROM students WHERE roll_number = '2024001';
   ```
   - Expected: 
     - `is_password_changed` = `1` (TRUE)
     - `password` is a different bcrypt hash than before

### Test 3.2: Password Validation

#### Test 3.2.1: Password Too Short
- New Password: `Short1`
- Expected Error: "Password must be at least 8 characters long"

#### Test 3.2.2: Passwords Don't Match
- New Password: `SecurePass123!`
- Confirm Password: `DifferentPass123!`
- Expected Error: "Passwords do not match"

#### Test 3.2.3: Empty Password
- New Password: (empty)
- Expected Error: "New password is required"

### Test 3.3: Skip Password Reset (Optional)
1. **On password reset page, click "Continue without changing"**
   - Expected: Redirects to `student_dashboard.php`
   - Note: This allows skipping password reset on first login

---

## ✅ Test 4: Login After Password Changed

### Objective
Verify that students with changed passwords don't see the reset page again.

1. **Use student who already reset password**
   - Roll Number: `2024001`
   - Password: `SecurePass123!` (new password from Test 3.1)
   - Click "Login"

2. **Expected Behavior**
   - Direct redirect to `student_dashboard.php` (NOT password reset page)
   - Dashboard displays with all student info

---

## ✅ Test 5: Student Dashboard

### Objective
Verify that dashboard displays only the student's registered sessions.

### Test 5.1: Dashboard Access

1. **View as Student 2021001**
   - Login with roll number: `2021001`
   - Follow password reset (if first time)
   - Navigate to: `http://localhost/IAP%20Portal/student_dashboard.php`

2. **Expected Content**
   - Welcome header: "Welcome, [First Name]!"
   - Student info badges: Roll Number, Department, Year, Sessions Registered count
   - Section: "Your Registered Sessions"

3. **Session Display**
   - Sessions grouped by Year (Year 1, Year 2, Year 3, Year 4)
   - Student 2021001 should see:
     - **Year 1:** "Introduction to Engineering Careers", "How to Ace Ideathons"
     - No Year 2, 3, or 4 sessions

### Test 5.2: Session Card Details

For each session card, verify:
- [ ] Session title displays
- [ ] Year badge shows (e.g., "Year 1")
- [ ] Description displays (if present)
- [ ] Registration date displays (e.g., "Jan 21, 2026")
- [ ] Status badge shows: "Registered" / "Completed" / "Dropped"
- [ ] "Take Quiz" button is present and clickable

### Test 5.3: Student Isolation

1. **Login as Student 2021002**
   - Roll Number: `2021002`
   - Password: `student@IAP` (or reset to new password)

2. **View Dashboard**
   - Expected: Only Student 2021002's sessions display
   - Expected: Student 2021002 should see:
     - **Year 2:** "Resume Building and Career Positioning", "Interview Preparation Fundamentals"
   - Expected: Year 1 sessions should NOT appear

3. **Verify in Different Browser/Incognito**
   - Login as different student
   - Verify isolation - each student sees only their own sessions

### Test 5.4: Empty Sessions

1. **Create a student with no registered sessions**
   - Insert via SQL or register new student
   - Don't add any entries in `student_sessions`

2. **Login as that student**
   - Expected: Empty state displays
   - Expected: Message: "You haven't registered for any sessions yet"

### Test 5.5: Logout Functionality

1. **From Dashboard, click Logout**
   - Expected: Session destroyed
   - Expected: Redirect to `student_login.php`
   - Expected: Cannot go back to dashboard by clicking back button (would need to log in again)

---

## ✅ Test 6: Quiz Access Control (Server-Side Validation)

### Objective
Verify that students can only access quizzes for sessions they're registered for.

### Test 6.1: Authorized Quiz Access

1. **Login as Student 2021001**
   - Go to dashboard
   - Click "Take Quiz" on "Introduction to Engineering Careers" (Year 1, Session ID 1)

2. **Expected Behavior**
   - Redirects to: `quiz.php?session_id=1`
   - Quiz page displays with question form
   - Session title appears: "Introduction to Engineering Careers"
   - Quiz questions visible

### Test 6.2: Unauthorized Quiz Access (Key Security Test!)

1. **Login as Student 2021001**
   - Student 2021001 is only registered for sessions 1 and 2
   - Student 2021001 is NOT registered for session 3

2. **Try to Access Session 3 Quiz Directly**
   - Open URL: `http://localhost/IAP%20Portal/quiz.php?session_id=3`
   - Expected Error: "You are not registered for this session or it does not exist."
   - Expected: Quiz form does NOT display
   - Expected: "Take Quiz" button does NOT work

3. **Verify Server-Side Validation**
   - Check browser DevTools → Network
   - Server sends validation query: `SELECT * FROM student_sessions WHERE student_id = ? AND session_id = ?`
   - If no results: Access denied

### Test 6.3: Invalid Session ID

1. **Try accessing non-existent session**
   - URL: `http://localhost/IAP%20Portal/quiz.php?session_id=9999`
   - Expected Error: "You are not registered for this session or it does not exist."

### Test 6.4: Non-Registered Status

1. **Manually change a student's registration status to 'dropped'**
   ```sql
   UPDATE student_sessions SET registration_status = 'dropped' WHERE student_id = 1 AND session_id = 1;
   ```

2. **Try to access that quiz**
   - Expected Error: "You cannot take this quiz. Your registration status is: dropped"

### Test 6.5: Without Session Authentication

1. **Clear all cookies/session**
   - Open incognito window OR
   - Close browser and clear cookies

2. **Try to access quiz directly**
   - URL: `http://localhost/IAP%20Portal/quiz.php?session_id=1`
   - Expected: Redirect to `student_login.php` (from `includes/student_session_check.php`)

---

## ✅ Test 7: Session Protection (Middleware)

### Objective
Verify that all student pages require authentication.

### Test 7.1: Dashboard Without Login

1. **Clear all cookies/sessions**
2. **Try to access dashboard directly**
   - URL: `http://localhost/IAP%20Portal/student_dashboard.php`
   - Expected: Redirect to `student_login.php`

### Test 7.2: Quiz Without Login

1. **Clear sessions**
2. **Try to access quiz directly**
   - URL: `http://localhost/IAP%20Portal/quiz.php?session_id=1`
   - Expected: Redirect to `student_login.php`

### Test 7.3: Session Validation

1. **Login as student**
2. **Delete student record from database**
   ```sql
   DELETE FROM students WHERE id = (SELECT student_id FROM sessions LIMIT 1);
   ```
3. **Refresh dashboard page**
   - Expected: Redirect to `student_login.php`
   - Expected: Message: "Session expired"

---

## ✅ Test 8: Data Integrity

### Test 8.1: Verify Prepared Statements

1. **Try SQL Injection in Roll Number**
   - Roll Number: `2021001' OR '1'='1`
   - Expected: "Roll number not found" (safe due to prepared statement)

2. **Try SQL Injection in Password**
   - Password: `anything' OR '1'='1`
   - Expected: "Invalid password" (safe due to prepared statement)

### Test 8.2: Check Password Hashing

1. **View any student's password in database**
   ```sql
   SELECT password FROM students LIMIT 1;
   ```
   - Expected: Long bcrypt hash (starts with `$2y$10$`)
   - Expected: NOT plain text
   - Expected: NOT visible in HTML source

---

## ✅ Test 9: UI/UX Verification

### Test 9.1: Bootstrap Responsiveness

1. **Test on different screen sizes**
   - [ ] Desktop (1920x1080)
   - [ ] Tablet (768x1024)
   - [ ] Mobile (375x667)
   - Expected: All forms and buttons remain functional and readable

### Test 9.2: Alert Messages

1. **Verify all alerts display correctly**
   - [ ] Success alerts (green) for registration, password reset
   - [ ] Error alerts (red) for invalid input
   - [ ] Info alerts (blue) for first-login message
   - [ ] All have proper icons and readable text

### Test 9.3: Form Validation Feedback

1. **Try empty form submission**
   - Expected: Red border around empty fields
   - Expected: Error message below field

---

## 📊 Test Results Table

Use this table to track all tests:

| Test | Description | Status | Notes |
|------|-------------|--------|-------|
| 1.1 | New student registration | ☐ Pass ☐ Fail | |
| 1.2 | Duplicate roll number error | ☐ Pass ☐ Fail | |
| 1.3 | Invalid email error | ☐ Pass ☐ Fail | |
| 2.1 | Login with default password | ☐ Pass ☐ Fail | |
| 2.2 | Invalid password error | ☐ Pass ☐ Fail | |
| 3.1 | Password reset with validation | ☐ Pass ☐ Fail | |
| 3.2 | is_password_changed updated to 1 | ☐ Pass ☐ Fail | |
| 4.1 | Login after password changed | ☐ Pass ☐ Fail | |
| 5.1 | Dashboard displays correct sessions | ☐ Pass ☐ Fail | |
| 5.2 | Session isolation between students | ☐ Pass ☐ Fail | |
| 6.1 | Authorized quiz access | ☐ Pass ☐ Fail | |
| 6.2 | Unauthorized quiz access blocked | ☐ Pass ☐ Fail | |
| 7.1 | Dashboard requires login | ☐ Pass ☐ Fail | |
| 7.2 | Quiz requires login | ☐ Pass ☐ Fail | |
| 8.1 | SQL injection prevented | ☐ Pass ☐ Fail | |
| 9.1 | Responsive design | ☐ Pass ☐ Fail | |

---

## 🔍 Debugging Tips

### Enable Error Display
Add to top of any PHP file:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Check Database Connection
```php
echo "Database: " . (isset($conn) && !$conn->connect_error ? "✓ Connected" : "✗ Failed");
echo "Student ID: " . ($_SESSION['student_id'] ?? "✗ Not set");
echo "Roll Number: " . ($_SESSION['roll_number'] ?? "✗ Not set");
```

### Verify Password Hash
```php
$test_hash = password_hash("student@IAP", PASSWORD_BCRYPT);
echo password_verify("student@IAP", $test_hash) ? "✓ Hash OK" : "✗ Hash Failed";
```

### Check Session Data
```php
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
```

### Database Query Testing
```sql
-- Check all students
SELECT * FROM students;

-- Check all sessions
SELECT * FROM sessions;

-- Check student registrations
SELECT s.roll_number, sess.title, ss.registration_status 
FROM student_sessions ss
JOIN students s ON ss.student_id = s.id
JOIN sessions sess ON ss.session_id = sess.id;
```

---

## ✅ Final Verification Checklist

- [ ] Database created and tables exist
- [ ] Sample data inserted correctly
- [ ] Registration system works and creates students with default password
- [ ] is_password_changed set to 0 on registration
- [ ] Login accepts roll number and default password
- [ ] First login redirects to password reset (when is_password_changed = 0)
- [ ] Password reset updates hash and sets is_password_changed = 1
- [ ] Dashboard shows only student's registered sessions
- [ ] Sessions grouped by year correctly
- [ ] "Take Quiz" button links to quiz.php?session_id={id}
- [ ] Quiz access validated server-side (student can only access registered sessions)
- [ ] All student pages protected with session check
- [ ] Logout clears session and redirects to login
- [ ] All prepared statements used correctly
- [ ] Passwords hashed with bcrypt (not plain text)
- [ ] Responsive design works on mobile/tablet/desktop

---

## 🎉 Success Criteria

System is fully functional when:
1. ✅ New students can register and receive default password
2. ✅ Students must reset password on first login
3. ✅ Subsequent logins skip password reset
4. ✅ Each student sees only their own sessions
5. ✅ Quiz access is strictly server-side validated
6. ✅ Unauthorized session access returns error
7. ✅ All pages are properly protected
8. ✅ All database queries use prepared statements
9. ✅ UI is responsive and user-friendly
10. ✅ No security vulnerabilities (SQL injection, etc.)

---

**Last Updated:** January 21, 2026
**System Version:** 1.0 - Complete & Production Ready
