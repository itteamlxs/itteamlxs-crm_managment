<?php
/**
 * Product Model
 * Uses vw_products, vw_category_summary, vw_low_stock_products views
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';

class ProductModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all products with categories
     */
    public function getAllProducts($page = 1, $limit = 10, $search = '') {
        try {
            $pagination = validatePagination($page, $limit);
            $searchPattern = '%' . $search . '%';
            
            $sql = "SELECT * FROM vw_products 
                    WHERE product_name LIKE ? OR sku LIKE ? OR category_name LIKE ?
                    ORDER BY product_name
                    LIMIT ? OFFSET ?";
            
            $products = $this->db->fetchAll($sql, [
                $searchPattern, $searchPattern, $searchPattern, 
                $pagination['limit'], $pagination['offset']
            ]);
            
            // Get total count
            $countSql = "SELECT COUNT(*) as total FROM vw_products 
                        WHERE product_name LIKE ? OR sku LIKE ? OR category_name LIKE ?";
            $total = $this->db->fetch($countSql, [$searchPattern, $searchPattern, $searchPattern])['total'];
            
            return [
                'products' => $products,
                'total' => $total,
                'pages' => ceil($total / $pagination['limit']),
                'current_page' => $pagination['page']
            ];
        } catch (Exception $e) {
            logError("Get products error: " . $e->getMessage());
            return ['products' => [], 'total' => 0, 'pages' => 0, 'current_page' => 1];
        }
    }
    
    /**
     * Get categories summary
     */
    public function getCategories() {
        try {
            return $this->db->fetchAll("SELECT * FROM vw_category_summary ORDER BY category_name");
        } catch (Exception $e) {
            logError("Get categories error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get low stock products
     */
    public function getLowStockProducts() {
        try {
            return $this->db->fetchAll("SELECT * FROM vw_low_stock_products ORDER BY stock_quantity ASC");
        } catch (Exception $e) {
            logError("Get low stock error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create product
     */
    public function createProduct($data) {
        try {
            $sql = "INSERT INTO products (category_id, product_name, sku, price, tax_rate, stock_quantity) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $this->db->execute($sql, [
                $data['category_id'],
                $data['product_name'],
                $data['sku'],
                $data['price'],
                $data['tax_rate'],
                $data['stock_quantity']
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            logError("Create product error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update product
     */
    public function updateProduct($productId, $data) {
        try {
            $sql = "UPDATE products 
                    SET category_id = ?, product_name = ?, sku = ?, price = ?, tax_rate = ?, stock_quantity = ?
                    WHERE product_id = ?";
            
            $this->db->execute($sql, [
                $data['category_id'],
                $data['product_name'],
                $data['sku'],
                $data['price'],
                $data['tax_rate'],
                $data['stock_quantity'],
                $productId
            ]);
            
            return true;
        } catch (Exception $e) {
            logError("Update product error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create category
     */
    public function createCategory($name, $description = '') {
        try {
            $sql = "INSERT INTO product_categories (category_name, description) VALUES (?, ?)";
            $this->db->execute($sql, [$name, $description]);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            logError("Create category error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get product by ID (from view)
     */
    public function getProductById($productId) {
        try {
            $sql = "SELECT * FROM vw_products WHERE product_id = ?";
            return $this->db->fetch($sql, [$productId]);
        } catch (Exception $e) {
            logError("Get product by ID error: " . $e->getMessage());
            return null;
        }
    }
}