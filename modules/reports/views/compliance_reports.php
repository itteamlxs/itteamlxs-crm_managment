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
        .filter-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
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
            
            <!-- Filtros por fecha -->
            <div class="card filter-card mb-4">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="bi bi-filter"></i> Filtrar por Fecha</h6>
                    <form method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="module" value="reports">
                        <input type="hidden" name="action" value="compliance">
                        
                        <div class="col-md-3">
                            <label for="startDate" class="form-label">Fecha Inicio</label>
                            <input type="date" id="startDate" name="start_date" class="form-control form-control-sm" 
                                   value="<?= sanitizeOutput($startDate) ?>">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="endDate" class="form-label">Fecha Fin</label>
                            <input type="date" id="endDate" name="end_date" class="form-control form-control-sm" 
                                   value="<?= sanitizeOutput($endDate) ?>">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="actionFilter" class="form-label">Tipo de Acción</label>
                            <select id="actionFilter" name="action_type" class="form-select form-select-sm">
                                <option value="">Todas las acciones</option>
                                <option value="INSERT" <?= $actionType == 'INSERT' ? 'selected' : '' ?>>INSERT</option>
                                <option value="UPDATE" <?= $actionType == 'UPDATE' ? 'selected' : '' ?>>UPDATE</option>
                                <option value="DELETE" <?= $actionType == 'DELETE' ? 'selected' : '' ?>>DELETE</option>
                                <option value="LOGIN" <?= $actionType == 'LOGIN' ? 'selected' : '' ?>>LOGIN</option>
                                <option value="LOGOUT" <?= $actionType == 'LOGOUT' ? 'selected' : '' ?>>LOGOUT</option>
                                <option value="STOCK_UPDATE" <?= $actionType == 'STOCK_UPDATE' ? 'selected' : '' ?>>STOCK_UPDATE</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-funnel"></i> Filtrar
                            </button>
                        </div>
                        
                        <?php if (!empty($startDate) || !empty($endDate) || !empty($actionType)): ?>
                        <div class="col-12">
                            <a href="<?= url('reports', 'compliance') ?>" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-x-circle"></i> Limpiar Filtros
                            </a>
                        </div>
                        <?php endif; ?>
                    </form>
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
                            <?php if (!empty($auditLogs)): ?>
                            <span class="badge bg-secondary ms-2"><?= number_format($totalLogs) ?> total</span>
                            <?php endif; ?>
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
                        <?php 
                        $totalLogs = $totalCount ?? $securityPosture['audit_log_count'] ?? 0;
                        $totalPages = ceil($totalLogs / $limit);
                        ?>
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
                                                    <span class="badge bg-primary"><?= sanitizeOutput($log['username'] ?? 'Usuario #' . $log['user_id']) ?></span>
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
                        <?php if ($totalPages > 1): ?>
                        <nav aria-label="Paginación de logs de auditoría" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('reports', 'compliance', array_merge($_GET, ['page' => 1])) ?>">
                                            <i class="bi bi-chevron-double-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('reports', 'compliance', array_merge($_GET, ['page' => $page - 1])) ?>">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <li class="page-item active">
                                    <span class="page-link">Página <?= $page ?> de <?= $totalPages ?></span>
                                </li>
                                
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('reports', 'compliance', array_merge($_GET, ['page' => $page + 1])) ?>">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('reports', 'compliance', array_merge($_GET, ['page' => $totalPages])) ?>">
                                            <i class="bi bi-chevron-double-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p class="text-muted small">
                                    Mostrando <?= count($auditLogs) ?> de <?= number_format($totalLogs) ?> registros
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="text-muted small">
                                    Página <?= $page ?> de <?= $totalPages ?>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-journal-text display-1 text-muted"></i>
                            <h5 class="text-muted mt-3">No hay logs de auditoría disponibles</h5>
                            <?php if (!empty($startDate) || !empty($endDate) || !empty($actionType)): ?>
                            <p class="text-muted">Pruebe con un rango de fechas diferente o filtros distintos</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('startDate').setAttribute('max', today);
        document.getElementById('endDate').setAttribute('max', today);
        
        document.getElementById('startDate').addEventListener('change', function() {
            const endDate = document.getElementById('endDate');
            if (this.value && endDate.value && this.value > endDate.value) {
                endDate.value = this.value;
            }
        });
        
        document.getElementById('endDate').addEventListener('change', function() {
            const startDate = document.getElementById('startDate');
            if (this.value && startDate.value && this.value < startDate.value) {
                startDate.value = this.value;
            }
        });
        
        document.getElementById('exportCSV').addEventListener('click', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('export', 'csv');
            window.location.href = url.toString();
        });
        
        document.getElementById('refreshReports').addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualizando...';
            
            const url = new URL(window.location.href);
            url.searchParams.set('action', 'refresh');
            
            fetch(url.toString(), {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error al actualizar los reportes');
                    this.disabled = false;
                    this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualizar';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar los reportes');
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualizar';
            });
        });
    });
    </script>
</body>
</html>