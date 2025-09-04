<?php
/**
 * Report Model
 * Database operations for reports using views directly
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';

class ReportModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Sales Reports
    public function getSalesPerformance() {
        try {
            $sql = "SELECT user_id, username, total_quotes, total_amount, conversion_rate 
                    FROM vw_sales_performance 
                    ORDER BY total_amount DESC";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            logError("Get sales performance failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function getSalesTrends($months = 12) {
        try {
            $sql = "SELECT month, total_amount, total_quotes, average_discount 
                    FROM vw_sales_trends 
                    ORDER BY month DESC 
                    LIMIT ?";
            return $this->db->fetchAll($sql, [$months]);
        } catch (Exception $e) {
            logError("Get sales trends failed: " . $e->getMessage());
            return [];
        }
    }
    
    // Client Reports
    public function getClientActivity() {
        try {
            $sql = "SELECT client_id, company_name, last_quote_date, total_quotes, total_amount 
                    FROM vw_client_activity 
                    ORDER BY last_quote_date DESC";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            logError("Get client activity failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function getClientPurchasePatterns() {
        try {
            $sql = "SELECT client_id, company_name, total_spend, purchase_count, last_purchase_date 
                    FROM vw_client_purchase_patterns 
                    ORDER BY total_spend DESC";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            logError("Get client purchase patterns failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function getTopClients($limit = 10) {
        try {
            $sql = "SELECT client_id, company_name, total_spend, purchase_count, rank 
                    FROM vw_top_clients 
                    ORDER BY rank ASC 
                    LIMIT ?";
            return $this->db->fetchAll($sql, [$limit]);
        } catch (Exception $e) {
            logError("Get top clients failed: " . $e->getMessage());
            return [];
        }
    }
    
    // Product Reports
    public function getProductPerformance() {
        try {
            $sql = "SELECT product_id, product_name, sku, total_sold, stock_quantity, category_name 
                    FROM vw_product_performance 
                    ORDER BY total_sold DESC";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            logError("Get product performance failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function getCategorySummary() {
        try {
            $sql = "SELECT category_id, category_name, product_count 
                    FROM vw_category_summary 
                    ORDER BY product_count DESC";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            logError("Get category summary failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function getLowStockProducts() {
        try {
            // Get low stock threshold from settings or use default
            $thresholdSql = "SELECT setting_value FROM settings WHERE setting_key = 'low_stock_threshold'";
            $thresholdResult = $this->db->fetch($thresholdSql);
            $threshold = $thresholdResult ? (int)$thresholdResult['setting_value'] : 10;
            
            $sql = "SELECT p.product_id, p.product_name, p.sku, p.stock_quantity, pc.category_name 
                    FROM products p
                    JOIN product_categories pc ON p.category_id = pc.category_id
                    WHERE p.stock_quantity < ?
                    ORDER BY p.stock_quantity ASC";
            return $this->db->fetchAll($sql, [$threshold]);
        } catch (Exception $e) {
            logError("Get low stock products failed: " . $e->getMessage());
            return [];
        }
    }
    
    // Compliance Reports - Access audit_logs directly since vw_audit_logs has SESSION_USER() issues
    public function getAuditLogs($limit = 100, $offset = 0) {
        try {
            $sql = "SELECT audit_id, user_id, action, entity_type, entity_id, ip_address, created_at 
                    FROM audit_logs 
                    ORDER BY created_at DESC 
                    LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$limit, $offset]);
        } catch (Exception $e) {
            logError("Get audit logs failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function getSecurityPosture() {
        try {
            // Query directly from tables since vw_security_posture has SESSION_USER() issues
            $sql = "SELECT 
                        SUM(u.failed_login_attempts) AS failed_login_count,
                        COUNT(CASE WHEN u.locked_until IS NOT NULL THEN 1 END) AS locked_accounts,
                        COUNT(CASE WHEN a.entity_type = 'ROLE_PERMISSIONS' THEN 1 END) AS permission_changes,
                        COUNT(a.audit_id) AS audit_log_count,
                        MAX(a.created_at) AS last_security_event
                    FROM users u
                    LEFT JOIN audit_logs a ON 1=1";
            return $this->db->fetch($sql);
        } catch (Exception $e) {
            logError("Get security posture failed: " . $e->getMessage());
            return [];
        }
    }
    
    // General utility methods
    public function getDateRangeData($table, $dateColumn, $startDate, $endDate) {
        try {
            $sql = "SELECT * FROM {$table} 
                    WHERE {$dateColumn} BETWEEN ? AND ? 
                    ORDER BY {$dateColumn} DESC";
            return $this->db->fetchAll($sql, [$startDate, $endDate]);
        } catch (Exception $e) {
            logError("Get date range data failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function refreshMaterializedViews() {
        try {
            $this->db->beginTransaction();
            
            // Refresh sales performance
            $this->db->execute("TRUNCATE TABLE materialized_sales_performance");
            $this->db->execute("INSERT INTO materialized_sales_performance 
                               SELECT user_id, username, total_quotes, total_amount, conversion_rate, NOW() as last_updated
                               FROM vw_sales_performance");
            
            // Refresh sales trends
            $this->db->execute("TRUNCATE TABLE materialized_sales_trends");
            $this->db->execute("INSERT INTO materialized_sales_trends 
                               SELECT month, total_amount, total_quotes, average_discount, NOW() as last_updated
                               FROM vw_sales_trends");
            
            // Refresh client purchase patterns
            $this->db->execute("TRUNCATE TABLE materialized_client_purchase_patterns");
            $this->db->execute("INSERT INTO materialized_client_purchase_patterns 
                               SELECT client_id, company_name, total_spend, purchase_count, last_purchase_date, NOW() as last_updated
                               FROM vw_client_purchase_patterns");
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Refresh materialized views failed: " . $e->getMessage());
            return false;
        }
    }
}