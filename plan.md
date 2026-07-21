# Sumeya Library - Future Roadmap & Development Plan

Now that we are fixing the core bugs like the database connection mismatch, here are some strategic plans to modernize and scale the library system in the future.

## 1. Security Enhancements
*   **CSRF Protection:** Add Cross-Site Request Forgery tokens to all forms (especially login and book modifications) to prevent malicious cross-site submissions.
*   **Rate Limiting:** Implement brute-force protection on `auth/login.php` to lock out IPs after 5 failed login attempts.
*   **Session Security:** Enforce secure cookies (HttpOnly, Secure flags) and implement session timeouts to automatically log idle librarians out.

## 2. Architecture & Code Quality
*   **Centralized Database Singleton:** Instead of recreating the `$conn` object in every script, wrap the PDO connection in a Database Class using the Singleton pattern so the connection is reused efficiently.
*   **Separation of Concerns:** Move SQL queries into separate Model classes (e.g., `UserModel`, `BookModel`) rather than keeping SQL strings directly inside the views (`login.php`). 
*   **Templating Engine:** Eventually migrate from raw PHP `include` files for headers/footers to a lightweight templating engine like Twig or Blade for cleaner HTML.

## 3. Feature Additions
*   **Role-Based Access Control (RBAC):** Introduce a roles table. A 'Super Admin' can add/remove librarians, while a 'Librarian' can only issue and return books.
*   **Automated Email Notifications:** Set up a cron job script that checks the `borrow` table daily and emails users who have overdue books.
*   **Fines & Payment Integration:** Add a fines module that calculates late fees automatically based on the return date, and eventually integrate an API (like Stripe or MPesa) for digital fine payment.
*   **Barcode/QR Scanner Support:** Add an endpoint and UI interface that allows librarians to click an input field and scan a book's ISBN barcode to auto-fill the book checkout form.

## 4. UI / UX Improvements
*   **Ajax Form Submissions:** Convert forms (like adding a book or borrowing) to use Fetch API/Ajax so the page doesn't have to reload on every action.
*   **Dashboard Analytics:** Add Chart.js to `dashboard/index.php` to visually show the most borrowed books, active members, and monthly activity.
