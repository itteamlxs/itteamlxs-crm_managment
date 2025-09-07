<?php
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
?>



<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput($pageTitle) ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-box"></i> <?= __('reports') ?>
                    </h1>
                    <div>
                        <button id="refreshReports" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-clockwise"></i> <?= __('refresh') ?>
                        </button>
                        <a href="<?= dashboardUrl() ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-house"></i> <?= __('back_to_dashboard') ?>
                        </a>
                    </div>
                </div>

                <!-- Summary Cards Row -->
                <div class="row mb-4">
                    <div class="col-lg-4 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title"><?= __('total_products') ?></h5>
                                        <h3 class="mb-0"><?= count($productPerformance) ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-box-seam display-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title"><?= __('categories') ?></h5>
                                        <h3 class="mb-0"><?= count($categorySummary) ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-tags display-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title"><?= __('low_stock_products') ?></h5>
                                        <h3 class="mb-0">
                                            <?php 
                                            // Count products with low stock based on productPerformance data
                                            $lowStockCount = 0;
                                            if (!empty($productPerformance)) {
                                                foreach ($productPerformance as $product) {
                                                    if ((int)$product['stock_quantity'] <= 10) {
                                                        $lowStockCount++;
                                                    }
                                                }
                                            }
                                            echo $lowStockCount;
                                            ?>
                                        </h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-exclamation-triangle display-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-graph-up"></i> <?= __('product_performance') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="productPerformanceChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-pie-chart"></i> <?= __('category_summary') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="categorySummaryChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Performance Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-table"></i> <?= __('product_performance_details') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped report-table">
                                <thead>
                                    <tr>
                                        <th><?= __('product_name') ?></th>
                                        <th><?= __('sku') ?></th>
                                        <th><?= __('category') ?></th>
                                        <th><?= __('total_sold') ?></th>
                                        <th><?= __('stock_quantity') ?></th>
                                        <th><?= __('status') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($productPerformance)): ?>
                                        <?php foreach ($productPerformance as $product): ?>
                                            <tr>
                                                <td><?= sanitizeOutput($product['product_name']) ?></td>
                                                <td><code><?= sanitizeOutput($product['sku']) ?></code></td>
                                                <td><?= sanitizeOutput($product['category_name']) ?></td>
                                                <td><?= number_format($product['total_sold'] ?? 0) ?></td>
                                                <td><?= number_format($product['stock_quantity']) ?></td>
                                                <td>
                                                    <?php 
                                                    $stockLevel = (int)$product['stock_quantity'];
                                                    if ($stockLevel <= 10): ?>
                                                        <span class="badge bg-danger"><?= __('low_stock') ?></span>
                                                    <?php elseif ($stockLevel <= 50): ?>
                                                        <span class="badge bg-warning"><?= __('medium_stock') ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success"><?= __('high_stock') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <?= __('no_data_available') ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <?php 
                // Create low stock products array from productPerformance
                $lowStockProducts = [];
                if (!empty($productPerformance)) {
                    foreach ($productPerformance as $product) {
                        if ((int)$product['stock_quantity'] <= 10) {
                            $lowStockProducts[] = $product;
                        }
                    }
                }
                ?>
                <?php if (!empty($lowStockProducts)): ?>
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle"></i> <?= __('low_stock_alert') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th><?= __('product_name') ?></th>
                                        <th><?= __('sku') ?></th>
                                        <th><?= __('category') ?></th>
                                        <th><?= __('stock_quantity') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lowStockProducts as $product): ?>
                                        <tr>
                                            <td><?= sanitizeOutput($product['product_name']) ?></td>
                                            <td><code><?= sanitizeOutput($product['sku']) ?></code></td>
                                            <td><?= sanitizeOutput($product['category_name']) ?></td>
                                            <td>
                                                <span class="badge bg-danger"><?= number_format($product['stock_quantity']) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pass PHP data to JavaScript
        const productPerformanceData = <?= json_encode($productPerformance) ?>;
        const categorySummaryData = <?= json_encode($categorySummary) ?>;
    </script>
    <script src="/crm-project/public/assets/js/reports.js"></script>
</body>
</html>