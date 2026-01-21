# IAP Portal - Student System Quick Start Guide

## 🚀 Installation & Testing (5 Minutes)

### Step 1: Import Database Schema
```bash
# Open MySQL command line or phpMyAdmin
mysql -u root -p < student_migration.sql

# Or in phpMyAdmin: Import → student_migration.sql
```

### Step 2: Test Student Login
1. Open your browser: `http://localhost/IAP%20Portal/student_login.php`
2. Use demo credentials:
   - **Roll Number:** `2021001`
   - **Password:** `student@IAP`
3. Click **Login**

### Step 3: First Login Experience
- You'll be prompted to change your password (or skip)
- Enter new password (min 8 characters)
- Click **Save Password** or **Continue**

### Step 4: View Dashboard
- You'll see your personalized dashboard
- View all registered sessions organized by year
- Click **Take Quiz** to access a session's quiz

---

## 📁 File Overview

| File | Purpose |
|------|---------|
| `student_login.php` | Student authentication page |
| `reset_password.php` | Password reset with strength indicator |
| `student_dashboard.php` | Personalized dashboard with sessions |
| `quiz.php` | Quiz page with access control |
| `includes/student_session_check.php` | Session protection for all student pages |
| `logout.php` | Updated to handle student/admin logouts |
| `student_migration.sql` | Database schema & sample data |
| `STUDENT_SYSTEM_DOCUMENTATION.md` | Full technical documentation |

---

## 🔐 Security Summary

✅ **Password Security**
- Bcrypt hashing (PASSWORD_BCRYPT)
- Minimum 8 characters
- Strength indicator on reset

✅ **Database Security**
- All queries use MySQLi prepared statements
- SQL injection prevention
- Parameterized queries

✅ **Session Security**
- Session validation on every page
- Database verification of student
- Automatic timeout handling

✅ **Access Control**
- Server-side validation for quizzes
- Students can only access registered sessions
- Cannot bypass via URL manipulation

---

## 🎯 Demo User Accounts

| Roll No | Name | Department | Year | Password |
|---------|------|-----------|------|----------|
| 2021001 | Test Student | Computer Science | 1 | student@IAP |
| 2021002 | Jane Smith | IT | 2 | student@IAP |
| 2021003 | Bob Johnson | Electronics | 3 | student@IAP |
| 2021004 | Alice Brown | Mechanical | 4 | student@IAP |

---

## 🔗 Navigation

**From Home Page (index.php):**
```
Home → [Student Login link] → student_login.php
```

**Student User Journey:**
```
student_login.php 
  → reset_password.php (first login only)
  → student_dashboard.php (main dashboard)
  → quiz.php (per session)
  → logout.php
```

---

## ⚙️ Database Configuration

If your MySQL credentials differ:

**Update in these files:**
1. `student_login.php` (line 30)
2. `includes/student_session_check.php` (line 30)
3. `reset_password.php` (line 16)

```php
$servername = "localhost";
$db_username = "root";
$db_password = "root@123";
```

---

## 🧪 Testing Checklist

- [ ] Login works with demo credentials
- [ ] Password reset appears on first login
- [ ] Can skip password reset
- [ ] Dashboard displays sessions by year
- [ ] Quiz page loads for registered sessions
- [ ] Quiz blocked for unregistered sessions
- [ ] Can complete and submit quiz
- [ ] Logout works correctly
- [ ] Session expires properly

---

## 📊 Session Data Structure

When student logs in, these session variables are set:

```php
$_SESSION['student_id']          // 1, 2, 3, etc.
$_SESSION['roll_number']         // '2021001'
$_SESSION['full_name']           // 'Test Student'
$_SESSION['email']               // 'test@example.com'
$_SESSION['department']          // 'Computer Science'
$_SESSION['year']                // '1'
$_SESSION['is_password_changed'] // true/false
```

---

## 🛠️ Customization Examples

### Add More Test Students
In `student_migration.sql`, add to INSERT statement:
```sql
INSERT IGNORE INTO students (roll_number, full_name, email, department, year, password, is_password_changed) 
VALUES 
('2021005', 'New Student', 'new@example.com', 'CSE', '1', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/ECm', FALSE);
```

### Change Default Password
Edit `student_login.php` line 78:
```php
$default_password_hash = password_hash("newpassword123", PASSWORD_BCRYPT);
```

### Add Questions to Quiz
Create `quiz_questions` table and populate questions:
```sql
INSERT INTO quiz_questions (session_id, question, question_type, options) 
VALUES (1, 'Sample Question?', 'multiple_choice', '["A","B","C","D"]');
```

---

## 🐛 Common Issues

**"Database connection failed"**
- Check MySQL is running
- Verify credentials match your setup
- Ensure iap_portal database exists

**"You are not registered for this session"**
- Verify student_sessions record exists
- Check registration_status is 'registered' or 'completed'

**"Session expired"**
- Check student record exists in database
- Verify roll_number matches exactly

---

## 📞 Support

See `STUDENT_SYSTEM_DOCUMENTATION.md` for:
- Complete technical documentation
- Troubleshooting guide
- Security features detail
- Database schema details
- Customization guide

---

## 🎓 Features at a Glance

✨ **Authentication**
- Roll number + password login
- Bcrypt password hashing
- Demo credentials for testing

✨ **First Login**
- Password reset prompt
- Can skip if needed
- Password strength indicator

✨ **Dashboard**
- Personalized welcome
- Year-wise session organization
- Registration status display
- Quiz access buttons

✨ **Quiz System**
- Multiple question types
- Server-side access control
- Cannot access unregistered quizzes
- Full validation

✨ **Security**
- Prepared statements
- SQL injection prevention
- Session validation
- Input sanitization

---

**Ready to use! Start testing with:** `http://localhost/IAP%20Portal/student_login.php`

Roll Number: `2021001` | Password: `student@IAP`
