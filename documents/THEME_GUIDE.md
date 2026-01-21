# 🎨 IAP Portal - Unified Theme Guide

## Overview

The entire IAP Portal now uses a **consistent, unified theme** across all pages. All styling is centralized in a single `theme.css` file that defines colors, layouts, components, and responsive behavior.

---

## 🎯 Color Scheme

### Primary Colors
- **Primary Start:** `#667eea` (Blue-Purple)
- **Primary End:** `#764ba2` (Deep Purple)
- **Gradient:** `linear-gradient(135deg, #667eea 0%, #764ba2 100%)`

### Neutral Colors
- **Text Dark:** `#1f2937` (Dark Gray)
- **Text Light:** `#6b7280` (Light Gray)
- **Background Light:** `#f8f9fa` (Very Light Gray)
- **Background White:** `#ffffff` (White)
- **Border Color:** `#e5e7eb` (Light Border)

### Status Colors
- **Success:** `#16a34a` (Green)
- **Danger:** `#dc2626` (Red)
- **Warning:** `#f59e0b` (Amber)
- **Info:** `#3b82f6` (Blue)

---

## 📦 CSS Variables

All colors are defined as CSS variables in `:root` for easy customization:

```css
:root {
    /* Primary Colors */
    --primary-start: #667eea;
    --primary-end: #764ba2;
    
    /* Neutral Colors */
    --text-dark: #1f2937;
    --bg-light: #f8f9fa;
    --border-color: #e5e7eb;
    
    /* Status Colors */
    --success: #16a34a;
    --danger: #dc2626;
    --warning: #f59e0b;
    --info: #3b82f6;
    
    /* Shadows */
    --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 10px 40px rgba(0, 0, 0, 0.2);
}
```

---

## 🧩 Component Library

### Navigation Bar
All navigation bars use the gradient primary color with white text.

```html
<nav class="navbar-custom">
    <a class="navbar-brand" href="index.php">
        <i class="fas fa-graduation-cap"></i> IAP Portal
    </a>
    <a class="nav-link" href="student_dashboard.php">Dashboard</a>
    <span class="logout-btn">Logout</span>
</nav>
```

**Styling:**
- Background: Purple gradient
- Text: White
- Hover effects: Smooth transitions
- Responsive: Collapses on mobile

---

### Login Card
All login pages use the same card design.

```html
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-sign-in-alt"></i>
            <h1>Student Login</h1>
        </div>
        <div class="login-body">
            <!-- Form content -->
        </div>
    </div>
</div>
```

**Styling:**
- Max width: 450px
- Centered on page
- Gradient header
- White body
- Box shadow

---

### Forms
All form elements use consistent styling.

```html
<div class="form-group">
    <label class="form-label">Field Name</label>
    <div class="input-icon">
        <i class="fas fa-icon"></i>
        <input class="form-control" type="text" placeholder="...">
    </div>
</div>
```

**Styling:**
- Light gray borders
- Purple focus state
- Icon in input field
- Consistent padding
- Smooth transitions

---

### Buttons

#### Primary Button
```html
<button class="btn-primary-custom">
    <i class="fas fa-check"></i> Save
</button>
```
- Gradient background
- White text
- Hover lift effect
- Box shadow on hover

#### Secondary Button
```html
<button class="btn-secondary-custom">
    <i class="fas fa-times"></i> Cancel
</button>
```
- Light gray background
- Dark text
- Border outline
- Purple on hover

#### Danger Button
```html
<button class="btn-danger-custom">
    <i class="fas fa-trash"></i> Delete
</button>
```
- Red background
- White text
- Hover darkens

---

### Page Headers
Used at the top of main pages.

```html
<div class="page-header">
    <h1>
        <i class="fas fa-chart-line"></i>
        Dashboard
    </h1>
    <p>Welcome back!</p>
</div>
```

**Styling:**
- Gradient background
- White text
- Large heading
- Icon included
- Rounded corners
- Box shadow

---

### Cards
Used to group related content.

```html
<div class="card-custom">
    <div class="card-header-custom">Session Title</div>
    <div class="card-body-custom">
        <!-- Content -->
    </div>
</div>
```

**Styling:**
- White background
- Rounded corners
- Subtle shadow
- Lift on hover
- Gradient header option

---

### Alerts
Used for messages and feedback.

```html
<!-- Success Alert -->
<div class="alert alert-success-custom">
    <i class="fas fa-check-circle"></i> Success message
</div>

<!-- Danger Alert -->
<div class="alert alert-danger-custom">
    <i class="fas fa-times-circle"></i> Error message
</div>

<!-- Warning Alert -->
<div class="alert alert-warning-custom">
    <i class="fas fa-exclamation-circle"></i> Warning message
</div>
```

**Colors:**
- Success: Green tones
- Danger: Red tones
- Warning: Amber tones
- Info: Blue tones

---

### Badges
Used for status indicators.

```html
<span class="badge-custom badge-registered">Registered</span>
<span class="badge-custom badge-completed">Completed</span>
<span class="badge-custom badge-pending">Pending</span>
<span class="badge-custom badge-dropped">Dropped</span>
```

**Status Colors:**
- Registered: Light green
- Completed: Darker green
- Pending: Amber
- Dropped: Red

---

### Password Strength Indicator
Shows password strength during reset.

```html
<div class="strength-bar">
    <div class="strength-bar-fill weak"></div>
</div>
<div class="password-strength strength-weak">Weak</div>
```

**States:**
- Weak: Red (33%)
- Medium: Amber (66%)
- Strong: Green (100%)

---

### Info Box
Used for important information.

```html
<div class="info-box">
    <strong>Note:</strong> Default password is student@IAP
</div>
```

**Styling:**
- Amber background
- Left border accent
- Dark text
- Compact padding

---

## 🎨 CSS Classes Reference

### Gradient Classes
- `.gradient-primary` - Full gradient background
- `.gradient-primary-light` - Light gradient background

### Text Classes
- `.text-primary-custom` - Primary color text
- `.text-center` - Center aligned
- `.text-muted` - Light gray text
- `.text-danger` - Red text
- `.text-success` - Green text
- `.text-warning` - Amber text

### Spacing Classes
- `.mt-1` through `.mt-5` - Margin top
- `.mb-1` through `.mb-5` - Margin bottom
- `.p-2` through `.p-5` - Padding
- `.gap-2` through `.gap-4` - Gap between flex items

### Component Classes
- `.card-custom` - Card container
- `.card-header-custom` - Card header
- `.card-body-custom` - Card body
- `.btn-primary-custom` - Primary button
- `.btn-secondary-custom` - Secondary button
- `.btn-danger-custom` - Danger button
- `.btn-success-custom` - Success button
- `.btn-block` - Full width button

---

## 📱 Responsive Design

The theme includes responsive breakpoints:

### Mobile (< 576px)
- Reduced font sizes
- Single-column layouts
- Smaller padding
- Touch-friendly button sizes

### Tablet (576px - 768px)
- Medium font sizes
- Flexible layouts
- Standard padding
- Stack grids single column

### Desktop (> 768px)
- Full font sizes
- Multi-column layouts
- Larger padding
- Full grid layouts

---

## 🔄 How to Apply Theme

### To Include Theme in New Pages

Add this to the `<head>` section:

```html
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Theme CSS -->
<link rel="stylesheet" href="theme.css">
```

### To Use Theme Classes

```html
<!-- Page Header -->
<div class="page-header">
    <h1><i class="fas fa-dashboard"></i> Page Title</h1>
</div>

<!-- Card -->
<div class="card-custom">
    <div class="card-header-custom">Card Title</div>
    <div class="card-body-custom">Content here</div>
</div>

<!-- Button -->
<button class="btn-primary-custom">
    <i class="fas fa-save"></i> Save
</button>

<!-- Alert -->
<div class="alert alert-success-custom">Success!</div>
```

---

## 🎨 Customization Guide

### Change Primary Color

To change the primary color globally, edit `theme.css`:

```css
:root {
    --primary-start: #NEW_COLOR_START;
    --primary-end: #NEW_COLOR_END;
}
```

This automatically updates:
- All gradients
- Navigation bars
- Buttons
- Links
- Focus states
- Hover effects

### Change Font Family

```css
:root {
    --font-family: 'Your Font', sans-serif;
}
```

### Change Border Radius

```css
:root {
    --radius-sm: 4px;
    --radius-md: 6px;
    --radius-lg: 10px;
}
```

### Change Shadows

```css
:root {
    --shadow-sm: 0 1px 5px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 5px 20px rgba(0, 0, 0, 0.2);
}
```

---

## ✅ Theme Implementation Checklist

### Current Pages with Theme Applied
- ✅ `student_login.php` - Theme + custom styles
- ✅ `reset_password.php` - Theme + custom styles
- ✅ `student_dashboard.php` - Theme + custom styles
- ✅ `quiz.php` - Theme + custom styles
- ✅ `logout.php` - Redirect handler

### Future Pages Should Include
- Add `theme.css` link in `<head>`
- Use `.btn-primary-custom` for buttons
- Use `.card-custom` for cards
- Use `.page-header` for titles
- Use `.alert-success-custom` for messages
- Use `.form-control` for inputs
- Use `.form-label` for labels

---

## 🎯 Design System Benefits

### Consistency
- Same colors across all pages
- Same component styles
- Same spacing and layout
- Same typography

### Maintainability
- Single source of truth (theme.css)
- Easy to update entire theme
- CSS variables for quick changes
- Documented components

### Scalability
- Can add new components
- Easy to extend styles
- Mobile responsive
- Accessible colors

### Developer Experience
- Clear class naming
- Documented components
- Easy to apply styles
- Reusable patterns

---

## 📊 Color Palette Summary

```
Primary Gradient:      #667eea → #764ba2
Success:               #16a34a
Danger:                #dc2626
Warning:               #f59e0b
Info:                  #3b82f6
Text Dark:             #1f2937
Text Light:            #6b7280
Background Light:      #f8f9fa
Background White:      #ffffff
Border:                #e5e7eb
```

---

## 🚀 Usage Examples

### Complete Login Page
```html
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="theme.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-lock"></i>
                <h1>Login</h1>
            </div>
            <div class="login-body">
                <form>
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input class="form-control" type="text">
                    </div>
                    <button class="btn-primary-custom btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
```

### Complete Dashboard Section
```html
<nav class="navbar-custom">
    <span class="navbar-brand">IAP Portal</span>
    <span class="logout-btn">Logout</span>
</nav>

<div class="page-header">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
</div>

<div class="card-custom">
    <div class="card-header-custom">Summary</div>
    <div class="card-body-custom">
        <p>Dashboard content here</p>
    </div>
</div>
```

---

## 📝 Notes

- All pages automatically inherit the theme when `theme.css` is included
- Custom inline styles in individual pages layer on top of theme styles
- CSS specificity is managed to allow both global and local overrides
- Theme is fully responsive and mobile-first approach
- Print styles are included for better PDF/print output

---

**Theme Version:** 1.0  
**Last Updated:** January 2026  
**Status:** Complete and Ready to Use

---

*For questions or modifications, refer to theme.css or contact your development team.*
