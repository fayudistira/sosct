# Test Application Form Submission

**Date**: February 3, 2026  
**Status**: Ready for Testing

---

## Quick Test Steps

### Option 1: Apply from Programs Page (Recommended)

1. Navigate to: `http://localhost/programs`
2. Click on any program card
3. Click "Apply Now" button on program detail page
4. Form should be pre-filled with selected program
5. Fill in all required fields:
   - Full Name: `John Doe`
   - Gender: `Male`
   - Date of Birth: `1995-01-15`
   - Place of Birth: `Jakarta`
   - Religion: `Islam`
   - Phone: `081234567890`
   - Email: `john.doe@example.com`
   - Street Address: `Jl. Sudirman No. 123`
   - District: `Kebayoran Baru`
   - Regency: `Jakarta Selatan`
   - Province: `DKI Jakarta`
   - Emergency Contact Name: `Jane Doe`
   - Emergency Contact Phone: `081234567891`
   - Emergency Contact Relation: `Sister`
   - Father's Name: `Robert Doe`
   - Mother's Name: `Mary Doe`
   - Photo: Upload any JPG/PNG (max 2MB)
6. Click "Submit Application"
7. Should redirect to success page with registration number

### Option 2: Apply from Apply Page

1. Navigate to: `http://localhost/apply`
2. Select a program from dropdown
3. Fill in all required fields (same as above)
4. Click "Submit Application"
5. Should redirect to success page with registration number

---

## Expected Results

### Success Scenario:
- ✅ Profile created with profile_number: `PROF-2026-0001`
- ✅ Admission created with registration_number: `REG-2026-0001`
- ✅ Photo uploaded to: `public/uploads/profiles/photos/`
- ✅ Documents uploaded to: `public/uploads/profiles/documents/`
- ✅ Redirect to: `/apply/success`
- ✅ Success message displayed with registration number

### Database Verification:

```sql
-- Check profile was created
SELECT * FROM profiles ORDER BY id DESC LIMIT 1;

-- Check admission was created
SELECT * FROM admissions ORDER BY id DESC LIMIT 1;

-- Check the relationship
SELECT 
    a.registration_number,
    p.profile_number,
    p.full_name,
    p.email,
    pr.title as program_title
FROM admissions a
JOIN profiles p ON p.id = a.profile_id
JOIN programs pr ON pr.id = a.program_id
ORDER BY a.id DESC LIMIT 1;
```

---

## Troubleshooting

### Error: "The program_id field is required"
- **Cause**: Form not sending program_id or course field
- **Fix**: Already fixed in apply.php - dropdown now sends program ID

### Error: "Failed to create profile"
- **Cause**: Validation error or database constraint
- **Check**: Look at error logs in `writable/logs/log-2026-02-03.log`

### Error: "Failed to create admission"
- **Cause**: AdmissionModel validation failing
- **Fix**: Already fixed - AdmissionModel now has correct fields

### Error: "Unknown column 'email' in 'where clause'"
- **Cause**: Old code trying to query admissions.email
- **Fix**: Already fixed - email is now in profiles table

### Error: "Program not found"
- **Cause**: No programs in database
- **Fix**: Run seeder: `php spark db:seed LanguageProgramSeeder`

---

## Test Data

### Sample Test User 1:
```
Full Name: Ahmad Rizki
Gender: Male
Date of Birth: 1998-05-20
Place of Birth: Bandung
Religion: Islam
Phone: 081234567890
Email: ahmad.rizki@example.com
Address: Jl. Asia Afrika No. 45, Sumur Bandung, Bandung, Jawa Barat
Emergency Contact: Siti Rizki (Mother) - 081234567891
Parents: Budi Rizki (Father), Siti Rizki (Mother)
```

### Sample Test User 2:
```
Full Name: Sarah Johnson
Gender: Female
Date of Birth: 2000-08-15
Place of Birth: Surabaya
Religion: Christian
Phone: 082345678901
Email: sarah.johnson@example.com
Address: Jl. Pemuda No. 78, Genteng, Surabaya, Jawa Timur
Emergency Contact: Michael Johnson (Father) - 082345678902
Parents: Michael Johnson (Father), Linda Johnson (Mother)
```

---

## Next Testing Phase

After successful application submission:

1. **Test Admission Management**
   - View admission list at `/dashboard/admissions`
   - View single admission details
   - Edit admission status (approve/reject)
   - Search admissions by name/email/registration number

2. **Test Student Conversion**
   - Approve an admission
   - Create student record from approved admission
   - Verify student_number generated

3. **Test Profile Management**
   - View profile list at `/dashboard/profiles`
   - Edit profile information
   - Upload/update documents

---

## Files to Monitor

- `writable/logs/log-2026-02-03.log` - Error logs
- `public/uploads/profiles/photos/` - Photo uploads
- `public/uploads/profiles/documents/` - Document uploads
- Database tables: `profiles`, `admissions`, `programs`

---

**Ready to Test!** 🚀
