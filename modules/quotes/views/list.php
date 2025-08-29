<!DOCTYPE html>
<html lang="<?= getUserLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('quotes_management') ?> - <?= __('app_name') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><?= __('quotes_management') ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('dashboard', 'index') ?>"><?= __('dashboard') ?></a></li>
                        <li class="breadcrumb-item active"><?= __('quotes') ?></li>
                    </ol>
                </nav>
            </div>
            <?php if (hasPermission('create_quotes')): ?>
                <a href="<?= url('quotes', 'create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> <?= __('create_quote') ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="module" value="quotes">
                    <input type="hidden" name="action" value="list">
                    
                    <div class="col-md-3">
                        <label for="search" class="form-label"><?= __('search') ?></label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?= sanitizeOutput($search) ?>" 
                               placeholder="<?= __('search_quotes_placeholder') ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="status" class="form-label"><?= __('status') ?></label>
                        <select class="form-select" id="status" name="status">
                            <option value=""><?= __('all_statuses') ?></option>
                            <option value="DRAFT" <?= $status === 'DRAFT' ? 'selected' : '' ?>><?= __('draft') ?></option>
                            <option value="SENT" <?= $status === 'SENT' ? 'selected' : '' ?>><?= __('sent') ?></option>
                            <option value="APPROVED" <?= $status === 'APPROVED' ? 'selected' : '' ?>><?= __('approved') ?></option>
                            <option value="REJECTED" <?= $status === 'REJECTED' ? 'selected' : '' ?>><?= __('rejected') ?></option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="client_id" class="form-label"><?= __('client') ?></label>
                        <select class="form-select" id="client_id" name="client_id">
                            <option value=""><?= __('all_clients') ?></option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['client_id'] ?>" 
                                        <?= $client_id == $client['client_id'] ? 'selected' : '' ?>>
                                    <?= sanitizeOutput($client['company_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="limit" class="form-label"><?= __('per_page') ?></label>
                        <select class="form-select" id="limit" name="limit">
                            <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search"></i> <?= __('search') ?>
                        </button>
                        <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary">
                            <?= __('clear') ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Summary -->
        <?php if ($total > 0): ?>
            <div class="alert alert-info">
                <?= __('showing_results', ['start' => (($page - 1) * $limit) + 1, 'end' => min($page * $limit, $total), 'total' => $total]) ?>
            </div>
        <?php endif; ?>

        <!-- Quotes Table -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($quotes)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-text display-1 text-muted"></i>
                        <h5 class="mt-3"><?= __('no_quotes_found') ?></h5>
                        <p class="text-muted">
                            <?php if (!empty($search) || !empty($status) || !empty($client_id)): ?>
                                <?= __('no_quotes_match_search') ?>
                            <?php else: ?>
                                <?= __('no_quotes_available') ?>
                            <?php endif; ?>
                        </p>
                        <?php if (hasPermission('create_quotes')): ?>
                            <a href="<?= url('quotes', 'create') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> <?= __('create_first_quote') ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th><?= __('quote_number') ?></th>
                                    <th><?= __('client') ?></th>
                                    <th><?= __('status') ?></th>
                                    <th><?= __('total_amount') ?></th>
                                    <th><?= __('issue_date') ?></th>
                                    <th><?= __('expiry_date') ?></th>
                                    <th><?= __('created_by') ?></th>
                                    <th><?= __('actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quotes as $quote): ?>
                                    <tr>
                                        <td>
                                            <strong><?= sanitizeOutput($quote['quote_number']) ?></strong>
                                        </td>
                                        <td><?= sanitizeOutput($quote['client_name']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= getStatusBadgeClass($quote['status']) ?>">
                                                <?= __('status_' . strtolower($quote['status'])) ?>
                                            </span>
                                        </td>
                                        <td><?= formatCurrency($quote['total_amount']) ?></td>
                                        <td><?= formatDate($quote['issue_date'], 'Y-m-d') ?></td>
                                        <td>
                                            <?php
                                            $expiryDate = strtotime($quote['expiry_date']);
                                            $today = strtotime('today');
                                            $isExpired = $expiryDate < $today;
                                            ?>
                                            <span class="<?= $isExpired ? 'text-danger' : '' ?>">
                                                <?= formatDate($quote['expiry_date'], 'Y-m-d') ?>
                                                <?php if ($isExpired): ?>
                                                    <i class="bi bi-exclamation-triangle" title="<?= __('expired') ?>"></i>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td><?= sanitizeOutput($quote['username']) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= url('quotes', 'view', ['id' => $quote['quote_id']]) ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="<?= __('view') ?>">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                
                                                <?php if (hasPermission('create_quotes') && $quote['status'] === 'DRAFT'): ?>
                                                    <a href="<?= url('quotes', 'edit', ['id' => $quote['quote_id']]) ?>" 
                                                       class="btn btn-sm btn-outline-secondary" title="<?= __('edit') ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if (hasPermission('create_quotes')): ?>
                                                    <a href="<?= url('quotes', 'duplicate', ['id' => $quote['quote_id']]) ?>" 
                                                       class="btn btn-sm btn-outline-info" title="<?= __('duplicate') ?>">
                                                        <i class="bi bi-files"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if (hasPermission('renew_quotes') && in_array($quote['status'], ['APPROVED', 'REJECTED'])): ?>
                                                    <a href="<?= url('quotes', 'renew', ['id' => $quote['quote_id']]) ?>" 
                                                       class="btn btn-sm btn-outline-warning" title="<?= __('renew') ?>">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <a href="<?= url('quotes', 'pdf', ['id' => $quote['quote_id']]) ?>" 
                                                   class="btn btn-sm btn-outline-danger" title="<?= __('download_pdf') ?>" target="_blank">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="<?= __('quotes_pagination') ?>">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('quotes', 'list', array_merge($_GET, ['page' => $page - 1])) ?>">
                                            <?= __('previous') ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);
                                
                                if ($startPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('quotes', 'list', array_merge($_GET, ['page' => 1])) ?>">1</a>
                                    </li>
                                    <?php if ($startPage > 2): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= url('quotes', 'list', array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('quotes', 'list', array_merge($_GET, ['page' => $totalPages])) ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('quotes', 'list', array_merge($_GET, ['page' => $page + 1])) ?>">
                                            <?= __('next') ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function getStatusBadgeClass(status) {
            switch(status) {
                case 'DRAFT': return 'secondary';
                case 'SENT': return 'primary';
                case 'APPROVED': return 'success';
                case 'REJECTED': return 'danger';
                default: return 'secondary';
            }
        }
        
        // Auto-submit form when filters change
        document.querySelectorAll('#status, #client_id, #limit').forEach(element => {
            element.addEventListener('change', function() {
                this.form.submit();
            });
        });
    </script>
</body>
</html>

<?php
/**
 * Helper function for status badge classes
 */
function getStatusBadgeClass($status) {
    switch($status) {
        case 'DRAFT': return 'secondary';
        case 'SENT': return 'primary';
        case 'APPROVED': return 'success';
        case 'REJECTED': return 'danger';
        default: return 'secondary';
    }
}
?>