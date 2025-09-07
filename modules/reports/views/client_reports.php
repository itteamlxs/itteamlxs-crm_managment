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
                        <i class="bi bi-people"></i> <?= __('client_reports') ?>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Refresh functionality
        document.getElementById('refreshReports').addEventListener('click', function() {
            const spinner = document.getElementById('loadingSpinner');
            const button = this;
            
            spinner.style.display = 'block';
            button.disabled = true;
            
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
                spinner.style.display = 'none';
                button.disabled = false;
            });
        });
    </script>
    <script src="/crm-project/public/assets/js/reports.js"></script>
</body>
</html>