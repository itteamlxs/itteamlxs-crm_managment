<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

requireLogin();
requirePermission('view_client_reports');
?>
<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput($pageTitle) ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/crm-project/public/assets/css/custom.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <div class="container-fluid">
            <!-- Header with Breadcrumbs -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">
                        <i class="bi bi-people"></i> <?= __('client_reports') ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?= url('dashboard', 'index') ?>">
                                    <i class="bi bi-house-door"></i> <?= __('dashboard') ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?= url('reports', 'sales') ?>">
                                    <i class="bi bi-graph-up"></i> <?= __('reports') ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="bi bi-people"></i> <?= __('client_reports') ?>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button id="refreshReports" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-clockwise"></i> <?= __('refresh') ?>
                        </button>
                        <a href="<?= dashboardUrl() ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-house"></i> <?= __('back_to_dashboard') ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Top Clients Table -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trophy"></i> <?= __('top_clients') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover report-table">
                            <thead class="table-dark">
                                <tr>
                                    <th><?= __('rank') ?></th>
                                    <th><?= __('company_name') ?></th>
                                    <th><?= __('total_spend') ?></th>
                                    <th><?= __('purchase_count') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topClients)): ?>
                                    <?php foreach ($topClients as $client): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary"><?= $client['rank'] ?></span>
                                            </td>
                                            <td class="fw-medium"><?= sanitizeOutput($client['company_name']) ?></td>
                                            <td class="text-success fw-bold"><?= formatCurrency($client['total_spend']) ?></td>
                                            <td><?= number_format($client['purchase_count']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                            <?= __('no_data_available') ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Client Activity Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-activity"></i> <?= __('client_activity') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped report-table">
                            <thead>
                                <tr>
                                    <th><?= __('company_name') ?></th>
                                    <th><?= __('last_quote_date') ?></th>
                                    <th><?= __('total_quotes') ?></th>
                                    <th><?= __('total_amount') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($clientActivity)): ?>
                                    <?php foreach ($clientActivity as $activity): ?>
                                        <tr>
                                            <td><?= sanitizeOutput($activity['company_name']) ?></td>
                                            <td>
                                                <?php if ($activity['last_quote_date']): ?>
                                                    <?= formatDate($activity['last_quote_date'], 'Y-m-d') ?>
                                                <?php else: ?>
                                                    <span class="text-muted"><?= __('never') ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= number_format($activity['total_quotes'] ?? 0) ?></td>
                                            <td><?= formatCurrency($activity['total_amount'] ?? 0) ?></td>
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

            <!-- Purchase Patterns Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-down"></i> <?= __('purchase_patterns') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped report-table">
                            <thead>
                                <tr>
                                    <th><?= __('company_name') ?></th>
                                    <th><?= __('total_spend') ?></th>
                                    <th><?= __('purchase_count') ?></th>
                                    <th><?= __('last_purchase_date') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($clientPurchasePatterns)): ?>
                                    <?php foreach ($clientPurchasePatterns as $pattern): ?>
                                        <tr>
                                            <td><?= sanitizeOutput($pattern['company_name']) ?></td>
                                            <td><?= formatCurrency($pattern['total_spend']) ?></td>
                                            <td><?= number_format($pattern['purchase_count']) ?></td>
                                            <td>
                                                <?php if ($pattern['last_purchase_date']): ?>
                                                    <?= formatDate($pattern['last_purchase_date'], 'Y-m-d') ?>
                                                <?php else: ?>
                                                    <span class="text-muted"><?= __('never') ?></span>
                                                <?php endif; ?>
                                            </td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Refresh functionality
        document.getElementById('refreshReports').addEventListener('click', function() {
            const button = this;
            
            button.disabled = true;
            button.innerHTML = '<i class="bi bi-arrow-clockwise"></i> <span class="spinner-border spinner-border-sm me-2" role="status"></span>';
            
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({action: 'refresh'})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to refresh reports: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while refreshing reports');
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-arrow-clockwise"></i> <?= __('refresh') ?>';
            });
        });
    </script>
    <script src="/crm-project/public/assets/js/reports.js"></script>
</body>
</html>