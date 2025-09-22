<?php
/**
 * Client Model
 * Handles client data operations using prepared statements and views
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';

class ClientModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get clients list with pagination and search
     * @param int $page
     * @param int $limit
     * @param string $search
     * @param string $orderBy
     * @return array
     */
    public function getClientsList($page = 1, $limit = 10, $search = '', $orderBy = 'created_at DESC') {
        $offset = ($page - 1) * $limit;
        
        $searchWhere = '';
        $params = [];
        
        if (!empty($search)) {
            $searchWhere = "WHERE (company_name LIKE ? OR contact_name LIKE ? OR email LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM vw_clients {$searchWhere}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        // Get clients data
        $sql = "SELECT client_id, company_name, contact_name, email, phone, created_at 
                FROM vw_clients 
                {$searchWhere} 
                ORDER BY {$orderBy} 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $clients = $this->db->fetchAll($sql, $params);
        
        return [
            'data' => $clients,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit)
        ];
    }
    
    /**
     * Get client by ID
     * @param int $clientId
     * @return array|false
     */
    public function getClientById($clientId) {
        $sql = "SELECT * FROM clients WHERE client_id = ? AND deleted_at IS NULL";
        return $this->db->fetch($sql, [$clientId]);
    }
    
    /**
     * Create new client
     * @param array $data
     * @return bool|int
     */
    public function createClient($data) {
        try {
            $sql = "INSERT INTO clients (company_name, contact_name, email, phone, address, tax_id, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $data['company_name'],
                $data['contact_name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['address'] ?? null,
                $data['tax_id'] ?? null,
                getCurrentUser()['user_id']
            ];
            
            $this->db->execute($sql, $params);
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            logError("Error creating client: " . $e->getMessage());
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
            logError("Error updating client: " . $e->getMessage());
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
            logError("Error deleting client: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if email exists (for validation)
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
     * Get client activity summary
     * @param int $clientId
     * @return array
     */
    public function getClientActivity($clientId) {
        $sql = "SELECT ca.activity_type, ca.activity_date, ca.details,
                       q.quote_number, q.total_amount
                FROM client_activities ca
                LEFT JOIN quotes q ON ca.quote_id = q.quote_id
                WHERE ca.client_id = ?
                ORDER BY ca.activity_date DESC
                LIMIT 10";
        
        return $this->db->fetchAll($sql, [$clientId]);
    }
    
    /**
     * Get client quotes summary
     * @param int $clientId
     * @return array
     */
    public function getClientQuotes($clientId) {
        $sql = "SELECT quote_id, quote_number, status, total_amount, issue_date, expiry_date
                FROM quotes
                WHERE client_id = ?
                ORDER BY created_at DESC
                LIMIT 5";
        
        return $this->db->fetchAll($sql, [$clientId]);
    }
}