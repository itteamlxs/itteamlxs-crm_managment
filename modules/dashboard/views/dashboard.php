<?php
/**
 * Dashboard View - Optimized Layout with Grid System
 */

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../../../config/db.php';

requireLogin();
$user = getCurrentUser();
$db = Database::getInstance();

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
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <!-- Force Password Change Modal -->
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
                    
                    <form id="forcePasswordChangeForm" method="POST" action="<?php echo url('dashboard', 'f_password'); ?>">
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
                                    <small class="text-muted"><?php echo __('Strength') ?: 'Fortaleza'; ?>:</small>
                                    <small id="strengthText" class="text-muted"></small>
                                </div>
                                <div class="progress" style="height: 4px;">
                                    <div id="strengthBar" class="progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                            
                            <div class="password-requirements mt-2" id="passwordRequirements" style="display: none;">
                                <div class="requirement" id="req-length">
                                    <i class="bi bi-x-circle"></i>
                                    <span><?php echo __('msj_lchar_pass') ?: 'Mínimo 8 caracteres'; ?></span>
                                </div>
                                <div class="requirement" id="req-uppercase">
                                    <i class="bi bi-x-circle"></i>
                                    <span><?php echo __('msj_mayus_pass') ?: 'Una letra mayúscula'; ?></span>
                                </div>
                                <div class="requirement" id="req-lowercase">
                                    <i class="bi bi-x-circle"></i>
                                    <span><?php echo __('msj_minus_pass') ?: 'Una letra minúscula'; ?></span>
                                </div>
                                <div class="requirement" id="req-number">
                                    <i class="bi bi-x-circle"></i>
                                    <span><?php echo __('msj_num_pass') ?: 'Un número'; ?></span>
                                </div>
                                <div class="requirement" id="req-special">
                                    <i class="bi bi-x-circle"></i>
                                    <span><?php echo __('msj_char_pass') ?: 'Un carácter especial'; ?></span>
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
                        <?php echo __('change_password_btn') ?: 'Cambiar Contraseña'; ?>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="main-content">
        <!-- Header - Full Width -->
        <?php include __DIR__ . '/../../../public/includes/dashboard_header.php'; ?>
        
        <!-- Statistics Cards - Full Width -->
        <?php include __DIR__ . '/../../../public/includes/dashboard_stats.php'; ?>
        
        <!-- Quick Actions - Full Width -->
        <?php include __DIR__ . '/../../../public/includes/dashboard_quick_actions.php'; ?>
        
        <!-- Charts Section - Full Width -->
        <?php include __DIR__ . '/../../../public/includes/dashboard_charts.php'; ?>
        
        <!-- Main Grid Layout: 2/3 left, 1/3 right -->
        <div class="row g-3">
            <!-- Left Column - Main Content (8 columns) -->
            <div class="col-lg-8">
                <!-- Recent Activities -->
                <?php include __DIR__ . '/../../../public/includes/dashboard_recent_activities.php'; ?>
                <br>
                <!-- Recent Quotes -->
                <?php include __DIR__ . '/../../../public/includes/dashboard_recent_quotes.php'; ?>
            </div>
            
            <!-- Right Column - Sidebar Widgets (4 columns) -->
            <div class="col-lg-4">
                <!-- Keyboard Shortcuts -->
                <?php include __DIR__ . '/../../../public/includes/dashboard_keyboard_shortcuts.php'; ?>
                
                <!-- Expiring Quotes -->
                <?php include __DIR__ . '/../../../public/includes/dashboard_expiring_quotes.php'; ?>
                
                <!-- Low Stock Products -->
                <?php include __DIR__ . '/../../../public/includes/dashboard_low_stock.php'; ?>
                
                <!-- Top Clients -->
                <?php include __DIR__ . '/../../../public/includes/dashboard_top_clients.php'; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Force Password Change Modal Logic
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
                    strengthText.textContent = '<?php echo __('weak') ?: 'Débil'; ?>';
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
            
            form.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('changePasswordBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Cambiando...';
            });
        }
    });
    <?php endif; ?>
    </script>

    <!-- En dashboard.php, clients/list.php, quotes/list.php, etc. -->
    <?php include __DIR__ . '/../../../public/includes/keyboard_shortcuts_global.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>