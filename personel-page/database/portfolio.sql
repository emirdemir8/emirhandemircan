-- Full-Stack Web Portfolio database export
-- Import this file before testing the PHP/MySQL features.

CREATE DATABASE IF NOT EXISTS portfolio_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portfolio_db;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS projects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(160) NOT NULL,
  category ENUM('frontend', 'javascript', 'fullstack') NOT NULL DEFAULT 'frontend',
  summary TEXT NOT NULL,
  technologies VARCHAR(255) NOT NULL,
  project_url VARCHAR(255) NOT NULL DEFAULT '#',
  display_order INT NOT NULL DEFAULT 0,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_projects_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contacts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL,
  message TEXT NOT NULL,
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default local review credentials: username admin, password admin123.
-- Change this password before publishing the live demo.
INSERT INTO admins (username, password_hash, is_active)
VALUES ('admin', '$2y$12$2gySG9Vv5p7EuH/UG3PgHeP6WFuYKIGTwx.LXBextSx50aqaG6Yie', 1)
ON DUPLICATE KEY UPDATE username = VALUES(username);

INSERT INTO projects (title, category, summary, technologies, project_url, display_order, is_featured, is_active) VALUES
('Clipboard Landing Page', 'frontend', 'Responsive landing page built with semantic HTML5, reusable CSS sections, and mobile-first layout rules.', 'HTML5, CSS3, Flexbox, Responsive Design', '../Landing%20Page/index.html', 1, 1, 1),
('News Homepage', 'javascript', 'Interactive news homepage that demonstrates DOM-based mobile navigation and layout behavior.', 'HTML5, CSS3, JavaScript, DOM', '../media/news%20page/index.html', 2, 1, 1),
('Full-Stack Portfolio Admin', 'fullstack', 'Database-backed portfolio management area with PHP sessions, cookies, project editing, and saved contact messages.', 'PHP, MySQL, Sessions, Cookies, AJAX', './admin/login.php', 3, 1, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);
