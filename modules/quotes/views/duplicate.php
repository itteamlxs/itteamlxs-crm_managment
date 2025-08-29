<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput(__('duplicate_quote')) ?> - <?= sanitizeOutput(__('app_name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><?= sanitizeOutput(__('duplicate_quote')) ?></h2>
                        <p class="text-muted"><?= sanitizeOutput(__('duplicate_quote_description')) ?></p>
                    </div>
                    <div class="btn-group">
                        <a href="<?= url('quotes', 'view', ['id' => $quote['quote_id']]) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> <?= sanitizeOutput(__('back_to_quote')) ?>
                        </a>
                        <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary">
                            <?= sanitizeOutput(__('back_to_list')) ?>
                        </a>
                    </div>
                </div>

                <!-- Original Quote Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-file-text"></i> <?= sanitizeOutput(__('original_quote_details')) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('quote_number')) ?>:</td>
                                        <td><?= sanitizeOutput($quote['quote_number']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('client')) ?>:</td>
                                        <td><?= sanitizeOutput($client['company_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('status')) ?>:</td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'DRAFT' => 'bg-secondary',
                                                'SENT' => 'bg-info',
                                                'APPROVED' => 'bg-success',
                                                'REJECTED' => 'bg-danger'
                                            ][$quote['status']] ?? 'bg-secondary';
                                            ?>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= sanitizeOutput(__(strtolower($quote['status']))) ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('total_amount')) ?>:</td>
                                        <td class="fw-bold"><?= sanitizeOutput(formatCurrency($quote['total_amount'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('issue_date')) ?>:</td>
                                        <td><?= sanitizeOutput(formatDate($quote['issue_date'], 'd/m/Y')) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('expiry_date')) ?>:</td>
                                        <td><?= sanitizeOutput(formatDate($quote['expiry_date'], 'd/m/Y')) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Preview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul"></i> <?= sanitizeOutput(__('items_to_duplicate')) ?>
                            <span class="badge bg-primary ms-2"><?= count($quote['items']) ?> <?= sanitizeOutput(__('items')) ?></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($quote['items'])): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th><?= sanitizeOutput(__('product')) ?></th>
                                            <th class="text-center"><?= sanitizeOutput(__('quantity')) ?></th>
                                            <th class="text-end"><?= sanitizeOutput(__('unit_price')) ?></th>
                                            <th class="text-center"><?= sanitizeOutput(__('discount')) ?></th>
                                            <th class="text-end"><?= sanitizeOutput(__('subtotal')) ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($quote['items'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= sanitizeOutput($item['product_name']) ?></strong>
                                                    <br><small class="text-muted">SKU: <?= sanitizeOutput($item['sku']) ?></small>
                                                </td>
                                                <td class="text-center"><?= sanitizeOutput($item['quantity']) ?></td>
                                                <td class="text-end"><?= sanitizeOutput(formatCurrency($item['unit_price'])) ?></td>
                                                <td class="text-center">
                                                    <?= $item['discount'] > 0 ? sanitizeOutput($item['discount']) . '%' : '-' ?>
                                                </td>
                                                <td class="text-end"><?= sanitizeOutput(formatCurrency($item['subtotal'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end"><?= sanitizeOutput(__('total')) ?>:</th>
                                            <th class="text-end"><?= sanitizeOutput(formatCurrency($quote['total_amount'])) ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted"><?= sanitizeOutput(__('no_items_found')) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Duplication Confirmation -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-files"></i> <?= sanitizeOutput(__('duplicate_confirmation')) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong><?= sanitizeOutput(__('duplication_notice')) ?>:</strong>
                            <?= sanitizeOutput(__('duplication_notice_text')) ?>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong><?= sanitizeOutput(__('what_will_be_created')) ?>:</strong>
                            <ul class="mb-0 mt-2">
                                <li><?= sanitizeOutput(__('new_quote_number_generated')) ?></li>
                                <li><?= sanitizeOutput(__('status_will_be_draft')) ?></li>
                                <li><?= sanitizeOutput(__('new_issue_date_today')) ?></li>
                                <li><?= sanitizeOutput(__('new_expiry_date_7_days')) ?></li>
                                <li><?= sanitizeOutput(__('same_client_and_items')) ?></li>
                                <li><?= sanitizeOutput(__('you_can_edit_after_creation')) ?></li>
                            </ul>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= url('quotes', 'view', ['id' => $quote['quote_id']]) ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> <?= sanitizeOutput(__('cancel')) ?>
                            </a>
                            <button type="button" class="btn btn-success" id="duplicateBtn" onclick="duplicateQuote()">
                                <i class="bi bi-files"></i> <?= sanitizeOutput(__('confirm_duplicate')) ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        const csrfToken = '<?= sanitizeOutput($csrfToken) ?>';
        const quoteId = <?= $quote['quote_id'] ?>;
        
        function duplicateQuote() {
            const duplicateBtn = document.getElementById('duplicateBtn');
            
            // Disable button and show loading
            duplicateBtn.disabled = true;
            duplicateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?= sanitizeOutput(__('processing')) ?>...';
            
            const formData = new FormData();
            formData.append('quote_id', quoteId);
            formData.append('csrf_token', csrfToken);
            
            fetch('<?= url('quotes', 'duplicate') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message || 'Cotización duplicada exitosamente', 'success');
                    
                    // Redirect to edit the new quote
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '<?= url('quotes', 'list') ?>';
                    }, 2000);
                } else {
                    showAlert(data.error || 'Error al duplicar la cotización', 'danger');
                    
                    // Re-enable button
                    duplicateBtn.disabled = false;
                    duplicateBtn.innerHTML = '<i class="bi bi-files"></i> <?= sanitizeOutput(__('confirm_duplicate')) ?>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error de conexión', 'danger');
                
                // Re-enable button
                duplicateBtn.disabled = false;
                duplicateBtn.innerHTML = '<i class="bi bi-files"></i> <?= sanitizeOutput(__('confirm_duplicate')) ?>';
            });
        }
        
        function showAlert(message, type = 'info') {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert at top of container
            const container = document.querySelector('.container-fluid');
            if (container) {
                container.insertBefore(alertDiv, container.firstChild);
            }
            
            // Auto-dismiss after 5 seconds for success messages
            if (type === 'success') {
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        }
    </script>
</body>
</html>