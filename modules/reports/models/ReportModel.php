<?php
/**
 * Report Model - Handles all report data operations
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/security.php';

class ReportModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get audit logs for compliance reports with filters - UPDATED FOR COMPLIANCE
     */
    public function getAuditLogs($page = 1, $limit = 20, $startDate = null, $endDate = null, $entityType = null, $action = null) {
        $pagination = validatePagination($page, $limit);
        
        $whereConditions = [];
        $params = [];
        
        if ($startDate) {
            $whereConditions[] = "created_at >= ?";
            $params[] = $startDate . ' 00:00:00';
        }
        
        if ($endDate) {
            $whereConditions[] = "created_at <= ?";
            $params[] = $endDate . ' 23:59:59';
        }
        
        if ($entityType) {
            $whereConditions[] = "entity_type = ?";
            $params[] = $entityType;
        }
        
        if ($action) {
            $whereConditions[] = "action = ?";
            $params[] = $action;
        }
        
        $whereClause = '';
        if (!empty($whereConditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        }
        
        // Get audit logs with new columns
        $sql = "SELECT audit_id, user_id, username, action, entity_type, entity_id, 
                       ip_address, user_agent, created_at, display_name, role_name
                FROM vw_audit_logs 
                {$whereClause} 
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $pagination['limit'];
        $params[] = $pagination['offset'];
        
        $logs = $this->db->fetchAll($sql, $params);
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM vw_audit_logs {$whereClause}";
        $countParams = array_slice($params, 0, -2);
        $totalResult = $this->db->fetch($countSql, $countParams);
        $total = $totalResult['total'] ?? 0;
        
        return [
            'logs' => $logs,
            'pagination' => [
                'current_page' => $pagination['page'],
                'per_page' => $pagination['limit'],
                'total' => $total,
                'total_pages' => ceil($total / $pagination['limit'])
            ]
        ];
    }
    
    /**
     * Get unique actions for filter dropdown - NEW FOR COMPLIANCE
     */
    public function getUniqueActions() {
        $sql = "SELECT DISTINCT action FROM vw_audit_logs ORDER BY action";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get unique entity types for filter dropdown - NEW FOR COMPLIANCE
     */
    public function getUniqueEntityTypes() {
        $sql = "SELECT DISTINCT entity_type FROM vw_audit_logs ORDER BY entity_type";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get security posture data - NEW FOR COMPLIANCE
     */
    public function getSecurityPosture() {
        $sql = "SELECT * FROM vw_security_posture";
        $result = $this->db->fetch($sql);
        return $result ?: [
            'failed_login_count' => 0,
            'locked_accounts' => 0,
            'permission_changes' => 0,
            'audit_log_count' => 0,
            'last_security_event' => null
        ];
    }
    
    // ==== EXISTING METHODS - DO NOT MODIFY ====
    
    /**
     * Get sales performance data
     */
    public function getSalesPerformance() {
        try {
            $sql = "SELECT * FROM materialized_sales_performance ORDER BY total_amount DESC";
            $results = $this->db->fetchAll($sql);
            
            if (empty($results)) {
                $sql = "SELECT * FROM vw_sales_performance ORDER BY total_amount DESC";
                $results = $this->db->fetchAll($sql);
            }
            
            return $results;
        } catch (Exception $e) {
            logError("Sales performance query failed: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get sales trends data
     */
    public function getSalesTrends() {
        try {
            $sql = "SELECT * FROM materialized_sales_trends ORDER BY month DESC LIMIT 12";
            $results = $this->db->fetchAll($sql);
            
            if (empty($results)) {
                $sql = "SELECT * FROM vw_sales_trends ORDER BY month DESC LIMIT 12";
                $results = $this->db->fetchAll($sql);
            }
            
            return array_reverse($results);
        } catch (Exception $e) {
            logError("Sales trends query failed: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get user activities for sales reports
     */
    public function getUserActivities($limit = 10) {
        $sql = "SELECT u.username, u.display_name, COUNT(q.quote_id) as quote_count, 
                       SUM(q.total_amount) as total_sales
                FROM users u
                LEFT JOIN quotes q ON u.user_id = q.user_id
                GROUP BY u.user_id, u.username, u.display_name
                ORDER BY total_sales DESC
                LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Get client purchase patterns - PUBLIC method
     */
    public function getClientPurchasePatterns() {
        try {
            $sql = "SELECT * FROM materialized_client_purchase_patterns ORDER BY total_spend DESC LIMIT 20";
            $results = $this->db->fetchAll($sql);
            
            if (empty($results)) {
                $sql = "SELECT * FROM vw_client_purchase_patterns ORDER BY total_spend DESC LIMIT 20";
                $results = $this->db->fetchAll($sql);
            }
            
            return $results;
        } catch (Exception $e) {
            logError("Client purchase patterns query failed: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get top clients
     */
    public function getTopClients() {
        $sql = "SELECT * FROM vw_top_clients ORDER BY rank ASC LIMIT 10";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get client activity data
     */
    public function getClientActivity($limit = 20) {
        $sql = "SELECT * FROM vw_client_activity ORDER BY last_quote_date DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Get product performance data
     */
    public function getProductPerformance($limit = 20) {
        $sql = "SELECT * FROM vw_product_performance ORDER BY total_sold DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Get category summary for product reports
     */
    public function getCategorySummary() {
        $sql = "SELECT * FROM vw_category_summary ORDER BY product_count DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get low stock products
     */
    public function getLowStockProducts() {
        $sql = "SELECT * FROM vw_low_stock_products ORDER BY stock_quantity ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get expiring quotes
     */
    public function getExpiringQuotes() {
        $sql = "SELECT * FROM vw_expiring_quotes ORDER BY days_until_expiry ASC";
        return $this->db->fetchAll($sql);
    }
}