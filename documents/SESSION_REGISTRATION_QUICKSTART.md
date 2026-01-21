# Session Registration Feature - Quick Start Guide

## How It Works

### For Students

#### Step 1: Find a Session
Visit the IAP Portal home page and browse sessions by year. You'll see all available sessions listed under Year 1-4.

#### Step 2: Register for Session
- **Option A - Right-Click**: Right-click on any session name → Select "Register for this Session"
- **Option B - Click Menu Icon**: Click the three-dot (⋮) icon next to session → Select "Register for this Session"

#### Step 3: Login or Register
- **If you have an account**: Enter your email and password → You'll be automatically registered for the session → Proceed to quiz
- **If you're new**: Click "Register here" → Fill in your details:
  - Full Name
  - Roll Number (unique identifier, e.g., 2021001)
  - Email
  - Department
  - Year (1-4)
  - Click Submit

#### Step 4: Set Password (First-Time Only)
After registration, you'll be prompted to set a new password:
- Enter your new password (min 8 characters)
- Confirm password
- Click "Change Password" (or skip if using default temporarily)

#### Step 5: Take Quiz
You'll be automatically redirected to the quiz page for your registered session.
- Answer the questions
- Submit your responses

---

### For Admins

#### View Registered Students

1. **Navigate to Admin Dashboard**: `admin_dashboard.php`
2. **Login** with admin credentials:
   - Username: `admin@example.com`
   - Password: `admin@123` (or your configured password)

3. **Click "View Registered Students"** in the sidebar

4. **Review Student Information**:
   - Student ID and Name
   - Email and Roll Number
   - Department and Year
   - Number of sessions registered for
   - List of sessions they registered for
   - Quiz count (dummy data, for now)
   - Module count (dummy data, for now)

#### Manage Sessions
- **Create Session**: Admin Panel → Create Session → Enter topic and year
- **View Registrations**: View Session Registrations → Shows manual registrations
- **View Students**: View Registered Students → Shows portal registrations

---

## Database Flow

### What Happens During Registration

1. **Session Selected**: 
   - User selects session from homepage
   - System verifies session exists in `sessions` table

2. **New Student Account Created** (if needed):
   - Roll number validated for uniqueness
   - Email validated for uniqueness
   - Password hashed using bcrypt
   - Account marked with `is_password_changed = 0`

3. **Session Registration**:
   - Entry created in `student_sessions` table
   - `student_id` linked to `session_id`
   - Status set to 'registered'
   - Duplicate prevention: UNIQUE constraint on (student_id, session_id)

4. **User Redirected**:
   - If new student: Password reset page → Quiz
   - If existing student: Directly to quiz

---

## Demo Account

### For Testing Student Flow

**Email**: `test@example.com`  
**Password**: `student@IAP` (default, must change on login)  
**Roll Number**: `2021001`

### Steps to Test
1. Go to home page
2. Right-click any session → Register
3. Use demo email
4. Will be prompted to reset password (skip or set new)
5. Automatically registered for selected session
6. Redirected to quiz page

---

## Key Database Tables

### students
```
id | roll_number | full_name | email | department | year | password | is_password_changed
```

### sessions
```
id | topic | year | created_at
```

### student_sessions
```
id | student_id | session_id | registration_status | registered_at
```

---

## Troubleshooting

### Issue: Context Menu Not Appearing
- **Solution**: Ensure JavaScript is enabled
- Check browser console for errors
- Try right-clicking again on session text

### Issue: "Email already exists"
- **Solution**: Use a different email or login if you already have an account

### Issue: Quiz page doesn't load after registration
- **Solution**: Refresh the page, or navigate to Dashboard and try again
- Check that `student_sessions` table has your record

### Issue: Password reset page redirects to dashboard
- **Solution**: This is normal if you already set a password
- You can change password from Account Settings (if available)

---

## API Endpoints

### For Developers

#### Register for Session
- **Endpoint**: `session_registration.php`
- **Method**: POST
- **Parameters**: `session_id` (int)
- **Response**: JSON with redirect_url

Example:
```javascript
fetch('session_registration.php', {
    method: 'POST',
    body: 'session_id=1'
})
.then(r => r.json())
.then(d => window.location.href = d.redirect_url)
```

---

## Files to Know About

| File | Purpose |
|------|---------|
| `index.php` | Home page with sessions, context menu |
| `session_registration.php` | Session selection handler |
| `student_login.php` | Student login with session support |
| `student_register.php` | Student registration with session support |
| `student_dashboard.php` | Student's session dashboard |
| `quiz.php` | Quiz for registered session |
| `reset_password.php` | Password reset/change |
| `Admin/admin_dashboard.php` | Admin panel with student tracking |

---

## Implementation Status

✅ Public session registration (context menu)  
✅ Student login with session linking  
✅ Student registration with auto-session link  
✅ Password reset with session flow  
✅ Admin student tracking view  
✅ Database schema for session registrations  
✅ Security (prepared statements, validation)  
✅ Responsive design  

⏳ Real quiz tracking (dummy data in place)  
⏳ Real module completion tracking (dummy data in place)  

---

## Next Steps (Future Development)

1. **Replace dummy values** with real quiz/module tracking
2. **Add email notifications** for registrations
3. **Create student progress reports**
4. **Add session capacity limits**
5. **Implement waitlist system**
6. **Add session unregistration option**
7. **Create quiz result tracking**

---

## Support

For issues or questions:
1. Check the `SESSION_REGISTRATION_IMPLEMENTATION.md` file for detailed documentation
2. Review browser console for JavaScript errors
3. Check database logs for SQL errors
4. Ensure all tables are created (run COMPLETE_SETUP_SQL.sql if needed)

---

**Last Updated**: January 21, 2026  
**Feature Status**: ✅ Production Ready  
**Version**: 1.0
