# Inventory Management

A web application for organizing campus IT equipment and supplies, built with PHP, MySQL, and JavaScript.

## About the project

I built this project over two years to bring campus IT inventory into one interface. It brings together equipment counts, asset tags, toner supplies, and reporting so users can see what is available and manage inventory across Downcity and Harborside.

The project covers both sides of a full-stack application: the pages and forms users interact with, and the PHP endpoints and database queries that support them. Its scope includes everyday inventory tasks, such as adding equipment and adjusting quantities, alongside staff dashboards and barcode printing.

## Features

- **Campus inventory:** Organize equipment and quantities across Downcity and Harborside.
- **Equipment tracking:** Manage laptops, desktops, monitors, Macs, printers, peripherals, and audio/visual equipment.
- **Asset records:** Track individual open equipment by asset tag, model, campus, and location.
- **Toner management:** Add and remove toner supplies, assign sticker IDs, and print barcodes.
- **Search and sorting:** Find equipment and supplies within inventory tables.
- **Staff reporting:** View inventory charts, totals, and recent equipment retrievals.
- **User accounts:** Login and registration connected to a campus worker roster.
- **Interface options:** Responsive layouts and switchable display themes.

## Built with

| Technology | Role |
| --- | --- |
| PHP | Backend request handling and account flows |
| MySQL | Inventory records, database views, and stored procedures |
| JavaScript | Interactive forms, search, and data loading through the Fetch API |
| HTML and CSS | Page structure, custom styling, responsive layouts, and themes |
| Bootstrap | Interface components and styling |
| Chart.js | Inventory charts on the staff dashboard |

## Inside the codebase

- `index.html`, `login.php`, and `register.php` handle the account interface and login/registration requests.
- `main.php` contains the primary inventory dashboard.
- `admin_page.php` provides staff reporting and model management.
- `query/` contains the PHP endpoints for inventory changes, searches, and reporting.
- `style.css` defines the shared appearance and responsive layouts.
- `templates/` contains logo assets.
- `main_test.php` contains an alternate dashboard page used during development.

## Development

This repository represents two years of work on a campus inventory application. It brings together frontend design, backend development, relational data, and workflows for managing equipment and supplies in one project.

## Running the project

The application requires a PHP-capable server and a MySQL database. Database connection files and schema definitions are not included in this repository, so additional configuration is required to run it locally.
