<?php
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name')); ?> - Renew Quote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2><i class="bi bi-arrow-repeat"></i> Renew Quote</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo dashboardUrl(); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo url('quotes', 'list'); ?>">Quotes</a></li>
                                <li class="breadcrumb-item active">Renew</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="<?php echo url('quotes', 'list'); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <!-- Parent Quote Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Original Quote Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Quote #:</strong><br>
                                <?php echo sanitizeOutput($parentQuote['quote_number']); ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Client:</strong><br>
                                <?php echo sanitizeOutput($parentQuote['company_name']); ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong><br>
                                <span class="badge bg-<?php 
                                    echo match($parentQuote['status']) {
                                        'DRAFT' => 'secondary',
                                        'SENT' => 'info',
                                        'APPROVED' => 'success',
                                        'REJECTED' => 'danger',
                                        default => 'secondary'
                                    };
                                ?>"><?php echo sanitizeOutput($parentQuote['status']); ?></span>
                            </div>
                            <div class="col-md-3">
                                <strong>Original Amount:</strong><br>
                                $<?php echo number_format($parentQuote['total_amount'], 2); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Renewal Form -->
                <form method="POST" id="renewalForm">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Renewal Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="issue_date" class="form-label">New Issue Date <span class="text-danger">*</span></label>
                                    <input type="date" name="issue_date" id="issue_date" class="form-control" 
                                           value="<?php echo sanitizeOutput($formData['issue_date']); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="expiry_date" class="form-label">New Expiry Date <span class="text-danger">*</span></label>
                                    <input type="date" name="expiry_date" id="expiry_date" class="form-control" 
                                           value="<?php echo sanitizeOutput($formData['expiry_date']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="update_prices" id="update_prices" class="form-check-input"
                                               <?php echo $formData['update_prices'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="update_prices">
                                            Update prices to current values
                                        </label>
                                        <small class="form-text text-muted d-block">
                                            Use current product prices instead of original quote prices
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="update_quantities" id="update_quantities" class="form-check-input"
                                               <?php echo $formData['update_quantities'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="update_quantities">
                                            Allow quantity modifications
                                        </label>
                                        <small class="form-text text-muted d-block">
                                            Enable editing of item quantities
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Review -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Items Review</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Original Price</th>
                                            <th>Current Price</th>
                                            <th>Price Change</th>
                                            <th>Quantity</th>
                                            <th>Discount</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-table">
                                        <?php foreach ($formData['items'] as $productId => $item): ?>
                                        <tr data-product-id="<?php echo $productId; ?>">
                                            <td>
                                                <strong><?php echo sanitizeOutput($item['product_name']); ?></strong><br>
                                                <small class="text-muted"><?php echo sanitizeOutput($item['sku']); ?></small>
                                            </td>
                                            <td>$<?php echo number_format($item['original_price'], 2); ?></td>
                                            <td>$<?php echo number_format($item['current_price'], 2); ?></td>
                                            <td class="price-change">
                                                <?php 
                                                $priceChange = $item['current_price'] - $item['original_price'];
                                                $changeClass = $priceChange > 0 ? 'text-danger' : ($priceChange < 0 ? 'text-success' : 'text-muted');
                                                $changeIcon = $priceChange > 0 ? 'arrow-up' : ($priceChange < 0 ? 'arrow-down' : 'dash');
                                                ?>
                                                <span class="<?php echo $changeClass; ?>">
                                                    <i class="bi bi-<?php echo $changeIcon; ?>"></i>
                                                    $<?php echo number_format(abs($priceChange), 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <input type="number" name="quantities[<?php echo $productId; ?>]" 
                                                       class="form-control form-control-sm quantity-input" 
                                                       value="<?php echo $item['original_quantity']; ?>" 
                                                       min="1" disabled>
                                            </td>
                                            <td><?php echo number_format($item['discount'], 2); ?>%</td>
                                            <td class="item-total fw-bold">
                                                $<?php echo number_format(
                                                    ($item['original_price'] * $item['original_quantity']) * 
                                                    (1 - $item['discount'] / 100) * 
                                                    (1 + $item['tax_rate'] / 100), 2
                                                ); ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="6" class="text-end">Total Amount:</th>
                                            <th id="grand-total">$<?php echo number_format($parentQuote['total_amount'], 2); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo url('quotes', 'list'); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat"></i> Create Renewed Quote
                        </button>
                    </div>
                </form>
            </div>

            <!-- Summary Sidebar -->
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Renewal Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col">Original Total:</div>
                            <div class="col text-end">$<?php echo number_format($parentQuote['total_amount'], 2); ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">New Total:</div>
                            <div class="col text-end" id="new-total">$<?php echo number_format($parentQuote['total_amount'], 2); ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">Difference:</div>
                            <div class="col text-end" id="total-difference">$0.00</div>
                        </div>
                        <hr>
                        <div class="small text-muted">
                            <div class="d-flex justify-content-between">
                                <span>Items with price changes:</span>
                                <span id="price-changes-count">0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total items:</span>
                                <span><?php echo count($formData['items']); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="small text-muted">
                            <i class="bi bi-info-circle"></i>
                            The renewed quote will be created with DRAFT status and can be edited before sending to the client.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update calculations when settings change
        function updateCalculations() {
            const updatePrices = document.getElementById('update_prices').checked;
            const updateQuantities = document.getElementById('update_quantities').checked;
            
            // Enable/disable quantity inputs
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.disabled = !updateQuantities;
            });
            
            // Calculate new totals via AJAX
            const formData = new FormData();
            formData.append('ajax_action', 'calculate_renewal');
            formData.append('update_prices', updatePrices ? '1' : '0');
            formData.append('update_quantities', updateQuantities ? '1' : '0');
            
            // Add quantities
            document.querySelectorAll('.quantity-input').forEach(input => {
                formData.append(input.name, input.value);
            });
            
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateItemsTable(data.items);
                    updateSummary(data.grand_total);
                }
            })
            .catch(error => {
                console.error('Error calculating renewal:', error);
            });
        }
        
        // Update items table with new calculations
        function updateItemsTable(items) {
            let priceChangesCount = 0;
            
            Object.keys(items).forEach(productId => {
                const row = document.querySelector(`tr[data-product-id="${productId}"]`);
                const item = items[productId];
                
                if (row) {
                    // Update quantity display
                    row.querySelector('.quantity-input').value = item.quantity;
                    
                    // Update price change indicator
                    const priceChangeCell = row.querySelector('.price-change');
                    const priceChange = parseFloat(item.price_change);
                    
                    if (priceChange !== 0) {
                        priceChangesCount++;
                        const changeClass = priceChange > 0 ? 'text-danger' : 'text-success';
                        const changeIcon = priceChange > 0 ? 'arrow-up' : 'arrow-down';
                        priceChangeCell.innerHTML = `
                            <span class="${changeClass}">
                                <i class="bi bi-${changeIcon}"></i>
                                $${Math.abs(priceChange).toFixed(2)}
                            </span>
                        `;
                    } else {
                        priceChangeCell.innerHTML = '<span class="text-muted"><i class="bi bi-dash"></i> $0.00</span>';
                    }
                    
                    // Update item total
                    row.querySelector('.item-total').textContent = '$' + item.item_total;
                }
            });
            
            // Update price changes count
            document.getElementById('price-changes-count').textContent = priceChangesCount;
        }
        
        // Update summary section
        function updateSummary(grandTotal) {
            const originalTotal = <?php echo $parentQuote['total_amount']; ?>;
            const newTotal = parseFloat(grandTotal.replace(/[,$]/g, ''));
            const difference = newTotal - originalTotal;
            
            document.getElementById('new-total').textContent = '$' + grandTotal;
            document.getElementById('grand-total').textContent = '$' + grandTotal;
            
            const differenceElement = document.getElementById('total-difference');
            const differenceClass = difference > 0 ? 'text-danger' : (difference < 0 ? 'text-success' : 'text-muted');
            const differenceSign = difference > 0 ? '+' : '';
            
            differenceElement.textContent = differenceSign + '$' + difference.toFixed(2);
            differenceElement.className = 'col text-end ' + differenceClass;
        }
        
        // Form validation
        document.getElementById('renewalForm').addEventListener('submit', function(e) {
            const issueDate = document.getElementById('issue_date').value;
            const expiryDate = document.getElementById('expiry_date').value;
            
            if (!issueDate || !expiryDate) {
                e.preventDefault();
                alert('Please fill in both issue and expiry dates.');
                return false;
            }
            
            if (new Date(expiryDate) <= new Date(issueDate)) {
                e.preventDefault();
                alert('Expiry date must be after issue date.');
                return false;
            }
            
            return true;
        });
        
        // Auto-calculate expiry date when issue date changes
        document.getElementById('issue_date').addEventListener('change', function() {
            const issueDate = new Date(this.value);
            const expiryDate = new Date(issueDate);
            expiryDate.setDate(issueDate.getDate() + 7); // Default 7 days
            
            document.getElementById('expiry_date').value = expiryDate.toISOString().split('T')[0];
        });
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('issue_date').setAttribute('min', today);
            document.getElementById('expiry_date').setAttribute('min', today);
            
            // Attach event listeners
            document.getElementById('update_prices').addEventListener('change', updateCalculations);
            document.getElementById('update_quantities').addEventListener('change', updateCalculations);
            
            // Attach quantity input listeners
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('input', updateCalculations);
            });
            
            // Initial calculation
            updateCalculations();
        });
    </script>
</body>
</html>