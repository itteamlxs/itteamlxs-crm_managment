<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

requireLogin();
requirePermission('view_compliance_reports');

// Initialize variables to avoid warnings
$isAdmin = $isAdmin ?? false;
$securityPosture = $securityPosture ?? [];
$userActivities = $userActivities ?? [];
$auditLogs = $auditLogs ?? [];
$startDate = $startDate ?? '';
$endDate = $endDate ?? '';
$pageTitle = $pageTitle ?? 'Reportes de Cumplimiento';
$page = $page ?? 1;
$limit = $limit ?? 50;
?>
<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput($pageTitle) ?> - <?= sanitizeOutput(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/crm-project/public/assets/css/custom.css" rel="stylesheet">
    <style>
        .metric-card { transition: transform 0.2s; }
        .metric-card:hover { transform: translateY(-2px); }
        .audit-table { font-size: 0.9rem; }
        .loading-spinner { display: none; }
        .badge-action { font-size: 0.75rem; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <div class="container-fluid">
            <!-- Header with Breadcrumbs -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">
                        <i class="bi bi-shield-check text-primary"></i> <?= sanitizeOutput($pageTitle) ?>
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
                                <i class="bi bi-shield-check"></i> <?= __('compliance_reports') ?>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button id="refreshReports" class="btn btn-success btn-sm me-2">
                            <i class="bi bi-arrow-clockwise"></i> Actualizar
                        </button>
                        <a href="<?= dashboardUrl() ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-house"></i> Panel de Control
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Security Posture Metrics -->
            <?php if (!empty($securityPosture)): ?>
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card metric-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Logs de Auditoría</h6>
                                    <h3 class="mb-0"><?= number_format($securityPosture['audit_log_count'] ?? 0) ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-journal-text display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card metric-card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Intentos Fallidos</h6>
                                    <h3 class="mb-0"><?= number_format($securityPosture['failed_login_count'] ?? 0) ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-x-circle display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card metric-card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Cuentas Inactivas</h6>
                                    <h3 class="mb-0"><?= number_format($securityPosture['inactive_accounts'] ?? 0) ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-person-x display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card metric-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Cambios de Permisos</h6>
                                    <h3 class="mb-0"><?= number_format($securityPosture['permission_changes'] ?? 0) ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-key display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- User Activity Summary (Admin Only) -->
            <?php if ($isAdmin && !empty($userActivities)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people"></i> Resumen de Actividad de Usuarios
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Total Acciones</th>
                                            <th>Última Actividad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($userActivities as $activity): ?>
                                        <tr>
                                            <td><?= sanitizeOutput($activity['username'] ?? 'Sistema') ?></td>
                                            <td><span class="badge bg-secondary"><?= number_format($activity['action_count']) ?></span></td>
                                            <td><small class="text-muted"><?= formatDate($activity['last_activity'], 'Y-m-d H:i:s') ?></small></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Audit Logs -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ul"></i> Logs de Auditoría
                        </h5>
                        <div>
                            <button id="exportCSV" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i> Exportar CSV
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="loadingSpinner" class="loading-spinner text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>

                    <?php if (!empty($auditLogs)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover audit-table report-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fecha/Hora</th>
                                        <th>Usuario</th>
                                        <th>Acción</th>
                                        <th>Tipo de Entidad</th>
                                        <th>ID Entidad</th>
                                        <?php if ($isAdmin): ?>
                                        <th>Dirección IP</th>
                                        <?php endif; ?>
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
                                                    <span class="badge bg-primary">Usuario #<?= $log['user_id'] ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Sistema</span>
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
                                                    'STOCK_UPDATE' => 'primary',
                                                    default => 'light'
                                                };
                                                ?>
                                                <span class="badge bg-<?= $actionClass ?> badge-action"><?= sanitizeOutput($log['action']) ?></span>
                                            </td>
                                            <td>
                                                <code class="small"><?= sanitizeOutput($log['entity_type']) ?></code>
                                            </td>
                                            <td>
                                                <small><?= number_format($log['entity_id']) ?></small>
                                            </td>
                                            <?php if ($isAdmin): ?>
                                            <td>
                                                <small class="text-muted"><?= sanitizeOutput($log['ip_address'] ?? 'N/A') ?></small>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav aria-label="Paginación de logs de auditoría" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('reports', 'compliance', array_merge($_GET, ['page' => $page - 1])) ?>">
                                            <i class="bi bi-chevron-left"></i> Anterior
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <li class="page-item active">
                                    <span class="page-link">Página <?= $page ?></span>
                                </li>
                                
                                <?php if (count($auditLogs) >= $limit): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('reports', 'compliance', array_merge($_GET, ['page' => $page + 1])) ?>">
                                            Siguiente <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-journal-text display-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No hay logs de auditoría disponibles</h5>
                            <?php if ($startDate || $endDate): ?>
                            <p class="text-muted">Pruebe con un rango de fechas diferente</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/crm-project/public/assets/js/reports.js"></script>
</body>
</html>