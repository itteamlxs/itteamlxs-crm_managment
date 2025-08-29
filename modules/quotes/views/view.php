<!DOCTYPE html>
<html lang="<?= getUserLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('quote_details') ?> - <?= sanitizeOutput($quote['quote_number']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .action-buttons .btn {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Success Message -->
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?= sanitizeOutput($successMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?= sanitizeOutput($errorMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><?= __('quote') ?> #<?= sanitizeOutput($quote['quote_number']) ?></h2>
                <p class="text-muted mb-0"><?= __('created_by') ?>: <?= sanitizeOutput($quote['created_by_name']) ?></p>
            </div>
            <span class="badge status-badge bg-<?= getQuoteStatusColor($quote['status']) ?>">
                <?= __('status_' . strtolower($quote['status'])) ?>
            </span>
        </div>

        <div class="row">
            <!-- Quote Details -->
            <div class="col-lg-8">
                <!-- Client Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-building"></i> <?= __('client_information') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><?= __('company') ?>:</strong><br>
                                <?= sanitizeOutput($quote['company_name']) ?>
                            </div>
                            <div class="col-md-6">
                                <strong><?= __('contact') ?>:</strong><br>
                                <?= sanitizeOutput($quote['contact_name']) ?>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong><?= __('email') ?>:</strong><br>
                                <a href="mailto:<?= sanitizeOutput($quote['client_email']) ?>">
                                    <?= sanitizeOutput($quote['client_email']) ?>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <strong><?= __('phone') ?>:</strong><br>
                                <?= sanitizeOutput($quote['client_phone'] ?: __('not_provided')) ?>
                            </div>
                        </div>
                        <?php if (!empty($quote['client_address'])): ?>
                            <div class="mt-3">
                                <strong><?= __('address') ?>:</strong><br>
                                <?= nl2br(sanitizeOutput($quote['client_address'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quote Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-calendar"></i> <?= __('quote_information') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong><?= __('issue_date') ?>:</strong><br>
                                <?= formatDate($quote['issue_date'], 'Y-m-d') ?>
                            </div>
                            <div class="col-md-4">
                                <strong><?= __('expiry_date') ?>:</strong><br>
                                <span class="<?= strtotime($quote['expiry_date']) < time() ? 'text-danger' : '' ?>">
                                    <?= formatDate($quote['expiry_date'], 'Y-m-d') ?>
                                    <?php if (strtotime($quote['expiry_date']) < time()): ?>
                                        <i class="bi bi-exclamation-triangle text-danger" title="<?= __('expired') ?>"></i>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="col-md-4">
                                <strong><?= __('created_at') ?>:</strong><br>
                                <?= formatDate($quote['created_at']) ?>
                            </div>
                        </div>
                        <?php if (!empty($quote['parent_quote_id'])): ?>
                            <div class="mt-3">
                                <div class="alert alert-info">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    <?= __('this_is_renewal_of_quote') ?>
                                    <a href="<?= url('quotes', 'view', ['id' => $quote['parent_quote_id']]) ?>">
                                        #<?= $quote['parent_quote_id'] ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quote Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> <?= __('quote_items') ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($quoteItems)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="text-muted mt-2"><?= __('no_items_found') ?></p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><?= __('product') ?></th>
                                            <th class="text-center"><?= __('quantity') ?></th>
                                            <th class="text-end"><?= __('unit_price') ?></th>
                                            <th class="text-center"><?= __('discount') ?></th>
                                            <th class="text-end"><?= __('tax_amount') ?></th>
                                            <th class="text-end"><?= __('subtotal') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($quoteItems as $item): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= sanitizeOutput($item['product_name']) ?></strong><br>
                                                    <small class="text-muted"><?= __('sku') ?>: <?= sanitizeOutput($item['sku']) ?></small>
                                                </td>
                                                <td class="text-center"><?= (int)$item['quantity'] ?></td>
                                                <td class="text-end"><?= formatCurrency($item['unit_price']) ?></td>
                                                <td class="text-center">
                                                    <?php if ($item['discount'] > 0): ?>
                                                        <span class="badge bg-warning">
                                                            <?= number_format($item['discount'], 1) ?>%
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end"><?= formatCurrency($item['tax_amount']) ?></td>
                                                <td class="text-end">
                                                    <strong><?= formatCurrency($item['subtotal']) ?></strong>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Action Buttons -->
                <div class="card mb-4 no-print">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-gear"></i> <?= __('actions') ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="action-buttons">
                            <a href="<?= url('quotes', 'list') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> <?= __('back_to_list') ?>
                            </a>
                            
                            <?php if (hasPermission('create_quotes') && $quote['status'] === 'DRAFT'): ?>
                                <a href="<?= url('quotes', 'edit', ['id' => $quoteId]) ?>" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> <?= __('edit') ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (hasPermission('create_quotes')): ?>
                                <a href="<?= url('quotes', 'duplicate', ['id' => $quoteId]) ?>" class="btn btn-info">
                                    <i class="bi bi-files"></i> <?= __('duplicate') ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (hasPermission('renew_quotes') && in_array($quote['status'], ['APPROVED', 'REJECTED'])): ?>
                                <a href="<?= url('quotes', 'renew', ['id' => $quoteId]) ?>" class="btn btn-warning">
                                    <i class="bi bi-arrow-clockwise"></i> <?= __('renew') ?>
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?= url('quotes', 'pdf', ['id' => $quoteId]) ?>" class="btn btn-danger" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> <?= __('download_pdf') ?>
                            </a>
                            
                            <?php if ($quote['status'] === 'DRAFT'): ?>
                                <a href="<?= url('quotes', 'send', ['id' => $quoteId]) ?>" class="btn btn-success">
                                    <i class="bi bi-envelope"></i> <?= __('send_to_client') ?>
                                </a>
                            <?php endif; ?>
                            
                            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="bi bi-printer"></i> <?= __('print') ?>
                            </button>
                        </div>
                        
                        <!-- Status Update -->
                        <?php if (hasPermission('create_quotes') && in_array($quote['status'], ['DRAFT', 'SENT'])): ?>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label"><?= __('update_status') ?>:</label>
                                <div class="btn-group d-flex" role="group">
                                    <?php if ($quote['status'] === 'DRAFT'): ?>
                                        <button type="button" class="btn btn-outline-primary" onclick="updateStatus('SENT')">
                                            <?= __('mark_as_sent') ?>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="updateStatus('REJECTED')">
                                            <?= __('reject') ?>
                                        </button>
                                    <?php elseif ($quote['status'] === 'SENT'): ?>
                                        <button type="button" class="btn btn-outline-success" onclick="updateStatus('APPROVED')">
                                            <?= __('approve') ?>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="updateStatus('REJECTED')">
                                            <?= __('reject') ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quote Summary -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-calculator"></i> <?= __('quote_summary') ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= __('subtotal') ?>:</span>
                                <span><?= formatCurrency($subtotal) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= __('discount') ?>:</span>
                                <span class="text-success">-<?= formatCurrency($totalDiscount) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span><?= __('tax') ?>:</span>
                                <span><?= formatCurrency($totalTax) ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5><?= __('total') ?>:</h5>
                                <h5 class="text-primary"><?= formatCurrency($quote['total_amount']) ?></h5>
                            </div>
                            
                            <?php if (abs($calculatedTotal - $quote['total_amount']) > 0.01): ?>
                                <div class="alert alert-warning mt-2 small">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <?= __('calculated_total_mismatch') ?>: <?= formatCurrency($calculatedTotal) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = '<?= $csrfToken ?>';
        const quoteId = <?= $quoteId ?>;
        
        function updateStatus(newStatus) {
            if (!confirm('<?= __('confirm_status_change') ?>')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('status', newStatus);
            formData.append('csrf_token', csrfToken);
            
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
                    showAlert('success', data.success);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    let errorMsg = data.error || 'Error updating status';
                    if (data.details && data.details.length > 0) {
                        errorMsg += '\n\n' + '<?= __('insufficient_stock_details') ?>:\n';
                        data.details.forEach(detail => {
                            errorMsg += `- ${detail.product_name}: <?= __('required') ?> ${detail.required}, <?= __('available') ?> ${detail.available}\n`;
                        });
                    }
                    showAlert('danger', errorMsg);
                }
            })
            .catch(error => {
                showAlert('danger', '<?= __('network_error') ?>');
                console.error('Error:', error);
            });
        }
        
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message.replace(/\n/g, '<br>')}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>

<?php
/**
 * Helper function for quote status colors
 */
function getQuoteStatusColor($status) {
    switch($status) {
        case 'DRAFT': return 'secondary';
        case 'SENT': return 'primary';
        case 'APPROVED': return 'success';
        case 'REJECTED': return 'danger';
        default: return 'secondary';
    }
}
?>