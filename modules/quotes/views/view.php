<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput(__('quote_details')) ?> - <?= sanitizeOutput(__('app_name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><?= sanitizeOutput(__('quote_details')) ?></h2>
                        <p class="text-muted"><?= sanitizeOutput(__('quote_number')) ?>: <strong><?= sanitizeOutput($quote['quote_number']) ?></strong></p>
                    </div>
                    <div class="btn-group" role="group">
                        <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> <?= sanitizeOutput(__('back_to_list')) ?>
                        </a>
                        
                        <!-- PDF Download -->
                        <a href="<?= url('quotes', 'pdf', ['id' => $quote['quote_id']]) ?>" class="btn btn-outline-info" target="_blank">
                            <i class="bi bi-file-pdf"></i> <?= sanitizeOutput(__('download_pdf')) ?>
                        </a>
                        
                        <!-- Edit Button -->
                        <?php if ($canEdit): ?>
                        <a href="<?= url('quotes', 'edit', ['id' => $quote['quote_id']]) ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> <?= sanitizeOutput(__('edit')) ?>
                        </a>
                        <?php endif; ?>
                        
                        <!-- Send Button -->
                        <?php if ($canSend): ?>
                        <a href="<?= url('quotes', 'send', ['id' => $quote['quote_id']]) ?>" class="btn btn-primary">
                            <i class="bi bi-envelope"></i> <?= sanitizeOutput(__('send_quote')) ?>
                        </a>
                        <?php endif; ?>
                        
                        <!-- Action Dropdown -->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <?= sanitizeOutput(__('actions')) ?>
                            </button>
                            <ul class="dropdown-menu">
                                <!-- Approve/Reject -->
                                <?php if ($canApproveReject): ?>
                                <li>
                                    <button class="dropdown-item text-success approve-quote" 
                                            data-quote-id="<?= sanitizeOutput($quote['quote_id']) ?>"
                                            data-quote-number="<?= sanitizeOutput($quote['quote_number']) ?>">
                                        <i class="bi bi-check-lg"></i> <?= sanitizeOutput(__('approve')) ?>
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger reject-quote" 
                                            data-quote-id="<?= sanitizeOutput($quote['quote_id']) ?>"
                                            data-quote-number="<?= sanitizeOutput($quote['quote_number']) ?>">
                                        <i class="bi bi-x-lg"></i> <?= sanitizeOutput(__('reject')) ?>
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                
                                <!-- Renew -->
                                <?php if ($canRenew): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= url('quotes', 'renew', ['id' => $quote['quote_id']]) ?>">
                                        <i class="bi bi-arrow-clockwise"></i> <?= sanitizeOutput(__('renew_quote')) ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                                
                                <!-- Duplicate -->
                                <li>
                                    <button class="dropdown-item duplicate-quote" 
                                            data-quote-id="<?= sanitizeOutput($quote['quote_id']) ?>"
                                            data-quote-number="<?= sanitizeOutput($quote['quote_number']) ?>">
                                        <i class="bi bi-files"></i> <?= sanitizeOutput(__('duplicate')) ?>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= sanitizeOutput($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= sanitizeOutput($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <!-- Quote Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-file-text"></i> <?= sanitizeOutput(__('quote_information')) ?>
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
                                                    <?php if ($isExpired): ?>
                                                        <span class="badge bg-warning text-dark ms-1"><?= sanitizeOutput(__('expired')) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold"><?= sanitizeOutput(__('total_amount')) ?>:</td>
                                                <td class="fw-bold text-primary"><?= sanitizeOutput(formatCurrency($quote['total_amount'])) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td class="fw-bold"><?= sanitizeOutput(__('issue_date')) ?>:</td>
                                                <td><?= sanitizeOutput(formatDate($quote['issue_date'], 'd/m/Y')) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold"><?= sanitizeOutput(__('expiry_date')) ?>:</td>
                                                <td><?= sanitizeOutput(formatDate($quote['expiry_date'], 'd/m/Y')) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold"><?= sanitizeOutput(__('created_by')) ?>:</td>
                                                <td><?= sanitizeOutput($user['display_name'] ?? $user['username']) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quote Items -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-list-ul"></i> <?= sanitizeOutput(__('quote_items')) ?>
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($quote['items'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><?= sanitizeOutput(__('product')) ?></th>
                                                    <th class="text-center"><?= sanitizeOutput(__('quantity')) ?></th>
                                                    <th class="text-end"><?= sanitizeOutput(__('unit_price')) ?></th>
                                                    <th class="text-center"><?= sanitizeOutput(__('discount')) ?></th>
                                                    <th class="text-end"><?= sanitizeOutput(__('tax_amount')) ?></th>
                                                    <th class="text-end"><?= sanitizeOutput(__('subtotal')) ?></th>
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
                                                        <div>
                                                            <strong><?= sanitizeOutput($item['product_name']) ?></strong>
                                                            <br><small class="text-muted">SKU: <?= sanitizeOutput($item['sku']) ?></small>
                                                        </div>
                                                    </td>
                                                    <td class="text-center"><?= sanitizeOutput($item['quantity']) ?></td>
                                                    <td class="text-end"><?= sanitizeOutput(formatCurrency($item['unit_price'])) ?></td>
                                                    <td class="text-center">
                                                        <?= $item['discount'] > 0 ? sanitizeOutput($item['discount']) . '%' : '-' ?>
                                                    </td>
                                                    <td class="text-end"><?= sanitizeOutput(formatCurrency($item['tax_amount'])) ?></td>
                                                    <td class="text-end fw-bold"><?= sanitizeOutput(formatCurrency($item['subtotal'])) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th colspan="5" class="text-end"><?= sanitizeOutput(__('subtotal')) ?>:</th>
                                                    <th class="text-end"><?= sanitizeOutput(formatCurrency($totalBeforeDiscount)) ?></th>
                                                </tr>
                                                <?php if ($totalDiscount > 0): ?>
                                                <tr>
                                                    <th colspan="5" class="text-end"><?= sanitizeOutput(__('discount')) ?>:</th>
                                                    <th class="text-end text-danger">-<?= sanitizeOutput(formatCurrency($totalDiscount)) ?></th>
                                                </tr>
                                                <?php endif; ?>
                                                <tr>
                                                    <th colspan="5" class="text-end"><?= sanitizeOutput(__('tax')) ?>:</th>
                                                    <th class="text-end"><?= sanitizeOutput(formatCurrency($totalTax)) ?></th>
                                                </tr>
                                                <tr class="table-primary">
                                                    <th colspan="5" class="text-end"><?= sanitizeOutput(__('total')) ?>:</th>
                                                    <th class="text-end"><?= sanitizeOutput(formatCurrency($grandTotal)) ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-cart-x display-4 text-muted"></i>
                                        <p class="text-muted mt-2"><?= sanitizeOutput(__('no_items_found')) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Client Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-building"></i> <?= sanitizeOutput(__('client_information')) ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold"><?= sanitizeOutput($client['company_name']) ?></h6>
                                <p class="mb-1"><?= sanitizeOutput($client['contact_name']) ?></p>
                                <p class="mb-1">
                                    <i class="bi bi-envelope"></i> 
                                    <a href="mailto:<?= sanitizeOutput($client['email']) ?>"><?= sanitizeOutput($client['email']) ?></a>
                                </p>
                                <?php if ($client['phone']): ?>
                                <p class="mb-1">
                                    <i class="bi bi-telephone"></i> <?= sanitizeOutput($client['phone']) ?>
                                </p>
                                <?php endif; ?>
                                <?php if ($client['address']): ?>
                                <p class="mb-1">
                                    <i class="bi bi-geo-alt"></i> <?= sanitizeOutput($client['address']) ?>
                                </p>
                                <?php endif; ?>
                                <?php if ($client['tax_id']): ?>
                                <p class="mb-0">
                                    <strong><?= sanitizeOutput(__('tax_id')) ?>:</strong> <?= sanitizeOutput($client['tax_id']) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quote Timeline -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-clock-history"></i> <?= sanitizeOutput(__('quote_timeline')) ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title"><?= sanitizeOutput(__('quote_created')) ?></h6>
                                            <p class="timeline-description">
                                                <?= sanitizeOutput(formatDate($quote['created_at'], 'd/m/Y H:i')) ?><br>
                                                <small class="text-muted"><?= sanitizeOutput(__('by')) ?> <?= sanitizeOutput($user['display_name'] ?? $user['username']) ?></small>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <?php if ($quote['status'] !== 'DRAFT'): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title"><?= sanitizeOutput(__('quote_sent')) ?></h6>
                                            <p class="timeline-description">
                                                <?= sanitizeOutput(formatDate($quote['updated_at'], 'd/m/Y H:i')) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (in_array($quote['status'], ['APPROVED', 'REJECTED'])): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker <?= $quote['status'] === 'APPROVED' ? 'bg-success' : 'bg-danger' ?>"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">
                                                <?= sanitizeOutput($quote['status'] === 'APPROVED' ? __('quote_approved') : __('quote_rejected')) ?>
                                            </h6>
                                            <p class="timeline-description">
                                                <?= sanitizeOutput(formatDate($quote['updated_at'], 'd/m/Y H:i')) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
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
        const csrfToken = '<?= $csrfToken ?? generateCSRFToken() ?>';
    </script>
    <script src="assets/js/quotes.js"></script>
    
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-marker {
            position: absolute;
            left: -26px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid white;
        }
        .timeline-title {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .timeline-description {
            font-size: 12px;
            margin-bottom: 0;
        }
    </style>
</body>
</html>