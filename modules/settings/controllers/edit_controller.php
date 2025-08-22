<?php
/**
 * Settings Edit Controller
 * Handles system settings management
 */

require_once __DIR__ . '/../models/SettingsModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Require login and manage_settings permission
requireLogin();
requirePermission('manage_settings');

$settingsModel = new SettingsModel();
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        // Collect form data
        $formSettings = [
            'company_display_name' => sanitizeInput($_POST['company_display_name'] ?? ''),
            'default_tax_rate' => sanitizeInput($_POST['default_tax_rate'] ?? '0.00'),
            'quote_expiry_days' => sanitizeInput($_POST['quote_expiry_days'] ?? '7'),
            'quote_expiry_notification_days' => sanitizeInput($_POST['quote_expiry_notification_days'] ?? '3'),
            'low_stock_threshold' => sanitizeInput($_POST['low_stock_threshold'] ?? '10'),
            'timezone' => sanitizeInput($_POST['timezone'] ?? 'America/New_York'),
            'available_languages' => json_encode($_POST['available_languages'] ?? ['es']),
            'smtp_host' => sanitizeInput($_POST['smtp_host'] ?? ''),
            'smtp_port' => sanitizeInput($_POST['smtp_port'] ?? '587'),
            'smtp_username' => sanitizeInput($_POST['smtp_username'] ?? ''),
            'smtp_encryption' => sanitizeInput($_POST['smtp_encryption'] ?? 'TLS'),
            'from_email' => sanitizeInput($_POST['from_email'] ?? ''),
            'from_name' => sanitizeInput($_POST['from_name'] ?? ''),
            'backup_time' => sanitizeInput($_POST['backup_time'] ?? '02:00:00')
        ];
        
        // Handle SMTP password (only update if provided)
        if (!empty($_POST['smtp_password'])) {
            $formSettings['smtp_password'] = sanitizeInput($_POST['smtp_password']);
        }
        
        // Validation
        $errors = [];
        
        if (empty($formSettings['company_display_name'])) {
            $errors[] = 'Company name is required';
        }
        
        if (!is_numeric($formSettings['default_tax_rate']) || $formSettings['default_tax_rate'] < 0) {
            $errors[] = 'Invalid tax rate';
        }
        
        if (!is_numeric($formSettings['quote_expiry_days']) || $formSettings['quote_expiry_days'] < 1) {
            $errors[] = 'Quote expiry days must be at least 1';
        }
        
        if (!is_numeric($formSettings['low_stock_threshold']) || $formSettings['low_stock_threshold'] < 0) {
            $errors[] = 'Invalid low stock threshold';
        }
        
        if (!empty($formSettings['from_email']) && !validateEmail($formSettings['from_email'])) {
            $errors[] = 'Invalid from email address';
        }
        
        // Process if no errors
        if (empty($errors)) {
            if ($settingsModel->updateSettings($formSettings)) {
                $success = 'Settings updated successfully';
                logSecurityEvent('SETTINGS_UPDATED', ['settings_count' => count($formSettings)]);
            } else {
                $error = 'Error updating settings';
            }
        } else {
            $error = implode('<br>', $errors);
        }
    }
}

// Get current settings
$currentSettings = $settingsModel->getAllSettings();

// Available options
$timezones = [
    'America/New_York' => 'Eastern Time (ET)',
    'America/Chicago' => 'Central Time (CT)',
    'America/Denver' => 'Mountain Time (MT)',
    'America/Los_Angeles' => 'Pacific Time (PT)',
    'Europe/London' => 'Greenwich Mean Time (GMT)',
    'Europe/Paris' => 'Central European Time (CET)',
    'Asia/Tokyo' => 'Japan Standard Time (JST)',
    'Australia/Sydney' => 'Australian Eastern Time (AET)'
];

$languages = [
    'es' => 'Spanish',
    'en' => 'English',
    'fr' => 'French',
    'zh' => 'Chinese'
];

$encryptionTypes = [
    'TLS' => 'TLS',
    'SSL' => 'SSL',
    'NONE' => 'None'
];

// Include view
include __DIR__ . '/../views/edit.php';