# CRM System Development README

## Overview

This README provides a comprehensive guide to developing a secure, scalable CRM (Customer Relationship Management) system for a single company, strictly adhering to the provided database schema (`singl_company_schema.txt`) and detailed documentation (`singl_schema_readme.md`). The system supports user roles (Admin, Seller, Auditor), client management, product inventory, quote generation with renewals and stock updates, reporting, backups, and compliance features. It is built using PHP for backend, HTML/JavaScript for frontend, Bootstrap via CDN for styling, and Composer-managed dependencies (e.g., PHPMailer for notifications, Dompdf for PDF generation, phpdotenv for .env handling, PHPUnit for testing).

### Development Process
The development follows a modular, iterative approach:
1. **Setup Environment**: Install PHP 8.2+, MySQL 8.0+, Composer. Execute the provided schema.sql to initialize the `crm_db` database.
2. **Strict Adherence to Database Structure**: All features must map directly to the schema. Use views (e.g., `vw_clients`, `vw_quotes`) for data reads to abstract and secure access. Direct queries (prepared statements only) for inserts/updates/deletes. Leverage triggers for auditing (`audit_logs`), stock updates, and activities. Partitioning (e.g., monthly for `audit_logs`) and materialized views (e.g., `materialized_sales_performance`) ensure scalability for 1000 concurrent users.
3. **Module-by-Module Development**: Implement one module at a time (as outlined in the Roadmap). Complete coding, integration with DB, security hardening, and testing before proceeding. This ensures independence: a failure in one module (e.g., quotes) doesn't affect others (e.g., auth).
4. **Testing**: Unit/integration tests with PHPUnit per module. Manual security audits (e.g., simulate SQLi/XSS). Stress test for concurrency.
5. **Deployment Integration**: Use .env for credentials. Dynamic loading via `public/index.php` (e.g., `?module=users&action=list`) for scalability. Cache views like `vw_settings` in Redis if needed.
6. **Key Principles**: Code in small, refactorized files. No monolithic files—include/require as needed. Prioritize existing DB data consumption before new features.

### Database Explanation
The database (`crm_db`) is designed for a single-company CRM, normalized to 3NF for data integrity and no redundancy. It includes:
- **Tables**: `roles`, `permissions`, `role_permissions` for RBAC; `users` for accounts (with encryption, lockouts); `clients` (soft deletes, TDE for sensitive data); `product_categories`, `products` for inventory; `quotes`, `quote_items` (partitioned by year, triggers for stock/activities); `audit_logs` (partitioned monthly, comprehensive tracking); `settings` (global configs, encrypted); `access_requests`; `client_activities` (partitioned); `backup_requests`.
- **Views**: Abstractions like `vw_clients`, `vw_quotes`, `vw_sales_performance` (some materialized for performance). Role-restricted (e.g., `vw_audit_logs` anonymized for auditors).
- **Triggers**: Automatic logging to `audit_logs`, stock updates on quote approval, client activity tracking.
- **Security Features**: TDE for sensitive columns, RLS for views, RBAC enforcement, three-strike lockouts, encrypted passwords (bcrypt), soft deletes.
- **Compliance & Resilience**: Aligns with ISO 27001 and NIST CSF. Partitioning, indexing, Group Replication for HA, daily backups via cron monitoring `backup_requests`.
- **Adaptation in Project**: The app must use PDO prepared statements exclusively. Reads via views for modularity/security. Writes trigger-compliant. Multi-language from `users.language`, notifications from `settings` SMTP. No deviations—e.g., quote renewals must reference `parent_quote_id`, stock checks via triggers.

The project strictly adapts to this: e.g., user auth queries `users` for login but uses views for profiles; quote creation inserts into `quotes`/`quote_items` and relies on triggers for side effects.

## Roadmap

This roadmap outlines the development sequence, with each module's steps, DB relations, and security emphasis. Proceed module-by-module: implement, test (PHPUnit, security scans), consume existing DB data, then advance.

### Prerequisites
- **Tech Stack Setup**:
  - PHP 8.2+ with PDO extension.
  - MySQL 8.0+ with the provided schema executed.
  - Composer: Install dependencies (`composer require phpmailer/phpmailer dompdf/dompdf vlucas/phpdotenv phpunit/phpunit`).
  - Bootstrap CDN: Include in HTML headers.
  - .env File: Store DB credentials (e.g., `DB_HOST=localhost`, `DB_USER=crm_user`, `DB_PASS=secure_password`, `DB_NAME=crm_db`).
  - Project Structure: As detailed in the Project Structure section below.
- **Global Security Practices**:
  - Use PDO with prepared statements for all queries to prevent SQLi.
  - Sanitize inputs: `htmlspecialchars()` for outputs (anti-XSS), `filter_var()` for validation.
  - RBAC: Check permissions via `role_permissions` table before actions (in `core/rbac.php`).
  - Session Security: HTTPS enforcement, secure cookies, CSRF tokens (in `core/security.php`).
  - Anti-LFI: Restrict file paths (e.g., uploads to designated dirs, validate with realpath()).
  - Error Handling: No direct error display; log to files.
  - Testing: Simulate attacks (e.g., SQLi/XSS attempts via OWASP ZAP), ensure views are used for reads.

### Module 1: Setup and Configuration
#### Description
Initialize the project, set up DB connection, and basic framework. This module handles global settings and ensures the system can read from `settings` table.

#### Development Steps
1. Create `.env` template and loader (`config/app.php` using phpdotenv).
2. Implement DB connection singleton (`config/db.php`): Use PDO, enable TDE/encryption as per schema.
3. Create shared helpers: `core/security.php` (sanitization, CSRF), `core/rbac.php` (permission checks), `core/helpers.php` (util functions).
4. Basic `public/index.php` to load modules dynamically (e.g., via GET param: `?module=users&action=list`).
5. Test: Connect to DB, query `vw_settings` (if admin), ensure no direct queries exposed.

#### DB Relations
- **Reads**: Use `vw_settings` view for global configs (e.g., timezone, available_languages). Prepared query: `SELECT * FROM vw_settings` with role check via session.
- **Writes**: Insert/update `settings` table only for admins (permission: manage_settings). Trigger logs to `audit_logs`.
- **Security**: RLS enforced in view; prepared statements for inserts (e.g., `INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)`).
- **Consumption**: Load existing settings data on app start; no new features until tested.

**Testing**: Verify connection with 1000 simulated users (stress test), check for SQLi vulnerabilities. Proceed only if stable.

### Module 2: Authentication and Login
#### Description
Handle user login, registration (limited to admins), password resets, and session management. Enforce three-strike lockout and force_password_change.

#### Development Steps
1. Create `/modules/auth/` : Controllers for processing, models for DB, views for forms.
2. JS for form validation (e.g., email checks) in `public/assets/js/auth.js`.
3. HTML views with Bootstrap forms.
4. Integrate PHPMailer for password reset emails (use `settings` for SMTP).
5. Test: Simulate logins, lockouts, resets.

#### DB Relations
- **Reads**: Query `users` table for authentication (e.g., `SELECT password_hash, failed_login_attempts FROM users WHERE username = ?`). Use views for profile display post-login.
- **Writes**: Update `users` for last_login_at, failed_attempts, locked_until (prepared: `UPDATE users SET failed_login_attempts = ? WHERE user_id = ?`).
- **Triggers**: After update, log to `audit_logs`.
- **Security**: Bcrypt verify for passwords; no direct SELECT on password_hash outside auth; anti-brute force via lockout.
- **Consumption**: Use existing users data; test with sample inserts from schema.

**Testing**: Security scans (e.g., OWASP ZAP for XSS/SQLi), ensure RBAC blocks unauthorized access. Proceed only if secure.

### Module 3: User Management
#### Description
Admin-only: Manage users, profiles, roles. Users can edit own profile. Support multi-language from `users.language`.

#### Development Steps
1. `/modules/users/` : Controllers for list/edit, model for queries, views for forms/tables.
2. JS for dynamic language switching in `public/assets/js/users.js`.
3. Profile picture upload: Sanitize paths to prevent LFI.
4. Test: Create/edit users, check language rendering.

#### DB Relations
- **Reads**: Use `users` table directly for lists (with RLS); views for reports.
- **Writes**: INSERT/UPDATE `users` (e.g., `INSERT INTO users (username, email, ...) VALUES (?, ?, ...)`); update role_id linking to `roles`.
- **Triggers**: After insert/update, log to `audit_logs`.
- **Security**: Permission checks (edit_own_profile, reset_user_password); sanitize profile_picture URL with `core/security.php`.
- **Consumption**: Display existing users; integrate with auth module.

**Testing**: Test concurrency (1000 users), RBAC enforcement. Proceed if modular isolation confirmed.

### Module 4: Role and Permission Management
#### Description
Admin-only: Assign roles/permissions, handle access requests.

#### Development Steps
1. `/modules/roles/` : Controllers for roles/assign, model for queries, views for lists/forms.
2. `/modules/access_requests/` : Controllers for requests/review, model, views.
3. JS for dynamic permission selection in `public/assets/js/roles.js`.
4. Test: Request/approve permissions.

#### DB Relations
- **Reads**: `roles`, `permissions`, `role_permissions` tables; `access_requests` for pending.
- **Writes**: INSERT into `role_permissions` and `access_requests` (prepared); UPDATE `access_requests` status.
- **Triggers**: Log changes to `audit_logs`.
- **Security**: Only manage_access_requests permission allows writes; prevent self-escalation via RBAC.
- **Consumption**: Use initial data from schema inserts.

**Testing**: Simulate unauthorized access attempts. Proceed if independent from prior modules.

### Module 5: Client Management
#### Description
Manage clients with soft deletes, tracking activities.

#### Development Steps
1. `/modules/clients/` : Controllers for CRUD, model using `vw_clients`, views for forms/tables.
2. Integrate with activities view.
3. Test: CRUD operations.

#### DB Relations
- **Reads**: `vw_clients` view for lists; `client_activities` for tracking.
- **Writes**: INSERT/UPDATE `clients` (e.g., set deleted_at for deletes); log to `client_activities`.
- **Partitioning**: Handle partitioned `client_activities` transparently via PDO.
- **Security**: Permission: view_clients; sanitize inputs like email/tax_id (TDE protected) with `core/security.php`.
- **Consumption**: Display existing clients.

**Testing**: Test soft deletes, activity logging. Proceed if no impact on other modules.

### Module 6: Product Management
#### Description
Manage products, categories, stock.

#### Development Steps
1. `/modules/products/` : Controllers for categories/products, model using `vw_products`, views.
2. JS for stock warnings in `public/assets/js/products.js`.
3. Test: Low stock notifications via `vw_low_stock_products`.

#### DB Relations
- **Reads**: `vw_products`, `vw_category_summary`, `vw_low_stock_products` views.
- **Writes**: INSERT/UPDATE `product_categories`, `products`.
- **Security**: Sanitize SKU/price; permission checks.
- **Consumption**: Use existing categories/products.

**Testing**: Query optimization checks. Proceed.

### Module 7: Quote Management
#### Description
Create/renew quotes, approve (update stock), generate PDFs.

#### Development Steps
1. `/modules/quotes/` : Controllers for create/list/renew/approve, model using `vw_quotes`, views.
2. Integrate Dompdf for quote PDFs.
3. JS for item calculations in `public/assets/js/quotes.js`.
4. Notifications via PHPMailer for expirations (`vw_expiring_quotes`).
5. Test: Stock updates on approval.

#### DB Relations
- **Reads**: `vw_quotes`, `vw_quote_items`, `vw_expiring_quotes` views.
- **Writes**: INSERT into `quotes`, `quote_items`; UPDATE status/stock (trigger handles stock/client_activities/audit_logs).
- **Partitioning**: Yearly on `quotes.issue_date`.
- **Security**: Permission: create_quotes, renew_quotes; prevent duplicate quotes via trigger; anti-XSS in forms.
- **Consumption**: Use existing quotes.

**Testing**: Simulate approvals, PDF generation. Proceed.

### Module 8: Reporting
#### Description
Various reports using materialized views.

#### Development Steps
1. `/modules/reports/` : Controllers for sub-reports, model for materialized views, views with Chart.js.
2. JS charts in `public/assets/js/reports.js` and `charts.js`.
3. Test: Refresh materialized views via scheduler.

#### DB Relations
- **Reads**: Materialized tables (`materialized_sales_performance`, etc.) and views (`vw_sales_performance`, `vw_audit_logs`, etc.).
- **Writes**: None; scheduler handles updates.
- **Security**: Role-restricted (e.g., view_compliance_reports); anonymized for auditors via view RLS.
- **Consumption**: Pull from existing data.

**Testing**: Performance with large datasets. Proceed.

### Module 9: Settings Management
#### Description
Admin-only: Edit global settings.

#### Development Steps
1. `/modules/settings/` : Controller for edit, model using `vw_settings`, view for form.
2. Test: Update SMTP for notifications.

#### DB Relations
- **Reads**: `vw_settings`.
- **Writes**: UPDATE `settings` (trigger to audit_logs).
- **Security**: manage_settings permission; encrypt sensitive like smtp_password with app logic.
- **Consumption**: Use initial settings.

**Testing**: Config changes. Proceed.

### Module 10: Backup Management
#### Description
Request/view backups.

#### Development Steps
1. `/modules/backups/` : Controllers for list/request, model for `backup_requests`, views.
2. Integrate with external cron (monitor `backup_requests`).
3. Test: Insert requests, update status.

#### DB Relations
- **Reads**: `backup_requests`.
- **Writes**: INSERT/UPDATE `backup_requests` (triggers to audit_logs).
- **Security**: manage_backups permission; secure cron execution.
- **Consumption**: Existing requests.

**Testing**: Simulate backups. Complete system integration.

## Post-Development
- Full Integration: Dynamic module loading in dashboard.
- Optimization: Redis caching for `vw_settings`.
- Deployment: High availability setup.
- Maintenance: Monitor audit_logs for compliance.

## Project Structure (Dir2)

The structure follows MVC (Model-View-Controller) for clarity and scalability. Each module has subdirectories: `controllers/` (business logic, DB calls via PDO), `models/` (DB abstractions, using views for reads), `views/` (HTML/JS presentation). This separation enhances security by isolating concerns—e.g., sanitization in controllers/models prevents XSS in views. Core shared logic in `core/` is reusable. Tests mirror modules for direct correspondence. Public/ is the only web-exposed dir (use .htaccess to protect others). Development is strictly module-by-module: e.g., complete `auth/` (code all files, test, integrate DB) before `users/`.

```
crm-project/
├── .env                     # Credenciales DB, SMTP, etc.
├── composer.json            # Dependencias: phpmailer, dompdf, phpdotenv, phpunit
├── config/
│   ├── db.php               # Conexión PDO singleton, usa .env
│   └── app.php              # Configs generales: rutas, timezone, carga .env
├── core/                    # Lógica base compartida
│   ├── helpers.php          # Funciones reutilizables: sanitizar, CSRF generator
│   ├── rbac.php             # Chequeos de permisos/RBAC
│   └── security.php         # Anti-XSS, anti-LFI, validaciones
├── modules/
│   ├── access_requests/
│   │   ├── controllers/
│   │   │   ├── requests_controller.php  # Lógica para listar requests
│   │   │   └── review_controller.php    # Lógica para revisar
│   │   ├── models/
│   │   │   └── AccessRequestModel.php   # Queries PDO para access_requests table/vista
│   │   └── views/
│   │       ├── requests.php             # HTML para lista
│   │       └── review.php               # HTML para form de review
│   ├── auth/
│   │   ├── controllers/
│   │   │   ├── auth_controller.php      # Procesar login/reset
│   │   │   ├── login_controller.php     # Manejar form login
│   │   │   └── logout_controller.php    # Cerrar sesión
│   │   ├── models/
│   │   │   └── AuthModel.php            # Queries para users (auth)
│   │   └── views/
│   │       ├── login.php                # Form login Bootstrap
│   │       └── reset.php                # Form reset (si aplica)
│   ├── backups/
│   │   ├── controllers/
│   │   │   ├── list_controller.php      # Lógica lista backups
│   │   │   └── request_controller.php   # Solicitar backup
│   │   ├── models/
│   │   │   └── BackupModel.php          # Queries para backup_requests
│   │   └── views/
│   │       ├── list.php                 # HTML lista
│   │       └── request.php              # Form request
│   ├── clients/
│   │   ├── controllers/
│   │   │   ├── add_controller.php       # Agregar client
│   │   │   ├── delete_controller.php    # Soft delete
│   │   │   ├── edit_controller.php      # Editar
│   │   │   └── list_controller.php      # Listar (usa vw_clients)
│   │   ├── models/
│   │   │   └── ClientModel.php          # Queries para clients/vw_clients
│   │   └── views/
│   │       ├── add.php                  # Form add
│   │       ├── edit.php                 # Form edit
│   │       └── list.php                 # Tabla Bootstrap
│   ├── products/
│   │   ├── controllers/
│   │   │   ├── categories_controller.php  # Manejar categorías
│   │   │   └── products_controller.php    # Manejar products
│   │   ├── models/
│   │   │   └── ProductModel.php           # Queries para products/vw_products
│   │   └── views/
│   │       ├── categories.php             # Lista categorías
│   │       └── products.php               # Lista products
│   ├── quotes/
│   │   ├── controllers/
│   │   │   ├── approve_controller.php     # Aprobar (update stock)
│   │   │   ├── create_controller.php      # Crear quote
│   │   │   ├── list_controller.php        # Listar (vw_quotes)
│   │   │   └── renew_controller.php       # Renovar
│   │   ├── models/
│   │   │   └── QuoteModel.php             # Queries para quotes/quote_items
│   │   └── views/
│   │       ├── create.php                 # Form create
│   │       ├── list.php                   # Tabla quotes
│   │       └── renew.php                  # Form renew
│   ├── reports/
│   │   ├── controllers/
│   │   │   ├── client_reports_controller.php    # Reportes clients
│   │   │   ├── compliance_reports_controller.php  # Compliance
│   │   │   ├── product_reports_controller.php     # Products
│   │   │   └── sales_reports_controller.php       # Sales
│   │   ├── models/
│   │   │   └── ReportModel.php                    # Queries para views materializadas
│   │   └── views/
│   │       ├── client_reports.php                 # HTML/Chart.js
│   │       ├── compliance_reports.php             # Audit logs
│   │       ├── product_reports.php                # Product perf
│   │       └── sales_reports.php                  # Sales trends
│   ├── roles/
│   │   ├── controllers/
│   │   │   ├── assign_controller.php      # Asignar permisos
│   │   │   └── roles_controller.php       # Manejar roles
│   │   ├── models/
│   │   │   └── RoleModel.php              # Queries para roles/permissions
│   │   └── views/
│   │       ├── assign.php                 # Form assign
│   │       └── roles.php                  # Lista roles
│   ├── settings/
│   │   ├── controllers/
│   │   │   └── edit_controller.php        # Editar settings
│   │   ├── models/
│   │   │   └── SettingsModel.php          # Queries para vw_settings
│   │   └── views/
│   │       └── edit.php                   # Form settings
│   └── users/
│       ├── controllers/
│       │   ├── edit_controller.php        # Editar user/profile
│       │   ├── list_controller.php        # Listar users
│       │   └── users_controller.php       # General (e.g., reset pw)
│       ├── models/
│       │   └── UserModel.php              # Queries para users
│       └── views/
│           ├── edit.php                   # Form profile
│           └── list.php                   # Tabla users
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── custom.css                 # Estilos extras (Bootstrap CDN principal)
│   │   └── js/
│   │       ├── auth.js                    # JS para auth (validación forms)
│   │       ├── charts.js                  # Chart.js helpers para reports
│   │       ├── clients.js                 # JS clients (e.g., AJAX lists)
│   │       ├── common.js                  # JS global (e.g., language switch)
│   │       ├── products.js                # JS products (stock warnings)
│   │       ├── quotes.js                  # JS quotes (cálculos items)
│   │       ├── reports.js                 # JS reports (dinámicos)
│   │       ├── roles.js                   # JS roles (permisos select)
│   │       └── users.js                   # JS users (profile upload)
│   └── index.php                          # Entry point: carga módulos dinámicamente (?module=users&action=list)
├── README.md                              # Este archivo
└── tests/                                 # Estructura mirroring modules/
    ├── access_requests/
    │   └── access_requests_test.php       # Tests para requests/review
    ├── auth/
    │   └── auth_test.php                  # Tests login/logout
    ├── backups/
    │   └── backups_test.php               # Tests backups
    ├── clients/
    │   └── clients_test.php               # Tests CRUD clients
    ├── products/
    │   └── products_test.php              # Tests products/categories
    ├── quotes/
    │   └── quotes_test.php                # Tests quotes/approve
    ├── reports/
    │   └── reports_test.php               # Tests reports views
    ├── roles/
    │   └── roles_test.php                 # Tests roles/assign
    ├── settings/
    │   └── settings_test.php              # Tests edit settings
    └── users/
        └── users_test.php                 # Tests users/edit
```

## Development Principles: Strict Module-by-Module and Cybersecurity Focus

- **Strict Module-by-Module Development**: Build in sequence from Module 1 to 10. For each: Code models (DB interactions), controllers (logic), views (UI). Integrate with DB using existing data (e.g., query views first). Test thoroughly (PHPUnit for functionality, security tools for vulns). Only proceed if the module is stable and independent—e.g., `auth/` must work alone before `users/`. This containment ensures one module's failure (e.g., bug in reports) doesn't crash the system.

- **Cybersecurity Emphasis**: Security is paramount, woven into every layer to mitigate OWASP Top 10 risks:
  - **Anti-SQLi**: Exclusive use of PDO prepared statements/bindValue. No concatenated queries. Views for reads abstract direct table access.
  - **Anti-XSS**: Sanitize all outputs with `htmlspecialchars()` or equivalent in `core/security.php`. Validate inputs with `filter_var()`. Escape in views.
  - **Anti-LFI/RFI**: Validate file paths (e.g., `realpath()` for uploads), restrict includes to whitelisted dirs. No user-controlled includes.
  - **RBAC/Access Control**: Enforce via `core/rbac.php`—query `role_permissions` before actions. RLS in DB views adds defense-in-depth.
  - **Session/CSRF**: Secure sessions (regenerate ID on login), CSRF tokens in forms (generate/validate in `core/helpers.php`).
  - **Encryption/Protection**: Handle TDE columns securely; bcrypt for passwords; encrypt SMTP in `settings`.
  - **Other Mitigations**: Input validation (e.g., regex for emails), rate limiting for logins, no error leaks (ini_set('display_errors', 0)), HTTPS required. Audit all code for injections, IDOR (Indirect Object Reference).
  - **Testing for Security**: Per module, run scans (e.g., simulate XSS with tainted inputs), fuzz testing. Ensure compliance with DB features (e.g., audit_logs capture all actions).