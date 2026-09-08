# Inventory Management

A PHP and MySQL web application for managing campus IT inventory. The interface supports Downcity and Harborside inventory, equipment model counts, individually tagged open equipment, toner supplies, and staff reporting. Registration uses an existing campus worker roster and an `@jwu.edu` email restriction.

## Project status

This repository contains application source, but is **not a complete, runnable installation by itself**. Database connection files and database schema definitions are absent from the reviewed working tree. 

## Features represented in the code

- Add and remove equipment models and adjust quantities by campus.
- Track laptops, desktops, monitors, Macs, printers, peripherals/consumables, and audio/visual equipment.
- Add and remove toner records and allocate reusable sticker IDs.
- Track open equipment by asset tag, model, location, campus, and staff name.
- Search and sort inventory tables.
- View staff charts, stock totals, and recent retrieval records.
- Print a barcode for the latest toner sticker using an external barcode service.
- Switch display themes and navigate to neighboring STS and scheduling applications.

These describe implemented UI and endpoint flows, not a claim that every flow has passed integration testing. Loaner laptop views are referenced by query endpoints, but a complete loan-management workflow is not included.

## Technology and architecture

The application uses plain PHP, MySQL, HTML, CSS, and browser JavaScript. There is no framework, package manifest, or build pipeline in this checkout. Authentication uses MySQLi; inventory queries use PDO. Pages load Bootstrap 4.5.2, jQuery slim 3.5.1, Popper, Font Awesome, and Google Fonts from CDNs. The administration page also loads Chart.js.

Browser forms send requests through `fetch()` to PHP endpoints. Those endpoints query database tables, views, and stored procedures and return JSON, HTML, or plain text. The browser builds inventory tables from the responses. PHP sessions store login details, while the current page navigation checks browser `sessionStorage`; this client-side check does not enforce server authorization.

## Repository layout

| Path | Purpose |
| --- | --- |
| `index.html` | Login and registration interface |
| `login.php` | Password verification and PHP session creation |
| `register.php` | Worker registration and missing-email update flow |
| `main.php` | Primary inventory dashboard, forms, search, and printing |
| `admin_page.php` | Staff reporting, charts, and model management |
| `main_test.php` | Alternate dashboard source; not an automated test suite |
| `style.css` | Shared layout, themes, responsive rules, and print styles |
| `query/expand.php` | Retrieve supported database views and sorting |
| `query/searchTest.php` | Search results rendered as HTML |
| `query/ModelAR.php` | Add/remove equipment models and associated stock records |
| `query/QuantityChange.php` | Adjust stock and record equipment retrievals |
| `query/openEQ.php` | Add/remove individually tagged open equipment |
| `query/tonerAdd.php`, `query/tonerRetrieval.php` | Add/remove toner and update sticker availability |
| `query/modelDropdown.php` | Model options from a stored procedure |
| `query/chartData.php`, `query/openCount.php` | Chart and open-equipment summaries |
| `query/recentSticker.php` | Latest toner sticker ID for printing |
| `templates/` | Logo images, plus generated NAS thumbnail directories |

## Requirements and missing dependencies

- PHP 8.0 or newer is required by the use of `str_ends_with()`; use a maintained PHP release and verify compatibility. Enable `pdo_mysql`, `mysqli`, sessions, and mysqlnd support for `mysqli_stmt::get_result()`.
- A MySQL database with the original tables, views, stored procedures, constraints, and seed data. The supported MySQL version has not been established by this checkout.
- A PHP-capable web server and a modern browser. CDN styling/scripts and remote barcode generation require internet access.
- Private connection configuration defining `$servername`, `$username`, `$password`, and `$dbname`.

The current source refers to different connection paths:

- `login.php` and `register.php`: root `db_connection.php`.
- Inventory mutation endpoints and `recentSticker.php`: root `db_conn.php` via `../db_conn.php`.
- Several read endpoints: `db_conn.php` without an explicit parent directory.

All of these configuration files are missing from the reviewed working tree. Standardize inventory imports to an explicit path such as `require_once __DIR__ . '/../db_conn.php';` before relying on a root configuration file. Account data may use a separate database, so confirm that before consolidating connections. Keep actual credentials outside Git and add sanitized configuration examples.

