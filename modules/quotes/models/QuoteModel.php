<?php
/**
 * Quote Model
 * Database operations for quotes and quote items
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';

class QuoteModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get quotes using view with pagination and search
     */
    public function getQuotes($search = '', $status = '', $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $params = [];
        
        $sql = "SELECT q.quote_id, q.quote_number, q.status, q.total_amount, 
                       q.issue_date, q.expiry_date, q.client_name, q.username
                FROM vw_quotes q WHERE 1=1";
        
        if (!empty($search)) {
            $sql .= " AND (q.quote_number LIKE ? OR q.client_name LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if (!empty($status)) {
            $sql .= " AND q.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY q.issue_date DESC LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get total count of quotes for pagination
     */
    public function getQuotesCount($search = '', $status = '') {
        $params = [];
        
        $sql = "SELECT COUNT(*) as count FROM vw_quotes q WHERE 1=1";
        
        if (!empty($search)) {
            $sql .= " AND (q.quote_number LIKE ? OR q.client_name LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if (!empty($status)) {
            $sql .= " AND q.status = ?";
            $params[] = $status;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] ?? 0;
    }
    
    /**
     * Get quote by ID with items
     */
    public function getQuoteById($quoteId) {
        $sql = "SELECT * FROM quotes WHERE quote_id = ?";
        $quote = $this->db->fetch($sql, [$quoteId]);
        
        if ($quote) {
            $quote['items'] = $this->getQuoteItems($quoteId);
        }
        
        return $quote;
    }
    
    /**
     * Get quote items using view
     */
    public function getQuoteItems($quoteId) {
        $sql = "SELECT * FROM vw_quote_items WHERE quote_id = ?";
        return $this->db->fetchAll($sql, [$quoteId]);
    }
    
    /**
     * Get client by ID
     */
    public function getClientById($clientId) {
        $sql = "SELECT * FROM clients WHERE client_id = ? AND deleted_at IS NULL";
        return $this->db->fetch($sql, [$clientId]);
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($userId) {
        $sql = "SELECT user_id, username, display_name, email FROM users WHERE user_id = ?";
        return $this->db->fetch($sql, [$userId]);
    }
    
    /**
     * Get clients for dropdown
     */
    public function getClients() {
        $sql = "SELECT client_id, company_name, contact_name, email FROM vw_clients ORDER BY company_name";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get products for quote items
     */
    public function getProducts() {
        $sql = "SELECT product_id, product_name, sku, price, tax_rate, stock_quantity, category_name 
                FROM vw_products WHERE stock_quantity > 0 ORDER BY product_name";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get product by ID
     */
    public function getProductById($productId) {
        $sql = "SELECT * FROM vw_products WHERE product_id = ?";
        return $this->db->fetch($sql, [$productId]);
    }
    
    /**
     * Generate next quote number
     */
    public function generateQuoteNumber() {
        $prefix = 'Q' . date('Y');
        
        $sql = "SELECT MAX(CAST(SUBSTRING(quote_number, 6) AS UNSIGNED)) as max_num 
                FROM quotes WHERE quote_number LIKE ?";
        
        $result = $this->db->fetch($sql, [$prefix . '%']);
        $nextNum = ($result['max_num'] ?? 0) + 1;
        
        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Create new quote with items
     */
    public function createQuote($data, $items) {
        $this->db->beginTransaction();
        
        try {
            // Insert quote
            $sql = "INSERT INTO quotes (client_id, user_id, quote_number, status, total_amount, 
                                      issue_date, expiry_date, parent_quote_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $this->db->execute($sql, [
                $data['client_id'],
                $data['user_id'],
                $data['quote_number'],
                $data['status'],
                $data['total_amount'],
                $data['issue_date'],
                $data['expiry_date'],
                $data['parent_quote_id'] ?? null
            ]);
            
            $quoteId = $this->db->lastInsertId();
            
            // Insert quote items
            $itemSql = "INSERT INTO quote_items (quote_id, product_id, quantity, unit_price, 
                                               discount, tax_amount, subtotal) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            foreach ($items as $item) {
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
            
            // Log client activity
            $this->logClientActivity($data['client_id'], $quoteId, 'QUOTE_CREATED', [
                'total_amount' => $data['total_amount']
            ]);
            
            $this->db->commit();
            return $quoteId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    /**
     * Update existing quote with items
     */
    public function updateQuote($quoteId, $data, $items) {
        $this->db->beginTransaction();
        
        try {
            // Update quote
            $sql = "UPDATE quotes SET client_id = ?, total_amount = ?, expiry_date = ?, 
                                     updated_at = NOW() WHERE quote_id = ?";
            
            $this->db->execute($sql, [
                $data['client_id'],
                $data['total_amount'],
                $data['expiry_date'],
                $quoteId
            ]);
            
            // Delete existing items
            $this->db->execute("DELETE FROM quote_items WHERE quote_id = ?", [$quoteId]);
            
            // Insert new items
            $itemSql = "INSERT INTO quote_items (quote_id, product_id, quantity, unit_price, 
                                               discount, tax_amount, subtotal) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            foreach ($items as $item) {
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
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    /**
     * Update quote status (simple version without triggers)
     */
    public function updateQuoteStatus($quoteId, $status) {
        // Disable triggers temporarily by using direct query
        $sql = "UPDATE quotes SET status = ?, updated_at = NOW() WHERE quote_id = ?";
        $this->db->execute($sql, [$status, $quoteId]);
    }
    
    /**
     * Approve quote manually (avoid trigger conflicts)
     */
    public function approveQuoteManual($quoteId) {
        $this->db->beginTransaction();
        
        try {
            // 1. Update quote status first
            $sql = "UPDATE quotes SET status = 'APPROVED', updated_at = NOW() WHERE quote_id = ?";
            $this->db->execute($sql, [$quoteId]);
            
            // 2. Manually update stock quantities
            $itemsSql = "SELECT qi.product_id, qi.quantity 
                        FROM quote_items qi 
                        WHERE qi.quote_id = ?";
            
            $items = $this->db->fetchAll($itemsSql, [$quoteId]);
            
            foreach ($items as $item) {
                $updateStockSql = "UPDATE products 
                                  SET stock_quantity = stock_quantity - ? 
                                  WHERE product_id = ?";
                $this->db->execute($updateStockSql, [$item['quantity'], $item['product_id']]);
            }
            
            // 3. Get quote and client info for activity log
            $quote = $this->getQuoteById($quoteId);
            
            // 4. Log client activity manually
            $this->logClientActivity($quote['client_id'], $quoteId, 'QUOTE_APPROVED', [
                'approved_amount' => $quote['total_amount'],
                'approved_by' => getCurrentUser()['user_id'] ?? 'system'
            ]);
            
            $this->db->commit();
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    /**
     * Check if quote can be approved (stock availability)
     */
    public function canApproveQuote($quoteId) {
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
        
        return empty($insufficientStock) ? ['can_approve' => true] : 
               ['can_approve' => false, 'insufficient_stock' => $insufficientStock];
    }
    
    /**
     * Get expiring quotes using view
     */
    public function getExpiringQuotes() {
        $sql = "SELECT * FROM vw_expiring_quotes ORDER BY days_until_expiry ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Renew quote (create copy with new dates)
     */
    public function renewQuote($originalQuoteId, $newExpiryDate) {
        $originalQuote = $this->getQuoteById($originalQuoteId);
        
        if (!$originalQuote) {
            throw new Exception('Quote not found');
        }
        
        $this->db->beginTransaction();
        
        try {
            // Create new quote
            $newQuoteNumber = $this->generateQuoteNumber();
            
            $sql = "INSERT INTO quotes (client_id, user_id, parent_quote_id, quote_number, 
                                      status, total_amount, issue_date, expiry_date) 
                    VALUES (?, ?, ?, ?, 'DRAFT', ?, ?, ?)";
            
            $this->db->execute($sql, [
                $originalQuote['client_id'],
                $originalQuote['user_id'],
                $originalQuoteId,
                $newQuoteNumber,
                $originalQuote['total_amount'],
                date('Y-m-d'),
                $newExpiryDate
            ]);
            
            $newQuoteId = $this->db->lastInsertId();
            
            // Copy items
            $itemSql = "INSERT INTO quote_items (quote_id, product_id, quantity, unit_price, 
                                               discount, tax_amount, subtotal)
                        SELECT ?, product_id, quantity, unit_price, discount, tax_amount, subtotal
                        FROM quote_items WHERE quote_id = ?";
            
            $this->db->execute($itemSql, [$newQuoteId, $originalQuoteId]);
            
            $this->db->commit();
            return $newQuoteId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    /**
     * Duplicate quote (create exact copy as DRAFT)
     */
    public function duplicateQuote($originalQuoteId) {
        $originalQuote = $this->getQuoteById($originalQuoteId);
        
        if (!$originalQuote) {
            throw new Exception('Quote not found');
        }
        
        $this->db->beginTransaction();
        
        try {
            // Create new quote
            $newQuoteNumber = $this->generateQuoteNumber();
            
            $sql = "INSERT INTO quotes (client_id, user_id, quote_number, status, total_amount, 
                                      issue_date, expiry_date) 
                    VALUES (?, ?, ?, 'DRAFT', ?, ?, ?)";
            
            $this->db->execute($sql, [
                $originalQuote['client_id'],
                getCurrentUser()['user_id'],
                $newQuoteNumber,
                $originalQuote['total_amount'],
                date('Y-m-d'),
                date('Y-m-d', strtotime('+7 days'))
            ]);
            
            $newQuoteId = $this->db->lastInsertId();
            
            // Copy items
            $itemSql = "INSERT INTO quote_items (quote_id, product_id, quantity, unit_price, 
                                               discount, tax_amount, subtotal)
                        SELECT ?, product_id, quantity, unit_price, discount, tax_amount, subtotal
                        FROM quote_items WHERE quote_id = ?";
            
            $this->db->execute($itemSql, [$newQuoteId, $originalQuoteId]);
            
            $this->db->commit();
            return $newQuoteId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    /**
     * Get SMTP settings
     */
    public function getSmtpSettings() {
        $sql = "SELECT setting_key, setting_value FROM settings 
                WHERE setting_key IN ('smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 
                                     'smtp_encryption', 'from_email', 'from_name')";
        
        $settings = $this->db->fetchAll($sql);
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return empty($result) ? null : $result;
    }
    
    /**
     * Get company settings for PDF
     */
    public function getCompanySettings() {
        $sql = "SELECT setting_key, setting_value FROM settings 
                WHERE setting_key IN ('company_display_name', 'company_address', 
                                     'company_phone', 'company_email')";
        
        $settings = $this->db->fetchAll($sql);
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $result;
    }
    
    /**
     * Generate PDF file and return path
     */
    public function generateQuotePdf($quoteId) {
        // This would integrate with the PDF controller logic
        // For now, return a placeholder path
        return '/tmp/quote_' . $quoteId . '_' . time() . '.pdf';
    }
    
    /**
     * Get email template for quote
     */
    public function getQuoteEmailTemplate($quote, $client, $customMessage = '') {
        $template = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background-color: #f8f9fa; padding: 15px; font-size: 12px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Cotización #{$quote['quote_number']}</h1>
            </div>
            <div class='content'>
                <p>Estimado/a {$client['contact_name']},</p>
                
                <p>Adjunto encontrará la cotización #{$quote['quote_number']} solicitada.</p>
                
                " . (!empty($customMessage) ? "<p>{$customMessage}</p>" : "") . "
                
                <p><strong>Detalles de la cotización:</strong></p>
                <ul>
                    <li>Número: {$quote['quote_number']}</li>
                    <li>Fecha: " . date('d/m/Y', strtotime($quote['issue_date'])) . "</li>
                    <li>Válida hasta: " . date('d/m/Y', strtotime($quote['expiry_date'])) . "</li>
                    <li>Total: $" . number_format($quote['total_amount'], 2) . "</li>
                </ul>
                
                <p>Si tiene alguna pregunta, no dude en contactarnos.</p>
                
                <p>Saludos cordiales.</p>
            </div>
            <div class='footer'>
                <p>Este es un mensaje automático. Por favor, no responda a este email.</p>
            </div>
        </body>
        </html>";
        
        return $template;
    }
    
    /**
     * Log client activity
     */
    public function logClientActivity($clientId, $quoteId, $activityType, $details = []) {
        $sql = "INSERT INTO client_activities (client_id, quote_id, activity_type, details) 
                VALUES (?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $clientId,
            $quoteId,
            $activityType,
            json_encode($details)
        ]);
    }
}