<?php
/**
 * Delete Client Controller
 * Handles client soft deletion
 */

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

// Require login and view_clients permission
requireLogin();
requirePermission('view_clients');

$clientModel = new ClientModel();
$clientId = (int)($_GET['id'] ?? 0);

// Validate client exists
if ($clientId > 0) {
    $client = $clientModel->getClientById($clientId);
    if ($client) {
        // Perform soft delete
        if ($clientModel->deleteClient($clientId)) {
            logSecurityEvent('CLIENT_DELETED', ['client_id' => $clientId]);
            $_SESSION['success_message'] = 'Client deleted successfully';
        } else {
            $_SESSION['error_message'] = 'Error deleting client';
        }
    } else {
        $_SESSION['error_message'] = 'Client not found';
    }
} else {
    $_SESSION['error_message'] = 'Invalid client ID';
}

// Redirect back to list
redirect(url('clients', 'list'));