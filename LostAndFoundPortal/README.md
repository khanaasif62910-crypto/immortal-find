# Lost and Found Portal

A complete, production-ready **Lost and Found Portal** web application built for **BCA Final Year Mini Project**.

---

## 🚀 Technology Stack

- **Frontend**: HTML5, CSS3 (Glassmorphism UI), Bootstrap 5.3, JavaScript (ES6+), Font Awesome 6
- **Backend**: Core PHP 8.x (with PDO for Secure Database Interactions)
- **Database**: MySQL / MariaDB (XAMPP Server Compatible)
- **Design Architecture**: Responsive Mobile-Friendly Layout, Glassmorphism Cards, Dark Navigation Bar, Custom Badges

---

## 📁 Folder Structure

```
LostAndFoundPortal/
├── assets/
│   ├── css/
│   │   └── style.css            # Custom CSS styles, glassmorphism, animations
│   ├── js/
│   │   └── main.js              # Form validations, image preview, alerts handler
│   ├── images/                  # Static graphic assets
│   └── uploads/                 # Uploaded item images repository
├── config/
│   └── config.php               # Database PDO connection, security & helpers
├── database/
│   └── schema.sql               # Full SQL database script & sample data
├── includes/
│   ├── header.php               # HTML head, Bootstrap 5 & Font Awesome CDN
│   ├── navbar.php               # Responsive dark navigation bar & menu
│   └── footer.php               # Responsive footer section
├── admin/
│   ├── index.php                # Secure Admin Login Page
│   ├── dashboard.php            # Admin Overview & Pending Approvals
│   ├── manage_lost.php          # Lost items approval/rejection/deletion
│   ├── manage_found.php         # Found items approval/rejection/deletion
│   ├── manage_users.php         # User accounts management
│   └── categories.php           # Item category management
├── user/
│   ├── dashboard.php            # User Dashboard & Stats
│   ├── report_lost.php          # Report lost item form with image preview
│   ├── report_found.php         # Report found item form with image preview
│   ├── my_reports.php           # Manage user submitted reports & mark claimed
│   ├── profile.php              # Edit user contact details
│   └── change_password.php      # Password update form
├── index.php                    # Public Landing Home Page
├── about.php                    # Project Overview Page
├── contact.php                  # Contact Helpdesk Page
├── search.php                   # Item Directory Search & Filters
├── login.php                    # User Login Page
├── register.php                 # User Registration Page
├── logout.php                   # Logout handler
└── README.md                    # Project Documentation & Installation Guide
```

---

## ⚙️ Installation & Setup in XAMPP

1. **Download / Copy Project**:
   Place the `LostAndFoundPortal` folder inside your XAMPP `htdocs` directory:
   `C:\xampp\htdocs\LostAndFoundPortal\`

2. **Start XAMPP Control Panel**:
   - Start **Apache** module.
   - Start **MySQL** module.

3. **Database Import**:
   - Open browser and navigate to `http://localhost/phpmyadmin/`
   - Click **New** on the left menu and create a database named `lost_and_found_db`.
   - Select `lost_and_found_db`, click on the **Import** tab.
   - Choose file: `LostAndFoundPortal/database/schema.sql` and click **Import**.

4. **Access the Portal**:
   - **Main Portal**: `http://localhost/LostAndFoundPortal/`
   - **Admin Panel**: `http://localhost/LostAndFoundPortal/admin/`

---

## 🔑 Login Credentials (Sample Data)

### 1. Admin Portal (`http://localhost/LostAndFoundPortal/admin/`)
- **Username**: `admin`
- **Password**: `admin123`

### 2. User Accounts (`http://localhost/LostAndFoundPortal/login.php`)
- **User 1 Email**: `rahul@example.com`
- **Password**: `user123`

- **User 2 Email**: `priya@example.com`
- **Password**: `user123`

---

## 🔒 Security Features Implemented

1. **Prepared Statements**: PDO prepared statements prevent SQL Injection attacks across all queries.
2. **Password Hashing**: Passwords stored securely using standard `password_hash()` (BCRYPT).
3. **XSS Protection**: Inputs sanitized with `htmlspecialchars()` before rendering.
4. **Session Security**: `session_regenerate_id()` applied on login to prevent session fixation.
5. **Image File Upload Validation**: Strict check for MIME type (`image/jpeg`, `image/png`, `image/webp`) and size limits (max 5MB).

---

## 🎓 Academic viva Features for BCA Presentation

- Role-based Access Control (Guest, User, Admin)
- Status Workflow (`Pending` ➔ `Approved` / `Rejected` ➔ `Claimed`)
- Live Image Upload Preview
- Responsive Filterable Search Module
- Dashboard Analytics & Counters
