# Emirhan Demircan - Full-Stack Web Portfolio

## Project Report

This portfolio is designed as a professional career asset and final project submission. It showcases selected work while demonstrating the required semester technologies: HTML5, CSS3, JavaScript/DOM, PHP, MySQL, AJAX, sessions, and cookies.

The main goal of this project is to create a dynamic and responsive personal portfolio website that can be used to present my skills, projects, and contact information to potential employers. The website includes a modern frontend interface, a MySQL-backed project system, a contact form, and a session-based admin dashboard.

## Project Overview

This project is a full-stack personal portfolio website developed to present my academic and technical work in a professional way. The website includes an about section, dynamic project cards, a skills section, a contact form, and an admin dashboard for managing portfolio content.

The frontend focuses on responsive design and user experience. The backend focuses on database connection, contact message management, project management, and secure admin login.

## Implemented Features

- **Semantic HTML5:** Header, navigation, main sections, article cards, contact form, and structured content.
- **Advanced CSS:** Responsive layout with CSS Grid/Flexbox, reusable variables, consistent colors, mobile navigation, and light/dark theme support.
- **JavaScript/DOM:** Mobile menu, project category filters, theme toggle persisted with local storage/cookies, AJAX project loading, and contact form validation.
- **PHP/MySQL:**
  - `api/projects.php` returns active portfolio projects from MySQL as JSON.
  - `api/contact.php` validates and stores contact messages in MySQL.
  - `admin/login.php`, `admin/dashboard.php`, and `admin/logout.php` provide a session-based admin dashboard.
- **AJAX Integration:** The homepage loads project cards with `fetch()` and submits contact messages without refreshing the page.
- **State Management:** Admin authentication uses PHP sessions; theme preference is stored using browser storage/cookies.

## Technologies Used

- **HTML5:** Used for semantic page structure, navigation, forms, and content sections.
- **CSS3:** Used for responsive design, layout styling, color system, spacing, and mobile-friendly pages.
- **JavaScript:** Used for DOM manipulation, mobile navigation, dark/light theme toggle, project filtering, AJAX requests, and client-side form validation.
- **PHP:** Used for server-side form handling, database connection, project API, contact API, and admin authentication.
- **MySQL:** Used to store project data, admin users, and contact messages.
- **AJAX / Fetch API:** Used to load project data and submit contact messages without refreshing the page.
- **Sessions and Cookies:** Used for admin login persistence and basic state management.

## How I Built the Project

I started by designing the frontend structure with semantic HTML sections such as header, about, projects, skills, and contact. After that, I created a responsive layout using CSS Grid and Flexbox so the website works properly on desktop and mobile screens.

Next, I added JavaScript features to make the interface interactive. The navigation menu works on mobile devices, the theme toggle saves the user's preference, and the projects section loads data dynamically using the Fetch API. The contact form is validated on the client side before being submitted.

For the backend, I created PHP API files for projects and contact messages. The projects API reads project records from the MySQL database and returns them as JSON. The contact API receives form submissions, validates the data again on the server side, and saves messages into the database.

I also developed a basic admin dashboard protected with PHP sessions. After logging in, the admin can view contact messages and manage portfolio project records. The SQL export file is included in the project folder so the database can be recreated easily.

## Database Structure

The database contains three main tables:

- **admins:** Stores admin login information.
- **projects:** Stores portfolio project details such as title, category, technologies, and project URL.
- **contacts:** Stores messages submitted from the contact form.

The database export file is located at:

```text
database/portfolio.sql
```

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
3. Update database credentials in `config/database.php` if needed:
   - `PORTFOLIO_DB_HOST`
   - `PORTFOLIO_DB_PORT`
   - `PORTFOLIO_DB_NAME`
   - `PORTFOLIO_DB_USER`
   - `PORTFOLIO_DB_PASS`
4. Open the project through a PHP-enabled URL, for example:
   ```text
   http://localhost/personel-page/index.html
   ```
5. Admin dashboard:
   ```text
   http://localhost/personel-page/admin/login.php
   ```
   Default login:
   - Username: `admin`
   - Password: `admin123`

> Important: The default admin password should be changed before using the project in a real production environment.

## Live Demo and Repository

- **Live Demo:** https://emirhandemircan.gt.tc/
- **GitHub Repository:** https://github.com/emirdemir8/emirhandemircan

## Delivery Package

The final submission includes:

- Source code ZIP containing the complete `personel-page` folder.
- Project report through this `README.md` file.
- SQL export file located in `database/portfolio.sql`.
- Public GitHub repository link.
- Live demo link.

## Notes

If the MySQL database is not configured yet, the homepage displays fallback project cards so the design remains reviewable. The dynamic requirement is fulfilled when `database/portfolio.sql` is imported and the PHP API can connect to MySQL.

## Conclusion

This portfolio project demonstrates the full development process of a dynamic web application. It combines frontend design, client-side interactivity, backend programming, database management, AJAX communication, and admin authentication.

The project helped me practice how different web technologies work together in a real full-stack application and how a portfolio can be used as a professional digital identity.
