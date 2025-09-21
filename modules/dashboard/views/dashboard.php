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

// Verificar si el usuario debe cambiar su contraseña
$forcePasswordChange = false;
try {
    $sql = "SELECT force_password_change FROM users WHERE user_id = ?";
    $result = $db->fetch($sql, [$user['user_id']]);
    if ($result) {
        $forcePasswordChange = (bool)$result['force_password_change'];
    }
} catch (Exception $e) {
    logError("Error checking force_password_change: " . $e->getMessage());
}

// Obtener mensajes de error/éxito de la sesión
$passwordChangeError = $_SESSION['password_change_error'] ?? '';
$passwordChangeSuccess = $_SESSION['password_change_success'] ?? '';

// Limpiar mensajes de la sesión
unset($_SESSION['password_change_error'], $_SESSION['password_change_success']);

// Obtener datos para la gráfica de tendencia de ventas
try {
    $salesData = $db->fetchAll("SELECT DATE(issue_date) as date, SUM(total_amount) as total FROM quotes WHERE status = 'APPROVED' AND issue_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(issue_date) ORDER BY date ASC");
} catch (Exception $e) {
    logError("Failed to get sales data: " . $e->getMessage());
    $salesData = [];
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
        
        .password-strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background-color: #dc3545; }
        .strength-fair { background-color: #fd7e14; }
        .strength-good { background-color: #ffc107; }
        .strength-strong { background-color: #198754; }
        
        .password-requirements {
            font-size: 0.875rem;
        }
        
        .password-requirements .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }
        
        .password-requirements .requirement i {
            margin-right: 0.5rem;
            width: 16px;
        }
        
        .requirement.met { color: #198754; }
        .requirement.unmet { color: #6c757d; }
        
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
                        <?php echo __('change_password_required'); ?>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <?php echo __('first_login_message'); ?>
                    </div>
                    
                    <?php if (!empty($passwordChangeError)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo sanitizeOutput($passwordChangeError); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($passwordChangeSuccess)): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo sanitizeOutput($passwordChangeSuccess); ?>
                    </div>
                    <script>
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    </script>
                    <?php else: ?>
                    
                    <form id="forcePasswordChangeForm" method="POST" action="/crm-project/public/index.php?module=dashboard&action=f_password">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="bi bi-lock me-1"></i>
                                <?php echo __('current_password'); ?>
                            </label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                <i class="bi bi-key me-1"></i>
                                <?php echo __('new_password'); ?>
                            </label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            
                            <div class="mt-2" id="passwordStrengthContainer" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Fortaleza:</small>
                                    <small id="strengthText" class="text-muted"></small>
                                </div>
                                <div class="progress" style="height: 4px;">
                                    <div id="strengthBar" class="progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                            
                            <div class="password-requirements mt-2" id="passwordRequirements" style="display: none;">
                                <div class="requirement" id="req-length">
                                    <i class="bi bi-x-circle"></i>
                                    <span>Mínimo 8 caracteres</span>
                                </div>
                                <div class="requirement" id="req-uppercase">
                                    <i class="bi bi-x-circle"></i>
                                    <span>Una letra mayúscula</span>
                                </div>
                                <div class="requirement" id="req-lowercase">
                                    <i class="bi bi-x-circle"></i>
                                    <span>Una letra minúscula</span>
                                </div>
                                <div class="requirement" id="req-number">
                                    <i class="bi bi-x-circle"></i>
                                    <span>Un número</span>
                                </div>
                                <div class="requirement" id="req-special">
                                    <i class="bi bi-x-circle"></i>
                                    <span>Un carácter especial</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="bi bi-check2-circle me-1"></i>
                                <?php echo __('confirm_password'); ?>
                            </label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </form>
                    
                    <?php endif; ?>
                </div>
                <?php if (empty($passwordChangeSuccess)): ?>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" form="forcePasswordChangeForm" id="changePasswordBtn">
                        <i class="bi bi-shield-check me-2"></i>
                        Cambiar Contraseña
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="main-content">
        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="bi bi-speedometer2 me-2"></i><?php echo __('dashboard') ?: 'Dashboard'; ?></h1>
                <p class="mb-0"><?php echo __('welcome') ?: 'Welcome'; ?>, <?php echo sanitizeOutput($user['display_name']); ?>! <?php echo __('role') ?: 'Role'; ?>: <?php echo sanitizeOutput(getUserRole()); ?> | <?php echo __('company') ?: 'Company'; ?>: <?php echo sanitizeOutput($companyName); ?></p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['total_clients']); ?></div>
                        <div class="stat-label"><?php echo __('total_clients') ?: 'Total Clients'; ?></div>
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
                        <div class="stat-label"><?php echo __('total_products') ?: 'Total Products'; ?></div>
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
                        <div class="stat-label"><?php echo __('monthly_quotes') ?: 'Monthly Quotes'; ?></div>
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
                        <div class="stat-label"><?php echo __('monthly_sales') ?: 'Monthly Sales'; ?></div>
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
                        <div class="stat-label"><?php echo __('pending_quotes') ?: 'Pending Quotes'; ?></div>
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
                        <div class="stat-label"><?php echo __('low_stock_alerts') ?: 'Low Stock Alerts'; ?></div>
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
                <?php echo __('quick_actions') ?: 'Quick Actions'; ?>
            </h5>
            <div class="quick-actions-grid">
                <?php if (canAccessModule('clients')): ?>
                <a href="<?php echo url('clients', 'add'); ?>" class="quick-action-btn">
                    <i class="bi bi-person-plus"></i>
                    <span><?php echo __('add_client') ?: 'Add Client'; ?></span>
                </a>
                <?php endif; ?>

                <?php if (canAccessModule('quotes')): ?>
                <a href="<?php echo url('quotes', 'create'); ?>" class="quick-action-btn">
                    <i class="bi bi-file-plus"></i>
                    <span><?php echo __('create_quote') ?: 'Create Quote'; ?></span>
                </a>
                <?php endif; ?>

                <?php if (canAccessModule('products')): ?>
                <a href="<?php echo url('products', 'add'); ?>" class="quick-action-btn">
                    <i class="bi bi-box-seam"></i>
                    <span><?php echo __('add_product') ?: 'Add Product'; ?></span>
                </a>
                <?php endif; ?>

                <a href="<?php echo url('users', 'edit', ['id' => $user['user_id']]); ?>" class="quick-action-btn">
                    <i class="bi bi-person-gear"></i>
                    <span><?php echo __('my_profile') ?: 'My Profile'; ?></span>
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
    // Force Password Change Modal Logic - SIN AJAX
    <?php if ($forcePasswordChange): ?>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('forcePasswordChangeModal'));
        modal.show();
        
        const form = document.getElementById('forcePasswordChangeForm');
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        if (form) {
            function checkPasswordStrength(password) {
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[^a-zA-Z0-9]/.test(password)
                };
                
                const score = Object.values(requirements).filter(Boolean).length;
                return { requirements, score };
            }
            
            function updateRequirements(requirements) {
                const reqElements = {
                    length: document.getElementById('req-length'),
                    uppercase: document.getElementById('req-uppercase'),
                    lowercase: document.getElementById('req-lowercase'),
                    number: document.getElementById('req-number'),
                    special: document.getElementById('req-special')
                };
                
                Object.keys(requirements).forEach(req => {
                    const element = reqElements[req];
                    if (element) {
                        const icon = element.querySelector('i');
                        if (requirements[req]) {
                            element.classList.add('met');
                            element.classList.remove('unmet');
                            icon.className = 'bi bi-check-circle';
                        } else {
                            element.classList.add('unmet');
                            element.classList.remove('met');
                            icon.className = 'bi bi-x-circle';
                        }
                    }
                });
            }
            
            function updateStrengthBar(score) {
                const strengthBar = document.getElementById('strengthBar');
                const strengthText = document.getElementById('strengthText');
                const percentage = (score / 5) * 100;
                
                strengthBar.style.width = percentage + '%';
                
                if (score <= 2) {
                    strengthBar.className = 'progress-bar strength-weak';
                    strengthText.textContent = 'Débil';
                } else if (score === 3) {
                    strengthBar.className = 'progress-bar strength-fair';
                    strengthText.textContent = 'Regular';
                } else if (score === 4) {
                    strengthBar.className = 'progress-bar strength-good';
                    strengthText.textContent = 'Buena';
                } else {
                    strengthBar.className = 'progress-bar strength-strong';
                    strengthText.textContent = 'Fuerte';
                }
            }
            
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                const container = document.getElementById('passwordStrengthContainer');
                const requirements = document.getElementById('passwordRequirements');
                
                if (password.length > 0) {
                    container.style.display = 'block';
                    requirements.style.display = 'block';
                    
                    const { requirements: reqs, score } = checkPasswordStrength(password);
                    updateRequirements(reqs);
                    updateStrengthBar(score);
                } else {
                    container.style.display = 'none';
                    requirements.style.display = 'none';
                }
            });
            
            confirmPasswordInput.addEventListener('input', function() {
                const newPassword = newPasswordInput.value;
                const confirmPassword = this.value;
                
                if (confirmPassword.length > 0) {
                    if (newPassword === confirmPassword) {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                    } else {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                }
            });
            
            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('changePasswordBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Cambiando...';
            });
        }
    });
    <?php endif; ?>
    
    // Charts
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
    </script>
</body>
</html>