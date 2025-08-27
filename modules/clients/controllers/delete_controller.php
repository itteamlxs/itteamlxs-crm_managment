<?php
/**
 * Client Delete Controller
 * Handle client soft deletion
 */

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Check permissions
requirePermission('view_clients');

$clientModel = new ClientModel();

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (isAjaxRequest()) {
        jsonResponse(['error' => 'Method not allowed'], 405);
    } else {
        redirect('/?module=clients&action=list');
    }
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
    if (isAjaxRequest()) {
        jsonResponse(['error' => __('invalid_security_token')], 403);
    } else {
        redirect('/?module=clients&action=list&error=token');
    }
}

// Get client ID
$clientId = (int)($_POST['id'] ?? 0);

if (!$clientId) {
    if (isAjaxRequest()) {
        jsonResponse(['error' => __('client_not_found')], 404);
    } else {
        redirect('/?module=clients&action=list');
    }
}

// Check if client exists
$client = $clientModel->getClientById($clientId);
if (!$client) {
    if (isAjaxRequest()) {
        jsonResponse(['error' => __('client_not_found')], 404);
    } else {
        redirect('/?module=clients&action=list');
    }
}

// Perform soft delete
if ($clientModel->deleteClient($clientId)) {
    if (isAjaxRequest()) {
        jsonResponse([
            'success' => true,
            'message' => __('client_deleted_successfully')
        ]);
    } else {
        redirect('/?module=clients&action=list&success=deleted');
    }
} else {
    if (isAjaxRequest()) {
        jsonResponse([
            'success' => false,
            'error' => __('error_deleting_client')
        ]);
    } else {
        redirect('/?module=clients&action=list&error=delete');
    }
}