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
    
    <style>
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            padding: 2rem;
            background-color: #f8f9fa;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-top: 80px;
            }
        }
        
        .dashboard-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(30, 58, 138, 0.1);
            border-left: 4px solid #1e3a8a;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(30, 58, 138, 0.15);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.2);
        }
        
        .stat-card .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: 0.7;
        }
        
        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(30, 58, 138, 0.1);
        }
        
        .quick-action-btn {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border: none;
            border-radius: 10px;
            color: white;
            padding: 1rem;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.3s ease;
            min-height: 100px;
            justify-content: center;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.3);
            color: white;
        }
        
        .quick-action-btn i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(30, 58, 138, 0.1);
        }
        
        .expiring-quote {
            border-left: 4px solid #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }
        
        .alert-custom {
            border-left: 4px solid #1e3a8a;
            background: rgba(30, 58, 138, 0.1);
            border-radius: 10px;
        }
        
        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(30, 58, 138, 0.1);
        }
        
        .table-custom thead {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            color: white;
        }
        
        .badge-custom {
            background: #1e3a8a;
            color: white;
        }
        
        .timezone-converter {
            background: white;
            border: 2px solid #1e3a8a;
            border-radius: 10px;
            padding: 20px;
            margin-top: 2rem;
        }
        
        .time-display {
            background: #1e3a8a;
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            text-align: center;
        }
        
        .time-value {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .time-zone {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .btn-calculate {
            background: #1e3a8a;
            color: white;
            border: 2px solid #1e3a8a;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-calculate:hover {
            background: white;
            color: #1e3a8a;
            border: 2px solid #1e3a8a;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 15px;
            border: 2px solid #e5e7eb;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
        }
    </style>
</head>
<body>
    <!-- Incluir la navegación ya existente -->
     <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Header del Dashboard -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <p class="text-muted mb-0">Resumen de actividad del CRM</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="refreshDashboard()">
                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                </button>
            </div>
        </div>
        
        <!-- Cards de Estadísticas -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-value" id="totalQuotes">0</div>
                            <div class="stat-label">Total Cotizaciones</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-file-text"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-value" id="totalClients">0</div>
                            <div class="stat-label">Clientes Activos</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-value" id="totalRevenue">$0</div>
                            <div class="stat-label">Ingresos del Mes</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-value" id="conversionRate">0%</div>
                            <div class="stat-label">Tasa Conversión</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Acciones Rápidas -->
        <div class="quick-actions mb-4">
            <h5 class="mb-3"><i class="bi bi-lightning-fill"></i> Acciones Rápidas</h5>
            <div class="row g-3">
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="#" class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#newQuoteModal">
                        <i class="bi bi-plus-circle"></i>
                        <span>Nueva Cotización</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="#" class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#newClientModal">
                        <i class="bi bi-person-plus"></i>
                        <span>Nuevo Cliente</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="#" class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#newProductModal">
                        <i class="bi bi-box-seam"></i>
                        <span>Nuevo Producto</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="#" class="quick-action-btn" onclick="window.print()">
                        <i class="bi bi-printer"></i>
                        <span>Imprimir</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="#" class="quick-action-btn" onclick="exportData()">
                        <i class="bi bi-download"></i>
                        <span>Exportar</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="#" class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#timezoneModal">
                        <i class="bi bi-clock"></i>
                        <span>Hora Mundial</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Gráficos y Tablas -->
        <div class="row g-3">
            <!-- Gráfico de Ventas -->
            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5><i class="bi bi-graph-up"></i> Tendencias de Ventas</h5>
                        <select class="form-select form-select-sm w-auto" id="salesPeriod">
                            <option value="7">Últimos 7 días</option>
                            <option value="30" selected>Últimos 30 días</option>
                            <option value="90">Últimos 90 días</option>
                        </select>
                    </div>
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
            
            <!-- Cotizaciones por Vencer -->
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-exclamation-triangle"></i> Cotizaciones por Vencer</h5>
                        <div id="expiringQuotes">
                            <div class="alert alert-custom mb-2">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>COT-2024-001</strong><br>
                                        <small>Cliente ABC Corp</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-danger">2 días</span>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-custom mb-2">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>COT-2024-002</strong><br>
                                        <small>Cliente XYZ Ltd</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-warning">5 días</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-3 mt-3">
            <!-- Últimas Actividades -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-clock-history"></i> Actividad Reciente</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Actividad</th>
                                        <th>Usuario</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody id="recentActivity">
                                    <tr>
                                        <td><i class="bi bi-file-plus text-success"></i> Nueva cotización creada</td>
                                        <td>Admin</td>
                                        <td>Hace 2 horas</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-person-plus text-primary"></i> Nuevo cliente registrado</td>
                                        <td>Vendedor 1</td>
                                        <td>Hace 4 horas</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-check-circle text-success"></i> Cotización aprobada</td>
                                        <td>Admin</td>
                                        <td>Ayer</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Top Clientes -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-trophy"></i> Mejores Clientes</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Compras</th>
                                    </tr>
                                </thead>
                                <tbody id="topClients">
                                    <tr>
                                        <td>ABC Corporation</td>
                                        <td>$25,000</td>
                                        <td>8</td>
                                    </tr>
                                    <tr>
                                        <td>XYZ Company</td>
                                        <td>$18,500</td>
                                        <td>6</td>
                                    </tr>
                                    <tr>
                                        <td>Tech Solutions</td>
                                        <td>$12,300</td>
                                        <td>4</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: Nueva Cotización -->
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
                    <button type="button" class="btn btn-primary">Crear Cotización</button>
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
                    <button type="button" class="btn btn-primary">Crear Cliente</button>
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
                    <button type="button" class="btn btn-primary">Crear Producto</button>
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
        // Inicializar datos del dashboard
        let salesChart;
        
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            initializeSalesChart();
            
            // Establecer fecha actual por defecto
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('fechaInput').value = today;
            
            // Establecer hora actual por defecto
            const now = new Date();
            const timeString = now.toTimeString().split(' ')[0];
            document.getElementById('horaOrigenInput').value = timeString;
        });
        
        function loadDashboardData() {
            // Simular carga de datos desde la base de datos
            document.getElementById('totalQuotes').textContent = '128';
            document.getElementById('totalClients').textContent = '45';
            document.getElementById('totalRevenue').textContent = '$85,420';
            document.getElementById('conversionRate').textContent = '68%';
        }
        
        function initializeSalesChart() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Ventas',
                        data: [12000, 19000, 15000, 25000, 22000, 30000],
                        borderColor: '#1e3a8a',
                        backgroundColor: 'rgba(30, 58, 138, 0.1)',
                        tension: 0.4,
                        fill: true
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
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
        
        function refreshDashboard() {
            loadDashboardData();
            salesChart.update();
        }
        
        function exportData() {
            alert('Funcionalidad de exportación en desarrollo');
        }
        
        // Funciones del conversor de zonas horarias
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
            const paisOrigen = document.getElementById('paisOrigen');
            const paisDestino = document.getElementById('paisDestino');
            
            if (!horaInput) {
                alert('Por favor, introduce una hora válida.');
                return;
            }
            
            // Simulación básica de conversión (en implementación real usarías una librería de fechas)
            const fechaHora = fechaInput ? `${fechaInput} ${horaInput}` : `${new Date().toISOString().split('T')[