<?php
/**
 * Client Edit Controller
 * Handle client editing form and processing
 */

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Check permissions
requirePermission('view_clients');

$clientModel = new ClientModel();
$errors = [];
$success = false;
$client = null;

// Get client ID
$clientId = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if (!$clientId) {
    if (isAjaxRequest()) {
        jsonResponse(['error' => __('client_not_found')], 404);
    } else {
        redirect('/?module=clients&action=list');
    }
}

// Load client data
$client = $clientModel->getClientById($clientId);
if (!$client) {
    if (isAjaxRequest()) {
        jsonResponse(['error' => __('client_not_found')], 404);
    } else {
        redirect('/?module=clients&action=list');
    }
}

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
        } elseif ($clientModel->emailExists($data['email'], $clientId)) {
            $errors[] = __('email_already_exists');
        }
        
        if (!empty($data['phone']) && !validatePhone($data['phone'])) {
            $errors[] = __('invalid_phone_format');
        }
        
        // Update client if no errors
        if (empty($errors)) {
            if ($clientModel->updateClient($clientId, $data)) {
                $success = true;
                // Reload client data
                $client = $clientModel->getClientById($clientId);
                
                if (isAjaxRequest()) {
                    jsonResponse([
                        'success' => true,
                        'message' => __('client_updated_successfully')
                    ]);
                } else {
                    redirect('/?module=clients&action=list&success=updated');
                }
            } else {
                $errors[] = __('error_updating_client');
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

// Get client statistics and activities
$clientStats = $clientModel->getClientStats($clientId);
$clientActivities = $clientModel->getClientActivities($clientId, 5);

// For non-AJAX requests, load the view
$pageTitle = __('edit_client');
require_once __DIR__ . '/../views/edit.php';