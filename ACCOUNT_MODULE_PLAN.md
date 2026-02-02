# Account Module Implementation Plan

## Overview
Add a comprehensive Account module to manage user profiles and application settings, integrating with CodeIgniter Shield authentication system.

---

## Module Structure

```
app/Modules/Account/
├── Config/
│   ├── Routes.php          # Account & Settings routes
│   └── Menu.php            # Menu configuration
├── Controllers/
│   ├── AccountController.php    # Profile management
│   └── SettingsController.php   # Settings management
├── Models/
│   └── UserProfileModel.php     # Extended user data
├── Views/
│   ├── profile/
│   │   ├── index.php           # View profile
│   │   ├── edit.php            # Edit profile
│   │   └── change_password.php # Change password
│   └── settings/
│       ├── index.php           # Settings dashboard
│       ├── general.php         # General settings
│       ├── security.php        # Security settings
│       └── notifications.php   # Notification preferences
└── Database/
    └── Migrations/
        └── 2026-02-XX-XXXXXX_CreateUserProfilesTable.php
```

---

## Features Breakdown

### 1. Profile Management (`/account`)

#### Features:
- **View Profile**
  - Display user information (name, email, username, avatar)
  - Show account creation date, last login
  - Display assigned roles/permissions
  - Show activity summary

- **Edit Profile**
  - Update personal information (first name, last name, phone)
  - Upload/change profile avatar
  - Update bio/description
  - Change username (with validation)
  - Update contact information

- **Change Password**
  - Current password verification
  - New password with strength indicator
  - Password confirmation
  - Integration with Shield password validators