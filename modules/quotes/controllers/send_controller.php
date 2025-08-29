<?php
/**
 * Quote Send Controller
 * Handle quote sending to client via email
 */

require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// Check permissions
requirePermission('create_quotes');

$quoteModel = new QuoteModel();
$currentUser = getCurrentUser();

// Handle AJAX send request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['success' => false, 'error' => __('invalid_security_token')], 403);
    }
    
    $quoteId = (int)($_POST['quote_id'] ?? 0);
    $emailMessage = sanitizeInput($_POST['email_message'] ?? '');
    
    if ($quoteId <= 0) {
        jsonResponse(['success' => false, 'error' => __('invalid_quote')], 400);
    }
    
    try {
        // Get quote details
        $quote = $quoteModel->getQuoteById($quoteId);
        
        if (!$quote) {
            jsonResponse(['success' => false, 'error' => __('quote_not_found')], 404);
        }
        
        // Check if quote can be sent
        if (!in_array($quote['status'], ['DRAFT'])) {
            jsonResponse(['success' => false, 'error' => __('quote_cannot_be_sent')], 400);
        }
        
        // Get client details
        $client = $quoteModel->getClientById($quote['client_id']);
        
        if (!$client) {
            jsonResponse(['success' => false, 'error' => __('client_not_found')], 404);
        }
        
        // Get SMTP settings
        $smtpSettings = $quoteModel->getSmtpSettings();
        
        if (!$smtpSettings) {
            jsonResponse(['success' => false, 'error' => __('smtp_not_configured')], 500);
        }
        
        // Generate PDF (will be implemented in pdf_controller)
        $pdfPath = $quoteModel->generateQuotePdf($quoteId);
        
        // Send email
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = $smtpSettings['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtpSettings['smtp_username'];
        $mail->Password = $smtpSettings['smtp_password'];
        $mail->SMTPSecure = $smtpSettings['smtp_encryption'] === 'TLS' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $smtpSettings['smtp_port'];
        $mail->CharSet = 'UTF-8';
        
        // Recipients
        $mail->setFrom($smtpSettings['from_email'], $smtpSettings['from_name']);
        $mail->addAddress($client['email'], $client['contact_name']);
        $mail->addReplyTo($smtpSettings['from_email'], $smtpSettings['from_name']);
        
        // Attachments
        if (file_exists($pdfPath)) {
            $mail->addAttachment($pdfPath, $quote['quote_number'] . '.pdf');
        }
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = __('quote_email_subject', ['quote_number' => $quote['quote_number']]);
        
        $emailBody = $quoteModel->getQuoteEmailTemplate($quote, $client, $emailMessage);
        $mail->Body = $emailBody;
        $mail->AltBody = strip_tags($emailBody);
        
        $mail->send();
        
        // Update quote status to SENT
        $quoteModel->updateQuoteStatus($quoteId, 'SENT');
        
        // Log activity
        $quoteModel->logClientActivity($quote['client_id'], $quoteId, 'QUOTE_SENT', [
            'sent_to' => $client['email'],
            'sent_by' => $currentUser['username']
        ]);
        
        // Clean up PDF file if exists
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }
        
        jsonResponse([
            'success' => true,
            'message' => __('quote_sent_successfully'),
            'quote_number' => $quote['quote_number'],
            'sent_to' => $client['email']
        ]);
        
    } catch (PHPMailerException $e) {
        logError("Quote email error: " . $e->getMessage());
        jsonResponse(['success' => false, 'error' => __('error_sending_email')], 500);
        
    } catch (Exception $e) {
        logError("Quote send error: " . $e->getMessage());
        jsonResponse(['success' => false, 'error' => __('error_sending_quote')], 500);
    }
}

// Handle non-AJAX requests - show send form
$quoteId = (int)($_GET['id'] ?? 0);

if ($quoteId <= 0) {
    $_SESSION['error'] = __('invalid_quote');
    redirect(url('quotes', 'list'));
}

try {
    // Get quote details
    $quote = $quoteModel->getQuoteById($quoteId);
    
    if (!$quote) {
        $_SESSION['error'] = __('quote_not_found');
        redirect(url('quotes', 'list'));
    }
    
    // Check if quote can be sent
    if (!in_array($quote['status'], ['DRAFT'])) {
        $_SESSION['error'] = __('quote_cannot_be_sent');
        redirect(url('quotes', 'view', ['id' => $quoteId]));
    }
    
    // Get client details
    $client = $quoteModel->getClientById($quote['client_id']);
    
} catch (Exception $e) {
    logError("Quote send load error: " . $e->getMessage());
    $_SESSION['error'] = __('error_loading_quote');
    redirect(url('quotes', 'list'));
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

// Include the view
require_once __DIR__ . '/../views/send.php';