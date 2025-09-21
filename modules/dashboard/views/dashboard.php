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
        /* Estilos adicionales para la gráfica de tendencia de ventas */
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
        
        .sales-trend-tooltip:after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid rgba(30, 58, 138, 0.9);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Dashboard Header -->
        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="bi bi-speedometer2 me-2"></i><?php echo __('dashboard') ?: 'Dashboard'; ?></h1>
                <p class="mb-0"><?php echo __('welcome') ?: 'Welcome'; ?>, <?php echo sanitizeOutput($user['display_name']); ?>! <?php echo __('role') ?: 'Role'; ?>: <?php echo sanitizeOutput(getUserRole()); ?> | <?php echo __('company') ?: 'Company'; ?>: <?php echo sanitizeOutput($companyName); ?></p>
            </div>
            <div class="dashboard-logo">
                <?php
                // Get company logo from settings
                $companyLogo = '';
                try {
                    $logoResult = $db->fetch("SELECT setting_value FROM vw_settings WHERE setting_key = 'company_logo'");
                    if ($logoResult && !empty($logoResult['setting_value'])) {
                        $companyLogo = $logoResult['setting_value'];
                    }
                } catch (Exception $e) {
                    logError("Failed to get company logo: " . $e->getMessage());
                }
                ?>
                <?php if (!empty($companyLogo)): ?>
                    <img src="<?php echo sanitizeOutput($companyLogo); ?>" alt="Company Logo" style="max-height: 60px; max-width: 150px;" class="img-fluid">
                <?php else: ?>
                    <i class="bi bi-building" style="font-size: 2.5rem; color: #1e3a8a; opacity: 0.7;"></i>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistics Cards -->
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

        <!-- Quick Actions -->
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

                <?php if (hasPermission('view_sales_reports')): ?>
                <a href="<?php echo url('reports', 'sales'); ?>" class="quick-action-btn">
                    <i class="bi bi-bar-chart"></i>
                    <span><?php echo __('view_reports') ?: 'View Reports'; ?></span>
                </a>
                <?php endif; ?>

                <a href="<?php echo url('users', 'edit', ['id' => $user['user_id']]); ?>" class="quick-action-btn">
                    <i class="bi bi-person-gear"></i>
                    <span><?php echo __('my_profile') ?: 'My Profile'; ?></span>
                </a>

                <?php if (hasPermission('manage_settings')): ?>
                <a href="<?php echo url('settings', 'edit'); ?>" class="quick-action-btn">
                    <i class="bi bi-gear"></i>
                    <span><?php echo __('settings') ?: 'Settings'; ?></span>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section mb-4">
            <div class="row g-3">
                <div class="col-12">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h6 class="chart-title">
                                <i class="bi bi-graph-up"></i>
                                Tendencia de Ventas (Últimos 7 Días)
                            </h6>
                            <div class="chart-actions">
                                <button class="btn btn-sm btn-outline-primary" id="refreshSalesChart">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                            </div>
                        </div>
                        <div class="sales-trend-container">
                            <canvas id="salesTrendChart"></canvas>
                            <div class="sales-trend-tooltip" id="salesTrendTooltip"></div>
                        </div>
                        <div class="chart-footer">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Muestra las ventas aprobadas de los últimos 30 días. Los datos se actualizan automáticamente.
                            </small>
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

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Left Column - Charts and Activities -->
            <div>
                <!-- Recent Activities -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h5 class="chart-title">
                            <i class="bi bi-activity"></i>
                            <?php echo __('recent_activities') ?: 'Recent Activities'; ?>
                        </h5>
                    </div>
                    <?php if (!empty($recentActivities)): ?>
                        <div class="table-responsive">
                            <table class="table dashboard-table">
                                <thead>
                                    <tr>
                                        <th><?php echo __('activity') ?: 'Activity'; ?></th>
                                        <th><?php echo __('client') ?: 'Client'; ?></th>
                                        <th><?php echo __('date') ?: 'Date'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentActivities as $activity): ?>
                                        <tr>
                                            <td>
                                                <span class="activity-icon <?php echo $activity['activity_type'] === 'QUOTE_APPROVED' ? 'success' : 'primary'; ?>">
                                                    <i class="bi bi-<?php echo $activity['activity_type'] === 'QUOTE_APPROVED' ? 'check-circle' : 'file-text'; ?>"></i>
                                                </span>
                                                <?php echo sanitizeOutput($activity['activity_type']); ?>
                                                <?php if ($activity['quote_number']): ?>
                                                    - <?php echo sanitizeOutput($activity['quote_number']); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo sanitizeOutput($activity['company_name']); ?></td>
                                            <td><?php echo formatDate($activity['activity_date'], 'M j, Y'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted"><?php echo __('no_recent_activities') ?: 'No recent activities'; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Recent Quotes -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h5 class="chart-title">
                            <i class="bi bi-file-text"></i>
                            <?php echo __('recent_quotes') ?: 'Recent Quotes'; ?>
                        </h5>
                        <?php if (canAccessModule('quotes')): ?>
                            <a href="<?php echo url('quotes', 'list'); ?>" class="btn btn-outline-dashboard btn-sm">
                                <?php echo __('view_all') ?: 'View All'; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($recentQuotes)): ?>
                        <div class="table-responsive">
                            <table class="table dashboard-table">
                                <thead>
                                    <tr>
                                        <th><?php echo __('quote_number') ?: 'Quote #'; ?></th>
                                        <th><?php echo __('client') ?: 'Client'; ?></th>
                                        <th><?php echo __('status') ?: 'Status'; ?></th>
                                        <th><?php echo __('amount') ?: 'Amount'; ?></th>
                                        <th><?php echo __('date') ?: 'Date'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentQuotes as $quote): ?>
                                        <tr>
                                            <td>
                                                <?php if (canAccessModule('quotes')): ?>
                                                    <a href="<?php echo url('quotes', 'view', ['id' => $quote['quote_id']]); ?>">
                                                        <?php echo sanitizeOutput($quote['quote_number']); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php echo sanitizeOutput($quote['quote_number']); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo sanitizeOutput($quote['client_name']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $quote['status'] === 'APPROVED' ? 'success' : ($quote['status'] === 'SENT' ? 'warning' : 'secondary'); ?>">
                                                    <?php echo sanitizeOutput($quote['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatCurrency($quote['total_amount']); ?></td>
                                            <td><?php echo formatDate($quote['issue_date'], 'M j, Y'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted"><?php echo __('no_recent_quotes') ?: 'No recent quotes'; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column - Alerts and Top Lists -->
            <div>
                <!-- Keyboard Shortcuts -->
                <div class="sidebar-card mb-3">
                    <h6 class="sidebar-card-title">
                        <i class="bi bi-keyboard"></i>
                        <?php echo __('keyboard_shortcuts') ?: 'Keyboard Shortcuts'; ?>
                    </h6>
                    <div class="shortcuts-list">
                        <div class="shortcut-item d-flex justify-content-between align-items-center mb-2">
                            <span class="shortcut-label"><?php echo __('new_client') ?: 'New Client'; ?></span>
                            <kbd class="shortcut-key">Ctrl Alt C</kbd>
                        </div>
                        <div class="shortcut-item d-flex justify-content-between align-items-center mb-2">
                            <span class="shortcut-label"><?php echo __('new_quote') ?: 'New Quote'; ?></span>
                            <kbd class="shortcut-key">Ctrl Alt Q</kbd>
                        </div>
                        <div class="shortcut-item d-flex justify-content-between align-items-center mb-2">
                            <span class="shortcut-label"><?php echo __('new_product') ?: 'New Product'; ?></span>
                            <kbd class="shortcut-key">Ctrl Alt N</kbd>
                        </div>
                        <div class="shortcut-item d-flex justify-content-between align-items-center mb-2">
                            <span class="shortcut-label"><?php echo __('dashboard') ?: 'Dashboard'; ?></span>
                            <kbd class="shortcut-key">Ctrl Alt D</kbd>
                        </div>
                        <div class="shortcut-item d-flex justify-content-between align-items-center">
                            <span class="shortcut-label"><?php echo __('profile') ?: 'Profile'; ?></span>
                            <kbd class="shortcut-key">Ctrl Alt P</kbd>
                        </div>
                    </div>
                </div>

                <!-- Expiring Quotes -->
                <?php if (!empty($expiringQuotes)): ?>
                <div class="sidebar-card mb-3">
                    <h6 class="sidebar-card-title">
                        <i class="bi bi-clock-history"></i>
                        <?php echo __('expiring_quotes') ?: 'Expiring Quotes'; ?>
                    </h6>
                    <?php foreach ($expiringQuotes as $quote): ?>
                        <div class="expiring-quote">
                            <div class="quote-info">
                                <div class="quote-details">
                                    <h6><?php echo sanitizeOutput($quote['quote_number']); ?></h6>
                                    <small><?php echo sanitizeOutput($quote['client_name']); ?></small>
                                </div>
                                <span class="quote-badge badge-<?php echo $quote['days_until_expiry'] <= 1 ? 'danger' : 'warning'; ?>">
                                    <?php echo $quote['days_until_expiry']; ?> <?php echo __('days') ?: 'days'; ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Low Stock Products -->
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
                                    <strong><?php echo sanitizeOutput($product['product_name']); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo sanitizeOutput($product['sku']); ?></small>
                                </div>
                                <span class="badge bg-danger"><?php echo $product['stock_quantity']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Top Clients -->
                <?php if (!empty($topClients)): ?>
                <div class="sidebar-card mb-3">
                    <h6 class="sidebar-card-title">
                        <i class="bi bi-star"></i>
                        <?php echo __('top_clients') ?: 'Top Clients'; ?>
                    </h6>
                    <?php foreach ($topClients as $client): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong><?php echo sanitizeOutput($client['company_name']); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo $client['purchase_count']; ?> <?php echo __('orders') ?: 'orders'; ?></small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold"><?php echo formatCurrency($client['total_spend']); ?></span>
                                <br>
                                <small class="text-muted"># <?php echo $client['rank']; ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
    .shortcuts-list {
        font-size: 0.875rem;
    }
    
    .shortcut-label {
        color: #374151;
        font-weight: 500;
    }
    
    .shortcut-key {
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #374151;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .sidebar-card {
        margin-bottom: 1.5rem;
    }
    
    .chart-card {
        margin-bottom: 1.5rem;
        min-height: 200px;
    }
    
    .charts-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(30, 58, 138, 0.08);
        border: 1px solid rgba(30, 58, 138, 0.1);
    }
    
    .charts-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e3a8a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .charts-container .chart-card {
        min-height: 280px;
        margin-bottom: 0;
    }
    
    .charts-container .chart-container {
        position: relative;
        height: 220px;
        width: 100%;
    }
    
    .charts-container .chart-header h6 {
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .chart-footer {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }
    </style>
    
    <script>
    // Charts - Improved Sales Trend Chart Logic from Document B
    const salesData = <?php echo json_encode($salesData); ?>;
    
    // Process sales data directly without generating arbitrary date ranges
    const salesTrendLabels = [];
    const salesTrendValues = [];
    
    if (salesData.length > 0) {
        // Use actual data from database
        salesData.forEach(item => {
            const date = new Date(item.date);
            salesTrendLabels.push(date.toLocaleDateString());
            salesTrendValues.push(parseFloat(item.total));
        });
    } else {
        // Show empty chart with message
        salesTrendLabels.push('Sin datos');
        salesTrendValues.push(0);
    }
    
    // Crear la gráfica de tendencia de ventas
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
    
    // Botón para actualizar la gráfica
    document.getElementById('refreshSalesChart').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualizando...';
        
        // Simular una actualización (en un caso real, harías una petición AJAX)
        setTimeout(() => {
            salesTrendChart.update();
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualizar';
        }, 1000);
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
    
    // Keyboard shortcuts functionality
    document.addEventListener('keydown', function(e) {
        // Ctrl + Alt + C - New Client
        if (e.ctrlKey && e.altKey && e.key === 'c') {
            e.preventDefault();
            <?php if (canAccessModule('clients')): ?>
            window.location.href = '<?php echo url('clients', 'add'); ?>';
            <?php endif; ?>
        }
        
        // Ctrl + Alt + Q - New Quote
        if (e.ctrlKey && e.altKey && e.key === 'q') {
            e.preventDefault();
            <?php if (canAccessModule('quotes')): ?>
            window.location.href = '<?php echo url('quotes', 'create'); ?>';
            <?php endif; ?>
        }
        
        // Ctrl + Alt + N - New Product
        if (e.ctrlKey && e.altKey && e.key === 'n') {
            e.preventDefault();
            <?php if (canAccessModule('products')): ?>
            window.location.href = '<?php echo url('products', 'add'); ?>';
            <?php endif; ?>
        }
        
        // Ctrl + Alt + D - Dashboard
        if (e.ctrlKey && e.altKey && e.key === 'd') {
            e.preventDefault();
            window.location.href = '<?php echo url('dashboard', 'index'); ?>';
        }
        
        // Ctrl + Alt + P - Profile
        if (e.ctrlKey && e.altKey && e.key === 'p') {
            e.preventDefault();
            window.location.href = '<?php echo url('users', 'edit', ['id' => $user['user_id']]); ?>';
        }
    });
    </script>
</body>
</html>