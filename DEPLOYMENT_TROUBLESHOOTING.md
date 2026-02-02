# Deployment Troubleshooting Guide - Thumbnail Display Issues

## Problem
Thumbnails upload successfully but don't display on the deployment server (works fine locally).

## Common Causes & Solutions

### 1. File Permissions (Most Common)

#### Quick Fix
Run this on your deployment server:

```bash
# Navigate to your application directory
cd /path/to/your/app

# Make the script executable
chmod +x fix_permissions.sh

# Run the script
sudo ./fix_permissions.sh
```

#### Manual Fix
If you can't run the script, do this manually:

```bash
# Set ownership (replace www-data with your web server user)
sudo chown -R www-data:www-data writable/

# Set directory permissions
sudo find writable/ -type d -exec chmod 755 {} \;

# Set file permissions
sudo find writable/ -type f -exec chmod 644 {} \;
```

#### Find Your Web Server User
```bash
# For Apache
ps aux | grep apache | grep -v grep

# For Nginx
ps aux | grep nginx | grep -v grep

# Common users:
# - Ubuntu/Debian: www-data
# - CentOS/RHEL: apache
# - Nginx: nginx or www-data
```

### 2. SELinux Issues (CentOS/RHEL)

If you're on CentOS/RHEL with SELinux enabled:

```bash
# Check if SELinux is enabled
getenforce

# If it returns "Enforcing", run:
sudo chcon -R -t httpd_sys_rw_content_t writable/
sudo setsebool -P httpd_can_network_connect 1
```

### 3. Apache .htaccess Issues

Make sure your `.htaccess` file in the `public` directory allows file serving:

```apache
# Add this to public/.htaccess if not present
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Don't rewrite if the file exists
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

### 4. Nginx Configuration

If using Nginx, ensure your config has:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### 5. Check File Paths

Verify files are actually uploaded:

```bash
# Check if files exist
ls -la writable/uploads/programs/thumbs/

# Check file permissions
ls -lh writable/uploads/programs/thumbs/
```

### 6. Check PHP Configuration

Ensure PHP can read the files:

```bash
# Check PHP user
php -r "echo exec('whoami');"

# This should match your web server user
```

### 7. Debug the Route

Test if the FileController route works:

```bash
# Try accessing a file directly via curl
curl -I https://yourdomain.com/writable/uploads/programs/thumbs/yourfile.png

# Should return 200 OK, not 404
```

### 8. Check Application Logs

```bash
# Check CodeIgniter logs
tail -f writable/logs/log-*.log

# Check web server error logs
# Apache:
sudo tail -f /var/log/apache2/error.log

# Nginx:
sudo tail -f /var/log/nginx/error.log
```

## Verification Steps

After applying fixes, verify:

1. **File exists:**
   ```bash
   ls -la writable/uploads/programs/thumbs/
   ```

2. **Permissions are correct:**
   ```bash
   # Directories should be 755
   # Files should be 644
   stat writable/uploads/programs/thumbs/yourfile.png
   ```

3. **Web server can read:**
   ```bash
   sudo -u www-data cat writable/uploads/programs/thumbs/yourfile.png > /dev/null
   echo $?  # Should return 0
   ```

4. **Route works:**
   - Open browser developer tools (F12)
   - Go to Network tab
   - Reload the page
   - Check the thumbnail request
   - Should return 200, not 404 or 403

## Quick Diagnostic Script

Create and run this on your server:

```bash
#!/bin/bash
echo "=== Diagnostic Report ==="
echo ""
echo "1. Web Server User:"
ps aux | grep -E 'apache|nginx|httpd' | grep -v grep | head -1 | awk '{print $1}'
echo ""
echo "2. Upload Directory Permissions:"
ls -ld writable/uploads/programs/thumbs/
echo ""
echo "3. Sample File Permissions:"
ls -l writable/uploads/programs/thumbs/ | head -5
echo ""
echo "4. PHP User:"
php -r "echo exec('whoami');"
echo ""
echo "5. SELinux Status:"
getenforce 2>/dev/null || echo "SELinux not installed"
echo ""
echo "6. Disk Space:"
df -h writable/
```

## Still Not Working?

### Check Browser Console
1. Open browser developer tools (F12)
2. Go to Console tab
3. Look for errors related to image loading
4. Check the Network tab for failed requests

### Common Error Messages

**403 Forbidden:**
- File permissions issue
- SELinux blocking access
- .htaccess blocking access

**404 Not Found:**
- Route not working
- File doesn't exist
- Wrong path in code

**500 Internal Server Error:**
- PHP error in FileController
- Check error logs

## Production Deployment Checklist

- [ ] Set `CI_ENVIRONMENT = production` in `.env`
- [ ] Set proper file permissions (755 for dirs, 644 for files)
- [ ] Set correct ownership (web server user)
- [ ] Configure SELinux (if applicable)
- [ ] Test file upload
- [ ] Test file display
- [ ] Check error logs
- [ ] Enable HTTPS
- [ ] Set up proper backups

## Contact Support

If none of these solutions work, provide:
1. Server OS and version
2. Web server (Apache/Nginx) and version
3. PHP version
4. Error logs
5. Output of diagnostic script
6. Screenshot of browser console errors
