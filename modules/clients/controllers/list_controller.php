<?php
/**
 * Clients List Controller
 * Handles client listing with pagination and search
 */

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Require login and view_clients permission
requireLogin();
requirePermission('view_clients');

$clientModel = new ClientModel();
$error = '';
$success = '';

// Get pagination and search parameters
$pagination = validatePagination($_GET['page'] ?? 1, $_GET['limit'] ?? 10, 50);
$search = sanitizeInput($_GET['search'] ?? '');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $action = $_POST['action'] ?? '';
        $clientId = (int)($_POST['client_id'] ?? 0);
        
        if ($action === 'delete' && $clientId > 0) {
            if ($clientModel->deleteClient($clientId)) {
                $success = 'Client deleted successfully';
                logSecurityEvent('CLIENT_DELETED', ['client_id' => $clientId]);
            } else {
                $error = 'Error deleting client';
            }
        }
    }
}

// Get clients data
$clients = $clientModel->getAllClients($pagination['limit'], $pagination['offset'], $search);
$totalClients = $clientModel->getClientsCount($search);
$totalPages = ceil($totalClients / $pagination['limit']);

// Include view
include __DIR__ . '/../views/list.php';