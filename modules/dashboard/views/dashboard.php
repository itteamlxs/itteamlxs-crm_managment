<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../../../config/db.php';

requireLogin();
$user = getCurrentUser();

$companyName = 'CRM System';
try {
    $db = Database::getInstance();
    $result = $db->fetch("SELECT setting_value FROM vw_settings WHERE setting_key = 'company_display_name'");
    if ($result) {
        $companyName = $result['setting_value'];
    }
} catch (Exception $e) {
    logError("Failed to get company name: " . $e->getMessage());
}

$salesData = [];
try {
    $salesData = $db->fetchAll("SELECT DATE(issue_date) as date, SUM(total_amount) as total FROM quotes WHERE status = 'APPROVED' AND issue_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(issue_date) ORDER BY date ASC");
} catch (Exception $e) {
    logError("Failed to get sales data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('dashboard') ?: 'Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/crm-project/public/assets/css/dash.css" rel="stylesheet">
    <style>
        .sales-trend-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .sales-trend-tooltip {
            background: rgba(30, 58, 138, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
            pointer-events: none;
            position: absolute;
            display: none;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .shortcuts-list {
            font-size: 0.875rem;
        }
        
        .shortcut-key {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
        }
        
        .chart-card {
            margin-bottom: 1.5rem;
            min-height: 200px;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <?php if ($forcePasswordChange): ?>
    <div class="modal fade" id="forcePasswordChangeModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-shield-exclamation me-2"></i>
                        Cambio de Contraseña Requerido
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Por seguridad, debes cambiar tu contraseña inicial antes de continuar.
                    </div>
                    
                    <form id="forcePasswordChangeForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="bi bi-lock me-1"></i>
                                Contraseña Actual
                            </label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                <i class="bi bi-key me-1"></i>
                                Nueva Contraseña
                            </label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <div class="form-text">
                                Mínimo 8 caracteres, mayúsculas, minúsculas, números y símbolos.
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="bi bi-check2-circle me-1"></i>
                                Confirmar Contraseña
                            </label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div id="passwordStrength" class="mb-3" style="display: none;">
                            <label class="form-label">Fortaleza:</label>
                            <div class="progress">
                                <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small id="strengthText" class="form-text"></small>
                        </div>
                        
                        <div class="alert alert-danger" id="passwordChangeError" style="display: none;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <span id="passwordChangeErrorText"></span>
                        </div>
                        
                        <div class="alert alert-success" id="passwordChangeSuccess" style="display: none;">
                            <i class="bi bi-check-circle me-2"></i>
                            Contraseña cambiada exitosamente.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" form="forcePasswordChangeForm" id="changePasswordBtn">
                        <i class="bi bi-shield-check me-2"></i>
                        Cambiar Contraseña
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="main-content">
        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
                <p class="mb-0">Bienvenido, <?php echo sanitizeOutput($user['display_name']); ?>! Rol: <?php echo sanitizeOutput(getUserRole()); ?></p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['total_clients']); ?></div>
                        <div class="stat-label">Total Clientes</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['total_products']); ?></div>
                        <div class="stat-label">Total Productos</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-box"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['monthly_quotes']); ?></div>
                        <div class="stat-label">Cotizaciones del Mes</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-file-text"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo formatCurrency($stats['monthly_sales']); ?></div>
                        <div class="stat-label">Ventas del Mes</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['pending_quotes']); ?></div>
                        <div class="stat-label">Cotizaciones Pendientes</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['low_stock_products']); ?></div>
                        <div class="stat-label">Stock Bajo</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="quick-actions">
            <h5 class="quick-actions-title">
                <i class="bi bi-lightning"></i>
                Acciones Rápidas
            </h5>
            <div class="quick-actions-grid">
                <?php if (canAccessModule('clients')): ?>
                <a href="<?php echo url('clients', 'add'); ?>" class="quick-action-btn">
                    <i class="bi bi-person-plus"></i>
                    <span>Agregar Cliente</span>
                </a>
                <?php endif; ?>

                <?php if (canAccessModule('quotes')): ?>
                <a href="<?php echo url('quotes', 'create'); ?>" class="quick-action-btn">
                    <i class="bi bi-file-plus"></i>
                    <span>Crear Cotización</span>
                </a>
                <?php endif; ?>

                <?php if (canAccessModule('products')): ?>
                <a href="<?php echo url('products', 'add'); ?>" class="quick-action-btn">
                    <i class="bi bi-box-seam"></i>
                    <span>Agregar Producto</span>
                </a>
                <?php endif; ?>

                <a href="<?php echo url('users', 'edit', ['id' => $user['user_id']]); ?>" class="quick-action-btn">
                    <i class="bi bi-person-gear"></i>
                    <span>Mi Perfil</span>
                </a>
            </div>
        </div>

        <div class="charts-section mb-4">
            <div class="row g-3">
                <div class="col-12">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h6 class="chart-title">
                                <i class="bi bi-graph-up"></i>
                                Tendencia de Ventas (Últimos 7 Días)
                            </h6>
                        </div>
                        <div class="sales-trend-container">
                            <canvas id="salesTrendChart"></canvas>
                            <div class="sales-trend-tooltip" id="salesTrendTooltip"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h6 class="chart-title">
                                <i class="bi bi-pie-chart"></i>
                                Estado de Cotizaciones
                            </h6>
                        </div>
                        <div class="chart-container">
                            <canvas id="quotesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h6 class="chart-title">
                                <i class="bi bi-people"></i>
                                Top 5 Clientes
                            </h6>
                        </div>
                        <div class="chart-container">
                            <canvas id="clientsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
    const salesData = <?php echo json_encode($salesData); ?>;
    
    const salesTrendLabels = [];
    const salesTrendValues = [];
    
    const last7Days = [];
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        last7Days.push(date.toISOString().split('T')[0]);
    }
    
    last7Days.forEach(day => {
        const sale = salesData.find(item => item.date === day);
        salesTrendLabels.push(new Date(day).toLocaleDateString());
        salesTrendValues.push(sale ? parseFloat(sale.total) : 0);
    });
    
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    const salesTrendChart = new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: salesTrendLabels,
            datasets: [{
                label: 'Ventas',
                data: salesTrendValues,
                borderColor: '#1e3a8a',
                backgroundColor: 'rgba(30, 58, 138, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#1e3a8a',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
    
    <?php
    $quotesStatusData = [];
    try {
        $quotesStatusData = $db->fetchAll("SELECT status, COUNT(*) as count FROM quotes WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) GROUP BY status");
    } catch (Exception $e) {
        logError("Chart data error: " . $e->getMessage());
    }
    ?>
    
    const quotesStatusData = <?php echo json_encode($quotesStatusData); ?>;
    const topClientsData = <?php echo json_encode(array_slice($topClients, 0, 5)); ?>;
    
    if (quotesStatusData.length > 0) {
        const quotesCtx = document.getElementById('quotesChart').getContext('2d');
        new Chart(quotesCtx, {
            type: 'doughnut',
            data: {
                labels: quotesStatusData.map(item => item.status),
                datasets: [{
                    data: quotesStatusData.map(item => parseInt(item.count)),
                    backgroundColor: ['#1e3a8a', '#3b82f6', '#60a5fa', '#93c5fd'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    if (topClientsData.length > 0) {
        const clientsCtx = document.getElementById('clientsChart').getContext('2d');
        new Chart(clientsCtx, {
            type: 'bar',
            data: {
                labels: topClientsData.map(client => client.company_name.substring(0, 12)),
                datasets: [{
                    label: 'Total',
                    data: topClientsData.map(client => parseFloat(client.total_spend)),
                    backgroundColor: '#1e3a8a'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    <?php if ($forcePasswordChange): ?>
    const forcePasswordModal = new bootstrap.Modal(document.getElementById('forcePasswordChangeModal'));
    forcePasswordModal.show();
    
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        const strengthDiv = document.getElementById('passwordStrength');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        if (password.length > 0) {
            strengthDiv.style.display = 'block';
            
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 50) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Débil';
            } else if (strength < 100) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Medio';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Fuerte';
            }
        } else {
            strengthDiv.style.display = 'none';
        }
    });
    
    document.getElementById('confirm_password').addEventListener('input', function() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        
        if (confirmPassword.length > 0) {
            if (newPassword !== confirmPassword) {
                this.classList.add('is-invalid');
                this.nextElementSibling.textContent = 'Las contraseñas no coinciden';
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        }
    });
    
    document.getElementById('forcePasswordChangeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('changePasswordBtn');
        const errorDiv = document.getElementById('passwordChangeError');
        const successDiv = document.getElementById('passwordChangeSuccess');
        
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Cambiando...';
        
        fetch('/?module=users&action=force_password_change', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            const contentType = response.headers.get('Content-Type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    throw new Error('Server returned non-JSON response');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                successDiv.style.display = 'block';
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                errorDiv.style.display = 'block';
                if (data.errors && Object.keys(data.errors).length > 0) {
                    let errorMessage = '';
                    for (const [field, message] of Object.entries(data.errors)) {
                        errorMessage += message + '. ';
                        const fieldElement = document.getElementById(field);
                        if (fieldElement) {
                            fieldElement.classList.add('is-invalid');
                            const feedback = fieldElement.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = message;
                            }
                        }
                    }
                    document.getElementById('passwordChangeErrorText').textContent = errorMessage;
                } else {
                    document.getElementById('passwordChangeErrorText').textContent = data.message || data.error || 'Error al cambiar la contraseña';
                }
            }
        })
        .catch(error => {
            errorDiv.style.display = 'block';
            document.getElementById('passwordChangeErrorText').textContent = 'Error de conexión';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-shield-check me-2"></i>Cambiar Contraseña';
        });
    });
    
    document.getElementById('current_password').addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
    
    document.getElementById('new_password').addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
    
    document.getElementById('confirm_password').addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
    <?php endif; ?>
    </script>
</body>
</html>