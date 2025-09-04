<!DOCTYPE html>
<html lang="<?php echo getUserLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('approve_quote'); ?> - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0"><?php echo __('approve_quote'); ?></h1>
                    <a href="<?php echo url('quotes', 'view', ['id' => $quoteId]); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> <?php echo __('back_to_quote'); ?>
                    </a>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h6><?php echo __('approval_blocked'); ?>:</h6>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo sanitizeOutput($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><?php echo __('quote_information'); ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5"><?php echo __('quote_number'); ?>:</dt>
                                            <dd class="col-sm-7"><?php echo sanitizeOutput($quote['quote_number']); ?></dd>
                                            
                                            <dt class="col-sm-5"><?php echo __('client'); ?>:</dt>
                                            <dd class="col-sm-7"><?php echo sanitizeOutput($quote['company_name']); ?></dd>
                                            
                                            <dt class="col-sm-5"><?php echo __('contact'); ?>:</dt>
                                            <dd class="col-sm-7"><?php echo sanitizeOutput($quote['contact_name']); ?></dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5"><?php echo __('issue_date'); ?>:</dt>
                                            <dd class="col-sm-7"><?php echo formatDate($quote['issue_date']); ?></dd>
                                            
                                            <dt class="col-sm-5"><?php echo __('expiry_date'); ?>:</dt>
                                            <dd class="col-sm-7"><?php echo formatDate($quote['expiry_date']); ?></dd>
                                            
                                            <dt class="col-sm-5"><?php echo __('total_amount'); ?>:</dt>
                                            <dd class="col-sm-7"><strong><?php echo formatCurrency($quote['total_amount']); ?></strong></dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><?php echo __('stock_verification'); ?></h5>
                            </div>
                            <div class="card-body">
                                <?php if ($stockCheck['can_approve']): ?>
                                    <div class="alert alert-success d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <div>
                                            <strong><?php echo __('stock_available'); ?></strong><br>
                                            <?php echo __('all_items_in_stock'); ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-danger d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <div>
                                            <strong><?php echo __('insufficient_stock'); ?></strong><br>
                                            <?php echo __('some_items_insufficient_stock'); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th><?php echo __('product'); ?></th>
                                                <th><?php echo __('sku'); ?></th>
                                                <th class="text-center"><?php echo __('required'); ?></th>
                                                <th class="text-center"><?php echo __('available'); ?></th>
                                                <th class="text-center"><?php echo __('status'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($quoteItems as $item): ?>
                                                <?php
                                                    $hasStock = true;
                                                    foreach ($stockCheck['insufficient_stock'] as $insufficient) {
                                                        if ($insufficient['product_name'] === $item['product_name']) {
                                                            $hasStock = false;
                                                            break;
                                                        }
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?php echo sanitizeOutput($item['product_name']); ?></td>
                                                    <td><?php echo sanitizeOutput($item['sku']); ?></td>
                                                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                                                    <td class="text-center">
                                                        <?php if (!$hasStock): ?>
                                                            <?php foreach ($stockCheck['insufficient_stock'] as $insufficient): ?>
                                                                <?php if ($insufficient['product_name'] === $item['product_name']): ?>
                                                                    <?php echo $insufficient['available']; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <?php echo $item['quantity']; ?>+
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($hasStock): ?>
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check"></i> <?php echo __('available'); ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">
                                                                <i class="bi bi-x"></i> <?php echo __('insufficient'); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><?php echo __('approval_actions'); ?></h5>
                            </div>
                            <div class="card-body">
                                <?php if ($stockCheck['can_approve']): ?>
                                    <div class="alert alert-info">
                                        <h6><?php echo __('approval_effects'); ?>:</h6>
                                        <ul class="mb-0 small">
                                            <li><?php echo __('quote_status_approved'); ?></li>
                                            <li><?php echo __('stock_will_be_reduced'); ?></li>
                                            <li><?php echo __('client_activity_logged'); ?></li>
                                            <li><?php echo __('audit_trail_created'); ?></li>
                                        </ul>
                                    </div>

                                    <form method="post" onsubmit="return confirm('<?php echo __('confirm_approve_quote'); ?>');">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="bi bi-check-circle"></i> <?php echo __('approve_quote'); ?>
                                            </button>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        <h6><?php echo __('cannot_approve'); ?>:</h6>
                                        <p class="mb-0 small"><?php echo __('resolve_stock_issues_first'); ?></p>
                                    </div>

                                    <div class="d-grid">
                                        <button type="button" class="btn btn-secondary" disabled>
                                            <i class="bi bi-x-circle"></i> <?php echo __('approval_blocked'); ?>
                                        </button>
                                    </div>

                                    <div class="mt-3">
                                        <h6><?php echo __('suggested_actions'); ?>:</h6>
                                        <ul class="small">
                                            <li><?php echo __('update_stock_levels'); ?></li>
                                            <li><?php echo __('reduce_quote_quantities'); ?></li>
                                            <li><?php echo __('contact_supplier'); ?></li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0"><?php echo __('quote_summary'); ?></h6>
                            </div>
                            <div class="card-body">
                                <?php
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
                                ?>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span><?php echo __('subtotal'); ?>:</span>
                                    <span><?php echo formatCurrency($subtotal); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span><?php echo __('discount'); ?>:</span>
                                    <span>-<?php echo formatCurrency($totalDiscount); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span><?php echo __('tax'); ?>:</span>
                                    <span><?php echo formatCurrency($totalTax); ?></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong><?php echo __('total'); ?>:</strong>
                                    <strong class="text-primary"><?php echo formatCurrency($total); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>