<?php
/**
 * Quote PDF Controller - Clean Professional Design
 * Generate PDF from quote data using Dompdf with structured layout matching reference
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
    $companyLogo = $db->fetch("SELECT setting_value FROM settings WHERE setting_key = 'company_logo'")['setting_value'] ?? '';
    $companySlogan = $db->fetch("SELECT setting_value FROM settings WHERE setting_key = 'company_slogan'")['setting_value'] ?? '';
} catch (Exception $e) {
    $companyName = 'Company Name';
    $companyLogo = '';
    $companySlogan = '';
}

// Convert logo to base64 for PDF
$logoData = '';
if (!empty($companyLogo)) {
    $logoPath = __DIR__ . '/../../../public' . str_replace('/crm-project/public', '', $companyLogo);
    if (file_exists($logoPath)) {
        $logoData = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
    }
}

// Generate PDF content
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización <?php echo sanitizeOutput($quote['quote_number']); ?></title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }
        
        body { 
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            margin-bottom: 30px;
        }
        
        .company-section {
            width: 100%;
            margin-bottom: 15px;
            position: relative;
        }
        
        .company-info {
            float: left;
            width: 75%;
        }
        
        .company-info h1 {
            color: #4a90e2;
            font-size: 32px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .company-tagline {
            color: #666;
            font-style: italic;
            font-size: 12px;
            margin: 0;
        }
        
        .logo-container {
            float: right;
            width: 25%;
            text-align: right;
        }
        
        .logo {
            max-width: 80px;
            max-height: 80px;
        }
        
        .logo-placeholder {
            width: 70px;
            height: 70px;
            background: #4a90e2;
            border-radius: 50%;
            display: inline-block;
            text-align: center;
            line-height: 70px;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .document-title {
            color: #4a90e2;
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
            padding-bottom: 8px;
            border-bottom: 3px solid #4a90e2;
        }
        
        .info-section {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        
        .info-box {
            display: table-cell;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 12px;
            vertical-align: top;
        }
        
        .info-box:first-child {
            width: 48%;
            margin-right: 2%;
        }
        
        .info-box:last-child {
            width: 48%;
            margin-left: 2%;
        }
        
        .info-box h3 {
            color: #4a90e2;
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
        }
        
        .info-row {
            margin: 6px 0;
            display: flex;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
            min-width: 75px;
            margin-right: 8px;
        }
        
        .info-value {
            color: #666;
        }
        
        .quote-details {
            text-align: left;
        }
        
        .quote-details .info-row {
            justify-content: flex-end;
        }
        
        .vendor-section {
            margin: 15px 0;
        }
        
        .vendor-box {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 12px;
            width: 47%;
        }
        
        .vendor-box h3 {
            color: #4a90e2;
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .products-table thead th {
            background: #4a90e2;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        
        .products-table tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .products-table tbody tr:nth-child(odd) {
            background: #f9f9f9;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .product-name {
            font-weight: bold;
            color: #333;
        }
        
        .totals-section {
            margin: 30px 0;
            display: flex;
            justify-content: flex-end;
        }
        
        .totals-table {
            border: 1px solid #ddd;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .totals-table .total-row {
            background: #4a90e2;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .currency {
            color: #27ae60;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-section">
            <div class="company-info">
                <h1><?php echo sanitizeOutput($companyName); ?></h1>
                <p class="company-tagline"><?php echo !empty($companySlogan) ? sanitizeOutput($companySlogan) : 'Envios confiables a todo el mundo'; ?></p>
            </div>
            <div class="logo-container">
                <?php if (!empty($logoData)): ?>
                    <img src="<?php echo $logoData; ?>" alt="Company Logo" class="logo">
                <?php else: ?>
                    <div class="logo-placeholder">CRM</div>
                <?php endif; ?>
            </div>
        </div>
        
        <h1 class="document-title">COTIZACIÓN</h1>
    </div>
    
    <div class="info-section">
        <div class="info-box">
            <h3>Información del Cliente</h3>
            <div class="info-row">
                <span class="info-label">Cliente:</span>
                <span class="info-value"><?php echo sanitizeOutput($quote['contact_name']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo sanitizeOutput($quote['client_email']); ?></span>
            </div>
            <?php if ($quote['client_phone']): ?>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span class="info-value"><?php echo sanitizeOutput($quote['client_phone']); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($quote['client_address']): ?>
            <div class="info-row">
                <span class="info-label">Dirección:</span>
                <span class="info-value"><?php echo sanitizeOutput($quote['client_address']); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="info-box quote-details">
            <h3>Detalles de la Cotización</h3>
            <div class="info-row">
                <span class="info-label">Número:</span>
                <span class="info-value"><?php echo sanitizeOutput($quote['quote_number']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Fecha:</span>
                <span class="info-value"><?php echo formatDate($quote['issue_date'], 'd/m/Y'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Hora:</span>
                <span class="info-value"><?php echo date('H:i:s'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Válida hasta:</span>
                <span class="info-value"><?php echo formatDate($quote['expiry_date'], 'd/m/Y'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Estado:</span>
                <span class="info-value"><?php echo sanitizeOutput($quote['status']); ?></span>
            </div>
        </div>
    </div>
    
    <div class="vendor-section">
        <div class="vendor-box">
            <h3>Vendedor</h3>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span class="info-value"><?php echo sanitizeOutput($quote['created_by_name']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo sanitizeOutput($user['email'] ?? 'root@sysadmin.com'); ?></span>
            </div>
        </div>
    </div>
    
    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 40%;">Producto/Servicio</th>
                <th style="width: 8%;" class="text-center">Cant.</th>
                <th style="width: 15%;" class="text-right">Precio Unit.</th>
                <th style="width: 10%;" class="text-center">Desc. %</th>
                <th style="width: 15%;" class="text-right">Subtotal</th>
                <th style="width: 10%;" class="text-center">IVA %</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quoteItems as $item): ?>
                <?php
                $itemSubtotalBeforeDiscount = $item['quantity'] * $item['unit_price'];
                $itemDiscountAmount = ($itemSubtotalBeforeDiscount * $item['discount']) / 100;
                $itemSubtotalAfterDiscount = $itemSubtotalBeforeDiscount - $itemDiscountAmount;
                $ivaRate = $itemSubtotalAfterDiscount > 0 ? ($item['tax_amount'] / $itemSubtotalAfterDiscount) * 100 : 0;
                ?>
                <tr>
                    <td>
                        <div class="product-name"><?php echo sanitizeOutput($item['product_name']); ?></div>
                        <div style="font-size: 9px; color: #999;">SKU: <?php echo sanitizeOutput($item['sku']); ?></div>
                    </td>
                    <td class="text-center"><?php echo sanitizeOutput($item['quantity']); ?></td>
                    <td class="text-right"><span class="currency"><?php echo formatCurrency($item['unit_price']); ?></span></td>
                    <td class="text-center"><?php echo number_format($item['discount'], 1); ?>%</td>
                    <td class="text-right"><span class="currency"><?php echo formatCurrency($itemSubtotalAfterDiscount); ?></span></td>
                    <td class="text-center"><?php echo number_format($ivaRate, 1); ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="totals-section" style="display: flex; justify-content: flex-end;">
        <table class="totals-table" style="width: 300px;">
            <tr>
                <td style="text-align: right; font-weight: bold;">Subtotal:</td>
                <td style="text-align: right;"><span class="currency"><?php echo formatCurrency($subtotal - $totalDiscount); ?></span></td>
            </tr>
            <tr>
                <td style="text-align: right; font-weight: bold;">IVA:</td>
                <td style="text-align: right;"><span class="currency"><?php echo formatCurrency($totalTax); ?></span></td>
            </tr>
            <tr class="total-row">
                <td style="text-align: right;">TOTAL:</td>
                <td style="text-align: right; font-size: 16px;"><?php echo formatCurrency($total); ?></td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>Gracias por confiar en <?php echo sanitizeOutput($companyName); ?></p>
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
$options->set('dpi', 96);

// Create PDF
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF
$filename = 'Cotizacion_' . $quote['quote_number'] . '_' . date('Y-m-d') . '.pdf';
$dompdf->stream($filename, ['Attachment' => 1]);