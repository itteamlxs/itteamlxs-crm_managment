<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput(__('renew_quote')) ?> - <?= sanitizeOutput(__('app_name')) ?></title>
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
                        <h2><?= sanitizeOutput(__('renew_quote')) ?></h2>
                        <p class="text-muted"><?= sanitizeOutput(__('renew_quote_description')) ?></p>
                    </div>
                    <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> <?= sanitizeOutput(__('back_to_list')) ?>
                    </a>
                </div>

                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= sanitizeOutput($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

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
                                        <td><?= sanitizeOutput($originalQuote['quote_number']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('client')) ?>:</td>
                                        <td><?= sanitizeOutput($clientName) ?></td>
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
                                            ][$originalQuote['status']] ?? 'bg-secondary';
                                            ?>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= sanitizeOutput(__(strtolower($originalQuote['status']))) ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('total_amount')) ?>:</td>
                                        <td><?= sanitizeOutput(formatCurrency($originalQuote['total_amount'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('issue_date')) ?>:</td>
                                        <td><?= sanitizeOutput(formatDate($originalQuote['issue_date'], 'd/m/Y')) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('expiry_date')) ?>:</td>
                                        <td>
                                            <?= sanitizeOutput(formatDate($originalQuote['expiry_date'], 'd/m/Y')) ?>
                                            <?php if (strtotime($originalQuote['expiry_date']) < time()): ?>
                                                <span class="badge bg-warning text-dark ms-1"><?= sanitizeOutput(__('expired')) ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quote Items Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul"></i> <?= sanitizeOutput(__('quote_items_summary')) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($originalQuote['items'])): ?>
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
                                        <?php foreach ($originalQuote['items'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= sanitizeOutput($item['product_name']) ?></strong>
                                                    <br><small class="text-muted"><?= sanitizeOutput($item['sku']) ?></small>
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
                                            <th class="text-end"><?= sanitizeOutput(formatCurrency($originalQuote['total_amount'])) ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted"><?= sanitizeOutput(__('no_items_found')) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Renewal Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-arrow-clockwise"></i> <?= sanitizeOutput(__('renewal_settings')) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="renewForm" method="POST" action="<?= url('quotes', 'renew') ?>">
                            <input type="hidden" name="csrf_token" value="<?= sanitizeOutput($csrfToken) ?>">
                            <input type="hidden" name="quote_id" value="<?= sanitizeOutput($originalQuote['quote_id']) ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expiry_days" class="form-label">
                                            <?= sanitizeOutput(__('new_expiry_days')) ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control" id="expiry_days" 
                                               name="expiry_days" value="7" min="1" max="365" required>
                                        <div class="form-text"><?= sanitizeOutput(__('renewal_expiry_help')) ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><?= sanitizeOutput(__('new_expiry_date')) ?></label>
                                        <input type="text" class="form-control" id="calculated_expiry" readonly>
                                        <div class="form-text"><?= sanitizeOutput(__('calculated_based_on_days')) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong><?= sanitizeOutput(__('renewal_notice')) ?>:</strong>
                                <?= sanitizeOutput(__('renewal_notice_text')) ?>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary">
                                    <?= sanitizeOutput(__('cancel')) ?>
                                </a>
                                <button type="submit" class="btn btn-success" id="renewBtn">
                                    <i class="bi bi-arrow-clockwise"></i> <?= sanitizeOutput(__('renew_quote')) ?>
                                </button>
                            </div>
                        </form>
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
        
        // Calculate expiry date based on days
        function updateExpiryDate() {
            const days = parseInt(document.getElementById('expiry_days').value) || 7;
            const today = new Date();
            const expiryDate = new Date(today.getTime() + (days * 24 * 60 * 60 * 1000));
            
            const options = { 
                year: 'numeric', 
                month: '2-digit', 
                day: '2-digit' 
            };
            
            document.getElementById('calculated_expiry').value = expiryDate.toLocaleDateString('es-ES', options);
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateExpiryDate();
            
            // Update expiry date when days change
            document.getElementById('expiry_days').addEventListener('input', updateExpiryDate);
            
            // Form submission
            document.getElementById('renewForm').addEventListener('submit', function(e) {
                const renewBtn = document.getElementById('renewBtn');
                renewBtn.disabled = true;
                renewBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?= sanitizeOutput(__('processing')) ?>...';
            });
        });
    </script>
</body>
</html>