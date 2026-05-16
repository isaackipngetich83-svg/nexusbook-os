NexusBook OS - Full-Stack Bookstore Dashboard
A clean, responsive web dashboard built for bookstore administrators to manage their daily business, track live inventory metrics, and securely process database updates.

 Key Features
Secure Admin Login: Uses PHP server-side sessions (session_start()) to block unauthorized visitors and protect the management panel.

Live Database Integration: Pulls real-time catalog data and customer information from a MariaDB backend database straight into the user interface.

Built-in Security: Shields the application from common web attacks by sanitizing all user inputs via driver escape methods (real_escape_string()).

Smart Inventory Management: Automatically calculates updates for book stock levels whenever a new sale or transaction is logged.

 Database Structure
The application relies on a structured database with three main tables working together:

Customers: Stores essential profile details and contact emails.

Books: Keeps track of titles, prices, and available stock levels.

Orders: Serves as a permanent history log for tracking all completed transactions.

 Built With
Frontend: Clean HTML5, layout design using CSS Flexbox and Grid, and icons from FontAwesome.

Backend: PHP 8 for processing server-side logic and database connectivity.

Database: MySQL / MariaDB managed through a local XAMPP environment node.
