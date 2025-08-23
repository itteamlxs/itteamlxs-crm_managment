<?php
/**
 * Product Model
 * Maneja productos y categorías usando vistas del esquema
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
     * Obtener productos con paginación y filtros
     */
    public function getProducts($page = 1, $limit = 10, $search = '', $categoryId = null) {
        try {
            $pagination = validatePagination($page, $limit);
            
            $conditions = [];
            $params = [];
            
            if (!empty($search)) {
                $conditions[] = "(product_name LIKE ? OR sku LIKE ?)";
                $params[] = '%' . $search . '%';
                $params[] = '%' . $search . '%';
            }
            
            if ($categoryId) {
                $conditions[] = "category_id = ?";
                $params[] = $categoryId;
            }
            
            $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
            
            $sql = "SELECT * FROM vw_products {$whereClause} 
                    ORDER BY product_name ASC
                    LIMIT ? OFFSET ?";
            
            $params[] = $pagination['limit'];
            $params[] = $pagination['offset'];
            
            $products = $this->db->fetchAll($sql, $params);
            
            // Contar total
            $countSql = "SELECT COUNT(*) as total FROM vw_products {$whereClause}";
            $countParams = array_slice($params, 0, count($params) - 2);
            $total = $this->db->fetch($countSql, $countParams)['total'];
            
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
     * Obtener producto por ID
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
    
    /**
     * Crear producto
     */
    public function createProduct($data) {
        try {
            // Validar SKU único
            if (!$this->isSkuUnique($data['sku'])) {
                return false;
            }
            
            $sql = "INSERT INTO products (category_id, product_name, sku, price, tax_rate, 
                                        stock_quantity, min_stock_level, description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $this->db->execute($sql, [
                $data['category_id'],
                $data['product_name'],
                $data['sku'],
                $data['price'],
                $data['tax_rate'] ?? 0.00,
                $data['stock_quantity'] ?? 0,
                $data['min_stock_level'] ?? 10,
                $data['description'] ?? ''
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            logError("Create product error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar producto
     */
    public function updateProduct($productId, $data) {
        try {
            // Validar SKU único (excluyendo el producto actual)
            if (!$this->isSkuUnique($data['sku'], $productId)) {
                return false;
            }
            
            $sql = "UPDATE products 
                    SET category_id = ?, product_name = ?, sku = ?, price = ?, 
                        tax_rate = ?, stock_quantity = ?, min_stock_level = ?, description = ?
                    WHERE product_id = ?";
            
            $this->db->execute($sql, [
                $data['category_id'],
                $data['product_name'],
                $data['sku'],
                $data['price'],
                $data['tax_rate'] ?? 0.00,
                $data['stock_quantity'] ?? 0,
                $data['min_stock_level'] ?? 10,
                $data['description'] ?? '',
                $productId
            ]);
            
            return true;
        } catch (Exception $e) {
            logError("Update product error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar producto (soft delete - stock = 0)
     */
    public function deleteProduct($productId) {
        try {
            // Verificar si tiene cotizaciones activas
            $checkSql = "SELECT COUNT(*) as count FROM quote_items qi 
                        JOIN quotes q ON qi.quote_id = q.quote_id 
                        WHERE qi.product_id = ? AND q.status IN ('pending', 'approved')";
            $result = $this->db->fetch($checkSql, [$productId]);
            
            if ($result['count'] > 0) {
                return ['error' => 'Cannot delete product with active quotes'];
            }
            
            $sql = "UPDATE products SET stock_quantity = 0, is_active = 0 WHERE product_id = ?";
            $this->db->execute($sql, [$productId]);
            return true;
        } catch (Exception $e) {
            logError("Delete product error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener categorías
     */
    public function getCategories($includeStats = false) {
        try {
            if ($includeStats) {
                return $this->db->fetchAll("SELECT * FROM vw_category_summary ORDER BY category_name");
            } else {
                return $this->db->fetchAll("SELECT category_id, category_name, description 
                                           FROM product_categories 
                                           ORDER BY category_name");
            }
        } catch (Exception $e) {
            logError("Get categories error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener categoría por ID
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
     * Crear categoría
     */
    public function createCategory($data) {
        try {
            $sql = "INSERT INTO product_categories (category_name, description) VALUES (?, ?)";
            $this->db->execute($sql, [$data['category_name'], $data['description'] ?? '']);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            logError("Create category error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar categoría
     */
    public function updateCategory($categoryId, $data) {
        try {
            $sql = "UPDATE product_categories SET category_name = ?, description = ? WHERE category_id = ?";
            $this->db->execute($sql, [$data['category_name'], $data['description'] ?? '', $categoryId]);
            return true;
        } catch (Exception $e) {
            logError("Update category error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar categoría
     */
    public function deleteCategory($categoryId) {
        try {
            // Verificar si tiene productos
            $checkSql = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";
            $result = $this->db->fetch($checkSql, [$categoryId]);
            
            if ($result['count'] > 0) {
                return ['error' => 'Cannot delete category with products'];
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
     * Obtener productos con stock bajo
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
     * Validar SKU único
     */
    private function isSkuUnique($sku, $excludeId = null) {
        try {
            $sql = "SELECT COUNT(*) as count FROM products WHERE sku = ?";
            $params = [$sku];
            
            if ($excludeId) {
                $sql .= " AND product_id != ?";
                $params[] = $excludeId;
            }
            
            $result = $this->db->fetch($sql, $params);
            return $result['count'] == 0;
        } catch (Exception $e) {
            logError("Check SKU unique error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar stock (para integraciones externas)
     */
    public function updateStock($productId, $quantity, $operation = 'set') {
        try {
            if ($operation === 'add') {
                $sql = "UPDATE products SET stock_quantity = stock_quantity + ? WHERE product_id = ?";
            } elseif ($operation === 'subtract') {
                $sql = "UPDATE products SET stock_quantity = GREATEST(0, stock_quantity - ?) WHERE product_id = ?";
            } else {
                $sql = "UPDATE products SET stock_quantity = ? WHERE product_id = ?";
            }
            
            $this->db->execute($sql, [$quantity, $productId]);
            return true;
        } catch (Exception $e) {
            logError("Update stock error: " . $e->getMessage());
            return false;
        }
    }
}