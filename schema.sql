-- schema_xampp.sql for CRM Database
-- Compatible con XAMPP/MariaDB en Fedora Linux
-- UTF8MB4 encoding, InnoDB engine
-- NOTA: TDE (Transparent Data Encryption) no está disponible en MariaDB/XAMPP.
-- Si necesitas cifrado, considera usar ENCRYPT() o AES_ENCRYPT() en columnas.

-- schema.sql for CRM Database (Single Company)
-- MySQL 8.0+, UTF8MB4 encoding, InnoDB engine
-- Designed for single company, RBAC, scalability, and compliance
-- Created: August 9, 2025

SET FOREIGN_KEY_CHECKS = 0;
DROP DATABASE IF EXISTS crm_db;
CREATE DATABASE crm_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crm_db;
SET FOREIGN_KEY_CHECKS = 1;


-- Table: roles
CREATE TABLE roles (
    role_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id),
    INDEX idx_role_name (role_name)
) ENGINE=InnoDB;

-- Table: permissions
CREATE TABLE permissions (
    permission_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    permission_name VARCHAR(100) NOT NULL UNIQUE,
    module VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (permission_id),
    INDEX idx_module (module)
) ENGINE=InnoDB;

-- Table: role_permissions
CREATE TABLE role_permissions (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(permission_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: users
CREATE TABLE users (
    user_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    profile_picture VARCHAR(255),
    language VARCHAR(10) NOT NULL DEFAULT 'es',
    role_id BIGINT UNSIGNED NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    failed_login_attempts INT NOT NULL DEFAULT 0,
    locked_until TIMESTAMP NULL,
    force_password_change BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login_at TIMESTAMP NULL,
    PRIMARY KEY (user_id),
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE RESTRICT,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_language (language)
) ENGINE=InnoDB;

-- Table: clients
CREATE TABLE clients (
    client_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    company_name VARCHAR(255) NOT NULL,
    contact_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    tax_id VARCHAR(50),
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    PRIMARY KEY (client_id),
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE RESTRICT,
    INDEX idx_email (email),
    INDEX idx_created_by (created_by)
) ENGINE=InnoDB;

-- Table: product_categories
CREATE TABLE product_categories (
    category_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (category_id),
    INDEX idx_category_name (category_name)
) ENGINE=InnoDB;

-- Table: products
CREATE TABLE products (
    product_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id BIGINT UNSIGNED NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    price DECIMAL(10,2) NOT NULL,
    tax_rate DECIMAL(5,2) NOT NULL,
    stock_quantity INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (product_id),
    FOREIGN KEY (category_id) REFERENCES product_categories(category_id) ON DELETE RESTRICT,
    INDEX idx_sku (sku),
    INDEX idx_category_id (category_id)
) ENGINE=InnoDB;

-- Table: quotes
CREATE TABLE quotes (
    quote_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    client_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    parent_quote_id BIGINT UNSIGNED NULL,
    quote_number VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('DRAFT', 'SENT', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'DRAFT',
    stock_updated BOOLEAN NOT NULL DEFAULT FALSE,
    total_amount DECIMAL(10,2) NOT NULL,
    issue_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (quote_id),
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE RESTRICT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (parent_quote_id) REFERENCES quotes(quote_id) ON DELETE SET NULL,
    INDEX idx_quote_number (quote_number),
    INDEX idx_client_id (client_id),
    INDEX idx_parent_quote_id (parent_quote_id),
    INDEX idx_status (status),
    INDEX idx_issue_date (issue_date)
) ENGINE=InnoDB;

-- Table: quote_items
CREATE TABLE quote_items (
    quote_item_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    quote_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    discount DECIMAL(5,2) NOT NULL,
    tax_amount DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (quote_item_id),
    FOREIGN KEY (quote_id) REFERENCES quotes(quote_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE RESTRICT,
    INDEX idx_quote_id (quote_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB;

-- Table: audit_logs
CREATE TABLE audit_logs (
    audit_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id BIGINT UNSIGNED NOT NULL,
    old_value JSON,
    new_value JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (audit_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_entity_type (entity_type),
    INDEX idx_entity_id (entity_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- Table: settings
CREATE TABLE settings (
    setting_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (setting_id),
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB;

-- Table: access_requests
CREATE TABLE access_requests (
    request_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    status ENUM('PENDING', 'APPROVED', 'DENIED') NOT NULL DEFAULT 'PENDING',
    requested_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    comments TEXT,
    PRIMARY KEY (request_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (permission_id) REFERENCES permissions(permission_id) ON DELETE RESTRICT,
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_permission_id (permission_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Table: client_activities
CREATE TABLE client_activities (
    activity_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    client_id BIGINT UNSIGNED NOT NULL,
    quote_id BIGINT UNSIGNED NULL,
    activity_type ENUM('QUOTE_CREATED', 'QUOTE_APPROVED', 'CONTACT') NOT NULL DEFAULT 'QUOTE_CREATED',
    activity_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    details JSON,
    PRIMARY KEY (activity_id),
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE RESTRICT,
    FOREIGN KEY (quote_id) REFERENCES quotes(quote_id) ON DELETE SET NULL,
    INDEX idx_client_id (client_id),
    INDEX idx_quote_id (quote_id),
    INDEX idx_activity_date (activity_date)
) ENGINE=InnoDB;

-- Table: backup_requests
CREATE TABLE backup_requests (
    backup_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    requested_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('PENDING', 'COMPLETED', 'FAILED') NOT NULL DEFAULT 'PENDING',
    created_by BIGINT UNSIGNED NULL,
    PRIMARY KEY (backup_id),
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_requested_at (requested_at),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Views
-- View: vw_clients
CREATE VIEW vw_clients AS
SELECT client_id, company_name, contact_name, email, phone, created_at
FROM clients
WHERE deleted_at IS NULL;

-- View: vw_products
CREATE VIEW vw_products AS
SELECT p.product_id, p.product_name, p.sku, p.price, p.tax_rate, p.stock_quantity, pc.category_name
FROM products p
JOIN product_categories pc ON p.category_id = pc.category_id;

-- View: vw_quotes
CREATE VIEW vw_quotes AS
SELECT q.quote_id, q.quote_number, q.status, q.total_amount, q.issue_date, q.expiry_date, c.company_name AS client_name, u.username
FROM quotes q
JOIN clients c ON q.client_id = c.client_id
JOIN users u ON q.user_id = u.user_id;

-- View: vw_quote_items
CREATE VIEW vw_quote_items AS
SELECT qi.quote_item_id, qi.quote_id, qi.quantity, qi.unit_price, qi.discount, qi.tax_amount, qi.subtotal, p.product_name, p.sku
FROM quote_items qi
JOIN quotes q ON qi.quote_id = q.quote_id
JOIN products p ON qi.product_id = p.product_id;

-- View: vw_sales_performance
CREATE VIEW vw_sales_performance AS
SELECT u.user_id, u.username,
       COUNT(q.quote_id) AS total_quotes,
       SUM(q.total_amount) AS total_amount,
       AVG(CASE WHEN q.status = 'APPROVED' THEN 1.0 ELSE 0.0 END) AS conversion_rate
FROM users u
LEFT JOIN quotes q ON u.user_id = q.user_id
GROUP BY u.user_id, u.username;

-- View: vw_sales_trends
CREATE VIEW vw_sales_trends AS
SELECT DATE_FORMAT(q.issue_date, '%Y-%m') AS month,
       SUM(q.total_amount) AS total_amount,
       COUNT(q.quote_id) AS total_quotes,
       AVG(qi.discount) AS average_discount
FROM quotes q
JOIN quote_items qi ON q.quote_id = qi.quote_id
GROUP BY DATE_FORMAT(q.issue_date, '%Y-%m');

-- View: vw_client_activity
CREATE VIEW vw_client_activity AS
SELECT c.client_id, c.company_name,
       MAX(q.created_at) AS last_quote_date,
       COUNT(q.quote_id) AS total_quotes,
       SUM(q.total_amount) AS total_amount
FROM clients c
LEFT JOIN quotes q ON c.client_id = q.client_id
GROUP BY c.client_id, c.company_name;

-- View: vw_product_performance
CREATE VIEW vw_product_performance AS
SELECT p.product_id, p.product_name, p.sku,
       SUM(qi.quantity) AS total_sold,
       p.stock_quantity,
       pc.category_name
FROM products p
JOIN product_categories pc ON p.category_id = pc.category_id
LEFT JOIN quote_items qi ON p.product_id = qi.product_id
GROUP BY p.product_id, p.product_name, p.sku, p.stock_quantity, pc.category_name;

-- View: vw_audit_logs
CREATE VIEW vw_audit_logs AS
SELECT a.audit_id, a.user_id, a.action, a.entity_type, a.entity_id, a.ip_address, a.created_at
FROM audit_logs a
WHERE (SELECT role_id FROM users WHERE user_id = SESSION_USER()) IN (
    SELECT role_id FROM role_permissions WHERE permission_id = (
        SELECT permission_id FROM permissions WHERE permission_name = 'view_compliance_reports'
    )
);

-- View: vw_security_posture
CREATE VIEW vw_security_posture AS
SELECT SUM(u.failed_login_attempts) AS failed_login_count,
       COUNT(CASE WHEN u.locked_until IS NOT NULL THEN 1 END) AS locked_accounts,
       COUNT(CASE WHEN a.entity_type = 'ROLE_PERMISSIONS' THEN 1 END) AS permission_changes,
       COUNT(a.audit_id) AS audit_log_count,
       MAX(a.created_at) AS last_security_event
FROM users u
LEFT JOIN audit_logs a ON 1=1
WHERE (SELECT role_id FROM users WHERE user_id = SESSION_USER()) IN (
    SELECT role_id FROM role_permissions WHERE permission_id = (
        SELECT permission_id FROM permissions WHERE permission_name = 'view_compliance_reports'
    )
);

-- View: vw_expiring_quotes
CREATE VIEW vw_expiring_quotes AS
SELECT q.quote_id, q.quote_number, q.client_id, c.company_name AS client_name, q.expiry_date,
       DATEDIFF(q.expiry_date, CURDATE()) AS days_until_expiry
FROM quotes q
JOIN clients c ON q.client_id = c.client_id
JOIN settings s ON s.setting_key = 'quote_expiry_notification_days'
WHERE q.status = 'SENT'
AND q.expiry_date <= DATE_ADD(CURDATE(), INTERVAL CAST(s.setting_value AS UNSIGNED) DAY);

-- View: vw_low_stock_products
CREATE VIEW vw_low_stock_products AS
SELECT p.product_id, p.product_name, p.sku, p.stock_quantity, pc.category_name
FROM products p
JOIN product_categories pc ON p.category_id = pc.category_id
JOIN settings s ON s.setting_key = 'low_stock_threshold'
WHERE p.stock_quantity < CAST(s.setting_value AS UNSIGNED);

-- View: vw_category_summary
CREATE VIEW vw_category_summary AS
SELECT pc.category_id, pc.category_name, COUNT(p.product_id) AS product_count
FROM product_categories pc
LEFT JOIN products p ON pc.category_id = p.category_id
GROUP BY pc.category_id, pc.category_name;

-- View: vw_client_purchase_patterns
CREATE VIEW vw_client_purchase_patterns AS
SELECT c.client_id, c.company_name,
       SUM(q.total_amount) AS total_spend,
       COUNT(q.quote_id) AS purchase_count,
       MAX(q.created_at) AS last_purchase_date
FROM clients c
LEFT JOIN quotes q ON c.client_id = q.client_id
WHERE q.status = 'APPROVED'
GROUP BY c.client_id, c.company_name;

-- View: vw_client_product_preferences
CREATE VIEW vw_client_product_preferences AS
SELECT c.client_id, c.company_name, p.product_id, p.product_name,
       SUM(qi.quantity) AS total_quantity
FROM clients c
JOIN quotes q ON c.client_id = q.client_id
JOIN quote_items qi ON q.quote_id = qi.quote_id
JOIN products p ON qi.product_id = p.product_id
WHERE q.status = 'APPROVED'
GROUP BY c.client_id, c.company_name, p.product_id, p.product_name;

-- View: vw_top_clients
CREATE VIEW vw_top_clients AS
SELECT c.client_id, c.company_name,
       SUM(q.total_amount) AS total_spend,
       COUNT(q.quote_id) AS purchase_count,
       RANK() OVER (ORDER BY SUM(q.total_amount) DESC) AS rank
FROM clients c
JOIN quotes q ON c.client_id = q.client_id
WHERE q.status = 'APPROVED'
GROUP BY c.client_id, c.company_name;

-- View: vw_settings
CREATE VIEW vw_settings AS
SELECT s.setting_id, s.setting_key, s.setting_value
FROM settings s
WHERE (SELECT role_id FROM users WHERE user_id = SESSION_USER()) IN (
    SELECT role_id FROM role_permissions WHERE permission_id = (
        SELECT permission_id FROM permissions WHERE permission_name = 'manage_settings'
    )
);

-- Triggers
DELIMITER //

-- Trigger: users_after_insert
CREATE TRIGGER users_after_insert
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, new_value, ip_address, user_agent, created_at)
    VALUES (NEW.user_id, 'INSERT', 'USER', NEW.user_id, JSON_OBJECT('username', NEW.username, 'email', NEW.email), CONNECTION_ID(), NULL, NOW());
END//

-- Trigger: users_after_update
CREATE TRIGGER users_after_update
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
    VALUES (NEW.user_id, 'UPDATE', 'USER', NEW.user_id,
            JSON_OBJECT('username', OLD.username, 'email', OLD.email, 'language', OLD.language),
            JSON_OBJECT('username', NEW.username, 'email', NEW.email, 'language', NEW.language),
            CONNECTION_ID(), NULL, NOW());
END//

-- Trigger: quotes_after_update
CREATE TRIGGER quotes_after_update
AFTER UPDATE ON quotes
FOR EACH ROW
BEGIN
    IF NEW.status = 'APPROVED' AND OLD.status != 'APPROVED' AND NEW.stock_updated = FALSE THEN
        UPDATE products p
        JOIN quote_items qi ON p.product_id = qi.product_id
        SET p.stock_quantity = p.stock_quantity - qi.quantity
        WHERE qi.quote_id = NEW.quote_id
        AND p.stock_quantity >= qi.quantity;
        
        UPDATE quotes
        SET stock_updated = TRUE
        WHERE quote_id = NEW.quote_id;
        
        INSERT INTO client_activities (client_id, quote_id, activity_type, activity_date, details)
        VALUES (NEW.client_id, NEW.quote_id, 'QUOTE_APPROVED', NOW(),
                JSON_OBJECT('total_amount', NEW.total_amount));
                
        INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
        VALUES (NEW.user_id, 'STOCK_UPDATE', 'QUOTE', NEW.quote_id,
                JSON_OBJECT('status', OLD.status),
                JSON_OBJECT('status', NEW.status, 'stock_updated', NEW.stock_updated),
                CONNECTION_ID(), NULL, NOW());
    END IF;
    
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
    VALUES (NEW.user_id, 'UPDATE', 'QUOTE', NEW.quote_id,
            JSON_OBJECT('status', OLD.status, 'parent_quote_id', OLD.parent_quote_id),
            JSON_OBJECT('status', NEW.status, 'parent_quote_id', NEW.parent_quote_id),
            CONNECTION_ID(), NULL, NOW());
END//

-- Trigger: settings_after_insert
CREATE TRIGGER settings_after_insert
AFTER INSERT ON settings
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, new_value, ip_address, user_agent, created_at)
    VALUES (NULL, 'INSERT', 'SETTINGS', NEW.setting_id,
            JSON_OBJECT('setting_key', NEW.setting_key, 'setting_value', NEW.setting_value),
            CONNECTION_ID(), NULL, NOW());
END//

-- Trigger: settings_after_update
CREATE TRIGGER settings_after_update
AFTER UPDATE ON settings
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
    VALUES (NULL, 'UPDATE', 'SETTINGS', NEW.setting_id,
            JSON_OBJECT('setting_key', OLD.setting_key, 'setting_value', OLD.setting_value),
            JSON_OBJECT('setting_key', NEW.setting_key, 'setting_value', NEW.setting_value),
            CONNECTION_ID(), NULL, NOW());
END//

-- Trigger: backup_requests_after_insert
CREATE TRIGGER backup_requests_after_insert
AFTER INSERT ON backup_requests
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, new_value, ip_address, user_agent, created_at)
    VALUES (NEW.created_by, 'INSERT', 'BACKUP_REQUEST', NEW.backup_id,
            JSON_OBJECT('status', NEW.status, 'requested_at', NEW.requested_at),
            CONNECTION_ID(), NULL, NOW());
END//

-- Trigger: backup_requests_after_update
CREATE TRIGGER backup_requests_after_update
AFTER UPDATE ON backup_requests
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
    VALUES (NEW.created_by, 'UPDATE', 'BACKUP_REQUEST', NEW.backup_id,
            JSON_OBJECT('status', OLD.status),
            JSON_OBJECT('status', NEW.status),
            CONNECTION_ID(), NULL, NOW());
END//

DELIMITER ;

-- Initial Data: Roles
INSERT INTO roles (role_name, description, created_at) VALUES
('Admin', 'Full access to all modules and settings', NOW()),
('Seller', 'Access to sales-related modules and reports', NOW()),
('Auditor', 'Access to compliance reports', NOW());

-- Initial Data: Permissions
INSERT INTO permissions (permission_name, module, description, created_at) VALUES
('edit_own_profile', 'users', 'Edit own user profile', NOW()),
('reset_user_password', 'users', 'Reset user passwords', NOW()),
('view_sales_reports', 'reports', 'View sales performance and trends', NOW()),
('view_client_reports', 'reports', 'View client activity and patterns', NOW()),
('view_product_reports', 'reports', 'View product performance and categories', NOW()),
('view_compliance_reports', 'reports', 'View audit logs and security posture', NOW()),
('request_access', 'access', 'Request additional permissions', NOW()),
('manage_access_requests', 'access', 'Review access requests', NOW()),
('manage_settings', 'settings', 'Manage company settings', NOW()),
('renew_quotes', 'quotes', 'Renew existing quotes', NOW()),
('manage_backups', 'backups', 'Manage backup requests', NOW()),
('view_clients', 'clients', 'View client details', NOW()),
('create_quotes', 'quotes', 'Create new quotes', NOW());

-- Initial Data: Role Permissions
INSERT INTO role_permissions (role_id, permission_id, created_at)
SELECT r.role_id, p.permission_id, NOW()
FROM roles r
JOIN permissions p ON p.permission_name IN (
    'edit_own_profile', 'reset_user_password', 'view_sales_reports', 'view_client_reports',
    'view_product_reports', 'view_compliance_reports', 'request_access', 'manage_access_requests',
    'manage_settings', 'renew_quotes', 'manage_backups', 'view_clients', 'create_quotes'
)
WHERE r.role_name = 'Admin';

INSERT INTO role_permissions (role_id, permission_id, created_at)
SELECT r.role_id, p.permission_id, NOW()
FROM roles r
JOIN permissions p ON p.permission_name IN (
    'edit_own_profile', 'view_sales_reports', 'request_access', 'renew_quotes',
    'view_clients', 'create_quotes'
)
WHERE r.role_name = 'Seller';

INSERT INTO role_permissions (role_id, permission_id, created_at)
SELECT r.role_id, p.permission_id, NOW()
FROM roles r
JOIN permissions p ON p.permission_name IN ('view_compliance_reports', 'request_access')
WHERE r.role_name = 'Auditor';

-- Materialized Tables for Report Views
CREATE TABLE materialized_sales_performance (
    user_id BIGINT UNSIGNED NOT NULL,
    username VARCHAR(50) NOT NULL,
    total_quotes BIGINT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    conversion_rate DECIMAL(5,2) NOT NULL,
    last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id)
) ENGINE=InnoDB;

CREATE TABLE materialized_sales_trends (
    month VARCHAR(7) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    total_quotes BIGINT NOT NULL,
    average_discount DECIMAL(5,2) NOT NULL,
    last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (month)
) ENGINE=InnoDB;

CREATE TABLE materialized_client_purchase_patterns (
    client_id BIGINT UNSIGNED NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    total_spend DECIMAL(10,2) NOT NULL,
    purchase_count BIGINT NOT NULL,
    last_purchase_date TIMESTAMP,
    last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (client_id)
) ENGINE=InnoDB;

-- Event Scheduler for Materialized Views and Backup Requests
SET GLOBAL event_scheduler = ON;

DELIMITER //

CREATE EVENT update_materialized_views
ON SCHEDULE EVERY 1 DAY STARTS '2025-01-01 01:00:00'
DO
BEGIN
    TRUNCATE TABLE materialized_sales_performance;
    INSERT INTO materialized_sales_performance
    SELECT * FROM vw_sales_performance;
    
    TRUNCATE TABLE materialized_sales_trends;
    INSERT INTO materialized_sales_trends
    SELECT * FROM vw_sales_trends;
    
    TRUNCATE TABLE materialized_client_purchase_patterns;
    INSERT INTO materialized_client_purchase_patterns
    SELECT * FROM vw_client_purchase_patterns;
END//

CREATE EVENT daily_backup_request
ON SCHEDULE EVERY 1 DAY STARTS '2025-01-01 02:00:00'
DO
BEGIN
    INSERT INTO backup_requests (requested_at, status)
    VALUES (NOW(), 'PENDING');
END//

DELIMITER ;

-- MySQL Users
CREATE USER 'crm_user'@'192.168.1.%' IDENTIFIED BY 'secure_password';
GRANT SELECT, INSERT, UPDATE ON crm_db.* TO 'crm_user'@'192.168.1.%';
CREATE USER 'backup_user'@'192.168.1.%' IDENTIFIED BY 'secure_backup_password';
GRANT SELECT, LOCK TABLES ON crm_db.* TO 'backup_user'@'192.168.1.%';

-- Backup Script (External, e.g., cron job)
-- Example cron: 0 2 * * * mysqldump -u backup_user -psecure_backup_password crm_db | gpg --encrypt > /backups/crm_backup_$(date +%Y%m%d).sql.gpg

-- Initial Settings
INSERT INTO settings (setting_key, setting_value, created_at) VALUES
('company_display_name', 'Company Name', NOW()),
('default_tax_rate', '0.00', NOW()),
('quote_expiry_days', '7', NOW()),
('quote_expiry_notification_days', '3', NOW()),
('low_stock_threshold', '10', NOW()),
('timezone', 'America/New_York', NOW()),
('available_languages', '["es", "en", "fr", "zh"]', NOW()),
('smtp_host', 'smtp.example.com', NOW()),
('smtp_port', '587', NOW()),
('smtp_username', 'user@example.com', NOW()),
('smtp_password', 'encrypted_password', NOW()),
('smtp_encryption', 'TLS', NOW()),
('from_email', 'no-reply@example.com', NOW()),
('from_name', 'Company Name', NOW()),
('backup_time', '02:00:00', NOW());