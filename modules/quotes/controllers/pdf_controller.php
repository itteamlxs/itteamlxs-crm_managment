<?php
/**
 * Quote PDF Controller
 * Generate and download quote PDFs
 */

require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Check permissions
requirePermission('view_clients');

$quoteModel = new QuoteModel();
$currentUser = getCurrentUser();

// Get quote ID from URL
$quoteId = (int)($_GET['id'] ?? 0);

if ($quoteId <= 0) {
    $_SESSION['error'] = __('invalid_quote');
    redirect(url('quotes', 'list'));
}

try {
    // Get quote details with items
    $quote = $quoteModel->getQuoteById($quoteId);
    
    if (!$quote) {
        $_SESSION['error'] = __('quote_not_found');
        redirect(url('quotes', 'list'));
    }
    
    // Get client details
    $client = $quoteModel->getClientById($quote['client_id']);
    
    // Get company settings
    $companySettings = $quoteModel->getCompanySettings();
    
    // Generate PDF content
    $html = generateQuotePdfHtml($quote, $client, $companySettings);
    
    // Configure Dompdf
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');
    $options->set('isRemoteEnabled', false);
    $options->set('isHtml5ParserEnabled', true);
    
    $dompdf = new Dompdf($options);
    
    // Load HTML
    $dompdf->loadHtml($html);
    
    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');
    
    // Render PDF
    $dompdf->render();
    
    // Output PDF
    $filename = 'quote_' . $quote['quote_number'] . '.pdf';
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($dompdf->output()));
    
    echo $dompdf->output();
    exit;
    
} catch (Exception $e) {
    logError("PDF generation error: " . $e->getMessage());
    $_SESSION['error'] = __('error_generating_pdf');
    redirect(url('quotes', 'view', ['id' => $quoteId]));
}

/**
 * Generate HTML content for PDF
 */
function generateQuotePdfHtml($quote, $client, $companySettings) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: 'DejaVu Sans', sans-serif;
                font-size: 12px;
                line-height: 1.4;
                color: #333;
            }
            .header {
                border-bottom: 2px solid #007bff;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }
            .company-info {
                float: left;
                width: 50%;
            }
            .quote-info {
                float: right;
                width: 45%;
                text-align: right;
            }
            .client-info {
                clear: both;
                margin-top: 30px;
                padding: 15px;
                background-color: #f8f9fa;
                border-left: 4px solid #007bff;
            }
            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin: 30px 0;
            }
            .items-table th,
            .items-table td {
                border: 1px solid #dee2e6;
                padding: 10px;
                text-align: left;
            }
            .items-table th {
                background-color: #007bff;
                color: white;
                font-weight: bold;
            }
            .items-table .text-right {
                text-align: right;
            }
            .items-table .text-center {
                text-align: center;
            }
            .total-section {
                float: right;
                width: 300px;
                margin-top: 20px;
            }
            .total-row {
                display: flex;
                justify-content: space-between;
                padding: 5px 0;
                border-bottom: 1px solid #eee;
            }
            .total-row.final {
                font-weight: bold;
                font-size: 14px;
                border-bottom: 2px solid #007bff;
                color: #007bff;
            }
            .footer {
                clear: both;
                margin-top: 50px;
                padding-top: 20px;
                border-top: 1px solid #dee2e6;
                font-size: 10px;
                color: #6c757d;
            }
            .clearfix::after {
                content: "";
                display: table;
                clear: both;
            }
        </style>
    </head>
    <body>
        <div class="header clearfix">
            <div class="company-info">
                <h1 style="color: #007bff; margin: 0; font-size: 24px;">
                    <?= htmlspecialchars($companySettings['company_display_name'] ?? 'Company Name') ?>
                </h1>
                <p style="margin: 5px 0 0 0;">
                    <?= htmlspecialchars($companySettings['company_address'] ?? '') ?><br>
                    <?= htmlspecialchars($companySettings['company_phone'] ?? '') ?><br>
                    <?= htmlspecialchars($companySettings['company_email'] ?? '') ?>
                </p>
            </div>
            <div class="quote-info">
                <h2 style="color: #007bff; margin: 0; font-size: 20px;">COTIZACIÓN</h2>
                <table style="margin-top: 10px;">
                    <tr>
                        <td><strong>Número:</strong></td>
                        <td><?= htmlspecialchars($quote['quote_number']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Fecha:</strong></td>
                        <td><?= date('d/m/Y', strtotime($quote['issue_date'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Vence:</strong></td>
                        <td><?= date('d/m/Y', strtotime($quote['expiry_date'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Estado:</strong></td>
                        <td><?= strtoupper($quote['status']) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="client-info">
            <h3 style="margin: 0 0 10px 0; color: #007bff;">CLIENTE</h3>
            <strong><?= htmlspecialchars($client['company_name']) ?></strong><br>
            <?= htmlspecialchars($client['contact_name']) ?><br>
            <?= htmlspecialchars($client['email']) ?><br>
            <?php if ($client['phone']): ?>
                <?= htmlspecialchars($client['phone']) ?><br>
            <?php endif; ?>
            <?php if ($client['address']): ?>
                <?= htmlspecialchars($client['address']) ?><br>
            <?php endif; ?>
            <?php if ($client['tax_id']): ?>
                <strong>RUC/NIT:</strong> <?= htmlspecialchars($client['tax_id']) ?>
            <?php endif; ?>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Producto</th>
                    <th style="width: 10%;" class="text-center">Cant.</th>
                    <th style="width: 15%;" class="text-right">P. Unit.</th>
                    <th style="width: 10%;" class="text-center">Desc.</th>
                    <th style="width: 12%;" class="text-right">Impuesto</th>
                    <th style="width: 13%;" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalBeforeDiscount = 0;
                $totalDiscount = 0;
                $totalTax = 0;
                $grandTotal = 0;
                
                foreach ($quote['items'] as $item): 
                    $subtotalBeforeDiscount = $item['unit_price'] * $item['quantity'];
                    $discountAmount = $subtotalBeforeDiscount * ($item['discount'] / 100);
                    
                    $totalBeforeDiscount += $subtotalBeforeDiscount;
                    $totalDiscount += $discountAmount;
                    $totalTax += $item['tax_amount'];
                    $grandTotal += $item['subtotal'];
                ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($item['product_name']) ?></strong><br>
                        <small>SKU: <?= htmlspecialchars($item['sku']) ?></small>
                    </td>
                    <td class="text-center"><?= $item['quantity'] ?></td>
                    <td class="text-right">$<?= number_format($item['unit_price'], 2) ?></td>
                    <td class="text-center">
                        <?= $item['discount'] > 0 ? number_format($item['discount'], 1) . '%' : '-' ?>
                    </td>
                    <td class="text-right">$<?= number_format($item['tax_amount'], 2) ?></td>
                    <td class="text-right">$<?= number_format($item['subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>$<?= number_format($totalBeforeDiscount, 2) ?></span>
            </div>
            <?php if ($totalDiscount > 0): ?>
            <div class="total-row">
                <span>Descuento:</span>
                <span>-$<?= number_format($totalDiscount, 2) ?></span>
            </div>
            <?php endif; ?>
            <div class="total-row">
                <span>Impuestos:</span>
                <span>$<?= number_format($totalTax, 2) ?></span>
            </div>
            <div class="total-row final">
                <span>TOTAL:</span>
                <span>$<?= number_format($grandTotal, 2) ?></span>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Términos y Condiciones:</strong></p>
            <p>
                • Esta cotización es válida hasta <?= date('d/m/Y', strtotime($quote['expiry_date'])) ?><br>
                • Los precios están expresados en dólares estadounidenses<br>
                • Los precios incluyen impuestos donde corresponda<br>
                • La disponibilidad de productos está sujeta a stock
            </p>
            
            <div style="text-align: center; margin-top: 30px;">
                <p>Generado el <?= date('d/m/Y H:i') ?></p>
            </div>
        </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}