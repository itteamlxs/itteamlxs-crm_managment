<?php
/**
 * Dashboard Charts Section Component
 * Sales trend, quote status, and top clients charts
 */

if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
    require_once __DIR__ . '/../../core/rbac.php';
    require_once __DIR__ . '/../../config/db.php';
}

requireLogin();
$user = getCurrentUser();
$db = Database::getInstance();

// Get sales trend data (last 30 days) - filtered by user
try {
    if ($user['is_admin']) {
        $salesData = $db->fetchAll("SELECT DATE(issue_date) as date, SUM(total_amount) as total FROM quotes WHERE status = 'APPROVED' AND issue_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY DATE(issue_date) ORDER BY date ASC");
    } else {
        $salesData = $db->fetchAll("SELECT DATE(issue_date) as date, SUM(total_amount) as total FROM quotes WHERE user_id = ? AND status = 'APPROVED' AND issue_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY DATE(issue_date) ORDER BY date ASC", [$user['user_id']]);
    }
} catch (Exception $e) {
    logError("Failed to get sales data: " . $e->getMessage());
    $salesData = [];
}

// Get quote status data - filtered by user
try {
    if ($user['is_admin']) {
        $quotesStatusData = $db->fetchAll("SELECT status, COUNT(*) as count FROM quotes WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) GROUP BY status");
    } else {
        $quotesStatusData = $db->fetchAll("SELECT status, COUNT(*) as count FROM quotes WHERE user_id = ? AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) GROUP BY status", [$user['user_id']]);
    }
} catch (Exception $e) {
    logError("Chart data error: " . $e->getMessage());
    $quotesStatusData = [];
}

// Get top clients - filtered by user
try {
    if ($user['is_admin']) {
        $topClients = $db->fetchAll("SELECT * FROM vw_top_clients LIMIT 5");
    } else {
        $topClients = $db->fetchAll("SELECT c.client_id, c.company_name, SUM(q.total_amount) as total_spend, COUNT(q.quote_id) as purchase_count FROM clients c JOIN quotes q ON c.client_id = q.client_id WHERE q.status = 'APPROVED' AND c.created_by = ? GROUP BY c.client_id, c.company_name ORDER BY total_spend DESC LIMIT 5", [$user['user_id']]);
    }
} catch (Exception $e) {
    logError("Top clients error: " . $e->getMessage());
    $topClients = [];
}
?>

<div class="charts-section mb-4">
    <div class="row g-3">
        <!-- Sales Trend Chart -->
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i class="bi bi-graph-up"></i>
                        <?php echo __('sales_trend') ?: 'Tendencia de Ventas'; ?> (<?php echo __('last_30_days') ?: 'Últimos 30 Días'; ?>)
                    </h6>
                    <div class="chart-actions">
                        <button class="btn btn-sm btn-outline-primary" id="refreshSalesChart">
                            <i class="bi bi-arrow-clockwise"></i> <?php echo __('update_sales') ?: 'Actualizar'; ?>
                        </button>
                    </div>
                </div>
                <div class="sales-trend-container">
                    <canvas id="salesTrendChart"></canvas>
                </div>
                <div class="chart-footer">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        <?php echo __('info_sales_trend') ?: 'Muestra las ventas aprobadas de los últimos 30 días. Los datos se actualizan automáticamente.'; ?>
                    </small>
                </div>
            </div>
        </div>

        <!-- Quote Status Chart -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i class="bi bi-pie-chart"></i>
                        <?php echo __('quote_status') ?: 'Estado de Cotizaciones'; ?>
                    </h6>
                </div>
                <div class="chart-container">
                    <canvas id="quotesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Clients Chart -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i class="bi bi-people"></i>
                        <?php echo __('client_rank') ?: 'Top 5 Clientes'; ?>
                    </h6>
                </div>
                <div class="chart-container">
                    <canvas id="clientsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Trend Chart
const salesData = <?php echo json_encode($salesData); ?>;
const salesTrendLabels = [];
const salesTrendValues = [];

if (salesData.length > 0) {
    salesData.forEach(item => {
        const date = new Date(item.date);
        salesTrendLabels.push(date.toLocaleDateString());
        salesTrendValues.push(parseFloat(item.total));
    });
} else {
    salesTrendLabels.push('Sin datos');
    salesTrendValues.push(0);
}

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

// Refresh button
document.getElementById('refreshSalesChart').addEventListener('click', function() {
    this.disabled = true;
    this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualizando...';
    
    setTimeout(() => {
        salesTrendChart.update();
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> <?php echo __('update_sales') ?: 'Actualizar'; ?>';
    }, 1000);
});

// Quote Status Chart
const quotesStatusData = <?php echo json_encode($quotesStatusData); ?>;

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

// Top Clients Chart
const topClientsData = <?php echo json_encode($topClients); ?>;

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