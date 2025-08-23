<?php
/**
 * Quote Model
 * Handles quote database operations using views and prepared statements
 * Strictly adheres to schema: uses vw_quotes, vw_quote_items, vw_expiring_quotes
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';

class QuoteModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all quotes using vw_quotes view
     * @param array $filters
     * @param array $pagination
     * @return array
     */
    public function getAllQuotes($filters = [], $pagination = []) {
        try {
            $sql = "SELECT * FROM vw_quotes WHERE 1=1";
            $params = [];
            
            // Apply filters
            if (!empty($filters['status'])) {
                $sql .= " AND status = ?";
                $params[] = $filters['status'];
            }
            
            if (!empty($filters['client_name'])) {
                $sql .= " AND client_name LIKE ?";
                $params[] = '%' . $filters['client_name'] . '%';
            }
            
            if (!empty($filters['username'])) {
                $sql .= " AND username LIKE ?";
                $params[] = '%' . $filters['username'] . '%';
            }
            
            if (!empty($filters['date_from'])) {
                $sql .= " AND issue_date >= ?";
                $params[] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $sql .= " AND issue_date <= ?";
                $params[] = $filters['date_to'];
            }
            
            // Order by
            $allowedColumns = ['quote_number', 'client_name', 'status', 'total_amount', 'issue_date', 'expiry_date'];
            $orderBy = sanitizeOrderBy($filters['order_by'] ?? 'issue_date DESC', $allowedColumns);
            $sql .= " ORDER BY " . $orderBy;
            
            // Pagination
            if (!empty($pagination['limit'])) {
                $sql .= " LIMIT ? OFFSET ?";
                $params[] = (int)$pagination['limit'];
                $params[] = (int)($pagination['offset'] ?? 0);
            }
            
            return $this->db->fetchAll($sql, $params);
            
        } catch (Exception $e) {
            logError("Get quotes failed: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get quote count for pagination
     * @param array $filters
     * @return int
     */
    public function getQuoteCount($filters = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM vw_quotes WHERE 1=1";
            $params = [];
            
            // Apply same filters as getAllQuotes
            if (!empty($filters['status'])) {
                $sql .= " AND status = ?";
                $params[] = $filters['status'];
            }
            
            if (!empty($filters['client_name'])) {
                $sql .= " AND client_name LIKE ?";
                $params[] = '%' . $filters['client_name'] . '%';
            }
            
            if (!empty($filters['username'])) {
                $sql .= " AND username LIKE ?";
                $params[] = '%' . $filters['username'] . '%';
            }
            
            if (!empty($filters['date_from'])) {
                $sql .= " AND issue_date >= ?";
                $params[] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $sql .= " AND issue_date <= ?";
                $params[] = $filters['date_to'];
            }
            
            $result = $this->db->fetch($sql, $params);
            return (int)($result['count'] ?? 0);
            
        } catch (Exception $e) {
            logError("Get quote count failed: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get quote by ID with items using views
     * @param int $quoteId
     * @return array|false
     */
    public function getQuoteById($quoteId) {
        try {
            // Get quote details from vw_quotes
            $sql = "SELECT * FROM vw_quotes WHERE quote_id = ?";
            $quote = $this->db->fetch($sql, [$quoteId]);
            
            if (!$quote) {
                return false;
            }
            
            // Get quote items from vw_quote_items
            $sql = "SELECT * FROM vw_quote_items WHERE quote_id = ?";
            $quote['items'] = $this->db->fetchAll($sql, [$quoteId]);
            
            return $quote;
            
        } catch (Exception $e) {
            logError("Get quote by ID failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get quote details directly from tables (for editing)
     * @param int $quoteId
     * @return array|false
     */
    public function getQuoteForEdit($quoteId) {
        try {
            $sql = "SELECT q.*, c.company_name, c.contact_name, c.email as client_email
                    FROM quotes q 
                    JOIN clients c ON q.client_id = c.client_id 
                    WHERE q.quote_id = ?";
            $quote = $this->db->fetch($sql, [$quoteId]);
            
            if (!$quote) {
                return false;
            }
            
            // Get quote items with product details
            $sql = "SELECT qi.*, p.product_name, p.sku, p.price as current_price, p.tax_rate
                    FROM quote_items qi
                    JOIN products p ON qi.product_id = p.product_id
                    WHERE qi.quote_id = ?";
            $quote['items'] = $this->db->fetchAll($sql, [$quoteId]);
            
            return $quote;
            
        } catch (Exception $e) {
            logError("Get quote for edit failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new quote with items
     * @param array $quoteData
     * @param array $items
     * @return int|false Quote ID or false on failure
     */
    public function createQuote($quoteData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Generate quote number
            $quoteNumber = $this->generateQuoteNumber();
            
            // Insert quote
            $sql = "INSERT INTO quotes (client_id, user_id, parent_quote_id, quote_number, 
                                      status, total_amount, issue_date, expiry_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $quoteData['client_id'],
                $quoteData['user_id'],
                $quoteData['parent_quote_id'] ?? null,
                $quoteNumber,
                $quoteData['status'] ?? 'DRAFT',
                $quoteData['total_amount'],
                $quoteData['issue_date'],
                $quoteData['expiry_date']
            ];
            
            $this->db->execute($sql, $params);
            $quoteId = $this->db->lastInsertId();
            
            // Insert quote items
            foreach ($items as $item) {
                $this->insertQuoteItem($quoteId, $item);
            }
            
            $this->db->commit();
            return $quoteId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Create quote failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update quote
     * @param int $quoteId
     * @param array $quoteData
     * @param array $items
     * @return bool
     */
    public function updateQuote($quoteId, $quoteData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Update quote
            $sql = "UPDATE quotes SET client_id = ?, status = ?, total_amount = ?, 
                                     issue_date = ?, expiry_date = ?, updated_at = NOW()
                    WHERE quote_id = ?";
            
            $params = [
                $quoteData['client_id'],
                $quoteData['status'],
                $quoteData['total_amount'],
                $quoteData['issue_date'],
                $quoteData['expiry_date'],
                $quoteId
            ];
            
            $this->db->execute($sql, $params);
            
            // Delete existing items and insert new ones
            $sql = "DELETE FROM quote_items WHERE quote_id = ?";
            $this->db->execute($sql, [$quoteId]);
            
            // Insert updated items
            foreach ($items as $item) {
                $this->insertQuoteItem($quoteId, $item);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Update quote failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Approve quote (triggers stock update via DB trigger)
     * @param int $quoteId
     * @return bool
     */
    public function approveQuote($quoteId) {
        try {
            $sql = "UPDATE quotes SET status = 'APPROVED', updated_at = NOW() WHERE quote_id = ?";
            $this->db->execute($sql, [$quoteId]);
            
            // The database trigger will handle:
            // - Stock quantity updates
            // - Client activity logging
            // - Audit log entries
            
            return true;
            
        } catch (Exception $e) {
            logError("Approve quote failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Renew quote (create copy with new dates)
     * @param int $parentQuoteId
     * @param array $renewalData
     * @return int|false New quote ID or false
     */
    public function renewQuote($parentQuoteId, $renewalData) {
        try {
            // Get parent quote
            $parentQuote = $this->getQuoteForEdit($parentQuoteId);
            if (!$parentQuote) {
                return false;
            }
            
            // Prepare new quote data
            $newQuoteData = [
                'client_id' => $parentQuote['client_id'],
                'user_id' => $renewalData['user_id'],
                'parent_quote_id' => $parentQuoteId,
                'status' => 'DRAFT',
                'total_amount' => $renewalData['total_amount'],
                'issue_date' => $renewalData['issue_date'],
                'expiry_date' => $renewalData['expiry_date']
            ];
            
            // Update items with current prices if needed
            $items = [];
            foreach ($parentQuote['items'] as $item) {
                $items[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $renewalData['update_quantities'] ? 
                                 $renewalData['items'][$item['product_id']]['quantity'] : 
                                 $item['quantity'],
                    'unit_price' => $renewalData['update_prices'] ? 
                                   $item['current_price'] : 
                                   $item['unit_price'],
                    'discount' => $item['discount'],
                    'tax_amount' => $this->calculateTaxAmount($item['unit_price'], $item['quantity'], $item['tax_rate'], $item['discount']),
                    'subtotal' => $this->calculateSubtotal($item['unit_price'], $item['quantity'], $item['discount'])
                ];
            }
            
            return $this->createQuote($newQuoteData, $items);
            
        } catch (Exception $e) {
            logError("Renew quote failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get expiring quotes using view
     * @return array
     */
    public function getExpiringQuotes() {
        try {
            $sql = "SELECT * FROM vw_expiring_quotes ORDER BY days_until_expiry ASC";
            return $this->db->fetchAll($sql);
            
        } catch (Exception $e) {
            logError("Get expiring quotes failed: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get clients for dropdown
     * @return array
     */
    public function getClients() {
        try {
            $sql = "SELECT client_id, company_name, contact_name, email FROM vw_clients ORDER BY company_name";
            return $this->db->fetchAll($sql);
            
        } catch (Exception $e) {
            logError("Get clients failed: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get products for quote items
     * @return array
     */
    public function getProducts() {
        try {
            $sql = "SELECT product_id, product_name, sku, price, tax_rate, stock_quantity, category_name 
                    FROM vw_products ORDER BY category_name, product_name";
            return $this->db->fetchAll($sql);
            
        } catch (Exception $e) {
            logError("Get products failed: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Check stock availability for quote approval
     * @param int $quoteId
     * @return array ['available' => bool, 'issues' => array]
     */
    public function checkStockAvailability($quoteId) {
        try {
            $sql = "SELECT qi.product_id, qi.quantity, p.product_name, p.stock_quantity
                    FROM quote_items qi
                    JOIN products p ON qi.product_id = p.product_id
                    WHERE qi.quote_id = ?";
            
            $items = $this->db->fetchAll($sql, [$quoteId]);
            $issues = [];
            
            foreach ($items as $item) {
                if ($item['stock_quantity'] < $item['quantity']) {
                    $issues[] = [
                        'product_name' => $item['product_name'],
                        'required' => $item['quantity'],
                        'available' => $item['stock_quantity'],
                        'shortage' => $item['quantity'] - $item['stock_quantity']
                    ];
                }
            }
            
            return [
                'available' => empty($issues),
                'issues' => $issues
            ];
            
        } catch (Exception $e) {
            logError("Check stock availability failed: " . $e->getMessage());
            return ['available' => false, 'issues' => []];
        }
    }
    
    /**
     * Insert quote item
     * @param int $quoteId
     * @param array $item
     */
    private function insertQuoteItem($quoteId, $item) {
        $sql = "INSERT INTO quote_items (quote_id, product_id, quantity, unit_price, 
                                        discount, tax_amount, subtotal) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $quoteId,
            $item['product_id'],
            $item['quantity'],
            $item['unit_price'],
            $item['discount'],
            $item['tax_amount'],
            $item['subtotal']
        ];
        
        $this->db->execute($sql, $params);
    }
    
    /**
     * Generate unique quote number
     * @return string
     */
    private function generateQuoteNumber() {
        $prefix = 'Q-' . date('Y') . '-';
        $sql = "SELECT MAX(CAST(SUBSTRING(quote_number, 8) AS UNSIGNED)) as max_num 
                FROM quotes WHERE quote_number LIKE ?";
        $result = $this->db->fetch($sql, [$prefix . '%']);
        
        $nextNum = ($result['max_num'] ?? 0) + 1;
        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Calculate tax amount
     * @param float $unitPrice
     * @param int $quantity
     * @param float $taxRate
     * @param float $discount
     * @return float
     */
    private function calculateTaxAmount($unitPrice, $quantity, $taxRate, $discount) {
        $subtotal = ($unitPrice * $quantity) * (1 - $discount / 100);
        return $subtotal * ($taxRate / 100);
    }
    
    /**
     * Calculate subtotal
     * @param float $unitPrice
     * @param int $quantity
     * @param float $discount
     * @return float
     */
    private function calculateSubtotal($unitPrice, $quantity, $discount) {
        return ($unitPrice * $quantity) * (1 - $discount / 100);
    }
    
    /**
     * Get quote statistics for user
     * @param int $userId
     * @return array
     */
    public function getUserQuoteStats($userId) {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_quotes,
                        SUM(CASE WHEN status = 'DRAFT' THEN 1 ELSE 0 END) as draft_quotes,
                        SUM(CASE WHEN status = 'SENT' THEN 1 ELSE 0 END) as sent_quotes,
                        SUM(CASE WHEN status = 'APPROVED' THEN 1 ELSE 0 END) as approved_quotes,
                        SUM(CASE WHEN status = 'REJECTED' THEN 1 ELSE 0 END) as rejected_quotes,
                        SUM(total_amount) as total_amount,
                        AVG(total_amount) as avg_amount
                    FROM quotes WHERE user_id = ?";
            
            return $this->db->fetch($sql, [$userId]) ?: [];
            
        } catch (Exception $e) {
            logError("Get user quote stats failed: " . $e->getMessage());
            return [];
        }
    }
}