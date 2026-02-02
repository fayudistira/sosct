# Thumbnail Display Fix - Step by Step Instructions

## Problem
Thumbnails upload successfully but don't display on deployment server (works fine locally).

## Root Cause
File permissions issue - the web server doesn't have permission to read the uploaded files.

---

## Solution Steps

### Step 1: Run Diagnostic Tool

1. Upload `public/check_uploads.php` to your server
2. Access it in browser: `https://yourdomain.com/check_uploads.php?key=debug123`
3. Review the diagnostic report
4. **DELETE the file after troubleshooting!**

### Step 2: Fix File Permissions

#### Option A: Using the Script (Recommended)

```bash
# SSH into your server
ssh user@yourserver.com

# Navigate to your application directory
cd /path/to/your/app

# Make the script executable
chmod +x fix_permissions.sh

# Run the script
sudo ./fix_permissions.sh
```

#### Option B: Manual Fix

```bash
# SSH into your server
ssh user@yourserver.com

# Navigate to your application directory
cd /path/to/your/app

# Find your web server user (usually www-data, apache, or nginx)
ps aux | grep -E 'apache|nginx|httpd' | grep -v grep | head -1 | awk '{print $1}'

# Set ownership (replace www-data with your web server user)
sudo chown -R www-data:www-data writable/

# Set directory permissions (755 = rwxr-xr-x)
sudo find writable/ -type d -exec chmod 755 {} \;

# Set file permissions (644 = rw-r--r--)
sudo find writable/ -type f -exec chmod 644 {} \;
```

### Step 3: Verify the Fix

1. Go to your Programs page
2. Check if thumbnails now display
3. Open browser developer tools (F12)
4. Go to Network tab
5. Reload the page
6. Check thumbnail requests - should return 200 OK

### Step 4: Check Logs (If Still Not Working)

```bash
# Check CodeIgniter logs
tail -f writable/logs/log-*.log

# Check web server error logs
# For Apache:
sudo tail -f /var/log/apache2/error.log

# For Nginx:
sudo tail -f /var/log/nginx/error.log
```

---

## Common Issues & Solutions

### Issue 1: Wrong Web Server User

**Symptom:** Files still not accessible after setting permissions

**Solution:**
```bash
# Find the correct web server user
ps aux | grep -E 'apache|nginx|httpd' | grep -v grep

# Common users by OS:
# Ubuntu/Debian: www-data
# CentOS/RHEL: apache
# Nginx: nginx or www-data

# Set ownership with correct user
sudo chown -R [correct-user]:[correct-user] writable/
```

### Issue 2: SELinux Blocking Access (CentOS/RHEL)

**Symptom:** 403 Forbidden errors in logs

**Solution:**
```bash
# Check if SELinux is enabled
getenforce

# If it returns "Enforcing", run:
sudo chcon -R -t httpd_sys_rw_content_t writable/
sudo setsebool -P httpd_can_network_connect 1
```

### Issue 3: Route Not Working

**Symptom:** 404 errors for thumbnail URLs

**Solution:**
1. Verify route in `app/Config/Routes.php`:
   ```php
   $routes->get('writable/uploads/(.+)', 'FileController::serve/$1');
   ```

2. Clear route cache:
   ```bash
   php spark cache:clear
   ```

3. Restart web server:
   ```bash
   # Apache
   sudo systemctl restart apache2
   
   # Nginx
   sudo systemctl restart nginx
   ```

### Issue 4: .htaccess Issues (Apache)

**Symptom:** Routes not working at all

**Solution:**
1. Ensure `mod_rewrite` is enabled:
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

2. Check Apache config allows `.htaccess`:
   ```apache
   <Directory /path/to/your/app/public>
       AllowOverride All
   </Directory>
   ```

---

## Verification Checklist

After applying fixes, verify:

- [ ] Files exist in `writable/uploads/programs/thumbs/`
- [ ] Directory permissions are 755
- [ ] File permissions are 644
- [ ] Files are owned by web server user
- [ ] Web server can read files
- [ ] Route returns 200 OK (not 404 or 403)
- [ ] Thumbnails display in browser
- [ ] No errors in logs

---

## Quick Test Commands

Run these on your server to verify:

```bash
# 1. Check if files exist
ls -la writable/uploads/programs/thumbs/

# 2. Check permissions
stat writable/uploads/programs/thumbs/

# 3. Test if web server can read
sudo -u www-data cat writable/uploads/programs/thumbs/[filename] > /dev/null
echo $?  # Should return 0

# 4. Test route with curl
curl -I https://yourdomain.com/writable/uploads/programs/thumbs/[filename]
# Should return: HTTP/1.1 200 OK
```

---

## Files Modified in This Fix

1. **app/Config/Routes.php** - Changed route from `(:any)` to `(.+)` to capture nested paths
2. **app/Controllers/FileController.php** - Added logging and better error handling
3. **fix_permissions.sh** - Script to fix permissions automatically
4. **public/check_uploads.php** - Diagnostic tool (DELETE after use!)

---

## Security Notes

1. **Never** make the `writable` directory publicly accessible
2. **Always** serve files through the FileController
3. **Delete** `check_uploads.php` after troubleshooting
4. **Use** proper file permissions (755 for dirs, 644 for files)
5. **Set** correct ownership (web server user)

---

## Still Not Working?

If you've tried everything and it's still not working:

1. Run the diagnostic tool: `check_uploads.php?key=debug123`
2. Check all logs (CodeIgniter + web server)
3. Verify your deployment environment matches local
4. Check if there's a CDN or proxy in front of your server
5. Verify the route is actually being hit (add logging)

### Get Help

Provide this information when asking for help:
- Server OS and version
- Web server (Apache/Nginx) and version
- PHP version
- Output from diagnostic tool
- Error logs
- Screenshot of browser console errors
- Output from verification commands

---

## Prevention for Future Deployments

Add this to your deployment script:

```bash
#!/bin/bash
# After deploying code, always run:

# Set permissions
sudo chown -R www-data:www-data writable/
sudo find writable/ -type d -exec chmod 755 {} \;
sudo find writable/ -type f -exec chmod 644 {} \;

# Clear cache
php spark cache:clear

# Restart web server
sudo systemctl restart apache2  # or nginx
```

---

## Summary

The issue is almost always file permissions. The web server needs:
1. **Read** permission on files (644)
2. **Execute** permission on directories (755)
3. **Ownership** by the web server user

Run the fix script, verify with the diagnostic tool, and you should be good to go!
