<?php
/**
 * Client Delete Controller
 * Handles client soft deletion with validation
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ClientModel.php';

// Check permissions
requirePermission('view_clients');

$clientModel = new ClientModel();

// Get client ID
$clientId = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if (!$clientId) {
    if (isAjaxRequest()) {
        jsonResponse(['success' => false, 'error' => __('client_not_found')], 400);
    }
    redirect(url('clients', 'list'));
}

// Get client data
$client = $clientModel->getClientById($clientId);
if (!$client) {
    if (isAjaxRequest()) {
        jsonResponse(['success' => false, 'error' => __('client_not_found')], 404);
    }
    redirect(url('clients', 'list'));
}

// Process deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        if (isAjaxRequest()) {
            jsonResponse(['success' => false, 'error' => __('invalid_security_token')], 400);
        }
        redirect(url('clients', 'list'));
    }
    
    // Perform soft delete
    if ($clientModel->deleteClient($clientId)) {
        $message = __('client_deleted_successfully');
        
        if (isAjaxRequest()) {
            jsonResponse([
                'success' => true,
                'message' => $message
            ]);
        }
        
        redirect(url('clients', 'list'));
    } else {
        $error = __('error_deleting_client');
        
        if (isAjaxRequest()) {
            jsonResponse(['success' => false, 'error' => $error], 500);
        }
        
        redirect(url('clients', 'list'));
    }
}

// If GET request, redirect back to list
redirect(url('clients', 'list'));