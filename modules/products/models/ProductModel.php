<?php
/**
 * Product Model
 * Database operations for products and categories
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';

class ProductModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all products with pagination and filters
     */
    public function getProducts($page = 1, $limit = 10, $search = '', $category_id = null, $orderBy = 'product_name ASC') {
        $pagination = validatePagination($page, $limit, 100);
        $allowedOrderColumns = ['product_name', 'sku', 'price', 'stock_quantity', 'category_name', 'created_at'];
        $orderBy = sanitizeOrderBy($orderBy, $allowedOrderColumns);
        
        $whereConditions = [];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(p.product_name LIKE ? OR p.sku LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if ($category_id) {
            $whereConditions[] = "p.category_id = ?";
            $params[] = $category_id;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $sql = "SELECT p.product_id, p.product_name, p.sku, p.price, p.tax_rate, 
                       p.stock_quantity, p.created_at, p.updated_at, pc.category_name
                FROM products p 
                JOIN product_categories pc ON p.category_id = pc.category_id 
                {$whereClause}
                ORDER BY {$orderBy}
                LIMIT {$pagination['limit']} OFFSET {$pagination['offset']}";
        
        $products = $this->db->fetchAll($sql, $params);
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total 
                     FROM products p 
                     JOIN product_categories pc ON p.category_id = pc.category_id 
                     {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        return [
            'products' => $products,
            'total' => $total,
            'page' => $pagination['page'],
            'limit' => $pagination['limit'],
            'pages' => ceil($total / $pagination['limit'])
        ];
    }
    
    /**
     * Get single product by ID
     */
    public function getProductById($productId) {
        $sql = "SELECT p.*, pc.category_name 
                FROM products p 
                JOIN product_categories pc ON p.category_id = pc.category_id 
                WHERE p.product_id = ?";
        return $this->db->fetch($sql, [$productId]);
    }
    
    /**
     * Check if SKU exists (excluding current product ID)
     */
    public function skuExists($sku, $excludeProductId = null) {
        $sql = "SELECT COUNT(*) as count FROM products WHERE sku = ?";
        $params = [$sku];
        
        if ($excludeProductId) {
            $sql .= " AND product_id != ?";
            $params[] = $excludeProductId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return ($result['count'] ?? 0) > 0;
    }
    
    /**
     * Create new product
     */
    public function createProduct($data) {
        $sql = "INSERT INTO products (category_id, product_name, sku, price, tax_rate, stock_quantity) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['category_id'],
            $data['product_name'],
            $data['sku'],
            $data['price'],
            $data['tax_rate'] ?? 0,
            $data['stock_quantity']
        ];
        
        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Update product
     */
    public function updateProduct($productId, $data) {
        $sql = "UPDATE products 
                SET category_id = ?, product_name = ?, sku = ?, price = ?, 
                    tax_rate = ?, stock_quantity = ?, updated_at = NOW()
                WHERE product_id = ?";
        
        $params = [
            $data['category_id'],
            $data['product_name'],
            $data['sku'],
            $data['price'],
            $data['tax_rate'] ?? 0,
            $data['stock_quantity'],
            $productId
        ];
        
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete product
     */
    public function deleteProduct($productId) {
        // Check if product is used in quotes
        $checkSql = "SELECT COUNT(*) as count FROM quote_items WHERE product_id = ?";
        $result = $this->db->fetch($checkSql, [$productId]);
        
        if (($result['count'] ?? 0) > 0) {
            throw new Exception('Cannot delete product: it is referenced in existing quotes');
        }
        
        $sql = "DELETE FROM products WHERE product_id = ?";
        return $this->db->execute($sql, [$productId]);
    }
    
    /**
     * Get all categories
     */
    public function getCategories() {
        $sql = "SELECT category_id, category_name, description, created_at 
                FROM product_categories 
                ORDER BY category_name";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get category by ID
     */
    public function getCategoryById($categoryId) {
        $sql = "SELECT * FROM product_categories WHERE category_id = ?";
        return $this->db->fetch($sql, [$categoryId]);
    }
    
    /**
     * Check if category name exists (excluding current category ID)
     */
    public function categoryNameExists($categoryName, $excludeCategoryId = null) {
        $sql = "SELECT COUNT(*) as count FROM product_categories WHERE category_name = ?";
        $params = [$categoryName];
        
        if ($excludeCategoryId) {
            $sql .= " AND category_id != ?";
            $params[] = $excludeCategoryId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return ($result['count'] ?? 0) > 0;
    }
    
    /**
     * Create new category
     */
    public function createCategory($data) {
        $sql = "INSERT INTO product_categories (category_name, description) VALUES (?, ?)";
        $params = [$data['category_name'], $data['description'] ?? ''];
        
        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Update category
     */
    public function updateCategory($categoryId, $data) {
        $sql = "UPDATE product_categories 
                SET category_name = ?, description = ? 
                WHERE category_id = ?";
        $params = [$data['category_name'], $data['description'] ?? '', $categoryId];
        
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete category
     */
    public function deleteCategory($categoryId) {
        // Check if category has products
        $checkSql = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";
        $result = $this->db->fetch($checkSql, [$categoryId]);
        
        if (($result['count'] ?? 0) > 0) {
            throw new Exception('Cannot delete category: it contains products');
        }
        
        $sql = "DELETE FROM product_categories WHERE category_id = ?";
        return $this->db->execute($sql, [$categoryId]);
    }
    
    /**
     * Get low stock products
     */
    public function getLowStockProducts() {
        $sql = "SELECT * FROM vw_low_stock_products ORDER BY stock_quantity ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get category summary
     */
    public function getCategorySummary() {
        $sql = "SELECT * FROM vw_category_summary ORDER BY category_name";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get product performance data
     */
    public function getProductPerformance() {
        $sql = "SELECT * FROM vw_product_performance ORDER BY total_sold DESC";
        return $this->db->fetchAll($sql);
    }
}