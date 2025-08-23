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
     * Get categories for dropdown (all categories)
     */
    public function getCategoriesForDropdown() {
        try {
            return $this->db->fetchAll("SELECT category_id, category_name, description FROM product_categories ORDER BY category_name");
        } catch (Exception $e) {
            logError("Get categories dropdown error: " . $e->getMessage());
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
     * Delete product (soft delete by setting stock to 0)
     */
    public function deleteProduct($productId) {
        try {
            $sql = "UPDATE products SET stock_quantity = 0 WHERE product_id = ?";
            $this->db->execute($sql, [$productId]);
            return true;
        } catch (Exception $e) {
            logError("Delete product error: " . $e->getMessage());
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
     * Update category
     */
    public function updateCategory($categoryId, $name, $description = '') {
        try {
            $sql = "UPDATE product_categories SET category_name = ?, description = ? WHERE category_id = ?";
            $this->db->execute($sql, [$name, $description, $categoryId]);
            return true;
        } catch (Exception $e) {
            logError("Update category error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete category (only if no products)
     */
    public function deleteCategory($categoryId) {
        try {
            // Check if category has products
            $checkSql = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";
            $result = $this->db->fetch($checkSql, [$categoryId]);
            
            if ($result['count'] > 0) {
                return false; // Cannot delete category with products
            }
            
            $sql = "DELETE FROM product_categories WHERE category_id = ?";
            $this->db->execute($sql, [$categoryId]);
            return true;
        } catch (Exception $e) {
            logError("Delete category error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get category by ID
     */
    public function getCategoryById($categoryId) {
        try {
            $sql = "SELECT * FROM product_categories WHERE category_id = ?";
            return $this->db->fetch($sql, [$categoryId]);
        } catch (Exception $e) {
            logError("Get category by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get product by ID (from table for editing - includes category_id)
     */
    public function getProductById($productId) {
        try {
            $sql = "SELECT p.*, pc.category_name 
                    FROM products p 
                    JOIN product_categories pc ON p.category_id = pc.category_id 
                    WHERE p.product_id = ?";
            return $this->db->fetch($sql, [$productId]);
        } catch (Exception $e) {
            logError("Get product by ID error: " . $e->getMessage());
            return null;
        }
    }
}