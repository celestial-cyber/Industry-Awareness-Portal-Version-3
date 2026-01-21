# IAP Portal Student System - Quick Reference Card

## 🎯 System at a Glance

**Complete Student Registration, Login, and Dashboard system with server-side quiz access control.**

| Aspect | Details |
|--------|---------|
| **Status** | ✅ Production Ready |
| **Students** | Unlimited (multi-tenant) |
| **Authentication** | Roll number + Password (bcrypt) |
| **First Login** | Mandatory password reset |
| **Dashboard** | Personalized per student |
| **Security** | MySQLi prepared statements, session validation |
| **UI** | Bootstrap 5 responsive |

---

## 📁 Core Files

| File | Purpose | Access |
|------|---------|--------|
| `student_register.php` | Registration form | Public |
| `student_login.php` | Login form | Public |
| `reset_password.php` | Password reset | Logged in students |
| `student_dashboard.php` | Main dashboard | Protected (students only) |
| `quiz.php` | Quiz interface | Protected + authorized |
| `includes/student_session_check.php` | Session middleware | Include in protected pages |

---

## 🚀 Quick Start

### 1. Register
```
URL: /student_register.php
Fields: Full Name, Roll Number, Email, Department, Year
Result: Account created with default password "student@IAP"
```

### 2. First Login
```
URL: /student_login.php
Credentials: Roll Number + "student@IAP"
Result: Redirects to password reset (mandatory)
```

### 3. Reset Password
```
URL: /reset_password.php?first_login=1
Action: Enter new password (min 8 chars)
Result: Updates is_password_changed=1, redirects to dashboard
```

### 4. Subsequent Login
```
URL: /student_login.php
Credentials: Roll Number + new password
Result: Directly to dashboard (no password reset)
```

### 5. Dashboard
```
URL: /student_dashboard.php
Shows: Only student's registered sessions, organized by year
Action: Click "Take Quiz" to access quiz
```

---

## 🔒 Security Checklist

- ✅ All passwords bcrypt hashed
- ✅ All queries use prepared statements
- ✅ All protected pages require session check
- ✅ Quiz access validated server-side
- ✅ HTML output escaped with htmlspecialchars()
- ✅ Input validated before database
- ✅ Students isolated (can't see other's data)
- ✅ Separate student and admin systems

---

## 💾 Database Schema

### students
```
id | roll_number | password | is_password_changed | email | department | year
```

### sessions
```
id | title | year | description
```

### student_sessions
```
id | student_id | session_id | registration_status | registered_at
```

---

## 🔧 Key Code Snippets

### Protect a Page
```php
<?php
require_once 'includes/student_session_check.php';
// Page is now protected - student must be logged in
?>
```

### Get Current Student ID
```php
$student_id = $_SESSION['student_id'];  // Current logged-in student
```

### Get Student's Sessions
```php
$sql = "SELECT * FROM student_sessions ss
        JOIN sessions s ON ss.session_id = s.id
        WHERE ss.student_id = ?";
// This ensures student only sees THEIR sessions
```

### Validate Quiz Access
```php
$sql = "SELECT * FROM student_sessions 
        WHERE student_id = ? AND session_id = ?";
// Returns 0 rows = NOT authorized
// Returns 1+ rows = AUTHORIZED
```

### Hash Password (Registration)
```php
$hash = password_hash("student@IAP", PASSWORD_BCRYPT);
```

### Verify Password (Login)
```php
if (password_verify($entered_password, $stored_hash)) {
    // Login successful
}
```

---

## 🧪 Test Credentials

```
Roll #: 2021001  | Password: student@IAP | Year: 1
Roll #: 2021002  | Password: student@IAP | Year: 2
Roll #: 2021003  | Password: student@IAP | Year: 3
Roll #: 2021004  | Password: student@IAP | Year: 4
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| "Database connection failed" | Check MySQL running, credentials in files |
| "Roll number not found" | Verify student registered in DB |
| "Invalid password" | Check password typed correctly (case-sensitive) |
| "Not registered for this session" | Verify student_sessions table has entry |
| Can't access dashboard | Check session started, login complete |
| Password reset doesn't work | Check is_password_changed field updated |

---

## 📊 User Flow (ASCII Diagram)

```
Registration
    ↓
Login (default password)
    ↓
Password Reset (mandatory on first login)
    ↓
Dashboard (sessions for this year only)
    ↓
Quiz (server-side validation)
    ↓
Logout
```

---

## ✅ Validation Rules

| Field | Rules |
|-------|-------|
| Roll Number | 3-20 alphanumeric, must be unique |
| Email | Valid email format, must be unique |
| Password | Min 8 characters, case-sensitive |
| Year | Must be 1, 2, 3, or 4 |
| Department | Cannot be empty |
| Full Name | 2-255 characters |

---

## 🛡️ SQL Injection Prevention

**All queries use prepared statements:**

```php
// ✅ SAFE - Prepared statement
$stmt = $conn->prepare("SELECT * FROM students WHERE roll_number = ?");
$stmt->bind_param("s", $input);
$stmt->execute();

// ❌ UNSAFE - String concatenation (NOT USED)
$sql = "SELECT * FROM students WHERE roll_number = '" . $input . "'";
```

---

## 🎨 UI Components

- **Bootstrap 5** - Responsive grid, navbar, cards, forms, alerts
- **Font Awesome** - Icons for buttons, badges, navigation
- **Custom CSS** - Gradient headers, card styling, animations
- **Mobile Responsive** - Works on desktop, tablet, mobile

---

## 📈 Scalability

Current setup supports:
- ✅ **Unlimited students** - Each has own dashboard
- ✅ **Multiple sessions** - Per-year categorization
- ✅ **Flexible registration** - Any student can register for any session
- ✅ **Concurrent access** - Multiple students logging in simultaneously
- ✅ **Data isolation** - Students can't see each other's data

---

## 🔄 Session Flow

```php
// Session starts
session_start();

// After successful login
$_SESSION['student_id'] = 1;
$_SESSION['roll_number'] = '2021001';
$_SESSION['full_name'] = 'John Doe';
$_SESSION['email'] = 'john@example.com';
$_SESSION['is_password_changed'] = true;

// Protected pages check
if (!isset($_SESSION['student_id'])) {
    redirect to login;
}

// Logout
session_destroy();
redirect to login;
```

---

## 📝 Files Included

### Documentation
- `STUDENT_SYSTEM_IMPLEMENTATION.md` - Complete implementation details
- `STUDENT_SYSTEM_COMPLETE_SETUP.md` - Setup guide
- `STUDENT_SYSTEM_TESTING_GUIDE.md` - Testing procedures
- `STUDENT_SYSTEM_QUICK_REFERENCE.md` - This file

### PHP Files
- `student_register.php` - Registration
- `student_login.php` - Login
- `reset_password.php` - Password reset
- `student_dashboard.php` - Dashboard
- `quiz.php` - Quiz with validation
- `includes/student_session_check.php` - Session protection

### SQL
- `COMPLETE_SETUP_SQL.sql` - Database schema and sample data

---

## 🎯 Next Steps (Optional)

1. **Email Verification** - Verify email on registration
2. **Forgot Password** - Recover forgotten passwords
3. **Admin Panel** - Manage students and sessions
4. **Quiz Grading** - Store and grade quiz responses
5. **Progress Tracking** - Track student progress
6. **Notifications** - Email alerts for registrations
7. **API** - REST API for mobile apps
8. **.env Configuration** - Move credentials to environment file

---

## 💡 Tips & Best Practices

1. **Always include session check** on protected pages
2. **Use prepared statements** for all SQL queries
3. **Hash passwords** on registration and reset only
4. **Validate input** before database operations
5. **Escape output** with htmlspecialchars()
6. **Test cross-student access** to verify isolation
7. **Check is_password_changed** on every login
8. **Use $conn from session_check.php** in protected pages

---

## 📞 Support Resources

- **PHP Docs:** https://www.php.net/
- **MySQLi:** https://www.php.net/manual/en/book.mysqli.php
- **Bootstrap:** https://getbootstrap.com/
- **Password Hashing:** https://www.php.net/manual/en/function.password-hash.php

---

**Version:** 1.0  
**Last Updated:** January 21, 2026  
**Status:** ✅ Complete & Production Ready
