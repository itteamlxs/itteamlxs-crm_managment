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
            error_log("Get sales performance failed: " . $e->getMessage());
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
            error_log("Get sales trends failed: " . $e->getMessage());
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
    
    // Compliance Reports
    public function getAuditLogs($limit = 100, $offset = 0, $startDate = '', $endDate = '', $actionType = '') {
        try {
            $sql = "SELECT al.audit_id, al.user_id, al.action, al.entity_type, al.entity_id, 
                           al.ip_address, al.created_at, u.username, u.display_name
                    FROM audit_logs al
                    LEFT JOIN users u ON al.user_id = u.user_id
                    WHERE 1=1";
            $params = [];
            
            if (!empty($startDate) && !empty($endDate)) {
                $sql .= " AND al.created_at BETWEEN ? AND ?";
                $params[] = $startDate . ' 00:00:00';
                $params[] = $endDate . ' 23:59:59';
            }
            
            if (!empty($actionType)) {
                $sql .= " AND al.action = ?";
                $params[] = $actionType;
            }
            
            $sql .= " ORDER BY al.created_at DESC LIMIT ? OFFSET ?";
            $params[] = (int)$limit;
            $params[] = (int)$offset;
            
            return $this->db->fetchAll($sql, $params);
        } catch (Exception $e) {
            error_log("Get audit logs failed: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAuditLogsCount($startDate = '', $endDate = '', $actionType = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM audit_logs al WHERE 1=1";
            $params = [];
            
            if (!empty($startDate) && !empty($endDate)) {
                $sql .= " AND al.created_at BETWEEN ? AND ?";
                $params[] = $startDate . ' 00:00:00';
                $params[] = $endDate . ' 23:59:59';
            }
            
            if (!empty($actionType)) {
                $sql .= " AND al.action = ?";
                $params[] = $actionType;
            }
            
            $result = $this->db->fetch($sql, $params);
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            error_log("Get audit logs count failed: " . $e->getMessage());
            return 0;
        }
    }
    
    public function getSecurityPosture() {
        try {
            $sql = "SELECT 
                        COALESCE(SUM(u.failed_login_attempts), 0) AS failed_login_count,
                        COALESCE(COUNT(CASE WHEN u.locked_until IS NOT NULL AND u.locked_until > NOW() THEN 1 END), 0) AS locked_accounts,
                        COALESCE(COUNT(CASE WHEN u.is_active = 0 THEN 1 END), 0) AS inactive_accounts,
                        COALESCE((SELECT COUNT(*) FROM audit_logs WHERE action IN ('PERMISSION_CHANGE', 'ROLE_UPDATE')), 0) AS permission_changes,
                        COALESCE((SELECT COUNT(*) FROM audit_logs), 0) AS audit_log_count,
                        (SELECT MAX(created_at) FROM audit_logs) AS last_security_event
                    FROM users u";
                    
            $result = $this->db->fetch($sql);
            
            if ($result) {
                $result['failed_login_count'] = (int)$result['failed_login_count'];
                $result['locked_accounts'] = (int)$result['locked_accounts'];
                $result['inactive_accounts'] = (int)$result['inactive_accounts'];
                $result['permission_changes'] = (int)$result['permission_changes'];
                $result['audit_log_count'] = (int)$result['audit_log_count'];
            }
            
            return $result ?: [
                'failed_login_count' => 0,
                'locked_accounts' => 0,
                'inactive_accounts' => 0,
                'permission_changes' => 0,
                'audit_log_count' => 0,
                'last_security_event' => null
            ];
            
        } catch (Exception $e) {
            error_log("Get security posture failed: " . $e->getMessage());
            return [
                'failed_login_count' => 0,
                'locked_accounts' => 0,
                'inactive_accounts' => 0,
                'permission_changes' => 0,
                'audit_log_count' => 0,
                'last_security_event' => null
            ];
        }
    }
    
    public function getUserActivities() {
        try {
            $sql = "SELECT u.username, COUNT(a.audit_id) as action_count, MAX(a.created_at) as last_activity
                    FROM users u
                    LEFT JOIN audit_logs a ON u.user_id = a.user_id
                    GROUP BY u.user_id, u.username
                    HAVING action_count > 0
                    ORDER BY action_count DESC
                    LIMIT 20";
            
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            error_log("Get user activities failed: " . $e->getMessage());
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
            
            $tables = ['materialized_sales_performance', 'materialized_sales_trends', 'materialized_client_purchase_patterns'];
            
            foreach ($tables as $table) {
                $checkSql = "SHOW TABLES LIKE ?";
                $exists = $this->db->fetch($checkSql, [$table]);
                
                if ($exists) {
                    switch ($table) {
                        case 'materialized_sales_performance':
                            $this->db->execute("TRUNCATE TABLE {$table}");
                            $this->db->execute("INSERT INTO {$table} 
                                               SELECT user_id, username, total_quotes, total_amount, conversion_rate, NOW() as last_updated
                                               FROM vw_sales_performance");
                            break;
                            
                        case 'materialized_sales_trends':
                            $this->db->execute("TRUNCATE TABLE {$table}");
                            $this->db->execute("INSERT INTO {$table} 
                                               SELECT month, total_amount, total_quotes, average_discount, NOW() as last_updated
                                               FROM vw_sales_trends");
                            break;
                            
                        case 'materialized_client_purchase_patterns':
                            $this->db->execute("TRUNCATE TABLE {$table}");
                            $this->db->execute("INSERT INTO {$table} 
                                               SELECT client_id, company_name, total_spend, purchase_count, last_purchase_date, NOW() as last_updated
                                               FROM vw_client_purchase_patterns");
                            break;
                    }
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            error_log("Refresh materialized views failed: " . $e->getMessage());
            return false;
        }
    }
}