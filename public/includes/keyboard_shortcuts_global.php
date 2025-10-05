<?php
/**
 * Global Keyboard Shortcuts Script - Extended Version
 * Include this in layout templates for site-wide shortcuts
 */

if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
    require_once __DIR__ . '/../../core/url_helper.php';
}

$user = getCurrentUser();
if (!$user) return;

// Pre-generate URLs and permissions for JavaScript
$shortcutsConfig = [
    // Creation shortcuts
    'clientsAdd' => [
        'url' => url('clients', 'add'),
        'enabled' => hasPermission('add_client')
    ],
    'quotesCreate' => [
        'url' => url('quotes', 'create'),
        'enabled' => hasPermission('create_quotes')
    ],
    'productsAdd' => [
        'url' => url('products', 'add'),
        'enabled' => hasPermission('add_products')
    ],
    
    // Navigation shortcuts
    'dashboard' => [
        'url' => url('dashboard', 'index'),
        'enabled' => true
    ],
    'profile' => [
        'url' => url('users', 'edit', ['id' => $user['user_id']]),
        'enabled' => true
    ],
    
    // Module shortcuts
    'clientsList' => [
        'url' => url('clients', 'list'),
        'enabled' => canAccessModule('clients')
    ],
    'quotesList' => [
        'url' => url('quotes', 'list'),
        'enabled' => canAccessModule('quotes')
    ],
    'productsList' => [
        'url' => url('products', 'list'),
        'enabled' => canAccessModule('products')
    ],
    'usersList' => [
        'url' => url('users', 'list'),
        'enabled' => hasPermission('reset_user_password') || $user['is_admin']
    ],
    
    // Reports shortcuts
    'salesReports' => [
        'url' => url('reports', 'sales'),
        'enabled' => hasPermission('view_sales_reports')
    ],
    
    // Admin shortcuts
    'settings' => [
        'url' => url('settings', 'edit'),
        'enabled' => hasPermission('manage_settings')
    ],
    'backups' => [
        'url' => url('backups', 'list'),
        'enabled' => hasPermission('manage_backups')
    ],
    
    // Logout
    'logout' => [
        'url' => url('auth', 'logout'),
        'enabled' => true
    ]
];
?>

<script>
// Global Keyboard Shortcuts - Extended
(function() {
    const shortcuts = <?php echo json_encode($shortcutsConfig); ?>;
    
    // Show shortcuts help modal
    function showShortcutsHelp() {
        const helpModal = document.createElement('div');
        helpModal.className = 'modal fade';
        helpModal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-keyboard me-2"></i>
                            Atajos de Teclado Disponibles
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="bi bi-plus-circle me-2"></i>Crear</h6>
                                ${shortcuts.clientsAdd.enabled ? '<div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Alt</kbd> + <kbd>C</kbd> - Nuevo Cliente</div>' : ''}
                                ${shortcuts.quotesCreate.enabled ? '<div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Alt</kbd> + <kbd>Q</kbd> - Nueva Cotización</div>' : ''}
                                ${shortcuts.productsAdd.enabled ? '<div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Alt</kbd> + <kbd>N</kbd> - Nuevo Producto</div>' : ''}
                                
                                <h6 class="text-primary mb-3 mt-4"><i class="bi bi-compass me-2"></i>Navegación</h6>
                                <div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Alt</kbd> + <kbd>D</kbd> - Dashboard</div>
                                <div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Alt</kbd> + <kbd>P</kbd> - Mi Perfil</div>
                                ${shortcuts.settings.enabled ? '<div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Alt</kbd> + <kbd>S</kbd> - Configuración</div>' : ''}
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="bi bi-list-ul me-2"></i>Módulos</h6>
                                ${shortcuts.clientsList.enabled ? '<div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>C</kbd> - Lista Clientes</div>' : ''}
                                ${shortcuts.quotesList.enabled ? '<div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>Q</kbd> - Lista Cotizaciones</div>' : ''}
                                ${shortcuts.productsList.enabled ? '<div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>P</kbd> - Lista Productos</div>' : ''}
                                ${shortcuts.usersList.enabled ? '<div class="mb-2"><kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>U</kbd> - Lista Usuarios</div>' : ''}
                                
                                <h6 class="text-primary mb-3 mt-4"><i class="bi bi-question-circle me-2"></i>Ayuda</h6>
                                <div class="mb-2"><kbd>Ctrl</kbd> + <kbd>/</kbd> - Mostrar esta ayuda</div>
                                <div class="mb-2"><kbd>Esc</kbd> - Cerrar modales</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(helpModal);
        const modal = new bootstrap.Modal(helpModal);
        modal.show();
        helpModal.addEventListener('hidden.bs.modal', function() {
            helpModal.remove();
        });
    }
    
    document.addEventListener('keydown', function(e) {
        // Ignore if user is typing in input fields (except help shortcut)
        if (e.target.matches('input, textarea, select') && !(e.ctrlKey && e.key === '/')) {
            return;
        }
        
        // === CREATION SHORTCUTS (Ctrl + Alt + Key) ===
        
        // Ctrl + Alt + C - New Client
        if (e.ctrlKey && e.altKey && e.key === 'c' && shortcuts.clientsAdd.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.clientsAdd.url;
        }
        
        // Ctrl + Alt + Q - New Quote
        if (e.ctrlKey && e.altKey && e.key === 'q' && shortcuts.quotesCreate.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.quotesCreate.url;
        }
        
        // Ctrl + Alt + N - New Product
        if (e.ctrlKey && e.altKey && e.key === 'n' && shortcuts.productsAdd.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.productsAdd.url;
        }
        
        // === NAVIGATION SHORTCUTS (Ctrl + Alt + Key) ===
        
        // Ctrl + Alt + D - Dashboard
        if (e.ctrlKey && e.altKey && e.key === 'd' && shortcuts.dashboard.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.dashboard.url;
        }
        
        // Ctrl + Alt + P - Profile
        if (e.ctrlKey && e.altKey && e.key === 'p' && shortcuts.profile.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.profile.url;
        }
        
        // Ctrl + Alt + S - Settings
        if (e.ctrlKey && e.altKey && e.key === 's' && shortcuts.settings.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.settings.url;
        }
        
        // Ctrl + Alt + R - Sales Reports
        if (e.ctrlKey && e.altKey && e.key === 'r' && shortcuts.salesReports.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.salesReports.url;
        }
        
        // Ctrl + Alt + B - Backups
        if (e.ctrlKey && e.altKey && e.key === 'b' && shortcuts.backups.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.backups.url;
        }
        
        // === MODULE LIST SHORTCUTS (Ctrl + Shift + Key) ===
        
        // Ctrl + Shift + C - Clients List
        if (e.ctrlKey && e.shiftKey && e.key === 'C' && shortcuts.clientsList.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.clientsList.url;
        }
        
        // Ctrl + Shift + Q - Quotes List
        if (e.ctrlKey && e.shiftKey && e.key === 'Q' && shortcuts.quotesList.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.quotesList.url;
        }
        
        // Ctrl + Shift + P - Products List
        if (e.ctrlKey && e.shiftKey && e.key === 'P' && shortcuts.productsList.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.productsList.url;
        }
        
        // Ctrl + Shift + U - Users List
        if (e.ctrlKey && e.shiftKey && e.key === 'U' && shortcuts.usersList.enabled) {
            e.preventDefault();
            window.location.href = shortcuts.usersList.url;
        }
        
        // === UTILITY SHORTCUTS ===
        
        // Ctrl + / - Show shortcuts help
        if (e.ctrlKey && e.key === '/') {
            e.preventDefault();
            showShortcutsHelp();
        }
        
        // Esc - Close all modals
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.show');
            modals.forEach(modal => {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            });
        }
    });
})();
</script>