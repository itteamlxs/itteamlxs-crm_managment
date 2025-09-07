<?php
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';

// Define current report action for navigation
$reportAction = $_GET['action'] ?? 'sales';
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
                        <i class="bi bi-graph-up"></i> <?= __('reports') ?>
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

                <!-- Page Header Only - No Navigation Tabs -->
                <div class="mb-4">
                    <h2 class="h4 text-primary"><?= __('sales_reports') ?></h2>
                    <p class="text-muted"><?= __('sales_reports_description') ?: 'Analyze sales performance and trends' ?></p>
                </div>

                <div id="loadingSpinner" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Sales Performance Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-check"></i> <?= __('sales_performance') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <canvas id="salesPerformanceChart" height="300"></canvas>
                            </div>
                            <div class="col-lg-4">
                                <div class="table-responsive">
                                    <table class="table table-sm report-table">
                                        <thead>
                                            <tr>
                                                <th><?= __('username') ?></th>
                                                <th><?= __('total_quotes') ?></th>
                                                <th><?= __('total_amount') ?></th>
                                                <th><?= __('conversion_rate') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($salesPerformance)): ?>
                                                <?php foreach ($salesPerformance as $performance): ?>
                                                    <tr>
                                                        <td><?= sanitizeOutput($performance['username']) ?></td>
                                                        <td><?= number_format($performance['total_quotes']) ?></td>
                                                        <td><?= formatCurrency($performance['total_amount']) ?></td>
                                                        <td><?= number_format($performance['conversion_rate'] * 100, 2) ?>%</td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        <?= __('no_data_available') ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Trends Section -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-graph-up-arrow"></i> <?= __('sales_trends') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <canvas id="salesTrendsChart" height="300"></canvas>
                            </div>
                            <div class="col-lg-4">
                                <div class="table-responsive">
                                    <table class="table table-sm report-table">
                                        <thead>
                                            <tr>
                                                <th><?= __('month') ?></th>
                                                <th><?= __('total_amount') ?></th>
                                                <th><?= __('total_quotes') ?></th>
                                                <th><?= __('average_discount') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($salesTrends)): ?>
                                                <?php foreach ($salesTrends as $trend): ?>
                                                    <tr>
                                                        <td><?= sanitizeOutput($trend['month']) ?></td>
                                                        <td><?= formatCurrency($trend['total_amount']) ?></td>
                                                        <td><?= number_format($trend['total_quotes']) ?></td>
                                                        <td><?= number_format($trend['average_discount'], 2) ?>%</td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        <?= __('no_data_available') ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pass PHP data to JavaScript
        const salesPerformanceData = <?= json_encode($salesPerformance) ?>;
        const salesTrendsData = <?= json_encode($salesTrends) ?>;
    </script>
    <script src="/crm-project/public/assets/js/reports.js"></script>
</body>
</html>