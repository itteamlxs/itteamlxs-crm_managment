<?php
/**
 * Client Model
 * Database operations for clients using views and prepared statements
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';

class ClientModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all clients using view with pagination
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAllClients($filters = [], $page = 1, $limit = 10) {
        try {
            $pagination = validatePagination($page, $limit);
            $whereConditions = [];
            $params = [];
            
            // Search filter
            if (!empty($filters['search'])) {
                $search = '%' . $filters['search'] . '%';
                $whereConditions[] = "(company_name LIKE ? OR contact_name LIKE ? OR email LIKE ?)";
                $params = array_merge($params, [$search, $search, $search]);
            }
            
            // Build WHERE clause
            $whereClause = '';
            if (!empty($whereConditions)) {
                $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            }
            
            // First try to get total count from the actual clients table instead of view
            $countSql = "SELECT COUNT(*) as total FROM clients c WHERE c.deleted_at IS NULL";
            if (!empty($whereConditions)) {
                $countSql = "SELECT COUNT(*) as total FROM clients c WHERE c.deleted_at IS NULL AND " . implode(' AND ', $whereConditions);
            }
            
            $totalResult = $this->db->fetch($countSql, $params);
            $total = $totalResult['total'] ?? 0;
            
            // Get clients from the actual table instead of view
            $sql = "SELECT c.client_id, c.company_name, c.contact_name, c.email, c.phone, c.created_at 
                    FROM clients c 
                    WHERE c.deleted_at IS NULL";
            
            if (!empty($whereConditions)) {
                $sql .= " AND " . implode(' AND ', $whereConditions);
            }
            
            $sql .= " ORDER BY c.created_at DESC 
                      LIMIT {$pagination['limit']} OFFSET {$pagination['offset']}";
            
            $clients = $this->db->fetchAll($sql, $params);
            
            return [
                'clients' => $clients,
                'total' => $total,
                'page' => $pagination['page'],
                'limit' => $pagination['limit'],
                'total_pages' => ceil($total / $pagination['limit'])
            ];
            
        } catch (Exception $e) {
            logError("Error in getAllClients: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get single client by ID
     * @param int $clientId
     * @return array|false
     */
    public function getClientById($clientId) {
        $sql = "SELECT c.*, u.username as created_by_name
                FROM clients c
                JOIN users u ON c.created_by = u.user_id
                WHERE c.client_id = ? AND c.deleted_at IS NULL";
        return $this->db->fetch($sql, [$clientId]);
    }
    
    /**
     * Create new client
     * @param array $data
     * @return int|false
     */
    public function createClient($data) {
        $user = getCurrentUser();
        if (!$user) {
            return false;
        }
        
        try {
            $sql = "INSERT INTO clients (company_name, contact_name, email, phone, address, tax_id, created_by, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $params = [
                $data['company_name'],
                $data['contact_name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['address'] ?? null,
                $data['tax_id'] ?? null,
                $user['user_id']
            ];
            
            $this->db->execute($sql, $params);
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            logError("Failed to create client: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update client
     * @param int $clientId
     * @param array $data
     * @return bool
     */
    public function updateClient($clientId, $data) {
        try {
            $sql = "UPDATE clients 
                    SET company_name = ?, contact_name = ?, email = ?, phone = ?, address = ?, tax_id = ?, updated_at = NOW()
                    WHERE client_id = ? AND deleted_at IS NULL";
            
            $params = [
                $data['company_name'],
                $data['contact_name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['address'] ?? null,
                $data['tax_id'] ?? null,
                $clientId
            ];
            
            $this->db->execute($sql, $params);
            return true;
            
        } catch (Exception $e) {
            logError("Failed to update client: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Soft delete client
     * @param int $clientId
     * @return bool
     */
    public function deleteClient($clientId) {
        try {
            $sql = "UPDATE clients SET deleted_at = NOW() WHERE client_id = ? AND deleted_at IS NULL";
            $this->db->execute($sql, [$clientId]);
            return true;
            
        } catch (Exception $e) {
            logError("Failed to delete client: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if email exists
     * @param string $email
     * @param int $excludeClientId
     * @return bool
     */
    public function emailExists($email, $excludeClientId = null) {
        $sql = "SELECT COUNT(*) as count FROM clients WHERE email = ? AND deleted_at IS NULL";
        $params = [$email];
        
        if ($excludeClientId) {
            $sql .= " AND client_id != ?";
            $params[] = $excludeClientId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return ($result['count'] ?? 0) > 0;
    }
    
    /**
     * Get client activities
     * @param int $clientId
     * @param int $limit
     * @return array
     */
    public function getClientActivities($clientId, $limit = 10) {
        $sql = "SELECT ca.*, q.quote_number
                FROM client_activities ca
                LEFT JOIN quotes q ON ca.quote_id = q.quote_id
                WHERE ca.client_id = ?
                ORDER BY ca.activity_date DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$clientId, $limit]);
    }
    
    /**
     * Get client statistics
     * @param int $clientId
     * @return array
     */
    public function getClientStats($clientId) {
        $sql = "SELECT 
                    COUNT(q.quote_id) as total_quotes,
                    COUNT(CASE WHEN q.status = 'APPROVED' THEN 1 END) as approved_quotes,
                    SUM(CASE WHEN q.status = 'APPROVED' THEN q.total_amount ELSE 0 END) as total_spent,
                    MAX(q.created_at) as last_quote_date
                FROM quotes q
                WHERE q.client_id = ?";
        
        $result = $this->db->fetch($sql, [$clientId]);
        
        return [
            'total_quotes' => (int)($result['total_quotes'] ?? 0),
            'approved_quotes' => (int)($result['approved_quotes'] ?? 0),
            'total_spent' => (float)($result['total_spent'] ?? 0),
            'last_quote_date' => $result['last_quote_date'] ?? null,
            'conversion_rate' => $result['total_quotes'] > 0 ? 
                round(($result['approved_quotes'] / $result['total_quotes']) * 100, 2) : 0
        ];
    }
}