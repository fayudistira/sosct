# Profile Module Setup Complete

## What Was Created

### 1. Database Migration
- **File**: `app/Database/Migrations/2026-02-02-042200_CreateProfilesTable.php`
- **Table**: `profiles`
- **Fields**: Same as admissions table minus `registration_number`, `course`, `status`, `application_date`, and `notes`
- **Foreign Key**: `user_id` references `users.id` with CASCADE on delete/update
- **Status**: ✅ Migration executed successfully

### 2. Account Module Structure
```
app/Modules/Account/
├── Config/
│   ├── Menu.php          # Menu configuration
│   └── Routes.php        # Module routes
├── Controllers/
│   └── ProfileController.php
├── Models/
│   └── ProfileModel.php
└── Views/
    ├── index.php         # Profile view or create button
    ├── create.php        # Create profile form
    └── edit.php          # Edit profile form
```

### 3. Routes Available
- `GET /account` - View profile or show create button
- `GET /account/create` - Create profile form
- `POST /account/store` - Store new profile
- `GET /account/edit` - Edit profile form
- `POST /account/update` - Update profile

All routes are protected by `session` filter (authentication required).

### 4. Features

#### Profile View (`/account`)
- If no profile exists: Shows a centered card with "Create Profile" button
- If profile exists: Shows profile details in a nice layout with:
  - Profile photo (or placeholder)
  - Personal information
  - Address details
  - Family information
  - Edit button

#### Create Profile (`/account/create`)
- Form with all fields from admissions minus registration_number and course
- Photo upload (JPG, PNG - Max 2MB)
- Multiple document uploads (PDF, Images - Max 5MB each)
- Validation on all required fields
- Redirects to profile view after creation

#### Edit Profile (`/account/edit`)
- Pre-filled form with existing data
- Can update photo (shows current photo)
- Can add more documents (keeps existing ones)
- Validation on all required fields
- Redirects to profile view after update

### 5. Profile Fields
- `user_id` (foreign key to users table)
- `full_name` *
- `nickname`
- `gender` * (Male/Female)
- `place_of_birth` *
- `date_of_birth` *
- `religion` *
- `citizen_id`
- `phone` *
- `street_address` *
- `district` *
- `regency` *
- `province` *
- `postal_code`
- `emergency_contact_name` *
- `emergency_contact_phone` *
- `emergency_contact_relation` *
- `father_name` *
- `mother_name` *
- `photo` (file path)
- `documents` (JSON array of file paths)
- Timestamps (created_at, updated_at, deleted_at)

\* = Required fields

### 6. Upload Directories Created
- `writable/uploads/profiles/photos/` - For profile photos
- `writable/uploads/profiles/documents/` - For supporting documents

### 7. Dashboard Integration
- Added "My Profile" link to user dropdown menu in dashboard layout
- Link appears between user info and logout button

## How It Works

1. **First Time User**: When a user logs in and clicks "My Profile", they see a button to create their profile
2. **Create Profile**: User fills out the form with personal information, uploads photo and documents
3. **View Profile**: After creation, user can view their complete profile information
4. **Edit Profile**: User can update their information anytime by clicking "Edit Profile"

## Security
- All routes require authentication (session filter)
- One profile per user (enforced by unique constraint on user_id)
- File upload validation (type and size)
- CSRF protection on forms
- Soft deletes enabled

## Next Steps (Optional)
- Add profile completion percentage indicator
- Add ability to delete individual documents
- Add profile photo cropping
- Add email verification when changing contact info
- Add profile visibility settings
