<?php
/**
 * Client List Controller
 * Handle client listing with pagination and search
 */

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Check permissions first
try {
    requirePermission('view_clients');
} catch (Exception $e) {
    if (isAjaxRequest()) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Access denied']);
        exit;
    }
    redirect('/');
}

// Handle AJAX requests for client data
if (isAjaxRequest()) {
    // Clear any previous output
    ob_clean();
    header('Content-Type: application/json');
    
    try {
        $clientModel = new ClientModel();
        
        $search = sanitizeInput($_GET['search'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = min(100, max(1, (int)($_GET['limit'] ?? 10)));
        
        $filters = [];
        if (!empty($search)) {
            $filters['search'] = $search;
        }
        
        $result = $clientModel->getAllClients($filters, $page, $limit);
        
        // Basic response structure
        $response = [
            'clients' => $result['clients'] ?? [],
            'total' => $result['total'] ?? 0,
            'page' => $result['page'] ?? 1,
            'limit' => $result['limit'] ?? 10,
            'total_pages' => $result['total_pages'] ?? 1
        ];
        
        // Sanitize client data
        foreach ($response['clients'] as &$client) {
            $client['company_name'] = htmlspecialchars($client['company_name'] ?? '', ENT_QUOTES, 'UTF-8');
            $client['contact_name'] = htmlspecialchars($client['contact_name'] ?? '', ENT_QUOTES, 'UTF-8');
            $client['email'] = htmlspecialchars($client['email'] ?? '', ENT_QUOTES, 'UTF-8');
            $client['phone'] = htmlspecialchars($client['phone'] ?? '', ENT_QUOTES, 'UTF-8');
        }
        
        echo json_encode($response);
        
    } catch (Exception $e) {
        error_log("Client list error: " . $e->getMessage());
        echo json_encode(['error' => 'Database error occurred']);
    }
    
    exit;
}

// For non-AJAX requests, load the view
$pageTitle = 'Gestión de Clientes';
require_once __DIR__ . '/../views/list.php';