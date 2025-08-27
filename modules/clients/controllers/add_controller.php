<?php
/**
 * Client Add Controller
 * Handle client creation form and processing
 */

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Check permissions
requirePermission('view_clients');

$clientModel = new ClientModel();
$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = __('invalid_security_token');
    } else {
        // Sanitize and validate input
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
                $success = true;
                if (isAjaxRequest()) {
                    jsonResponse([
                        'success' => true,
                        'message' => __('client_created_successfully'),
                        'client_id' => $clientId
                    ]);
                } else {
                    redirect('/?module=clients&action=list&success=created');
                }
            } else {
                $errors[] = __('error_creating_client');
            }
        }
    }
    
    // Return errors for AJAX requests
    if (isAjaxRequest()) {
        jsonResponse([
            'success' => false,
            'errors' => $errors
        ]);
    }
}

// For non-AJAX requests, load the view
$pageTitle = __('add_client');
require_once __DIR__ . '/../views/add.php';