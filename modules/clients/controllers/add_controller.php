<?php
/**
 * Client Add Controller
 * Handles new client creation with validation
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ClientModel.php';

// Check permissions
requirePermission('add_client');

$clientModel = new ClientModel();
$errors = [];
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = __('invalid_security_token');
    } else {
        // Sanitize input data
        $data = [
            'company_name' => sanitizeInput($_POST['company_name'] ?? ''),
            'contact_name' => sanitizeInput($_POST['contact_name'] ?? ''),
            'email' => sanitizeInput($_POST['email'] ?? ''),
            'phone' => sanitizeInput($_POST['phone'] ?? ''),
            'address' => sanitizeInput($_POST['address'] ?? ''),
            'tax_id' => sanitizeInput($_POST['tax_id'] ?? '')
        ];
        
        // Validation
        if (empty($data['company_name'])) {
            $errors[] = __('company_name_required');
        }
        
        if (empty($data['contact_name'])) {
            $errors[] = __('contact_name_required');
        }
        
        if (empty($data['email'])) {
            $errors[] = __('email_required');
        } elseif (!validateEmail($data['email'])) {
            $errors[] = __('invalid_email_format');
        } elseif ($clientModel->emailExists($data['email'])) {
            $errors[] = __('email_already_exists');
        }
        
        if (!empty($data['phone']) && !validatePhone($data['phone'])) {
            $errors[] = __('invalid_phone_format');
        }
        
        // Create client if no errors
        if (empty($errors)) {
            $clientId = $clientModel->createClient($data);
            
            if ($clientId) {
                $success = __('client_created_successfully');
                
                // For AJAX requests
                if (isAjaxRequest()) {
                    jsonResponse([
                        'success' => true,
                        'message' => $success,
                        'client_id' => $clientId
                    ]);
                }
                
                // Redirect to client list
                redirect(url('clients', 'list'));
            } else {
                $errors[] = __('error_creating_client');
            }
        }
    }
    
    // For AJAX requests with errors
    if (isAjaxRequest() && !empty($errors)) {
        jsonResponse([
            'success' => false,
            'errors' => $errors
        ], 400);
    }
}

// Include the view
include __DIR__ . '/../views/add.php';