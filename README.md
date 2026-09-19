# CRUD User

Simple PHP + MySQL admin app for managing Users, Departments, and Roles. A Role
controls which pages a logged-in user is allowed to see/access.

## Stack

- Plain PHP (PDO, no framework) — one file per page, no build step.
- MySQL.
- Deployed on Railway (Nixpacks auto-detects PHP from `composer.json`).

## Local structure

```
index.php          redirects to login/dashboard
login.php / logout.php
dashboard.php
reports.php, tasks.php, invoices.php, ...   10 sample pages (see "Sample pages" below)
users.php           Users CRUD (assign department + role)
departments.php      Departments CRUD
roles.php            Roles CRUD + page checkboxes
includes/            config.php (db connection), auth.php, functions.php, header/footer.php, sample.php
assets/style.css
sql/schema.sql       run this once against your database
sql/add_sample_pages.sql   registers the sample pages on a database that already has the schema
bin/seed_admin.php   CLI script to create the first login
```

## Deploying to Railway

1. **Push this repo to GitHub**, then in Railway: New Project → Deploy from GitHub repo → pick `crud-user`.
2. **Add a MySQL database**: in the same Railway project, "New" → "Database" → "Add MySQL". Railway automatically
   injects `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE` (and `MYSQL_URL`) into your PHP
   service's environment — `includes/config.php` reads these automatically, so no manual DB config is needed.
3. **Run the schema** once the MySQL service is up. Easiest options:
   - Railway dashboard → MySQL service → "Data" tab → Query, paste the contents of `sql/schema.sql`.
   - Or with the Railway CLI: `railway connect mysql < sql/schema.sql` (from the project root, linked to this project).
4. **Create your first login** (also via Railway CLI, run against the deployed service):
   ```
   railway run php bin/seed_admin.php you@example.com "YourPassword123!" "Your Name"
   ```
5. Open the deployed domain — you should land on `login.php`.

## Roles & pages

`pages` is a small lookup table (`page_key`, `label`) seeded with `dashboard`, `users`, `departments`, `roles`.
A Role is linked to pages via `role_pages`. When editing a Role, checking a page grants everyone with that role
access to it (both the nav link and the page itself — `requirePage('key')` at the top of each page file enforces
this server-side, not just in the nav).

To add a new page later:
1. Insert a row into `pages` (`page_key`, `label`).
2. Add the corresponding entry to the `$navItems` array in `includes/header.php`.
3. Call `requirePage('your_page_key');` at the top of the new PHP file.
4. Grant it to the relevant roles from the Roles screen.

## Sample pages

`reports`, `announcements`, `tasks`, `attendance`, `leave_requests`, `inventory`, `customers`, `invoices`
and `audit_log` are boilerplate pages with hard-coded example data, rendered by `renderSamplePage()`
in `includes/sample.php`. Each one is gated by `requirePage()` like the rest. To turn one into a real page,
replace the arrays in its file with database queries (or write your own markup and drop the helper).
Permissions are cached in the session at login, so log out and back in after granting a new page.

## Settings page

`settings.php` is static: every signed-in user sees it in the sidebar and can open it, regardless of role. It is not
in the `pages` table and has no `requirePage()` call. It currently holds Change Password (current password required,
new password min. 8 characters).

## Local development

PHP/MySQL aren't installed on this machine, so this project was built and will be verified by deploying to Railway
directly. If you'd like to run it locally instead, install PHP 8.1+ and MySQL, create a local database, set
`DB_HOST` / `DB_PORT` / `DB_USER` / `DB_PASSWORD` / `DB_NAME` env vars (or edit the fallbacks in
`includes/config.php`), run `sql/schema.sql`, then `php -S localhost:8000`.
