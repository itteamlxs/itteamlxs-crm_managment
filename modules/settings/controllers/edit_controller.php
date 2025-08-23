<?php
/**
 * Settings Edit Controller
 * Handles settings management with RBAC and validation
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/SettingsModel.php';

requireLogin();
requirePermission('manage_settings');

$settingsModel = new SettingsModel();
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = __('invalid_security_token');
    } else {
        $updates = 0;
        $failed = 0;
        
        foreach ($_POST as $key => $value) {
            if ($key === 'csrf_token') continue;
            
            $value = sanitizeInput($value);
            
            // Skip empty values or unchanged values
            $currentSetting = $settingsModel->getSettingByKey($key);
            if ($currentSetting && $currentSetting['setting_value'] === $value) {
                continue;
            }
            
            // Skip empty password fields (keep current)
            if ($key === 'smtp_password' && empty($value)) {
                continue;
            }
            
            $validation = $settingsModel->validateSetting($key, $value);
            if (!$validation['valid']) {
                $errors[] = $key . ': ' . $validation['error'];
                $failed++;
                continue;
            }
            
            if ($settingsModel->updateSetting($key, $value)) {
                $updates++;
            } else {
                $failed++;
                $errors[] = __('error_updating_setting', ['setting' => $key]);
            }
        }
        
        if ($updates > 0 && $failed === 0) {
            $success = __('settings_updated_successfully');
        } elseif ($updates > 0 && $failed > 0) {
            $success = __('partial_settings_updated', ['updated' => $updates, 'failed' => $failed]);
        } elseif ($failed > 0) {
            $errors[] = __('error_updating_settings');
        }
    }
    
    if (isAjaxRequest()) {
        if (!empty($errors)) {
            jsonResponse(['success' => false, 'errors' => $errors], 400);
        } else {
            jsonResponse(['success' => true, 'message' => $success]);
        }
    }
}

$settingsByCategory = $settingsModel->getSettingsByCategory();
$availableTimezones = $settingsModel->getAvailableTimezones();
$smtpEncryptionOptions = $settingsModel->getSmtpEncryptionOptions();

$pageTitle = __('settings_management');
$currentModule = 'settings';

include __DIR__ . '/../views/edit.php';