# Emirhan Demircan - Full-Stack Web Portfolio

## Project Report

This portfolio is designed as a professional career asset and final project submission. It showcases selected work while demonstrating the required semester technologies: HTML5, CSS3, JavaScript/DOM, PHP, MySQL, AJAX, sessions, and cookies.

## Implemented Features

- **Semantic HTML5:** Header, navigation, main sections, article cards, contact form, and a technical requirements table.
- **Advanced CSS:** Responsive layout with CSS Grid/Flexbox, reusable variables, consistent colors, mobile navigation, and light/dark theme support.
- **JavaScript/DOM:** Mobile menu, project category filters, theme toggle persisted with local storage/cookies, AJAX project loading, and contact form validation.
- **PHP/MySQL:**
  - `api/projects.php` returns active portfolio projects from MySQL as JSON.
  - `api/contact.php` validates and stores contact messages in MySQL.
  - `admin/login.php`, `admin/dashboard.php`, and `admin/logout.php` provide a session-based admin dashboard.
- **AJAX Integration:** The homepage loads project cards with `fetch()` and submits contact messages without refreshing the page.
- **State Management:** Admin authentication uses PHP sessions; theme/admin helper state uses cookies.

## Folder Structure

```text
personel-page/
├── index.html
├── README.md
├── admin/
│   ├── admin.css
│   ├── auth.php
│   ├── dashboard.php
│   ├── login.php
│   └── logout.php
├── api/
│   ├── contact.php
│   └── projects.php
├── assets/
│   ├── css/style.css
│   ├── images/
│   └── js/script.js
├── config/database.php
└── database/portfolio.sql
```

## Local Setup

1. Copy the `personel-page` folder into a PHP-enabled server directory such as XAMPP `htdocs` or a free PHP host.
2. Create/import the database by running `database/portfolio.sql` in phpMyAdmin or MySQL CLI.
3. Update database credentials with environment variables if needed:
   - `PORTFOLIO_DB_HOST`
   - `PORTFOLIO_DB_PORT`
   - `PORTFOLIO_DB_NAME`
   - `PORTFOLIO_DB_USER`
   - `PORTFOLIO_DB_PASS`
4. Open `index.html` in the PHP-enabled host URL.
5. Admin dashboard: `admin/login.php`
   - Username: `admin`
   - Password: `admin123`
   - Change this password before publishing.

## Submission Checklist

- Source code ZIP: include the complete `personel-page` folder.
- Project report: this `README.md` file can be submitted as the brief report.
- SQL export: include `database/portfolio.sql` in the ZIP.
- GitHub repository: add the public repository link before LMS submission.
- Live demo: deploy to a PHP/MySQL host and add the working URL before submission.

## Notes

If the MySQL database is not configured yet, the homepage displays fallback project cards so the design remains reviewable. The dynamic requirement is fulfilled when `database/portfolio.sql` is imported and the PHP API can connect to MySQL.
