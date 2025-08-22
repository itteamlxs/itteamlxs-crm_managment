<?php
/**
 * Edit Client Controller
 * Handles client editing
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
$clientId = (int)($_GET['id'] ?? 0);

// Get client data
$client = null;
if ($clientId > 0) {
    $client = $clientModel->getClientById($clientId);
    if (!$client) {
        redirect(url('clients', 'list'));
    }
}

if (!$client) {
    redirect(url('clients', 'list'));
}

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
            'tax_id' => sanitizeInput($_POST['tax_id'] ?? '')
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
        } elseif ($clientModel->emailExists($formData['email'], $clientId)) {
            $errors[] = 'Email already exists';
        }
        
        if (!empty($formData['phone']) && !validatePhone($formData['phone'])) {
            $errors[] = 'Invalid phone format';
        }
        
        // Process if no errors
        if (empty($errors)) {
            if ($clientModel->updateClient($clientId, $formData)) {
                $success = 'Client updated successfully';
                logSecurityEvent('CLIENT_UPDATED', ['client_id' => $clientId]);
                
                // Update client data for redisplay
                $client = array_merge($client, $formData);
            } else {
                $error = 'Error updating client';
            }
        } else {
            $error = implode('<br>', $errors);
            // Update form data for redisplay
            $client = array_merge($client, $formData);
        }
    }
}

// Get client activities
$activities = $clientModel->getClientActivities($clientId);

// Include view
include __DIR__ . '/../views/edit.php';