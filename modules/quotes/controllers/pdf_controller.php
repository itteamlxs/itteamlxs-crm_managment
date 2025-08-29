<?php
/**
 * Quote PDF Controller
 * Generate PDF from quote data using Dompdf
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Check permissions
requireLogin();
requirePermission('view_clients');

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

// Check if user can view this quote
if (!$user['is_admin'] && getUserRole() === 'Seller' && $quote['user_id'] != $user['user_id']) {
    $_SESSION['error_message'] = __('access_denied');
    redirect(url('quotes', 'list'));
}

// Get quote items
$quoteItems = $quoteModel->getQuoteItems($quoteId);

// Calculate totals
$subtotal = 0;
$totalDiscount = 0;
$totalTax = 0;

foreach ($quoteItems as $item) {
    $itemSubtotal = $item['quantity'] * $item['unit_price'];
    $itemDiscount = ($itemSubtotal * $item['discount']) / 100;
    
    $subtotal += $itemSubtotal;
    $totalDiscount += $itemDiscount;
    $totalTax += $item['tax_amount'];
}

$total = $subtotal - $totalDiscount + $totalTax;

// Get company settings
try {
    $db = Database::getInstance();
    $companyName = $db->fetch("SELECT setting_value FROM settings WHERE setting_key = 'company_display_name'")['setting_value'] ?? 'Company Name';
} catch (Exception $e) {
    $companyName = 'Company Name';
}

// Generate PDF content
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote <?php echo sanitizeOutput($quote['quote_number']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-name { font-size: 18px; font-weight: bold; }
        .quote-title { font-size: 16px; margin-top: 10px; }
        .info-section { margin: 20px 0; }
        .info-row { margin: 5px 0; }
        .client-info { float: left; width: 50%; }
        .quote-info { float: right; width: 45%; text-align: right; }
        .clearfix { clear: both; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { width: 300px; margin-left: auto; }
        .totals td { border: none; padding: 5px 10px; }
        .total-row { font-weight: bold; border-top: 2px solid #000; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name"><?php echo sanitizeOutput($companyName); ?></div>
        <div class="quote-title">COTIZACIÓN</div>
    </div>
    
    <div class="info-section">
        <div class="client-info">
            <strong>Cliente:</strong><br>
            <?php echo sanitizeOutput($quote['company_name']); ?><br>
            <?php echo sanitizeOutput($quote['contact_name']); ?><br>
            <?php echo sanitizeOutput($quote['client_email']); ?><br>
            <?php if ($quote['client_phone']): ?>
                Tel: <?php echo sanitizeOutput($quote['client_phone']); ?><br>
            <?php endif; ?>
            <?php if ($quote['client_address']): ?>
                <?php echo sanitizeOutput($quote['client_address']); ?><br>
            <?php endif; ?>
        </div>
        
        <div class="quote-info">
            <div class="info-row"><strong>Cotización #:</strong> <?php echo sanitizeOutput($quote['quote_number']); ?></div>
            <div class="info-row"><strong>Fecha:</strong> <?php echo formatDate($quote['issue_date'], 'd/m/Y'); ?></div>
            <div class="info-row"><strong>Válida hasta:</strong> <?php echo formatDate($quote['expiry_date'], 'd/m/Y'); ?></div>
            <div class="info-row"><strong>Estado:</strong> <?php echo sanitizeOutput($quote['status']); ?></div>
            <div class="info-row"><strong>Vendedor:</strong> <?php echo sanitizeOutput($quote['created_by_name']); ?></div>
        </div>
        
        <div class="clearfix"></div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>SKU</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Precio Unit.</th>
                <th class="text-center">Descuento</th>
                <th class="text-right">Impuestos</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quoteItems as $item): ?>
                <tr>
                    <td><?php echo sanitizeOutput($item['product_name']); ?></td>
                    <td><?php echo sanitizeOutput($item['sku']); ?></td>
                    <td class="text-center"><?php echo sanitizeOutput($item['quantity']); ?></td>
                    <td class="text-right"><?php echo formatCurrency($item['unit_price']); ?></td>
                    <td class="text-center"><?php echo sanitizeOutput($item['discount']); ?>%</td>
                    <td class="text-right"><?php echo formatCurrency($item['tax_amount']); ?></td>
                    <td class="text-right"><?php echo formatCurrency($item['subtotal']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <table class="totals">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right"><?php echo formatCurrency($subtotal); ?></td>
        </tr>
        <tr>
            <td>Descuento Total:</td>
            <td class="text-right">-<?php echo formatCurrency($totalDiscount); ?></td>
        </tr>
        <tr>
            <td>Impuestos Total:</td>
            <td class="text-right"><?php echo formatCurrency($totalTax); ?></td>
        </tr>
        <tr class="total-row">
            <td><strong>Total:</strong></td>
            <td class="text-right"><strong><?php echo formatCurrency($total); ?></strong></td>
        </tr>
    </table>
    
    <div class="footer">
        <p>Esta cotización es válida hasta <?php echo formatDate($quote['expiry_date'], 'd/m/Y'); ?></p>
        <p>Generado el <?php echo formatDate(date('Y-m-d H:i:s'), 'd/m/Y H:i'); ?></p>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

// Configure Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', false);
$options->set('isPhpEnabled', false);

// Create PDF
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF
$filename = 'Cotizacion_' . $quote['quote_number'] . '_' . date('Y-m-d') . '.pdf';
$dompdf->stream($filename, ['Attachment' => 1]);