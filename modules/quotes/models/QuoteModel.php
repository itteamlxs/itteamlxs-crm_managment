<?php
/**
 * Quote Model
 * Database operations for quotes using views and prepared statements
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';

class QuoteModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get quotes list using direct table queries for filtering
     * @param array $params
     * @return array
     */
    public function getQuotesList($params = []) {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $status = $params['status'] ?? '';
        $client_id = $params['client_id'] ?? '';
        $user_id = $params['user_id'] ?? '';
        
        $pagination = validatePagination($page, $limit, 50);
        
        $where = [];
        $bindParams = [];
        
        if (!empty($search)) {
            $where[] = "(q.quote_number LIKE ? OR c.company_name LIKE ?)";
            $bindParams[] = "%{$search}%";
            $bindParams[] = "%{$search}%";
        }
        
        if (!empty($status)) {
            $where[] = "q.status = ?";
            $bindParams[] = $status;
        }
        
        if (!empty($client_id)) {
            $where[] = "q.client_id = ?";
            $bindParams[] = $client_id;
        }
        
        if (!empty($user_id)) {
            $where[] = "q.user_id = ?";
            $bindParams[] = $user_id;
        }
        
        $whereClause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);
        
        // Count total records using direct tables
        $countSql = "SELECT COUNT(*) as total 
                     FROM quotes q
                     JOIN clients c ON q.client_id = c.client_id
                     JOIN users u ON q.user_id = u.user_id
                     {$whereClause}";
        
        $totalResult = $this->db->fetch($countSql, $bindParams);
        $total = $totalResult['total'] ?? 0;
        
        // Get records using direct tables
        $sql = "SELECT q.quote_id, q.quote_number, q.status, q.total_amount, q.issue_date, q.expiry_date, 
                       c.company_name as client_name, u.username
                FROM quotes q
                JOIN clients c ON q.client_id = c.client_id
                JOIN users u ON q.user_id = u.user_id
                {$whereClause}
                ORDER BY q.issue_date DESC
                LIMIT ? OFFSET ?";
        
        $bindParams[] = $pagination['limit'];
        $bindParams[] = $pagination['offset'];
        
        $quotes = $this->db->fetchAll($sql, $bindParams);
        
        return [
            'quotes' => $quotes,
            'total' => $total,
            'page' => $pagination['page'],
            'limit' => $pagination['limit'],
            'total_pages' => ceil($total / $pagination['limit'])
        ];
    }
    
    /**
     * Get quote by ID using vw_quotes view
     * @param int $quoteId
     * @return array|false
     */
    public function getQuoteById($quoteId) {
        $sql = "SELECT q.*, c.company_name, c.contact_name, c.email as client_email,
                       c.phone as client_phone, c.address as client_address,
                       u.display_name as created_by_name
                FROM quotes q
                JOIN clients c ON q.client_id = c.client_id
                JOIN users u ON q.user_id = u.user_id
                WHERE q.quote_id = ?";
        
        return $this->db->fetch($sql, [$quoteId]);
    }
    
    /**
     * Get quote items using vw_quote_items view
     * @param int $quoteId
     * @return array
     */
    public function getQuoteItems($quoteId) {
        $sql = "SELECT qi.quote_item_id, qi.quote_id, qi.product_id, qi.quantity, qi.unit_price, qi.discount, 
                       qi.tax_amount, qi.subtotal, p.product_name, p.sku
                FROM quote_items qi
                JOIN products p ON qi.product_id = p.product_id
                WHERE qi.quote_id = ?
                ORDER BY qi.quote_item_id";
        
        return $this->db->fetchAll($sql, [$quoteId]);
    }
    
    /**
     * Get clients for dropdown
     * @return array
     */
    public function getActiveClients() {
        $sql = "SELECT client_id, company_name, contact_name, email 
                FROM vw_clients 
                ORDER BY company_name";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get products for quote items
     * @return array
     */
    public function getAvailableProducts() {
        $sql = "SELECT product_id, product_name, sku, price, tax_rate, stock_quantity, category_name
                FROM vw_products 
                WHERE stock_quantity > 0
                ORDER BY category_name, product_name";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Generate next quote number
     * @return string
     */
    public function generateQuoteNumber() {
        $prefix = 'QT' . date('Y') . '-';
        
        $sql = "SELECT quote_number FROM quotes 
                WHERE quote_number LIKE ? 
                ORDER BY quote_number DESC 
                LIMIT 1";
        
        $result = $this->db->fetch($sql, [$prefix . '%']);
        
        if ($result) {
            $lastNumber = str_replace($prefix, '', $result['quote_number']);
            $nextNumber = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }
        
        return $prefix . $nextNumber;
    }
    
    /**
     * Create new quote
     * @param array $quoteData
     * @param array $items
     * @return int|false
     */
    public function createQuote($quoteData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Insert quote
            $sql = "INSERT INTO quotes (client_id, user_id, quote_number, status, 
                                      total_amount, issue_date, expiry_date, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $this->db->execute($sql, [
                $quoteData['client_id'],
                $quoteData['user_id'],
                $quoteData['quote_number'],
                $quoteData['status'],
                $quoteData['total_amount'],
                $quoteData['issue_date'],
                $quoteData['expiry_date']
            ]);
            
            $quoteId = $this->db->lastInsertId();
            
            // Insert quote items
            foreach ($items as $item) {
                $itemSql = "INSERT INTO quote_items (quote_id, product_id, quantity, 
                                                   unit_price, discount, tax_amount, subtotal, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                
                $this->db->execute($itemSql, [
                    $quoteId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['discount'],
                    $item['tax_amount'],
                    $item['subtotal']
                ]);
            }
            
            $this->db->commit();
            return $quoteId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Error creating quote: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update existing quote
     * @param int $quoteId
     * @param array $quoteData
     * @param array $items
     * @return bool
     */
    public function updateQuote($quoteId, $quoteData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Update quote
            $sql = "UPDATE quotes 
                    SET client_id = ?, status = ?, total_amount = ?, 
                        issue_date = ?, expiry_date = ?, updated_at = NOW()
                    WHERE quote_id = ?";
            
            $this->db->execute($sql, [
                $quoteData['client_id'],
                $quoteData['status'],
                $quoteData['total_amount'],
                $quoteData['issue_date'],
                $quoteData['expiry_date'],
                $quoteId
            ]);
            
            // Delete existing items
            $deleteSql = "DELETE FROM quote_items WHERE quote_id = ?";
            $this->db->execute($deleteSql, [$quoteId]);
            
            // Insert new items
            foreach ($items as $item) {
                $itemSql = "INSERT INTO quote_items (quote_id, product_id, quantity, 
                                                   unit_price, discount, tax_amount, subtotal, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                
                $this->db->execute($itemSql, [
                    $quoteId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['discount'],
                    $item['tax_amount'],
                    $item['subtotal']
                ]);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Error updating quote: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Approve quote with manual stock update and activity logging
     * @param int $quoteId
     * @return bool
     */
    public function approveQuote($quoteId) {
        try {
            $this->db->beginTransaction();
            
            // Get quote data
            $quote = $this->getQuoteById($quoteId);
            if (!$quote) {
                throw new Exception("Quote not found");
            }
            
            // Get quote items
            $items = $this->getQuoteItems($quoteId);
            
            // Update stock for each item
            foreach ($items as $item) {
                $updateStockSql = "UPDATE products 
                                  SET stock_quantity = stock_quantity - ? 
                                  WHERE product_id = ? AND stock_quantity >= ?";
                
                $this->db->execute($updateStockSql, [
                    $item['quantity'],
                    $item['product_id'],
                    $item['quantity']
                ]);
            }
            
            // Update quote status and mark stock as updated
            $updateQuoteSql = "UPDATE quotes 
                              SET status = 'APPROVED', stock_updated = TRUE, updated_at = NOW()
                              WHERE quote_id = ?";
            
            $this->db->execute($updateQuoteSql, [$quoteId]);
            
            // Log client activity
            $activitySql = "INSERT INTO client_activities (client_id, quote_id, activity_type, activity_date, details)
                           VALUES (?, ?, 'QUOTE_APPROVED', NOW(), ?)";
            
            $this->db->execute($activitySql, [
                $quote['client_id'],
                $quoteId,
                json_encode(['total_amount' => $quote['total_amount']])
            ]);
            
            // Log audit entry
            $auditSql = "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, created_at)
                        VALUES (?, 'APPROVE_QUOTE', 'QUOTE', ?, ?, ?, ?, NOW())";
            
            $this->db->execute($auditSql, [
                getCurrentUser()['user_id'],
                $quoteId,
                json_encode(['status' => 'SENT']),
                json_encode(['status' => 'APPROVED', 'stock_updated' => true]),
                getClientIP()
            ]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Error approving quote: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update quote status (for non-approval status changes)
     * @param int $quoteId
     * @param string $status
     * @return bool
     */
    public function updateQuoteStatus($quoteId, $status) {
        try {
            // For approval, use the specialized method
            if ($status === 'APPROVED') {
                return $this->approveQuote($quoteId);
            }
            
            $sql = "UPDATE quotes 
                    SET status = ?, updated_at = NOW()
                    WHERE quote_id = ?";
            
            $this->db->execute($sql, [$status, $quoteId]);
            return true;
            
        } catch (Exception $e) {
            logError("Error updating quote status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create renewal quote
     * @param int $parentQuoteId
     * @param array $quoteData
     * @param array $items
     * @return int|false
     */
    public function createRenewalQuote($parentQuoteId, $quoteData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Insert renewal quote with parent reference
            $sql = "INSERT INTO quotes (client_id, user_id, parent_quote_id, quote_number, 
                                      status, total_amount, issue_date, expiry_date, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $this->db->execute($sql, [
                $quoteData['client_id'],
                $quoteData['user_id'],
                $parentQuoteId,
                $quoteData['quote_number'],
                $quoteData['status'],
                $quoteData['total_amount'],
                $quoteData['issue_date'],
                $quoteData['expiry_date']
            ]);
            
            $quoteId = $this->db->lastInsertId();
            
            // Insert quote items
            foreach ($items as $item) {
                $itemSql = "INSERT INTO quote_items (quote_id, product_id, quantity, 
                                                   unit_price, discount, tax_amount, subtotal, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                
                $this->db->execute($itemSql, [
                    $quoteId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['discount'],
                    $item['tax_amount'],
                    $item['subtotal']
                ]);
            }
            
            $this->db->commit();
            return $quoteId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Error creating renewal quote: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get expiring quotes using view
     * @return array
     */
    public function getExpiringQuotes() {
        $sql = "SELECT quote_id, quote_number, client_id, client_name, 
                       expiry_date, days_until_expiry
                FROM vw_expiring_quotes
                ORDER BY days_until_expiry ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Check if quote can be approved (stock availability)
     * @param int $quoteId
     * @return array
     */
    public function checkStockAvailability($quoteId) {
        $sql = "SELECT qi.product_id, qi.quantity, p.stock_quantity, p.product_name
                FROM quote_items qi
                JOIN products p ON qi.product_id = p.product_id
                WHERE qi.quote_id = ?";
        
        $items = $this->db->fetchAll($sql, [$quoteId]);
        $insufficientStock = [];
        
        foreach ($items as $item) {
            if ($item['stock_quantity'] < $item['quantity']) {
                $insufficientStock[] = [
                    'product_name' => $item['product_name'],
                    'required' => $item['quantity'],
                    'available' => $item['stock_quantity']
                ];
            }
        }
        
        return [
            'can_approve' => empty($insufficientStock),
            'insufficient_stock' => $insufficientStock
        ];
    }
}