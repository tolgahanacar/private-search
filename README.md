# Private Search System with PHP

This is a modern, secure, and clean search & details template built in PHP, styled using the retro-aesthetic **PaperCSS** framework. It has been fully updated for **PHP 8.5+** compatibility and absolute security.

---

## 📂 Project Structure

All files have been renamed to English to maintain high code standardization:

```text
private-search/
├── config/
│   ├── connect.php        # Strict-typed database PDO connection setup
│   └── functions.php      # Security filters and secure term highlighting helper
├── config.php             # (Optional) Configuration constants
├── root.php               # Common bootstrapping and autoload file
├── index.php              # Entrypoint (redirects to search page)
├── search.php             # Main search query layout with PaperCSS alerts
├── detail.php             # Details view with highlighting and dynamic content suggestions
├── request.rest           # Rest client file for manual API testing
└── README.md              # Documentation
```

---

## ✨ Features

- **PHP 8.5+ Compatible**: Implements `declare(strict_types=1);` and modern variable validation.
- **Strict Database Security**: Utilizes PDO prepared statements with strict bindings to prevent SQL Injection.
- **XSS & HTML Injection Mitigations**: Sanitization helpers clean inputs, and highlighting functions safely escape HTML before rendering highlight blocks.
- **Case-Preserving Search Highlighting**: Regex-based term highlighting maintains original string casing.
- **Related Recommendations**: Displays posts with similar content excluding the active ID.
- **SEO & OG Ready**: Automatically generates meta description and OpenGraph tags with character limits (max 155).

---

## 🚀 Installation & Setup

### 1. Database Setup

Create a MySQL database (e.g. `ara`) and run the following schema definition:

```sql
CREATE DATABASE IF NOT EXISTS `ara` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ara`;

CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO `posts` (`title`, `content`) VALUES
('Introduction to PHP 8.5', 'PHP 8.5 introduces awesome new features, strict types, and execution speed improvements.'),
('Securing PHP Applications', 'Preventing XSS and SQL injection requires parameterized queries and escaping inputs.'),
('PaperCSS Guide', 'PaperCSS is a cute retro-style CSS framework that resembles handwriting on paper.');
```

### 2. Configure Database Connection

Open `config/connect.php` and set your local DB name:
```php
$dbName = 'ara'; // Change to match your local database name
```

### 3. Run Locally

You can serve this directory with a PHP local development server:
```bash
php -S localhost:8000
```
Then navigate to `http://localhost:8000` in your browser.
