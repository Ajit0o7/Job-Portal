# Job Portal

A full-featured Job Portal built with PHP and MySQL, featuring Job Seeker, Employer, and Admin modules.

## Quick Start (After Cloning)

### Step 1 — Run the one-click setup script
> **Right-click `setup.bat` → Run as Administrator**

This script will automatically:
- Enable `mod_rewrite` in your Apache config
- Set `AllowOverride All` so `.htaccess` files work
- Restart Apache for you

### Step 2 — Import the database
1. Start **MySQL** in your XAMPP/WAMP Control Panel
2. Open **phpMyAdmin** at `http://localhost/phpmyadmin`
3. Create a new database named `portal_db`
4. Click **Import** and select `portal_db.sql` from this folder

### Step 3 — Access the site
Open your browser and visit:
```
http://localhost/Job-Portal/
```
*(Replace `Job-Portal` with whatever folder name you cloned into)*

---

## Requirements
- PHP 8.0+
- MySQL 5.7+
- Apache with `mod_rewrite` enabled
- XAMPP or WAMP

## Manual XAMPP Setup (if setup.bat doesn't work)
1. Open `C:\xampp\apache\conf\httpd.conf`
2. Find and uncomment: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Find both occurrences of `AllowOverride None` and change to `AllowOverride All`
4. Save and restart Apache

## Modules
| Module | URL |
|--------|-----|
| Home | `/home-page.php` |
| Job Seeker | `/jobseeker/jobseekerHome.php` |
| Employer | `/employer/employerHome.php` |
| Admin | `/admin/` |
