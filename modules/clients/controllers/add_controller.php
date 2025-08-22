<?php
/**
 * Add Client Controller
 * Handles new client creation
 */

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

// Require login and view_clients permission
requireLogin();
requirePermission('view_clients');

$clientModel = new ClientModel();
$error = '';
$success = '';

// Initialize form data
$formData = [
    'company_name' => '',
    'contact_name' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
    'tax_id' => ''
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        // Collect form data
        $formData = [
            'company_name' => sanitizeInput($_POST['company_name'] ?? ''),
            'contact_name' => sanitizeInput($_POST['contact_name'] ?? ''),
            'email' => sanitizeInput($_POST['email'] ?? ''),
            'phone' => sanitizeInput($_POST['phone'] ?? ''),
            'address' => sanitizeInput($_POST['address'] ?? ''),
            'tax_id' => sanitizeInput($_POST['tax_id'] ?? ''),
            'created_by' => getCurrentUser()['user_id']
        ];
        
        // Validation
        $errors = [];
        
        if (empty($formData['company_name'])) {
            $errors[] = 'Company name is required';
        }
        
        if (empty($formData['contact_name'])) {
            $errors[] = 'Contact name is required';
        }
        
        if (empty($formData['email'])) {
            $errors[] = 'Email is required';
        } elseif (!validateEmail($formData['email'])) {
            $errors[] = 'Invalid email format';
        } elseif ($clientModel->emailExists($formData['email'])) {
            $errors[] = 'Email already exists';
        }
        
        if (!empty($formData['phone']) && !validatePhone($formData['phone'])) {
            $errors[] = 'Invalid phone format';
        }
        
        // Process if no errors
        if (empty($errors)) {
            $clientId = $clientModel->createClient($formData);
            if ($clientId) {
                $success = 'Client created successfully';
                logSecurityEvent('CLIENT_CREATED', ['client_id' => $clientId]);
                
                // Redirect to client list
                redirect(url('clients', 'list'));
            } else {
                $error = 'Error creating client';
            }
        } else {
            $error = implode('<br>', $errors);
        }
    }
}

// Include view
include __DIR__ . '/../views/add.php';