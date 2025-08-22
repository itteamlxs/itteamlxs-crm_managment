<?php
/**
 * Client Model
 * Handles client database operations using views and direct queries
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';

class ClientModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all clients with pagination
     * @param int $limit
     * @param int $offset
     * @param string $search
     * @return array
     */
    public function getAllClients($limit = 10, $offset = 0, $search = '') {
        try {
            $searchCondition = '';
            $params = [];
            
            if (!empty($search)) {
                $searchCondition = "WHERE company_name LIKE ? OR contact_name LIKE ? OR email LIKE ?";
                $searchTerm = "%{$search}%";
                $params = [$searchTerm, $searchTerm, $searchTerm];
            }
            
            $sql = "SELECT client_id, company_name, contact_name, email, phone, created_at
                    FROM vw_clients
                    {$searchCondition}
                    ORDER BY company_name
                    LIMIT ? OFFSET ?";
            
            $params[] = $limit;
            $params[] = $offset;
            
            return $this->db->fetchAll($sql, $params);
        } catch (Exception $e) {
            logError("Get all clients error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get total clients count
     * @param string $search
     * @return int
     */
    public function getClientsCount($search = '') {
        try {
            $searchCondition = '';
            $params = [];
            
            if (!empty($search)) {
                $searchCondition = "WHERE company_name LIKE ? OR contact_name LIKE ? OR email LIKE ?";
                $searchTerm = "%{$search}%";
                $params = [$searchTerm, $searchTerm, $searchTerm];
            }
            
            $sql = "SELECT COUNT(*) as count FROM clients WHERE deleted_at IS NULL {$searchCondition}";
            $result = $this->db->fetch($sql, $params);
            
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            logError("Get clients count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get client by ID
     * @param int $clientId
     * @return array|false
     */
    public function getClientById($clientId) {
        try {
            $sql = "SELECT client_id, company_name, contact_name, email, phone, 
                           address, tax_id, created_by, created_at, updated_at
                    FROM clients 
                    WHERE client_id = ? AND deleted_at IS NULL";
            
            return $this->db->fetch($sql, [$clientId]);
        } catch (Exception $e) {
            logError("Get client by ID error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new client
     * @param array $clientData
     * @return int|false
     */
    public function createClient($clientData) {
        try {
            $sql = "INSERT INTO clients (company_name, contact_name, email, phone, 
                                       address, tax_id, created_by, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $params = [
                $clientData['company_name'],
                $clientData['contact_name'],
                $clientData['email'],
                $clientData['phone'] ?? null,
                $clientData['address'] ?? null,
                $clientData['tax_id'] ?? null,
                $clientData['created_by']
            ];
            
            $this->db->execute($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            logError("Create client error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update client
     * @param int $clientId
     * @param array $clientData
     * @return bool
     */
    public function updateClient($clientId, $clientData) {
        try {
            $sql = "UPDATE clients 
                    SET company_name = ?, contact_name = ?, email = ?, phone = ?, 
                        address = ?, tax_id = ?, updated_at = NOW()
                    WHERE client_id = ? AND deleted_at IS NULL";
            
            $params = [
                $clientData['company_name'],
                $clientData['contact_name'],
                $clientData['email'],
                $clientData['phone'] ?? null,
                $clientData['address'] ?? null,
                $clientData['tax_id'] ?? null,
                $clientId
            ];
            
            $this->db->execute($sql, $params);
            return true;
        } catch (Exception $e) {
            logError("Update client error: " . $e->getMessage());
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
            $sql = "UPDATE clients SET deleted_at = NOW() WHERE client_id = ?";
            $this->db->execute($sql, [$clientId]);
            return true;
        } catch (Exception $e) {
            logError("Delete client error: " . $e->getMessage());
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
        try {
            $sql = "SELECT COUNT(*) as count FROM clients WHERE email = ? AND deleted_at IS NULL";
            $params = [$email];
            
            if ($excludeClientId) {
                $sql .= " AND client_id != ?";
                $params[] = $excludeClientId;
            }
            
            $result = $this->db->fetch($sql, $params);
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            logError("Check email exists error: " . $e->getMessage());
            return true;
        }
    }
    
    /**
     * Get client activities
     * @param int $clientId
     * @return array
     */
    public function getClientActivities($clientId) {
        try {
            $sql = "SELECT activity_id, activity_type, activity_date, details
                    FROM client_activities 
                    WHERE client_id = ? 
                    ORDER BY activity_date DESC 
                    LIMIT 10";
            
            return $this->db->fetchAll($sql, [$clientId]);
        } catch (Exception $e) {
            logError("Get client activities error: " . $e->getMessage());
            return [];
        }
    }
}