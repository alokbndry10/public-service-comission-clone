# E-Gov Project AI Coding Instructions

## Project Overview
This is a **Nepali government e-services portal** for a Public Service Commission (लोक सेवा आयोग). It's a XAMPP-based web application with PHP backend, vanilla JavaScript frontend, and MySQL database. All user-facing content is in **Nepali (Devanagari script)**.

## Architecture & Data Flow

### Stack
- **Frontend**: Static HTML pages with Bootstrap 5, Nepali fonts (Noto Sans Devanagari), vanilla JavaScript
- **Backend**: PHP 7+ APIs returning JSON (no framework, pure PHP)
- **Database**: MySQL via PDO with prepared statements
- **Deployment**: XAMPP local server (Apache + MySQL)

### Core Components
1. **config/database.php** - Singleton Database class, all APIs use `include_once '../config/database.php'`
2. **api/** folder - REST-like JSON endpoints (`notices.php`, `applications.php`, `submit_application.php`, etc.)
3. **js/api-handler.js** - Shared frontend API client functions using `fetch()`
4. **uploads/applications/** - User-uploaded files (PDF/Word documents only)

### Data Flow Pattern
```
HTML page → js/api-handler.js function → api/{endpoint}.php → config/database.php → MySQL
```

Example: Loading notices
1. `notices.html` calls `fetchNotices()` from `api-handler.js`
2. Fetches `api/notices.php?page=1&limit=10`
3. PHP uses `Database` class to query `notices` table
4. Returns JSON with `{success: true, data: [...], pagination: {...}}`

## Database Schema

**Key tables** (see `database-setup.sql`):
- `notices` - सूचनाहरू with category ENUM ('विज्ञापन', 'परीक्षा तालिका', 'नतिजा', 'सूचना')
- `applications` - Job postings with status ENUM ('खुला', 'समाप्त')
- `user_applications` - Submitted applications (FK to `applications.id`)
- `exam_schedules` - Exam dates and times
- `results` - Published exam results with file paths
- `contact_messages` - Contact form submissions
- `publications` - Documents and publications

All tables use `utf8mb4_unicode_ci` for Nepali text support.

## Critical Conventions

### PHP API Pattern
Every API file follows this structure:
```php
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Query with PDO prepared statements
    $stmt = $db->prepare("SELECT ... WHERE category = :category");
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
```

**NEVER** use raw SQL or `mysqli`. Always use PDO with prepared statements via `Database::getConnection()`.

### File Upload Pattern (see `submit_application.php`)
1. Validate file type: Only `application/pdf`, `application/msword`, `application/vnd.openxmlformats-officedocument.wordprocessingml.document`
2. Validate size: Max 5MB
3. Store in `../uploads/applications/` with unique filename: `uniqid() . '_' . time() . '.ext'`
4. Error messages in Nepali: "फाइल साइज ५ MB भन्दा कम हुनुपर्छ"

### Frontend JavaScript Pattern
All API calls use async/await with try-catch:
```javascript
async function fetchNotices(page = 1, limit = 10, category = '') {
    try {
        const response = await fetch(`${API_BASE_URL}notices.php?page=${page}&limit=${limit}`);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error fetching notices:', error);
        return { success: false, message: 'Failed to fetch notices' };
    }
}
```

### Pagination Standard
APIs support `?page=1&limit=10` and return:
```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "page": 1,
    "limit": 10,
    "total": 42,
    "totalPages": 5
  }
}
```

### Nepali Language Requirements
- **All** user messages must be in Nepali (error messages, success messages, labels)
- HTML uses `lang="ne"` attribute
- Database stores Nepali text in VARCHAR/TEXT fields with `utf8mb4_unicode_ci`
- Example messages:
  - Success: "आवेदन सफलतापूर्वक पेश गरियो"
  - Error: "सबै आवश्यक फिल्डहरू भर्नुहोस्"

## Development Workflow

### Local Testing
1. Start XAMPP (Apache + MySQL)
2. Access via `http://localhost/egov/`
3. Use `test/test-api.html` to verify API endpoints
4. Check `uploads/applications/` directory exists with write permissions

### Database Changes
1. Update `database-setup.sql` with new schema
2. Run SQL in phpMyAdmin (`http://localhost/phpmyadmin`)
3. Never use migrations - manual SQL execution only

### Adding New API Endpoint
1. Create `api/new_endpoint.php` following the PHP API pattern above
2. Add corresponding function in `js/api-handler.js`
3. Return JSON with `{success: bool, message: string, data: object}`
4. Test with `test/test-api.html` by adding a test button

## Common Pitfalls

❌ **Don't** use relative paths in APIs - always `../config/database.php`, `../uploads/`  
❌ **Don't** mix English and Nepali in user-facing text  
❌ **Don't** use `GET` method for data modifications - use `POST` with proper headers  
❌ **Don't** forget CORS headers in APIs (`Access-Control-Allow-Origin: *`)  
❌ **Don't** hardcode database credentials elsewhere - only in `config/database.php`

✅ **Do** use PDO prepared statements with proper param binding  
✅ **Do** validate all user input (file types, sizes, required fields)  
✅ **Do** return consistent JSON structure `{success, message, data}`  
✅ **Do** use Bootstrap 5 classes for styling (already included)  
✅ **Do** handle file uploads in `uploads/applications/` with unique filenames

## Key Files Reference

- [config/database.php](config/database.php) - Database connection class (credentials: localhost, egov_db, root, no password)
- [api/notices.php](api/notices.php) - Pagination + filtering example
- [api/submit_application.php](api/submit_application.php) - File upload + form validation example
- [js/api-handler.js](js/api-handler.js) - Frontend API client library
- [database-setup.sql](database-setup.sql) - Complete schema with indexes
- [ARCHITECTURE.txt](ARCHITECTURE.txt) - Visual architecture diagram
