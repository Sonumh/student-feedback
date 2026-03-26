# Gateway Electronics — Student Feedback Portal
## cPanel Setup Guide

---

### STEP 1 — Upload Files
1. Log in to cPanel → File Manager
2. Navigate to `public_html/`
3. Upload the `student-feedback/` folder (entire directory)
   → Result: `public_html/student-feedback/`

---

### STEP 2 — Create MySQL Database
1. cPanel → **MySQL Databases**
2. Create a new database → e.g. `yourusername_gateway_feedback`
3. Create a DB user → note username & password
4. Assign the user to the database with **ALL PRIVILEGES**

---

### STEP 3 — Import Database Schema
1. cPanel → **phpMyAdmin**
2. Select your new database
3. Click **Import** tab
4. Upload `database.sql` from this folder
5. Click **Go**

---

### STEP 4 — Edit config.php
Open `student-feedback/config.php` and update:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'yourusername_gateway_feedback');  // Your full DB name
define('DB_USER', 'yourusername_dbuser');             // Your DB username
define('DB_PASS', 'your_password_here');              // Your DB password
```

---

### STEP 5 — Change Admin Password
The default credentials are:
- **Username:** `admin`
- **Password:** `Admin@1234`

**IMPORTANT:** Change this immediately after first login.
To change the password:
1. Log in to phpMyAdmin
2. Select `gateway_feedback` database → `admins` table
3. Run this SQL (replace `NewPassword123` with your password):

```sql
UPDATE admins 
SET password_hash = '$2y$10$' || MD5('NewPassword123')
WHERE username = 'admin';
```

OR use PHP to generate a proper bcrypt hash:
```php
<?php echo password_hash('YourNewPassword', PASSWORD_DEFAULT); ?>
```
Then paste the hash directly into phpMyAdmin.

---

### STEP 6 — Access the Site
- **Feedback Form:** `https://gatewayelectronics.in/student-feedback/`
- **Admin Login:**  `https://gatewayelectronics.in/student-feedback/admin/login.php`
- **Admin Dashboard:** `https://gatewayelectronics.in/student-feedback/admin/dashboard.php`

---

### FILE STRUCTURE
```
student-feedback/
├── index.php          ← Public feedback form
├── submit.php         ← Form POST handler
├── config.php         ← DB credentials (EDIT THIS)
├── database.sql       ← Import into phpMyAdmin
├── assets/
│   ├── css/style.css  ← All styles
│   └── js/form.js     ← Form interactions
└── admin/
    ├── login.php      ← Admin login
    ├── auth.php       ← Session guard
    ├── dashboard.php  ← Stats overview
    ├── feedback.php   ← View/filter/export feedback
    ├── events.php     ← Manage events
    └── logout.php     ← Logout
```

---

### REQUIREMENTS
- PHP 7.4+ (cPanel default is fine)
- MySQL 5.7+ or MariaDB 10.3+
- PDO extension enabled (default on cPanel)

---

### SUPPORT
Gateway Electronics IT Department
