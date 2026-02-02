#!/bin/bash
# Script to fix file permissions for CodeIgniter 4 deployment
# Run this on your deployment server

echo "Fixing CodeIgniter 4 file permissions..."

# Set ownership to web server user (adjust www-data if your server uses a different user)
# Common users: www-data (Ubuntu/Debian), apache (CentOS/RHEL), nginx (Nginx)
WEB_USER="www-data"

# Get the current directory
APP_DIR=$(pwd)

echo "Setting ownership to $WEB_USER..."
sudo chown -R $WEB_USER:$WEB_USER "$APP_DIR/writable"

echo "Setting directory permissions (755)..."
sudo find "$APP_DIR/writable" -type d -exec chmod 755 {} \;

echo "Setting file permissions (644)..."
sudo find "$APP_DIR/writable" -type f -exec chmod 644 {} \;

echo "Creating upload directories if they don't exist..."
sudo mkdir -p "$APP_DIR/writable/uploads/programs/thumbs"
sudo mkdir -p "$APP_DIR/writable/uploads/admissions/photos"
sudo mkdir -p "$APP_DIR/writable/uploads/admissions/documents"
sudo mkdir -p "$APP_DIR/writable/uploads/profiles/photos"
sudo mkdir -p "$APP_DIR/writable/uploads/profiles/documents"
sudo mkdir -p "$APP_DIR/writable/uploads/receipts"

echo "Setting ownership for upload directories..."
sudo chown -R $WEB_USER:$WEB_USER "$APP_DIR/writable/uploads"

echo "Setting permissions for upload directories..."
sudo chmod -R 755 "$APP_DIR/writable/uploads"

echo "Done! Permissions have been set."
echo ""
echo "If you're still having issues, check:"
echo "1. Your web server user (might not be www-data)"
echo "2. SELinux settings (if enabled)"
echo "3. File paths in your application"
