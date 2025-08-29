<?php
/**
 * Send Quote Controller
 * Handle sending quotes via email using PHPMailer
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check permissions
requireLogin();
requirePermission('create_quotes');

$quoteModel = new QuoteModel();
$user = getCurrentUser();

// Get quote ID
$quoteId = (int)($_GET['id'] ?? 0);

if (empty($quoteId)) {
    redirect(url('quotes', 'list'));
}

// Get quote data
$quote = $quoteModel->getQuoteById($quoteId);

if (!$quote) {
    $_SESSION['error_message'] = __('quote_not_found');
    redirect(url('quotes', 'list'));
}

// Check if user can send this quote
if (!$user['is_admin'] && getUserRole() === 'Seller' && $quote['user_id'] != $user['user_id']) {
    $_SESSION['error_message'] = __('access_denied');
    redirect(url('quotes', 'view', ['id' => $quoteId]));
}

// Check if quote can be sent (only DRAFT quotes)
if ($quote['status'] !== 'DRAFT') {
    $_SESSION['error_message'] = __('only_draft_quotes_can_be_sent');
    redirect(url('quotes', 'view', ['id' => $quoteId]));
}

// Initialize variables
$errors = [];
$success = false;
$emailData = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = __('invalid_security_token');
    } else {
        // Get form data
        $emailData = [
            'to_email' => sanitizeInput($_POST['to_email'] ?? $quote['client_email']),
            'cc_email' => sanitizeInput($_POST['cc_email'] ?? ''),
            'subject' => sanitizeInput($_POST['subject'] ?? ''),
            'message' => sanitizeInput($_POST['message'] ?? ''),
            'attach_pdf' => isset($_POST['attach_pdf'])
        ];
        
        // Validate required fields
        if (empty($emailData['to_email'])) {
            $errors[] = __('recipient_email_required');
        } elseif (!validateEmail($emailData['to_email'])) {
            $errors[] = __('invalid_recipient_email');
        }
        
        if (!empty($emailData['cc_email']) && !validateEmail($emailData['cc_email'])) {
            $errors[] = __('invalid_cc_email');
        }
        
        if (empty($emailData['subject'])) {
            $errors[] = __('email_subject_required');
        }
        
        if (empty($emailData['message'])) {
            $errors[] = __('email_message_required');
        }
        
        // Send email if no errors
        if (empty($errors)) {
            $emailResult = sendQuoteEmail($quote, $emailData);
            
            if ($emailResult['success']) {
                // Update quote status to SENT
                if ($quoteModel->updateQuoteStatus($quoteId, 'SENT')) {
                    $_SESSION['success_message'] = __('quote_sent_successfully');
                    redirect(url('quotes', 'view', ['id' => $quoteId]));
                } else {
                    $errors[] = __('quote_sent_but_status_not_updated');
                }
            } else {
                $errors[] = $emailResult['error'];
            }
        }
    }
}

// Set default email data if not set
if (empty($emailData)) {
    $emailData = [
        'to_email' => $quote['client_email'],
        'cc_email' => '',
        'subject' => __('quote_email_subject', ['quote_number' => $quote['quote_number']]),
        'message' => generateDefaultEmailMessage($quote),
        'attach_pdf' => true
    ];
}

// Get SMTP settings for form display
try {
    $db = Database::getInstance();
    $smtpSettings = $db->fetchAll("SELECT setting_key, setting_value FROM vw_settings WHERE setting_key LIKE 'smtp_%' OR setting_key IN ('from_email', 'from_name')");
    $smtpConfigured = !empty($smtpSettings);
} catch (Exception $e) {
    $smtpConfigured = false;
}

// CSRF Token
$csrfToken = generateCSRFToken();

/**
 * Send quote email using PHPMailer
 */
function sendQuoteEmail($quote, $emailData) {
    try {
        // Get SMTP settings
        $db = Database::getInstance();
        $settings = [];
        $smtpSettings = $db->fetchAll("SELECT setting_key, setting_value FROM vw_settings WHERE setting_key LIKE 'smtp_%' OR setting_key IN ('from_email', 'from_name')");
        
        foreach ($smtpSettings as $setting) {
            $settings[$setting['setting_key']] = $setting['setting_value'];
        }
        
        if (empty($settings['smtp_host'])) {
            return ['success' => false, 'error' => __('smtp_not_configured')];
        }
        
        // Create PHPMailer instance
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = $settings['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $settings['smtp_username'];
        $mail->Password = $settings['smtp_password'];
        $mail->SMTPSecure = strtolower($settings['smtp_encryption']) === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int)($settings['smtp_port'] ?? 587);
        
        // Recipients
        $mail->setFrom($settings['from_email'], $settings['from_name']);
        $mail->addAddress($emailData['to_email']);
        
        if (!empty($emailData['cc_email'])) {
            $mail->addCC($emailData['cc_email']);
        }
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $emailData['subject'];
        $mail->Body = nl2br($emailData['message']);
        $mail->AltBody = strip_tags($emailData['message']);
        
        // Attach PDF if requested
        if ($emailData['attach_pdf']) {
            $pdfContent = generateQuotePDF($quote);
            if ($pdfContent) {
                $mail->addStringAttachment($pdfContent, "Quote_{$quote['quote_number']}.pdf", 'base64', 'application/pdf');
            }
        }
        
        $mail->send();
        
        // Log the email activity
        logSecurityEvent('QUOTE_EMAIL_SENT', [
            'quote_id' => $quote['quote_id'],
            'quote_number' => $quote['quote_number'],
            'recipient' => $emailData['to_email']
        ]);
        
        return ['success' => true];
        
    } catch (Exception $e) {
        logError("Failed to send quote email: " . $e->getMessage());
        return ['success' => false, 'error' => __('failed_to_send_email') . ': ' . $e->getMessage()];
    }
}

/**
 * Generate default email message
 */
function generateDefaultEmailMessage($quote) {
    $message = __('quote_email_default_message', [
        'client_name' => $quote['contact_name'],
        'quote_number' => $quote['quote_number'],
        'total_amount' => formatCurrency($quote['total_amount']),
        'expiry_date' => formatDate($quote['expiry_date'], 'Y-m-d')
    ]);
    
    return $message;
}

/**
 * Generate quote PDF content (simplified version)
 */
function generateQuotePDF($quote) {
    try {
        // This would normally use Dompdf to generate PDF
        // For now, return null to indicate PDF generation is not available
        // The actual PDF generation will be implemented in pdf_controller.php
        return null;
        
    } catch (Exception $e) {
        logError("Failed to generate PDF for quote {$quote['quote_id']}: " . $e->getMessage());
        return null;
    }
}

// Include view
require_once __DIR__ . '/../views/send.php';