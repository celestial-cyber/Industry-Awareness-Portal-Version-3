# 🎨 IAP Portal - Theme Visual Reference

## Color Palette

### Primary Gradient
```
┌─────────────────────────────────────────┐
│ Primary: #667eea → #764ba2              │
│ Linear Gradient (135deg)                │
│ Used for: Headers, Buttons, Navigation  │
└─────────────────────────────────────────┘
```

### Status Colors
```
✅ Success: #16a34a (Green)     - Used for positive actions
❌ Danger:  #dc2626 (Red)       - Used for destructive actions
⚠️  Warning: #f59e0b (Amber)    - Used for warnings
ℹ️  Info:    #3b82f6 (Blue)     - Used for informational messages
```

### Neutral Colors
```
📝 Text Dark:       #1f2937 (Dark Gray)    - Main text
💬 Text Light:      #6b7280 (Light Gray)   - Secondary text
🔲 Background Light: #f8f9fa (Very Light)  - Page background
⬜ Background White: #ffffff (White)        - Card backgrounds
📏 Border:          #e5e7eb (Light Border) - Dividers
```

---

## Component Styles

### Login Page
```
┌─────────────────────────────────┐
│  Gradient Header (Purple)       │
│  ╭─────────────────────────────╮│
│  │ IAP Portal Login            ││
│  │ Icon | Heading              ││
│  ╰─────────────────────────────╯│
│ ┌─────────────────────────────┐ │
│ │ Roll Number:  [Input Field] │ │
│ │ Password:     [Input Field] │ │
│ │ ┌───────────────────────────┐ │
│ │ │  [Login Button (Purple)]  │ │
│ │ └───────────────────────────┘ │
│ └─────────────────────────────┘ │
└─────────────────────────────────┘
```

### Navigation Bar
```
┌─────────────────────────────────────────────┐
│ [🎓] IAP Portal  Dashboard  Profile  [Logout] │
│ ← Gradient Background (Purple) - White Text │
│ ← Responsive, Collapses on Mobile           │
└─────────────────────────────────────────────┘
```

### Dashboard Card
```
┌─────────────────────────────────┐
│ Gradient Header (Purple)        │
│ Card Title                      │
├─────────────────────────────────┤
│                                 │
│  Content Area (White Background)│
│                                 │
│  • List items                   │
│  • Information blocks           │
│  • Text content                 │
│                                 │
└─────────────────────────────────┘
↓ Hover: Lifts up slightly with shadow effect
```

### Buttons

**Primary Button:**
```
┌──────────────────────────┐
│ [💾 Save] (Purple Grad)  │ ← White Text, Box Shadow on Hover
└──────────────────────────┘
```

**Secondary Button:**
```
┌──────────────────────────┐
│ [❌ Cancel] (Light Gray) │ ← Dark Text, Purple on Hover
└──────────────────────────┘
```

**Danger Button:**
```
┌──────────────────────────┐
│ [🗑️ Delete] (Red)        │ ← White Text, Dark Red on Hover
└──────────────────────────┘
```

### Form Elements
```
Field Label (Dark, Bold)
┌────────────────────────────────────────┐
│ 🔑 [Password Input Field]              │
│ Text dark gray, light gray border      │
│ Purple focus ring, smooth transition   │
└────────────────────────────────────────┘
```

### Alerts

**Success Alert:**
```
┌──────────────────────────────────────┐
│ ✅ Operation completed successfully! │
│ ← Light green background, green text │
│ ← Green left border accent            │
└──────────────────────────────────────┘
```

**Error Alert:**
```
┌──────────────────────────────────────┐
│ ❌ An error occurred!                │
│ ← Light red background, red text    │
│ ← Red left border accent             │
└──────────────────────────────────────┘
```

**Warning Alert:**
```
┌──────────────────────────────────────┐
│ ⚠️ Warning: Check this information   │
│ ← Light amber background, amber text │
│ ← Amber left border accent           │
└──────────────────────────────────────┘
```

### Badges/Status Indicators
```
[🟢 Registered]   ← Light green background
[🟢 Completed]    ← Darker green background
[🟡 Pending]      ← Amber background
[🔴 Dropped]      ← Light red background
```

### Password Strength Indicator
```
Password Strength: Medium

┌──────────────────────────┐
│▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░│ ← 66% filled
└──────────────────────────┘
   🟠 Medium (Amber)

Weak   ← 🔴 Red
Medium ← 🟠 Amber
Strong ← 🟢 Green
```

---

## Page Layouts

### Login Page Layout
```
┌──────────────────────────────┐
│                              │
│                              │
│     ┌────────────────────┐   │
│     │  Login Card        │   │
│     │  450px wide        │   │
│     │  Centered          │   │
│     │  Shadow            │   │
│     └────────────────────┘   │
│                              │
│                              │
└──────────────────────────────┘
Background: Purple Gradient (Centered)
```

### Dashboard Layout
```
┌──────────────────────────────────────────┐
│  ┌─────────────────────────────────────┐ │
│  │ [🎓] IAP Portal  Dashboard [Logout]  │ │
│  └─────────────────────────────────────┘ │
│                                          │
│  ┌─────────────────────────────────────┐ │
│  │ Welcome, Student Name!              │ │
│  │ Graduate Year: 2024                 │ │
│  └─────────────────────────────────────┘ │
│                                          │
│  ┌──────────────────┐ ┌──────────────┐  │
│  │ Session Card 1   │ │ Session Card│  │
│  │ [Take Quiz ▶]    │ │ [Take Quiz▶]│  │
│  └──────────────────┘ └──────────────┘  │
│  ┌──────────────────┐ ┌──────────────┐  │
│  │ Session Card 2   │ │ Session Card│  │
│  │ [Take Quiz ▶]    │ │ [Take Quiz▶]│  │
│  └──────────────────┘ └──────────────┘  │
│                                          │
└──────────────────────────────────────────┘
Background: Light gray (#f8f9fa)
```

### Quiz Page Layout
```
┌──────────────────────────────────────────┐
│  ┌─────────────────────────────────────┐ │
│  │ [🎓] IAP Portal  Quiz      [Logout]  │ │
│  └─────────────────────────────────────┘ │
│                                          │
│  ┌─────────────────────────────────────┐ │
│  │ Session Title - Quiz                │ │
│  │ 3 Questions | 15 Minutes            │ │
│  └─────────────────────────────────────┘ │
│                                          │
│  ┌─────────────────────────────────────┐ │
│  │ Q1: Question text?                  │ │
│  │  ○ Option A                         │ │
│  │  ○ Option B                         │ │
│  │  ● Option C                         │ │
│  │  ○ Option D                         │ │
│  ├─────────────────────────────────────┤ │
│  │ Q2: How would you rate?             │ │
│  │  ☆ ☆ ☆ ☆ ☆ (Rate 1-5)            │ │
│  ├─────────────────────────────────────┤ │
│  │ Q3: Your feedback?                  │ │
│  │ ┌─────────────────────────────────┐ │ │
│  │ │ [Text area for response]        │ │ │
│  │ └─────────────────────────────────┘ │ │
│  ├─────────────────────────────────────┤ │
│  │  [← Previous] [Submit →]            │ │
│  └─────────────────────────────────────┘ │
│                                          │
└──────────────────────────────────────────┘
```

---

## Responsive Breakpoints

### Desktop View (> 768px)
- Full width sidebar (if applicable)
- Multi-column grid layouts
- Large fonts
- Full navigation bar

### Tablet View (576px - 768px)
- Single or dual column layouts
- Medium fonts
- Collapsed sidebars
- Stack navigation if needed

### Mobile View (< 576px)
- Single column layout
- Small fonts
- Hamburger menu for navigation
- Touch-friendly buttons
- Full-width cards

---

## Shadow Effects

### Small Shadow (Cards)
```
0 2px 10px rgba(0, 0, 0, 0.1)
↑ Subtle, used for card hover
```

### Medium Shadow (Buttons)
```
0 10px 40px rgba(0, 0, 0, 0.2)
↑ Noticeable, used for card shadow
```

### Large Shadow (Modals)
```
0 20px 60px rgba(0, 0, 0, 0.15)
↑ Prominent, used for emphasis
```

---

## Font Hierarchy

### Page Title (H1)
```
Size: 32px
Weight: 700 (Bold)
Color: White (on gradient) or Dark (on white)
Usage: Page headers
```

### Section Title (H2)
```
Size: 28px
Weight: 700 (Bold)
Color: Primary or Dark
Usage: Card headers, form titles
```

### Label (Form)
```
Size: 14px
Weight: 600 (Semibold)
Color: Dark
Usage: Input labels
```

### Body Text
```
Size: 14px
Weight: 400 (Regular)
Color: Dark or Light
Usage: Content, descriptions
```

### Small Text (Captions)
```
Size: 12px
Weight: 400 (Regular)
Color: Light
Usage: Timestamps, helpers
```

---

## Icon Styles

### Icons in Headers
```
Size: 48px
Color: White
Used in: Login header, page headers
```

### Icons in Buttons
```
Size: 16px
Color: Inherit (from button)
Used in: Buttons, links
Margin: 8px gap from text
```

### Icons in Navigation
```
Size: 24px
Color: White
Used in: Navbar, menus
```

### Status Icons
```
✅ Check: Green (#16a34a)
❌ Times: Red (#dc2626)
⚠️ Warning: Amber (#f59e0b)
ℹ️ Info: Blue (#3b82f6)
```

---

## Spacing System

### Padding
```
Small (p-2):   1rem
Medium (p-3):  1.5rem
Large (p-4):   2rem
XLarge (p-5):  3rem
```

### Margins
```
Small (mt-1):  0.5rem
Medium (mt-3): 1.5rem
Large (mt-4):  2rem
XLarge (mt-5): 3rem
```

### Gaps (Flex)
```
Small (gap-2):  1rem
Medium (gap-3): 1.5rem
Large (gap-4):  2rem
```

---

## Border Radius Values

```
Small (--radius-sm):  6px   - Used on badges, small elements
Medium (--radius-md): 8px   - Used on buttons, inputs
Large (--radius-lg):  12px  - Used on cards, headers
```

---

## Animation Effects

### Hover Transform
```
transform: translateY(-2px)
↑ Lifts element on hover
Smooth transition: 0.3s ease
```

### Focus State
```
border-color: #667eea
box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25)
↑ Purple ring around focused element
```

### Transitions
```
All effects: 0.3s ease
Smooth, not jarring
Professional feel
```

---

## Implementation Checklist

### For New Pages
- [ ] Include `theme.css` in `<head>`
- [ ] Use `.page-header` for titles
- [ ] Use `.card-custom` for cards
- [ ] Use `.btn-primary-custom` for main buttons
- [ ] Use `.alert-success-custom` for messages
- [ ] Use `.form-control` for inputs
- [ ] Use `.navbar-custom` for navigation
- [ ] Test on mobile (< 576px)
- [ ] Test on tablet (576px - 768px)
- [ ] Test on desktop (> 768px)

---

## Quick Color Reference

| Element | Color | Hex |
|---------|-------|-----|
| Primary Gradient Start | Blue-Purple | #667eea |
| Primary Gradient End | Deep Purple | #764ba2 |
| Success | Green | #16a34a |
| Danger | Red | #dc2626 |
| Warning | Amber | #f59e0b |
| Info | Blue | #3b82f6 |
| Text Dark | Dark Gray | #1f2937 |
| Text Light | Light Gray | #6b7280 |
| Background Light | Very Light | #f8f9fa |
| Background White | White | #ffffff |
| Border | Light Border | #e5e7eb |

---

**Visual Reference Version:** 1.0  
**Theme Version:** 1.0  
**Last Updated:** January 2026  

*Use this guide for design consistency and color references.*
