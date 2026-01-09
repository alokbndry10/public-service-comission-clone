# E-GOV PROJECT - PHP MySQL Integration Guide

## Complete Step-by-Step Setup Guide

### Prerequisites
- XAMPP installed on your system
- Basic understanding of HTML, PHP, and MySQL

---

## STEP 1: Start XAMPP

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache**
3. Click **Start** next to **MySQL**
4. Ensure both modules show "Running" status

---

## STEP 2: Move Project to XAMPP Directory

1. Copy your entire `egov` folder
2. Paste it into: `C:\xampp\htdocs\`
3. Final path should be: `C:\xampp\htdocs\egov\`

---

## STEP 3: Create Database and Tables

### 3.1 Open phpMyAdmin
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click on "New" in the left sidebar

### 3.2 Create Database
1. Database name: `egov_db`
2. Collation: `utf8mb4_unicode_ci`
3. Click "Create"

### 3.3 Create Tables
1. Click on `egov_db` in the left sidebar
2. Click on "SQL" tab
3. Copy and paste the SQL from the file: `database-setup.sql`
4. Click "Go" to execute

---

## STEP 4: Insert Sample Data (Optional)

To test the system, insert sample data:

1. In phpMyAdmin, with `egov_db` selected
2. Click "SQL" tab
3. Copy and paste this SQL:

```sql
-- Sample notices
INSERT INTO notices (title, description, category, publish_date) VALUES
('सेवा सम्बन्धी सूचना', 'यो एक महत्वपूर्ण सूचना हो', 'सूचना', '2026-01-05'),
('परीक्षा तालिका प्रकाशित', 'आगामी परीक्षाको तालिका', 'परीक्षा तालिका', '2026-01-04'),
('नतिजा प्रकाशन', 'विज्ञापन नं. १२३/०८२ को नतिजा', 'नतिजा', '2026-01-03'),
('नयाँ विज्ञापन', 'रोजगार सम्बन्धी विज्ञापन', 'विज्ञापन', '2026-01-02');

-- Sample applications
INSERT INTO applications (position_title, advertisement_no, department, total_positions, deadline, status) VALUES
('स्वास्थ्यकर्मी', '123/082', 'स्वास्थ्य विभाग', 10, '2026-02-15', 'खुला'),
('शिक्षक', '124/082', 'शिक्षा विभाग', 5, '2026-02-20', 'खुला'),
('इन्जिनियर', '125/082', 'यातायात विभाग', 3, '2026-02-25', 'खुला'),
('नर्स', '126/082', 'स्वास्थ्य विभाग', 8, '2026-03-01', 'खुला');

-- Sample exam schedules
INSERT INTO exam_schedules (exam_name, exam_date, exam_time, description) VALUES
('लिखित परीक्षा - विज्ञापन १२३/०८२', '2026-03-01', '10:00:00', 'स्वास्थ्यकर्मी पदको लिखित परीक्षा'),
('अन्तर्वार्ता - विज्ञापन १२२/०८२', '2026-02-28', '09:00:00', 'प्राविधिक पदको अन्तर्वार्ता'),
('व्यावहारिक परीक्षा', '2026-03-05', '11:00:00', 'व्यावहारिक परीक्षा सम्बन्धी जानकारी');
```

4. Click "Go"

---

## STEP 5: Test API Endpoints

### 5.1 Test APIs Using Test Page
1. Open browser
2. Go to: `http://localhost/egov/test-api.html`
3. Click each "Test" button to verify APIs are working
4. You should see success messages with data

### 5.2 Test Individual APIs

**Test Notices API:**
- URL: `http://localhost/egov/api/notices.php`
- Expected: JSON response with notices

**Test Applications API:**
- URL: `http://localhost/egov/api/applications.php`
- Expected: JSON response with applications

**Test Exams API:**
- URL: `http://localhost/egov/api/exams.php`
- Expected: JSON response with exam schedules

---

## STEP 6: Project File Structure

Your project should now have this structure:

```
C:\xampp\htdocs\egov\
│
├── config/
│   └── database.php           (Database connection)
│
├── api/
│   ├── notices.php            (Fetch notices)
│   ├── applications.php       (Fetch job applications)
│   ├── submit_application.php (Submit applications)
│   ├── contact.php            (Contact form)
│   ├── exams.php              (Exam schedules)
│   ├── results.php            (Exam results)
│   └── publications.php       (Publications)
│
├── js/
│   └── api-handler.js         (JavaScript API functions)
│
├── uploads/
│   ├── notices/
│   ├── applications/
│   └── results/
│
├── index.html
├── notices.html
├── applications.html
├── exams.html
├── contact.html
├── publications.html
├── style.css
├── test-api.html              (API testing page)
└── README.md                  (This file)
```

---

## STEP 7: Access Your Website

1. Open browser
2. Go to: `http://localhost/egov/`
3. Navigate through different pages

---

## STEP 8: Common Issues and Solutions

### Issue 1: "Database connection failed"
**Solution:**
- Check if MySQL is running in XAMPP
- Verify database name in `config/database.php`
- Default credentials: username=`root`, password=`` (empty)

### Issue 2: "404 Not Found" errors
**Solution:**
- Ensure project is in `C:\xampp\htdocs\egov\`
- Check file paths are correct
- Restart Apache in XAMPP

### Issue 3: "Access denied" error
**Solution:**
- Check MySQL username/password in `config/database.php`
- Default XAMPP credentials: root / (no password)

### Issue 4: File upload not working
**Solution:**
- Check `uploads/` folder exists
- Ensure folder has write permissions
- Check PHP file upload settings in php.ini

---

## STEP 9: Using the API in Your HTML Files

Add this script tag to your HTML files:
```html
<script src="js/api-handler.js"></script>
```

Then use the functions:

```javascript
// Load notices
fetchNotices(1, 10).then(result => {
    if (result.success) {
        console.log(result.data);
        // Display notices in your HTML
    }
});

// Load applications
fetchApplications().then(result => {
    if (result.success) {
        console.log(result.data);
        // Display applications in your HTML
    }
});

// Submit contact form
const formData = {
    fullName: 'राम बहादुर',
    email: 'ram@example.com',
    phone: '9812345678',
    subject: 'परीक्षा सम्बन्धी',
    message: 'परीक्षा कहिले हुन्छ?'
};

submitContactForm(formData).then(result => {
    if (result.success) {
        alert(result.message);
    }
});
```

---

## STEP 10: Next Steps

### Optional Enhancements:

1. **Create Admin Panel**
   - Manage notices, applications, exams
   - View submitted applications
   - Respond to contact messages

2. **Add Authentication**
   - User login/registration
   - Admin authentication
   - Role-based access control

3. **Email Notifications**
   - Send confirmation emails
   - Notify admins of new submissions

4. **File Download Feature**
   - Download application forms
   - View notice PDFs
   - Download results

---

## Database Configuration

If you need to change database credentials:

Edit `config/database.php`:
```php
private $host = "localhost";      // Database host
private $db_name = "egov_db";     // Database name
private $username = "root";        // MySQL username
private $password = "";            // MySQL password (empty for XAMPP default)
```

---

## API Endpoints Reference

| Endpoint | Method | Description |
|----------|--------|-------------|
| `api/notices.php` | GET | Get all notices (with pagination) |
| `api/applications.php` | GET | Get open job applications |
| `api/submit_application.php` | POST | Submit job application |
| `api/contact.php` | POST | Submit contact message |
| `api/exams.php` | GET | Get exam schedules |
| `api/results.php` | GET | Get exam results |
| `api/publications.php` | GET | Get publications |

---

## Support

For issues or questions:
1. Check XAMPP Apache and MySQL logs
2. View browser console for JavaScript errors
3. Check PHP error logs in `C:\xampp\php\logs\`

---

## Security Notes

**For Production Deployment:**
1. Change database password
2. Add input validation and sanitization
3. Implement CSRF protection
4. Use HTTPS
5. Add file upload validation
6. Implement rate limiting
7. Add user authentication

---

**Last Updated:** January 6, 2026
