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
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-shield-check"></i> <?= __('reports') ?>
                    </h1>
                    <div>
                        <a href="<?= dashboardUrl() ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-house"></i> <?= __('back_to_dashboard') ?>
                        </a>
                    </div>
                </div>

                <!-- Reports Navigation Tabs -->
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('reports', 'sales') ?>">
                            <i class="bi bi-graph-up"></i> <?= __('sales_reports') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('reports', 'clients') ?>">
                            <i class="bi bi-people"></i> <?= __('client_reports') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('reports', 'products') ?>">
                            <i class="bi bi-box"></i> <?= __('product_reports') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= url('reports', 'compliance') ?>">
                            <i class="bi bi-shield-check"></i> <?= __('compliance_reports') ?>
                        </a>
                    </li>
                </ul>

                <!-- Security Posture Cards -->
                <?php if (!empty($securityPosture)): ?>
                <div class="row mb-4">
                    <div class="col-lg-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= __('total_audit_logs') ?></h6>
                                        <h4 class="mb-0"><?= number_format($securityPosture['audit_log_count'] ?? 0) ?></h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-journal-text display-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= __('failed_logins') ?></h6>
                                        <h4 class="mb-0"><?= number_format($securityPosture['failed_login_count'] ?? 0) ?></h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-x-circle display-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= __('locked_accounts') ?></h6>
                                        <h4 class="mb-0"><?= number_format($securityPosture['locked_accounts'] ?? 0) ?></h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-lock display-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div class="card bg-secondary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= __('permission_changes') ?></h6>
                                        <h4 class="mb-0"><?= number_format($securityPosture['permission_changes'] ?? 0) ?></h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-key display-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Last Security Event -->
                <?php if (!empty($securityPosture['last_security_event'])): ?>
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle"></i>
                    <strong><?= __('last_security_event') ?>:</strong> 
                    <?= formatDate($securityPosture['last_security_event'], 'Y-m-d H:i:s') ?>
                </div>
                <?php endif; ?>

                <!-- Audit Logs Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ul"></i> <?= __('audit_logs') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($auditLogs)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover report-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th><?= __('date_time') ?></th>
                                            <th><?= __('user') ?></th>
                                            <th><?= __('action') ?></th>
                                            <th><?= __('entity_type') ?></th>
                                            <th><?= __('entity_id') ?></th>
                                            <th><?= __('ip_address') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($auditLogs as $log): ?>
                                            <tr>
                                                <td>
                                                    <small><?= formatDate($log['created_at'], 'Y-m-d H:i:s') ?></small>
                                                </td>
                                                <td>
                                                    <?php if ($log['user_id']): ?>
                                                        <span class="badge bg-primary"><?= __('user') ?> #<?= $log['user_id'] ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><?= __('system') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $actionClass = match($log['action']) {
                                                        'INSERT' => 'success',
                                                        'UPDATE' => 'warning',
                                                        'DELETE' => 'danger',
                                                        'LOGIN' => 'info',
                                                        'LOGOUT' => 'secondary',
                                                        default => 'primary'
                                                    };
                                                    ?>
                                                    <span class="badge bg-<?= $actionClass ?>"><?= sanitizeOutput($log['action']) ?></span>
                                                </td>
                                                <td>
                                                    <code><?= sanitizeOutput($log['entity_type']) ?></code>
                                                </td>
                                                <td>
                                                    <small><?= number_format($log['entity_id']) ?></small>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= sanitizeOutput($log['ip_address']) ?></small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <nav aria-label="Audit logs pagination" class="mt-3">
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('reports', 'compliance', ['page' => $page - 1]) ?>">
                                                <?= __('previous') ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <li class="page-item active">
                                        <span class="page-link"><?= $page ?></span>
                                    </li>
                                    
                                    <?php if (count($auditLogs) >= 50): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('reports', 'compliance', ['page' => $page + 1]) ?>">
                                                <?= __('next') ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-journal-text display-1 text-muted"></i>
                                <h5 class="text-muted mt-3"><?= __('no_audit_logs_available') ?></h5>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>