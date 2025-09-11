<?php
// Prevenir acceso directo
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../../../config/app.php';
    require_once __DIR__ . '/../../../core/helpers.php';
    require_once __DIR__ . '/../../../core/rbac.php';
}

// Cargar dependencias necesarias para nav.php
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../config/db.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $isEdit ? __('edit_user') : __('add_user'); ?> - <?php echo sanitizeOutput(APP_NAME); ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2>
                            <i class="bi bi-person-gear"></i>
                            <?php echo $isEdit ? __('edit_user') : __('add_user'); ?>
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="/crm-project/public/?module=dashboard&action=index"><?php echo __('dashboard'); ?></a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="/crm-project/public/?module=users&action=list"><?php echo __('users'); ?></a>
                                </li>
                                <li class="breadcrumb-item active">
                                    <?php echo $isEdit ? __('edit') : __('add'); ?>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="/crm-project/public/?module=users&action=list" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> <?php echo __('back_to_list'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-badge"></i>
                            <?php echo $isEdit ? __('user_details') : __('new_user_details'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" id="userForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <!-- Profile Picture -->
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <?php if (!empty($user['profile_picture'])): ?>
                                        <img id="profilePreview" 
                                             src="/<?php echo sanitizeOutput($user['profile_picture']); ?>" 
                                             alt="Profile Picture" 
                                             class="rounded-circle border" 
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                    <?php else: ?>
                                        <div id="profilePreview" 
                                             class="rounded-circle border bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 120px; height: 120px;">
                                            <i class="bi bi-person display-4 text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <button type="button" 
                                            class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle"
                                            onclick="document.getElementById('profilePicture').click()">
                                        <i class="bi bi-camera"></i>
                                    </button>
                                </div>
                                <input type="file" 
                                       id="profilePicture" 
                                       name="profile_picture" 
                                       accept="image/*" 
                                       class="d-none"
                                       onchange="previewImage(this)">
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <?php echo __('profile_picture_help'); ?>
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">
                                            <?php echo __('username'); ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="username" 
                                               name="username" 
                                               value="<?php echo sanitizeOutput($user['username']); ?>"
                                               required
                                               pattern="[a-zA-Z0-9_]{3,50}"
                                               title="<?php echo __('username_format_help'); ?>">
                                        <div class="form-text">
                                            <?php echo __('username_format_help'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <?php echo __('email'); ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="<?php echo sanitizeOutput($user['email']); ?>"
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="display_name" class="form-label">
                                    <?php echo __('display_name'); ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="display_name" 
                                       name="display_name" 
                                       value="<?php echo sanitizeOutput($user['display_name']); ?>"
                                       required
                                       maxlength="100">
                            </div>
                            
                            <div class="mb-3">
                                <label for="language" class="form-label">
                                    <?php echo __('language'); ?>
                                </label>
                                <select class="form-select" id="language" name="language">
                                    <?php foreach ($availableLanguages as $lang): ?>
                                        <option value="<?php echo $lang; ?>" 
                                                <?php echo $user['language'] === $lang ? 'selected' : ''; ?>>
                                            <?php echo strtoupper($lang); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Password Fields (New Users Only) -->
                            <?php if (!$isEdit): ?>
                                <hr>
                                <h6><?php echo __('password_settings'); ?></h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">
                                                <?php echo __('password'); ?> <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password" 
                                                   name="password" 
                                                   required
                                                   minlength="8">
                                            <div class="form-text">
                                                <?php echo __('password_requirements'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label">
                                                <?php echo __('confirm_password'); ?> <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="confirm_password" 
                                                   name="confirm_password" 
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Change Password Fields (Edit Users) -->
                            <?php if ($isEdit): ?>
                                <hr>
                                <h6>
                                    <i class="bi bi-shield-lock"></i> <?php echo __('change_password'); ?>
                                </h6>
                                <p class="text-muted small">
                                    <?php echo __('leave_blank_to_keep_current_password'); ?>
                                </p>
                                
                                <!-- Current Password (only for own profile) -->
                                <?php if ($targetUserId === $currentUser['user_id']): ?>
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">
                                            <?php echo __('current_password'); ?>
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="current_password" 
                                               name="current_password">
                                        <div class="form-text">
                                            <?php echo __('required_to_change_password'); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- New Password -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">
                                                <?php echo __('new_password'); ?>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="new_password" 
                                                   name="new_password" 
                                                   minlength="8">
                                            <div class="form-text">
                                                <?php echo __('password_requirements'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="confirm_new_password" class="form-label">
                                                <?php echo __('confirm_new_password'); ?>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="confirm_new_password" 
                                                   name="confirm_new_password">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Admin Fields -->
                            <?php if (hasPermission('reset_user_password') || getCurrentUser()['is_admin']): ?>
                                <hr>
                                <h6><?php echo __('administrative_settings'); ?></h6>
                                
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">
                                        <?php echo __('role'); ?>
                                    </label>
                                    <select class="form-select" id="role_id" name="role_id">
                                        <option value=""><?php echo __('select_role'); ?></option>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?php echo $role['role_id']; ?>" 
                                                    <?php echo $user['role_id'] == $role['role_id'] ? 'selected' : ''; ?>>
                                                <?php echo sanitizeOutput($role['role_name']); ?>
                                                <?php if (!empty($role['description'])): ?>
                                                    - <?php echo sanitizeOutput($role['description']); ?>
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="hidden" name="is_admin" value="0">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_admin" 
                                                   name="is_admin" 
                                                   value="1"
                                                   <?php echo $user['is_admin'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_admin">
                                                <strong><?php echo __('admin_user'); ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <?php echo __('admin_user_help'); ?>
                                                </small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input type="hidden" name="is_active" value="0">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_active" 
                                                   name="is_active" 
                                                   value="1"
                                                   <?php echo $user['is_active'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_active">
                                                <strong><?php echo __('active_user'); ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <?php echo __('active_user_help'); ?>
                                                </small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between pt-3">
                                <a href="/crm-project/public/?module=users&action=list" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> <?php echo __('cancel'); ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i>
                                    <?php echo $isEdit ? __('update_user') : __('create_user'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Additional Actions for Edit -->
                <?php if ($isEdit && (hasPermission('reset_user_password') || getCurrentUser()['is_admin'])): ?>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0 text-warning">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo __('danger_zone'); ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><?php echo __('reset_password'); ?></h6>
                                    <p class="text-muted small">
                                        <?php echo __('reset_password_help'); ?>
                                    </p>
                                    <button type="button" 
                                            class="btn btn-warning" 
                                            onclick="resetUserPassword(<?php echo $user['user_id']; ?>)">
                                        <i class="bi bi-key"></i> <?php echo __('reset_password'); ?>
                                    </button>
                                </div>
                                <?php if ($user['user_id'] !== getCurrentUser()['user_id']): ?>
                                    <div class="col-md-6">
                                        <h6><?php echo __('deactivate_user'); ?></h6>
                                        <p class="text-muted small">
                                            <?php echo __('deactivate_user_help'); ?>
                                        </p>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="deactivateUserProfile(<?php echo $user['user_id']; ?>)">
                                            <i class="bi bi-person-x"></i> <?php echo __('deactivate'); ?>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Hidden Action Form -->
        <form id="dangerActionForm" method="POST" style="display: none;">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" id="dangerAction">
            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profilePreview');
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace div with img
                        const img = document.createElement('img');
                        img.id = 'profilePreview';
                        img.src = e.target.result;
                        img.alt = 'Profile Picture';
                        img.className = 'rounded-circle border';
                        img.style.cssText = 'width: 120px; height: 120px; object-fit: cover;';
                        preview.parentNode.replaceChild(img, preview);
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function resetUserPassword(userId) {
            if (confirm('<?php echo __('confirm_reset_password_action'); ?>')) {
                document.getElementById('dangerAction').value = 'reset_password';
                document.getElementById('dangerActionForm').submit();
            }
        }
        
        function deactivateUserProfile(userId) {
            if (confirm('<?php echo __('confirm_deactivate_action'); ?>')) {
                document.getElementById('dangerAction').value = 'deactivate';
                document.getElementById('dangerActionForm').submit();
            }
        }
        
        // Form validation
        document.getElementById('userForm').addEventListener('submit', function(e) {
            // New user password validation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (password && confirmPassword) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('<?php echo __('passwords_do_not_match'); ?>');
                    return false;
                }
            }
            
            // Edit user password validation
            const newPassword = document.getElementById('new_password');
            const confirmNewPassword = document.getElementById('confirm_new_password');
            const currentPassword = document.getElementById('current_password');
            
            if (newPassword && confirmNewPassword) {
                // If new password is entered, validate it
                if (newPassword.value && confirmNewPassword.value) {
                    if (newPassword.value !== confirmNewPassword.value) {
                        e.preventDefault();
                        alert('<?php echo __('passwords_do_not_match'); ?>');
                        return false;
                    }
                    
                    // For own profile, current password is required
                    if (currentPassword && !currentPassword.value) {
                        e.preventDefault();
                        alert('<?php echo __('current_password_required'); ?>');
                        return false;
                    }
                }
                
                // If only one password field is filled
                if (newPassword.value !== confirmNewPassword.value) {
                    e.preventDefault();
                    alert('<?php echo __('passwords_do_not_match'); ?>');
                    return false;
                }
            }
        });
        
        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>