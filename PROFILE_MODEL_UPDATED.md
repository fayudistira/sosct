# ProfileModel Updated - Fix for Application Form

## 🐛 Issue

When submitting the application form, you got this error:
```
CodeIgniter\Database\Exceptions\DatabaseException #1054
Unknown column 'email' in 'where clause'
```

## ✅ Root Cause

The `ProfileModel` was missing the new fields (`profile_number` and `email`) in its `allowedFields` array, even though the database migration had added them to the table.

## 🔧 Fix Applied

### 1. Updated `allowedFields`
Added missing fields:
- `profile_number` - Unique profile identifier
- `email` - Email address

### 2. Updated `validationRules`
- Made `user_id` optional (`permit_empty`) - not everyone needs login
- Added `email` validation with uniqueness check
- Added `profile_number` validation
- Made `profile_number` optional (auto-generated)

### 3. Added `generateProfileNumber()` Method
New method to generate unique profile numbers:
- Format: `PROF-YYYY-NNNN`
- Example: `PROF-2026-0001`
- Auto-increments per year

## 📝 Updated ProfileModel

**File**: `app/Modules/Account/Models/ProfileModel.php`

### New allowedFields:
```php
protected $allowedFields = [
    'profile_number',  // NEW
    'user_id',
    'full_name',
    'nickname',
    'gender',
    'place_of_birth',
    'date_of_birth',
    'religion',
    'citizen_id',
    'phone',
    'email',          // NEW
    'street_address',
    'district',
    'regency',
    'province',
    'postal_code',
    'emergency_contact_name',
    'emergency_contact_phone',
    'emergency_contact_relation',
    'father_name',
    'mother_name',
    'position',
    'photo',
    'documents'
];
```

### New Method:
```php
public function generateProfileNumber(): string
{
    $year = date('Y');
    $prefix = "PROF-{$year}-";
    
    $lastRecord = $this->like('profile_number', $prefix)
                      ->orderBy('id', 'DESC')
                      ->first();
    
    if ($lastRecord) {
        $lastNumber = (int) substr($lastRecord['profile_number'], -4);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    
    return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}
```

## ✅ Application Form Should Now Work

The application form can now:
1. Create a profile with `profile_number` and `email`
2. Validate email uniqueness
3. Auto-generate profile numbers
4. Create admission record linking to profile

## 🧪 Test the Fix

Try submitting the application form again. It should now:
1. Generate profile number (PROF-2026-0001)
2. Save profile with email
3. Create admission record
4. Show success page with registration number

---

**Fixed**: 2026-02-03  
**Status**: ✅ Ready to test
