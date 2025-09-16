// Global Variables
        let salesChart, statusChart;
        const forcePasswordChange = <?php echo json_encode($forcePasswordChange); ?>;
        const salesData = <?php echo json_encode($salesTrends); ?>;
        const statusData = <?php echo json_encode($statusDistribution); ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            setupPasswordValidation();
            
            // Show password change modal if required
            if (forcePasswordChange) {
                const modal = new bootstrap.Modal(document.getElementById('forcePasswordChangeModal'));
                modal.show();
            }
            
            // Set default values for timezone converter
            const today = new Date().toISOString().split('T')[0];
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
$dashboardStats = [];
$recentActivity = [];
$expiringQuotes = [];
$topClients = [];
$topProducts = [];
$salesTrends = [];
$statusDistribution = [];

try {
    $db = Database::getInstance();
    
    // Get company name
    $result = $db->fetch("SELECT setting_value FROM vw_settings WHERE setting_key = 'company_display_name'");
    if ($result) {
        $companyName = $result['setting_value'];
    }
    
    // Dashboard Statistics
    $statsQueries = [
        'total_quotes' => "SELECT COUNT(*) as count FROM quotes",
        'total_clients' => "SELECT COUNT(*) as count FROM vw_clients",
        'pending_quotes' => "SELECT COUNT(*) as count FROM quotes WHERE status = 'SENT'",
        'total_revenue' => "SELECT COALESCE(SUM(total_amount), 0) as amount FROM quotes WHERE status = 'APPROVED' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())",
        'conversion_rate' => "SELECT ROUND((SELECT COUNT(*) FROM quotes WHERE status = 'APPROVED') * 100.0 / NULLIF((SELECT COUNT(*) FROM quotes WHERE status IN ('SENT', 'APPROVED', 'REJECTED')), 0), 1) as rate",
        'avg_deal_size' => "SELECT COALESCE(AVG(total_amount), 0) as avg_amount FROM quotes WHERE status = 'APPROVED'"
    ];
    
    foreach ($statsQueries as $key => $query) {
        $result = $db->fetch($query);
        $dashboardStats[$key] = $result[array_key_first($result)] ?? 0;
    }
    
    // Recent Activity from audit_logs
    $recentActivity = $db->fetchAll("
        SELECT a.action, a.entity_type, a.entity_id, a.created_at, u.username, u.display_name,
               CASE 
                   WHEN a.action = 'INSERT' AND a.entity_type = 'QUOTE' THEN 'Nueva cotización creada'
                   WHEN a.action = 'INSERT' AND a.entity_type = 'USER' THEN 'Nuevo cliente registrado'
                   WHEN a.action = 'UPDATE' AND a.entity_type = 'QUOTE' THEN 'Cotización actualizada'
                   WHEN a.action = 'STOCK_UPDATE' THEN 'Stock actualizado'
                   ELSE CONCAT(a.action, ' en ', a.entity_type)
               END as activity_description,
               CASE 
                   WHEN a.action = 'INSERT' THEN 'success'
                   WHEN a.action = 'UPDATE' THEN 'primary'
                   WHEN a.action = 'STOCK_UPDATE' THEN 'warning'
                   ELSE 'secondary'
               END as activity_type
        FROM audit_logs a
        LEFT JOIN users u ON a.user_id = u.user_id
        ORDER BY a.created_at DESC
        LIMIT 10
    ");
    
    // Expiring Quotes
    $expiringQuotes = $db->fetchAll("
        SELECT quote_id, quote_number, company_name as client_name, expiry_date,
               DATEDIFF(expiry_date, CURDATE()) as days_until_expiry
        FROM vw_expiring_quotes
        ORDER BY days_until_expiry ASC
        LIMIT 5
    ");
    
    // Top Clients by total spend
    $topClients = $db->fetchAll("
        SELECT client_id, company_name, total_spend, purchase_count
        FROM vw_top_clients
        ORDER BY total_spend DESC
        LIMIT 5
    ");
    
    // Top Products by quantity sold
    $topProducts = $db->fetchAll("
        SELECT product_id, product_name, sku, total_sold, 
               (total_sold * (SELECT AVG(unit_price) FROM quote_items qi WHERE qi.product_id = pp.product_id)) as revenue
        FROM vw_product_performance pp
        WHERE total_sold IS NOT NULL
        ORDER BY total_sold DESC
        LIMIT 5
    ");
    
    // Sales Trends for chart (last 6 months)
    $salesTrends = $db->fetchAll("
        SELECT month, total_amount, total_quotes
        FROM vw_sales_trends
        ORDER BY month DESC
        LIMIT 6
    ");
    $salesTrends = array_reverse($salesTrends);
    
    // Status Distribution for pie chart
    $statusDistribution = $db->fetchAll("
        SELECT status,
               COUNT(*) as count,
               CASE 
                   WHEN status = 'APPROVED' THEN 'Aprobadas'
                   WHEN status = 'SENT' THEN 'Pendientes'
                   WHEN status = 'REJECTED' THEN 'Rechazadas'
                   WHEN status = 'DRAFT' THEN 'Borrador'
                   ELSE status
               END as status_label
        FROM quotes
        GROUP BY status
    ");
    
} catch (Exception $e) {
    logError("Dashboard data loading failed: " . $e->getMessage());
    // Initialize with empty arrays to prevent errors
    $dashboardStats = [
        'total_quotes' => 0,
        'total_clients' => 0,
        'pending_quotes' => 0,
        'total_revenue' => 0,
        'conversion_rate' => 0,
        'avg_deal_size' => 0
    ];
    $recentActivity = [];
    $expiringQuotes = [];
    $topClients = [];
    $topProducts = [];
    $salesTrends = [];
    $statusDistribution = [];
}

// Check if user needs to change password
$forcePasswordChange = $user['force_password_change'] ?? false;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CRM System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="/crm-project/public/assets/css/dash.css" rel="stylesheet">
</head>
<body>
    <!-- Include Navigation -->
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Header del Dashboard -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1>Dashboard</h1>
                    <p>Resumen completo de actividad del CRM - <?php echo sanitizeOutput($companyName); ?></p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-dashboard" onclick="refreshDashboard()">
                        <i class="bi bi-arrow-clockwise"></i> Actualizar
                    </button>
                    <button class="btn btn-dashboard" onclick="generateReport()">
                        <i class="bi bi-file-earmark-text"></i> Reporte
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Cards de Estadísticas Optimizadas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($dashboardStats['total_quotes']); ?></div>
                        <div class="stat-label">Cotizaciones</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> +12%
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-file-text"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($dashboardStats['total_clients']); ?></div>
                        <div class="stat-label">Clientes</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> +8%
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value">$<?php echo number_format($dashboardStats['total_revenue'], 2); ?></div>
                        <div class="stat-label">Ingresos</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> +18%
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($dashboardStats['conversion_rate'], 1); ?>%</div>
                        <div class="stat-label">Conversión</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> +5%
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value"><?php echo number_format($dashboardStats['pending_quotes']); ?></div>
                        <div class="stat-label">Pendientes</div>
                        <div class="stat-change negative">
                            <i class="bi bi-arrow-down"></i> -3%
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <div class="stat-value">$<?php echo number_format($dashboardStats['avg_deal_size'], 0); ?></div>
                        <div class="stat-label">Ticket Promedio</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> +7%
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-calculator"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Acciones Rápidas Optimizadas -->
        <div class="quick-actions">
            <h5 class="quick-actions-title">
                <i class="bi bi-lightning-fill"></i> Acciones Rápidas
            </h5>
            <div class="quick-actions-grid">
                <a href="#" class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#newQuoteModal">
                    <i class="bi bi-plus-circle"></i>
                    <span>Nueva Cotización</span>
                </a>
                <a href="#" class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#newClientModal">
                    <i class="bi bi-person-plus"></i>
                    <span>Nuevo Cliente</span>
                </a>
                <a href="#" class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#newProductModal">
                    <i class="bi bi-box-seam"></i>
                    <span>Nuevo Producto</span>
                </a>
                <a href="#" class="quick-action-btn" onclick="generateReport()">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Reporte Ventas</span>
                </a>
                <a href="#" class="quick-action-btn" onclick="exportData()">
                    <i class="bi bi-download"></i>
                    <span>Exportar Datos</span>
                </a>
                <a href="#" class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#timezoneModal">
                    <i class="bi bi-clock"></i>
                    <span>Hora Mundial</span>
                </a>
                <a href="#" class="quick-action-btn" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                    <span>Imprimir</span>
                </a>
                <a href="#" class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#settingsModal">
                    <i class="bi bi-gear"></i>
                    <span>Configuración</span>
                </a>
            </div>
        </div>
        
        <!-- Gráficos y Contenido Principal -->
        <div class="dashboard-grid">
            <!-- Gráfico de Ventas -->
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="bi bi-graph-up"></i> Tendencias de Ventas
                    </h5>
                    <div class="chart-controls">
                        <select class="form-select form-select-sm" id="salesPeriod" style="width: auto;">
                            <option value="7">7 días</option>
                            <option value="30" selected>30 días</option>
                            <option value="90">90 días</option>
                            <option value="365">1 año</option>
                        </select>
                        <button class="btn btn-outline-dashboard btn-sm" onclick="updateChart()">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
                <canvas id="salesChart" height="280"></canvas>
            </div>
            
            <!-- Cotizaciones por Vencer y Alertas -->
            <div class="sidebar-card">
                <h5 class="sidebar-card-title">
                    <i class="bi bi-exclamation-triangle text-warning"></i> Alertas Importantes
                </h5>
                <div id="expiringQuotes">
                    <?php if (empty($expiringQuotes)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            No hay cotizaciones próximas a vencer
                        </div>
                    <?php else: ?>
                        <?php foreach ($expiringQuotes as $quote): ?>
                            <div class="expiring-quote">
                                <div class="quote-info">
                                    <div class="quote-details">
                                        <h6><?php echo sanitizeOutput($quote['quote_number']); ?></h6>
                                        <small><?php echo sanitizeOutput($quote['client_name']); ?></small>
                                    </div>
                                    <span class="quote-badge <?php echo $quote['days_until_expiry'] <= 2 ? 'badge-danger' : 'badge-warning'; ?>">
                                        <?php echo $quote['days_until_expiry']; ?> días
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <!-- Check for low stock products -->
                    <?php
                    try {
                        $lowStockProducts = $db->fetchAll("SELECT COUNT(*) as count FROM vw_low_stock_products");
                        $lowStockCount = $lowStockProducts[0]['count'] ?? 0;
                        if ($lowStockCount > 0):
                    ?>
                        <div class="expiring-quote">
                            <div class="quote-info">
                                <div class="quote-details">
                                    <h6>Stock Bajo</h6>
                                    <small><?php echo $lowStockCount; ?> productos críticos</small>
                                </div>
                                <span class="quote-badge badge-warning">Stock</span>
                            </div>
                        </div>
                    <?php 
                        endif;
                    } catch (Exception $e) {
                        // Ignore low stock check errors
                    }
                    ?>
                </div>
                
                <!-- Mini Chart -->
                <div class="mt-4">
                    <h6 class="mb-3">
                        <i class="bi bi-pie-chart"></i> Distribución Estado
                    </h6>
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Tablas de Actividad -->
        <div class="bottom-grid">
            <!-- Últimas Actividades -->
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="bi bi-clock-history"></i> Actividad Reciente
                    </h5>
                    <a href="#" class="btn btn-outline-dashboard btn-sm">Ver Todo</a>
                </div>
                <div class="table-responsive">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Actividad</th>
                                <th>Usuario</th>
                                <th>Tiempo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="recentActivity">
                            <?php if (empty($recentActivity)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="bi bi-inbox"></i> No hay actividad reciente
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentActivity as $activity): ?>
                                    <tr>
                                        <td>
                                            <div class="activity-icon <?php echo $activity['activity_type']; ?>">
                                                <i class="bi bi-<?php echo $activity['action'] === 'INSERT' ? 'plus-circle' : 'pencil-square'; ?>"></i>
                                            </div>
                                            <?php echo sanitizeOutput($activity['activity_description']); ?>
                                        </td>
                                        <td>
                                            <strong><?php echo sanitizeOutput($activity['display_name'] ?? $activity['username'] ?? 'Sistema'); ?></strong><br>
                                            <small class="text-muted"><?php echo sanitizeOutput($activity['entity_type']); ?></small>
                                        </td>
                                        <td>
                                            <small><?php echo formatDate($activity['created_at'], 'H:i'); ?></small><br>
                                            <small class="text-muted"><?php echo formatDate($activity['created_at'], 'd/m/Y'); ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = match($activity['activity_type']) {
                                                'success' => 'bg-success',
                                                'primary' => 'bg-primary', 
                                                'warning' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?>">
                                                <?php echo ucfirst($activity['action']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Top Clientes y Productos -->
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="bi bi-trophy"></i> Rendimiento
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-dashboard active" onclick="showTopClients()">
                            Clientes
                        </button>
                        <button type="button" class="btn btn-outline-dashboard" onclick="showTopProducts()">
                            Productos
                        </button>
                    </div>
                </div>
                
                <div id="topClientsTable">
                    <div class="table-responsive">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Pedidos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($topClients)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            <i class="bi bi-inbox"></i> No hay datos de clientes
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($topClients as $client): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo sanitizeOutput($client['company_name']); ?></strong><br>
                                                <small class="text-muted">ID: <?php echo $client['client_id']; ?></small>
                                            </td>
                                            <td>$<?php echo number_format($client['total_spend'], 2); ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo $client['purchase_count']; ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="topProductsTable" style="display: none;">
                    <div class="table-responsive">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Vendidos</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($topProducts)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            <i class="bi bi-inbox"></i> No hay datos de productos
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($topProducts as $product): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo sanitizeOutput($product['product_name']); ?></strong><br>
                                                <small class="text-muted">SKU: <?php echo sanitizeOutput($product['sku']); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success"><?php echo number_format($product['total_sold']); ?></span>
                                            </td>
                                            <td>$<?php echo number_format($product['revenue'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: Cambio de Contraseña Obligatorio -->
    <?php if ($forcePasswordChange): ?>
    <div class="modal fade password-modal" id="forcePasswordChangeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-shield-lock"></i>
                        Cambio de Contraseña Requerido
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Por seguridad, debes cambiar tu contraseña antes de continuar.
                    </div>
                    
                    <form id="passwordChangeForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword" name="new_password" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('newPassword')">
                                    <i class="bi bi-eye" id="toggleIcon1"></i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="strengthBar"></div>
                            </div>
                            <small id="strengthText" class="text-muted">Ingresa una contraseña</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('confirmPassword')">
                                    <i class="bi bi-eye" id="toggleIcon2"></i>
                                </button>
                            </div>
                            <div id="passwordMatch" class="mt-2"></div>
                        </div>
                        
                        <div class="password-requirements">
                            <h6>Requisitos de contraseña:</h6>
                            <ul id="passwordRequirements">
                                <li id="req-length">Mínimo 8 caracteres</li>
                                <li id="req-upper">Al menos una mayúscula</li>
                                <li id="req-lower">Al menos una minúscula</li>
                                <li id="req-number">Al menos un número</li>
                                <li id="req-special">Al menos un carácter especial</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dashboard" onclick="changePassword()" id="changePasswordBtn" disabled>
                        <i class="bi bi-shield-check"></i> Cambiar Contraseña
                        <div class="loading-spinner"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Otros Modales (Nueva Cotización, Cliente, etc.) -->
    <div class="modal fade" id="newQuoteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-file-text"></i> Nueva Cotización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="newQuoteForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Cliente</label>
                                <select class="form-select" required>
                                    <option value="">Seleccionar Cliente</option>
                                    <option value="1">ABC Corporation</option>
                                    <option value="2">XYZ Company</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" rows="3" placeholder="Descripción de la cotización..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-dashboard">Crear Cotización</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: Nuevo Cliente -->
    <div class="modal fade" id="newClientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus"></i> Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="newClientForm">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la Empresa</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contacto Principal</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-dashboard">Crear Cliente</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: Nuevo Producto -->
    <div class="modal fade" id="newProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-box-seam"></i> Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="newProductForm">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Precio</label>
                                <input type="number" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Stock</label>
                                <input type="number" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" required>
                                <option value="">Seleccionar Categoría</option>
                                <option value="1">Electrónicos</option>
                                <option value="2">Servicios</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-dashboard">Crear Producto</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: Conversor de Zonas Horarias -->
    <div class="modal fade" id="timezoneModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-globe-americas"></i> Conversor de Zonas Horarias</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Hora de origen</label>
                            <input type="time" id="horaOrigenInput" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha</label>
                            <input type="date" id="fechaInput" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">País/Zona de origen</label>
                            <select class="form-select" id="paisOrigen">
                                <option value="Europe/Madrid">España</option>
                                <option value="America/New_York">Estados Unidos (Nueva York)</option>
                                <option value="America/Mexico_City">México</option>
                                <option value="America/Bogota">Colombia</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-outline-primary d-block mx-auto" onclick="intercambiarPaises()">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">País/Zona de destino</label>
                            <select class="form-select" id="paisDestino">
                                <option value="America/New_York">Estados Unidos (Nueva York)</option>
                                <option value="Europe/Madrid">España</option>
                                <option value="America/Mexico_City">México</option>
                                <option value="America/Bogota">Colombia</option>
                            </select>
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn-calculate" onclick="calcularHora()">
                                <i class="bi bi-calculator"></i> Calcular Conversión
                            </button>
                        </div>
                    </div>
                    
                    <div id="resultados" style="display: none;" class="mt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="time-display">
                                    <div class="time-zone">Hora de origen</div>
                                    <div class="time-value" id="horaOrigenDisplay"></div>
                                    <div class="time-zone" id="zonaOrigenDisplay"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="time-display">
                                    <div class="time-zone">Hora de destino</div>
                                    <div class="time-value" id="horaDestinoDisplay"></div>
                                    <div class="time-zone" id="zonaDestinoDisplay"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global Variables
        let salesChart, statusChart;
        const forcePasswordChange = <?php echo json_encode($forcePasswordChange); ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            initializeCharts();
            setupPasswordValidation();
            
            // Show password change modal if required
            if (forcePasswordChange) {
                const modal = new bootstrap.Modal(document.getElementById('forcePasswordChangeModal'));
                modal.show();
            }
            
            // Set default values for timezone converter
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('fechaInput').value = today;
            
            const now = new Date();
            const timeString = now.toTimeString().split(' ')[0].substring(0, 5);
            document.getElementById('horaOrigenInput').value = timeString;
        });
        
        // Dashboard Data Loading
        function loadDashboardData() {
            // Simulate loading data from database
            animateCounter('totalQuotes', 128);
            animateCounter('totalClients', 45);
            animateCounter('pendingQuotes', 23);
            animateCounter('conversionRate', 68, '%');
            
            // Revenue with currency formatting
            animateCounter('totalRevenue', 85420, '
                            );
            animateCounter('avgDealSize', 1890, '
                            );
        }
        
        function animateCounter(elementId, target, prefix = '') {
            const element = document.getElementById(elementId);
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                let displayValue = Math.floor(current);
                if (prefix === '
                            ) {
                    displayValue = '
                             + displayValue.toLocaleString();
                } else if (prefix === '%') {
                    displayValue = displayValue + '%';
                } else {
                    displayValue = displayValue.toLocaleString();
                }
                
                element.textContent = displayValue;
            }, 16);
        }
        
        // Chart Initialization
        function initializeCharts() {
            initializeSalesChart();
            initializeStatusChart();
        }
        
        function initializeSalesChart() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Ventas ($)',
                        data: [12000, 19000, 15000, 25000, 22000, 30000],
                        borderColor: '#1e3a8a',
                        backgroundColor: 'rgba(30, 58, 138, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#1e3a8a',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    }, {
                        label: 'Cotizaciones',
                        data: [20, 35, 28, 45, 38, 52],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '
                             + value.toLocaleString();
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    }
                }
            });
        }
        
        function initializeStatusChart() {
            const ctx = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Aprobadas', 'Pendientes', 'Rechazadas', 'Borrador'],
                    datasets: [{
                        data: [45, 25, 15, 15],
                        backgroundColor: [
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#6b7280'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Password Change Functionality
        function setupPasswordValidation() {
            if (!forcePasswordChange) return;
            
            const newPassword = document.getElementById('newPassword');
            const confirmPassword = document.getElementById('confirmPassword');
            const changeBtn = document.getElementById('changePasswordBtn');
            
            if (newPassword) {
                newPassword.addEventListener('input', validatePassword);
                confirmPassword.addEventListener('input', checkPasswordMatch);
            }
        }
        
        function validatePassword() {
            const password = document.getElementById('newPassword').value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const changeBtn = document.getElementById('changePasswordBtn');
            
            const requirements = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^a-zA-Z0-9]/.test(password)
            };
            
            // Update requirement list
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                if (element) {
                    element.className = requirements[req] ? 'requirement-met' : '';
                }
            });
            
            // Calculate strength
            const passed = Object.values(requirements).filter(Boolean).length;
            let strength = 'weak';
            let strengthClass = 'strength-weak';
            
            if (passed >= 5) {
                strength = 'strong';
                strengthClass = 'strength-strong';
                strengthText.textContent = 'Contraseña muy segura';
                strengthText.style.color = '#10b981';
            } else if (passed >= 4) {
                strength = 'good';
                strengthClass = 'strength-good';
                strengthText.textContent = 'Contraseña segura';
                strengthText.style.color = '#3b82f6';
            } else if (passed >= 2) {
                strength = 'fair';
                strengthClass = 'strength-fair';
                strengthText.textContent = 'Contraseña regular';
                strengthText.style.color = '#f59e0b';
            } else {
                strengthText.textContent = 'Contraseña débil';
                strengthText.style.color = '#ef4444';
            }
            
            strengthBar.className = `password-strength-bar ${strengthClass}`;
            
            // Enable/disable button
            const confirmMatch = checkPasswordMatch();
            changeBtn.disabled = !(passed >= 4 && confirmMatch);
        }
        
        function checkPasswordMatch() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirmPassword.length === 0) {
                matchDiv.innerHTML = '';
                return false;
            }
            
            if (newPassword === confirmPassword) {
                matchDiv.innerHTML = '<small class="text-success"><i class="bi bi-check-circle"></i> Las contraseñas coinciden</small>';
                return true;
            } else {
                matchDiv.innerHTML = '<small class="text-danger"><i class="bi bi-x-circle"></i> Las contraseñas no coinciden</small>';
                return false;
            }
        }
        
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId === 'newPassword' ? 'toggleIcon1' : 'toggleIcon2');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
        
        async function changePassword() {
            const form = document.getElementById('passwordChangeForm');
            const btn = document.getElementById('changePasswordBtn');
            const formData = new FormData(form);
            
            btn.classList.add('loading');
            btn.disabled = true;
            
            try {
                const response = await fetch('/?module=auth&action=change_password', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal and reload page
                    const modal = bootstrap.Modal.getInstance(document.getElementById('forcePasswordChangeModal'));
                    modal.hide();
                    
                    // Show success message
                    showAlert('Contraseña cambiada exitosamente', 'success');
                    
                    // Reload page after a delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showAlert(result.message || 'Error al cambiar contraseña', 'danger');
                }
            } catch (error) {
                showAlert('Error de conexión', 'danger');
            } finally {
                btn.classList.remove('loading');
                btn.disabled = false;
            }
        }
        
        // Utility Functions
        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
        
        function refreshDashboard() {
            window.location.reload();
        }
        
        function updateChart() {
            const period = document.getElementById('salesPeriod').value;
            showAlert(`Actualizando gráfico para ${period} días...`, 'info');
            
            // Make AJAX call to update chart data based on period
            fetch(`/?module=dashboard&action=chart_data&period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && salesChart) {
                        salesChart.data.labels = data.labels;
                        salesChart.data.datasets[0].data = data.sales;
                        salesChart.data.datasets[1].data = data.quotes;
                        salesChart.update();
                    }
                })
                .catch(error => {
                    console.error('Error updating chart:', error);
                });
        }
        
        function exportData() {
            showAlert('Iniciando exportación de datos...', 'info');
            // Implement export functionality
        }
        
        function generateReport() {
            showAlert('Generando reporte de ventas...', 'info');
            // Implement report generation
        }
        
        function showTopClients() {
            document.getElementById('topClientsTable').style.display = 'block';
            document.getElementById('topProductsTable').style.display = 'none';
            
            // Update button states
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }
        
        function showTopProducts() {
            document.getElementById('topClientsTable').style.display = 'none';
            document.getElementById('topProductsTable').style.display = 'block';
            
            // Update button states
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }
        
        // Timezone Converter Functions
        function intercambiarPaises() {
            const paisOrigen = document.getElementById('paisOrigen');
            const paisDestino = document.getElementById('paisDestino');
            
            const temp = paisOrigen.value;
            paisOrigen.value = paisDestino.value;
            paisDestino.value = temp;
        }
        
        function calcularHora() {
            const horaInput = document.getElementById('horaOrigenInput').value;
            const fechaInput = document.getElementById('fechaInput').value;
            const paisOrigen = document.getElementById('paisOrigen').value;
            const paisDestino = document.getElementById('paisDestino').value;
            
            if (!horaInput) {
                showAlert('Por favor, introduce una hora válida.', 'warning');
                return;
            }
            
            // Show results section
            document.getElementById('resultados').style.display = 'block';
            
            // Update display (simplified conversion)
            document.getElementById('horaOrigenDisplay').textContent = horaInput;
            document.getElementById('zonaOrigenDisplay').textContent = paisOrigen.split('/')[1];
            
            // Simulate conversion
            const [hours, minutes] = horaInput.split(':');
            let convertedHours = parseInt(hours) + (paisDestino.includes('New_York') ? -6 : 0);
            if (convertedHours < 0) convertedHours += 24;
            if (convertedHours >= 24) convertedHours -= 24;
            
            document.getElementById('horaDestinoDisplay').textContent = 
                String(convertedHours).padStart(2, '0') + ':' + minutes;
            document.getElementById('zonaDestinoDisplay').textContent = paisDestino.split('/')[1];
        }
    </script>
</body>
</html>