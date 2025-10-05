<?php
/**
 * Dashboard Low Stock Products Component
 * Displays products with inventory below threshold
 */

if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
    require_once __DIR__ . '/../../core/rbac.php';
    require_once __DIR__ . '/../../core/url_helper.php';
    require_once __DIR__ . '/../../config/db.php';
}

requireLogin();
$user = getCurrentUser();
$db = Database::getInstance();

// Get low stock products
$lowStockProducts = [];
try {
    $lowStockProducts = $db->fetchAll("SELECT * FROM vw_low_stock_products ORDER BY stock_quantity ASC LIMIT 5");
} catch (Exception $e) {
    logError("Low stock products error: " . $e->getMessage());
    $lowStockProducts = [];
}
?>

<?php if (!empty($lowStockProducts)): ?>
<div class="sidebar-card mb-3">
    <h6 class="sidebar-card-title">
        <i class="bi bi-exclamation-triangle"></i>
        <?php echo __('low_stock_products') ?: 'Low Stock Products'; ?>
    </h6>
    <?php foreach ($lowStockProducts as $product): ?>
        <div class="alert-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>
                        <?php if (canAccessModule('products')): ?>
                            <a href="<?php echo url('products', 'edit', ['id' => $product['product_id']]); ?>" class="text-decoration-none text-dark">
                                <?php echo sanitizeOutput($product['product_name']); ?>
                            </a>
                        <?php else: ?>
                            <?php echo sanitizeOutput($product['product_name']); ?>
                        <?php endif; ?>
                    </strong>
                    <br>
                    <small class="text-muted"><?php echo sanitizeOutput($product['sku']); ?></small>
                    <br>
                    <small class="text-muted">
                        <i class="bi bi-tag"></i> <?php echo sanitizeOutput($product['category_name']); ?>
                    </small>
                </div>
                <span class="badge bg-danger"><?php echo $product['stock_quantity']; ?></span>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (canAccessModule('products')): ?>
        <div class="mt-2">
            <a href="<?php echo url('products', 'list'); ?>" class="btn btn-outline-dashboard btn-sm w-100">
                <i class="bi bi-box"></i> <?php echo __('view_all') ?: 'View All Products'; ?>
            </a>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>