<?php
/**
 * Send Quote Controller - Simplified Version
 * Handle sending quotes via email with automatic message generation
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
$quoteItems = $quoteModel->getQuoteItems($quoteId);

if (!$quote) {
    $_SESSION['error_message'] = 'Cotización no encontrada';
    redirect(url('quotes', 'list'));
}

// Check if user can send this quote
if (!$user['is_admin'] && getUserRole() === 'Seller' && $quote['user_id'] != $user['user_id']) {
    $_SESSION['error_message'] = 'Acceso denegado';
    redirect(url('quotes', 'view', ['id' => $quoteId]));
}

// Check if quote can be sent (only DRAFT quotes)
if ($quote['status'] !== 'DRAFT') {
    $_SESSION['error_message'] = 'Solo se pueden enviar cotizaciones en borrador';
    redirect(url('quotes', 'view', ['id' => $quoteId]));
}

// Initialize variables
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token de seguridad inválido';
    } else {
        // Send email with auto-generated content
        $emailResult = sendQuoteEmail($quote, $quoteItems, $user);
        
        if ($emailResult['success']) {
            // Update quote status to SENT
            if ($quoteModel->updateQuoteStatus($quoteId, 'SENT')) {
                $_SESSION['success_message'] = 'Cotización enviada exitosamente';
                redirect(url('quotes', 'view', ['id' => $quoteId]));
            } else {
                $errors[] = 'Cotización enviada pero no se pudo actualizar el estado';
            }
        } else {
            $errors[] = $emailResult['error'];
        }
    }
}

// Get SMTP settings for form display
try {
    $db = Database::getInstance();
    $smtpSettings = $db->fetchAll("SELECT setting_key, setting_value FROM vw_settings WHERE setting_key LIKE 'smtp_%' OR setting_key IN ('from_email', 'from_name', 'company_display_name')");
    $smtpConfigured = !empty($smtpSettings);
    
    $settings = [];
    foreach ($smtpSettings as $setting) {
        $settings[$setting['setting_key']] = $setting['setting_value'];
    }
} catch (Exception $e) {
    $smtpConfigured = false;
    $settings = [];
}

// CSRF Token
$csrfToken = generateCSRFToken();

/**
 * Send quote email with auto-generated content
 */
function sendQuoteEmail($quote, $quoteItems, $user) {
    try {
        // Get SMTP settings
        $db = Database::getInstance();
        $settings = [];
        $smtpSettings = $db->fetchAll("SELECT setting_key, setting_value FROM vw_settings WHERE setting_key LIKE 'smtp_%' OR setting_key IN ('from_email', 'from_name', 'company_display_name')");
        
        foreach ($smtpSettings as $setting) {
            $settings[$setting['setting_key']] = $setting['setting_value'];
        }
        
        if (empty($settings['smtp_host'])) {
            return ['success' => false, 'error' => 'SMTP no configurado'];
        }
        
        // Generate email content
        $subject = "Cotización #{$quote['quote_number']} - " . ($settings['company_display_name'] ?? 'CRM System');
        $message = generateQuoteEmailContent($quote, $quoteItems, $user, $settings);
        
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
        $mail->addAddress($quote['client_email']);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);
        
        $mail->send();
        
        // Log the email activity
        logSecurityEvent('QUOTE_EMAIL_SENT', [
            'quote_id' => $quote['quote_id'],
            'quote_number' => $quote['quote_number'],
            'recipient' => $quote['client_email']
        ]);
        
        return ['success' => true];
        
    } catch (Exception $e) {
        logError("Failed to send quote email: " . $e->getMessage());
        return ['success' => false, 'error' => 'Error al enviar correo: ' . $e->getMessage()];
    }
}

/**
 * Generate complete quote email content
 */
function generateQuoteEmailContent($quote, $quoteItems, $user, $settings) {
    $companyName = $settings['company_display_name'] ?? 'CRM System';
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='border-bottom: 3px solid #007bff; padding-bottom: 20px; margin-bottom: 30px;'>
                <h1 style='color: #007bff; margin: 0;'>{$companyName}</h1>
                <p style='margin: 5px 0; color: #666;'>Cotización #{$quote['quote_number']}</p>
            </div>
            
            <div style='margin-bottom: 30px;'>
                <h2 style='color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px;'>Estimado/a {$quote['contact_name']},</h2>
                <p>Nos complace enviarle la cotización solicitada para <strong>{$quote['company_name']}</strong>.</p>
            </div>
            
            <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;'>
                <h3 style='margin-top: 0; color: #007bff;'>Información de la Cotización</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; font-weight: bold;'>Número de Cotización:</td>
                        <td style='padding: 8px 0;'>{$quote['quote_number']}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; font-weight: bold;'>Fecha de Emisión:</td>
                        <td style='padding: 8px 0;'>" . formatDate($quote['issue_date'], 'Y-m-d') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; font-weight: bold;'>Fecha de Vencimiento:</td>
                        <td style='padding: 8px 0; color: #dc3545; font-weight: bold;'>" . formatDate($quote['expiry_date'], 'Y-m-d') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; font-weight: bold;'>Vendedor:</td>
                        <td style='padding: 8px 0;'>{$user['display_name']}</td>
                    </tr>
                </table>
            </div>
            
            <div style='margin-bottom: 30px;'>
                <h3 style='color: #007bff; margin-bottom: 20px;'>Productos Cotizados</h3>
                <table style='width: 100%; border-collapse: collapse; border: 1px solid #dee2e6;'>
                    <thead>
                        <tr style='background: #007bff; color: white;'>
                            <th style='padding: 12px; text-align: left; border: 1px solid #dee2e6;'>Producto</th>
                            <th style='padding: 12px; text-align: center; border: 1px solid #dee2e6;'>Cantidad</th>
                            <th style='padding: 12px; text-align: right; border: 1px solid #dee2e6;'>Precio Unit.</th>
                            <th style='padding: 12px; text-align: right; border: 1px solid #dee2e6;'>Descuento</th>
                            <th style='padding: 12px; text-align: right; border: 1px solid #dee2e6;'>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>";
    
    foreach ($quoteItems as $item) {
        $message .= "
                        <tr>
                            <td style='padding: 10px; border: 1px solid #dee2e6;'>
                                <strong>{$item['product_name']}</strong><br>
                                <small style='color: #666;'>SKU: {$item['sku']}</small>
                            </td>
                            <td style='padding: 10px; text-align: center; border: 1px solid #dee2e6;'>{$item['quantity']}</td>
                            <td style='padding: 10px; text-align: right; border: 1px solid #dee2e6;'>" . formatCurrency($item['unit_price']) . "</td>
                            <td style='padding: 10px; text-align: right; border: 1px solid #dee2e6;'>{$item['discount']}%</td>
                            <td style='padding: 10px; text-align: right; border: 1px solid #dee2e6;'>" . formatCurrency($item['subtotal']) . "</td>
                        </tr>";
    }
    
    $message .= "
                    </tbody>
                </table>
            </div>
            
            <div style='background: #28a745; color: white; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 30px;'>
                <h2 style='margin: 0; font-size: 28px;'>TOTAL: " . formatCurrency($quote['total_amount']) . "</h2>
            </div>
            
            <div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin-bottom: 30px;'>
                <p style='margin: 0; color: #856404;'>
                    <strong>⚠️ Importante:</strong> Esta cotización es válida hasta el <strong>" . formatDate($quote['expiry_date'], 'Y-m-d') . "</strong>.
                    Los precios pueden cambiar después de esta fecha.
                </p>
            </div>
            
            <div style='margin-bottom: 30px;'>
                <h3 style='color: #007bff;'>Información de Contacto</h3>
                <p>Para cualquier consulta sobre esta cotización, puede contactarnos:</p>
                <ul>
                    <li><strong>Vendedor:</strong> {$user['display_name']}</li>
                    <li><strong>Email:</strong> " . ($settings['from_email'] ?? 'contacto@empresa.com') . "</li>
                    <li><strong>Empresa:</strong> {$companyName}</li>
                </ul>
            </div>
            
            <div style='text-align: center; padding: 20px; border-top: 2px solid #eee; margin-top: 30px;'>
                <p style='margin: 0; color: #666;'>Gracias por confiar en {$companyName}</p>
                <p style='margin: 5px 0; color: #999; font-size: 12px;'>Este correo fue generado automáticamente el " . date('Y-m-d H:i:s') . "</p>
            </div>
        </div>
    </body>
    </html>";
    
    return $message;
}

// Include view
require_once __DIR__ . '/../views/send.php';