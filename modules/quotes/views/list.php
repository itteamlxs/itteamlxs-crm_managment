<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput(__('quotes_management')) ?> - <?= sanitizeOutput(__('app_name')) ?></title>
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
                        <h2><?= sanitizeOutput(__('quotes_management')) ?></h2>
                        <p class="text-muted"><?= sanitizeOutput(__('manage_quotes_description')) ?></p>
                    </div>
                    <div class="btn-group">
                        <a href="<?= url('dashboard', 'index') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-house"></i> <?= sanitizeOutput(__('dashboard')) ?>
                        </a>
                        <?php if ($canCreateQuotes): ?>
                        <a href="<?= url('quotes', 'create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> <?= sanitizeOutput(__('create_quote')) ?>
                        </a>
                        <?php endif; ?>
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

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="module" value="quotes">
                            <input type="hidden" name="action" value="list">
                            
                            <div class="col-md-4">
                                <label for="search" class="form-label"><?= sanitizeOutput(__('search')) ?></label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="<?= sanitizeOutput($search) ?>" 
                                       placeholder="<?= sanitizeOutput(__('search_quotes_placeholder')) ?>">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="status" class="form-label"><?= sanitizeOutput(__('status')) ?></label>
                                <select class="form-select" id="status" name="status">
                                    <?php foreach ($statusOptions as $value => $label): ?>
                                        <option value="<?= sanitizeOutput($value) ?>" 
                                                <?= $status === $value ? 'selected' : '' ?>>
                                            <?= sanitizeOutput(__($label)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="limit" class="form-label"><?= sanitizeOutput(__('per_page')) ?></label>
                                <select class="form-select" id="limit" name="limit">
                                    <option value="10" <?= $limit === 10 ? 'selected' : '' ?>>10</option>
                                    <option value="25" <?= $limit === 25 ? 'selected' : '' ?>>25</option>
                                    <option value="50" <?= $limit === 50 ? 'selected' : '' ?>>50</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary me-2">
                                    <i class="bi bi-search"></i> <?= sanitizeOutput(__('search')) ?>
                                </button>
                                <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary">
                                    <?= sanitizeOutput(__('clear')) ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quotes Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?= sanitizeOutput(__('quotes_list')) ?></h5>
                        <small class="text-muted">
                            <?= sanitizeOutput(__('showing_results', ['start' => (($page - 1) * $limit) + 1, 'end' => min($page * $limit, $totalCount), 'total' => $totalCount])) ?>
                        </small>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($quotes)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th><?= sanitizeOutput(__('quote_number')) ?></th>
                                            <th><?= sanitizeOutput(__('client')) ?></th>
                                            <th><?= sanitizeOutput(__('status')) ?></th>
                                            <th><?= sanitizeOutput(__('total_amount')) ?></th>
                                            <th><?= sanitizeOutput(__('issue_date')) ?></th>
                                            <th><?= sanitizeOutput(__('expiry_date')) ?></th>
                                            <th><?= sanitizeOutput(__('seller')) ?></th>
                                            <th><?= sanitizeOutput(__('actions')) ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($quotes as $quote): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= url('quotes', 'view', ['id' => $quote['quote_id']]) ?>" class="text-decoration-none">
                                                        <strong><?= sanitizeOutput($quote['quote_number']) ?></strong>
                                                    </a>
                                                </td>
                                                <td><?= sanitizeOutput($quote['client_name']) ?></td>
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
                                                <td><?= sanitizeOutput(formatCurrency($quote['total_amount'])) ?></td>
                                                <td><?= sanitizeOutput(formatDate($quote['issue_date'], 'd/m/Y')) ?></td>
                                                <td>
                                                    <?= sanitizeOutput(formatDate($quote['expiry_date'], 'd/m/Y')) ?>
                                                    <?php if ($quote['status'] === 'SENT' && strtotime($quote['expiry_date']) < time()): ?>
                                                        <i class="bi bi-exclamation-triangle text-warning" title="<?= sanitizeOutput(__('expired')) ?>"></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= sanitizeOutput($quote['username']) ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <!-- View button -->
                                                        <a href="<?= url('quotes', 'view', ['id' => $quote['quote_id']]) ?>" 
                                                           class="btn btn-outline-info btn-sm" 
                                                           title="<?= sanitizeOutput(__('view_details')) ?>">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        
                                                        <!-- Edit button for DRAFT quotes -->
                                                        <?php if ($quote['status'] === 'DRAFT' && $canCreateQuotes): ?>
                                                            <a href="<?= url('quotes', 'edit', ['id' => $quote['quote_id']]) ?>" 
                                                               class="btn btn-outline-primary btn-sm" 
                                                               title="<?= sanitizeOutput(__('edit_quote')) ?>">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <!-- Send button for DRAFT quotes -->
                                                        <?php if ($quote['status'] === 'DRAFT' && $canCreateQuotes): ?>
                                                            <a href="<?= url('quotes', 'send', ['id' => $quote['quote_id']]) ?>" 
                                                               class="btn btn-outline-warning btn-sm" 
                                                               title="<?= sanitizeOutput(__('send_quote')) ?>">
                                                                <i class="bi bi-envelope"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <!-- PDF download button -->
                                                        <a href="<?= url('quotes', 'pdf', ['id' => $quote['quote_id']]) ?>" 
                                                           class="btn btn-outline-secondary btn-sm" 
                                                           title="<?= sanitizeOutput(__('download_pdf')) ?>" target="_blank">
                                                            <i class="bi bi-file-pdf"></i>
                                                        </a>
                                                        
                                                        <!-- Approve/Reject actions for SENT quotes -->
                                                        <?php if ($quote['status'] === 'SENT' && $canCreateQuotes): ?>
                                                            <button type="button" class="btn btn-success btn-sm approve-quote" 
                                                                    data-quote-id="<?= sanitizeOutput($quote['quote_id']) ?>"
                                                                    data-quote-number="<?= sanitizeOutput($quote['quote_number']) ?>"
                                                                    title="<?= sanitizeOutput(__('approve_quote')) ?>">
                                                                <i class="bi bi-check-lg"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm reject-quote" 
                                                                    data-quote-id="<?= sanitizeOutput($quote['quote_id']) ?>"
                                                                    data-quote-number="<?= sanitizeOutput($quote['quote_number']) ?>"
                                                                    title="<?= sanitizeOutput(__('reject_quote')) ?>">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        
                                                        <!-- Actions dropdown -->
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                                    data-bs-toggle="dropdown" title="<?= sanitizeOutput(__('more_actions')) ?>">
                                                                <i class="bi bi-three-dots"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <!-- Renew action -->
                                                                <?php if ($canRenewQuotes && in_array($quote['status'], ['APPROVED', 'REJECTED', 'SENT'])): ?>
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?= url('quotes', 'renew', ['id' => $quote['quote_id']]) ?>">
                                                                            <i class="bi bi-arrow-clockwise"></i> <?= sanitizeOutput(__('renew_quote')) ?>
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                                
                                                                <!-- Duplicate action -->
                                                                <li>
                                                                    <a class="dropdown-item" href="<?= url('quotes', 'duplicate', ['id' => $quote['quote_id']]) ?>">
                                                                        <i class="bi bi-files"></i> <?= sanitizeOutput(__('duplicate_quote')) ?>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-file-text display-1 text-muted"></i>
                                <h5 class="mt-3"><?= sanitizeOutput(__('no_quotes_found')) ?></h5>
                                <p class="text-muted">
                                    <?= !empty($search) || !empty($status) ? 
                                        sanitizeOutput(__('no_quotes_match_search')) : 
                                        sanitizeOutput(__('no_quotes_available')) ?>
                                </p>
                                <?php if ($canCreateQuotes): ?>
                                    <a href="<?= url('quotes', 'create') ?>" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> <?= sanitizeOutput(__('create_first_quote')) ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="card-footer">
                            <nav aria-label="<?= sanitizeOutput(__('quotes_pagination')) ?>">
                                <ul class="pagination justify-content-center mb-0">
                                    <li class="page-item <?= !$hasPrev ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= $hasPrev ? url('quotes', 'list', array_merge($_GET, ['page' => $page - 1])) : '#' ?>">
                                            <?= sanitizeOutput(__('previous')) ?>
                                        </a>
                                    </li>
                                    
                                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= url('quotes', 'list', array_merge($_GET, ['page' => $i])) ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <li class="page-item <?= !$hasNext ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= $hasNext ? url('quotes', 'list', array_merge($_GET, ['page' => $page + 1])) : '#' ?>">
                                            <?= sanitizeOutput(__('next')) ?>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        const csrfToken = '<?= $csrfToken ?? generateCSRFToken() ?>';
        const translations = {
            'confirm_approve_quote': '¿Está seguro que desea aprobar la cotización?',
            'confirm_reject_quote': '¿Está seguro que desea rechazar la cotización?',
            'confirm_duplicate_quote': '¿Está seguro que desea duplicar la cotización?'
        };
    </script>
    <script src="assets/js/quotes.js"></script>
</body>
</html>