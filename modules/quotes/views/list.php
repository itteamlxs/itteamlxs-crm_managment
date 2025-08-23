<?php
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name')); ?> - Quotes</title>
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
                        <h2><i class="bi bi-file-text"></i> Quotes Management</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo dashboardUrl(); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Quotes</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="<?php echo url('quotes', 'create'); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> New Quote
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

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Expiring Quotes Alert -->
        <?php if (!empty($expiringQuotes)): ?>
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="bi bi-clock"></i> 
                <strong>Expiring Quotes:</strong> <?php echo count($expiringQuotes); ?> quotes are expiring soon.
                <button type="button" class="btn btn-sm btn-outline-warning ms-2" data-bs-toggle="modal" data-bs-target="#expiringQuotesModal">
                    View Details
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- User Statistics -->
        <?php if (!empty($userStats)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Your Quote Statistics</h6>
                            <div class="row text-center">
                                <div class="col-md-2">
                                    <div class="d-flex flex-column">
                                        <span class="fs-4 text-primary"><?php echo (int)($userStats['total_quotes'] ?? 0); ?></span>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex flex-column">
                                        <span class="fs-4 text-secondary"><?php echo (int)($userStats['draft_quotes'] ?? 0); ?></span>
                                        <small class="text-muted">Draft</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex flex-column">
                                        <span class="fs-4 text-info"><?php echo (int)($userStats['sent_quotes'] ?? 0); ?></span>
                                        <small class="text-muted">Sent</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex flex-column">
                                        <span class="fs-4 text-success"><?php echo (int)($userStats['approved_quotes'] ?? 0); ?></span>
                                        <small class="text-muted">Approved</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex flex-column">
                                        <span class="fs-4 text-danger"><?php echo (int)($userStats['rejected_quotes'] ?? 0); ?></span>
                                        <small class="text-muted">Rejected</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex flex-column">
                                        <span class="fs-4 text-warning">$<?php echo number_format((float)($userStats['total_amount'] ?? 0), 2); ?></span>
                                        <small class="text-muted">Total Value</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="module" value="quotes">
                    <input type="hidden" name="action" value="list">
                    
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="DRAFT" <?php echo $filters['status'] === 'DRAFT' ? 'selected' : ''; ?>>Draft</option>
                            <option value="SENT" <?php echo $filters['status'] === 'SENT' ? 'selected' : ''; ?>>Sent</option>
                            <option value="APPROVED" <?php echo $filters['status'] === 'APPROVED' ? 'selected' : ''; ?>>Approved</option>
                            <option value="REJECTED" <?php echo $filters['status'] === 'REJECTED' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Client</label>
                        <input type="text" name="client_name" class="form-control" 
                               placeholder="Client name..." value="<?php echo sanitizeOutput($filters['client_name']); ?>">
                    </div>
                    
                    <?php if ($user['is_admin']): ?>
                    <div class="col-md-2">
                        <label class="form-label">User</label>
                        <input type="text" name="username" class="form-control" 
                               placeholder="Username..." value="<?php echo sanitizeOutput($filters['username']); ?>">
                    </div>
                    <?php endif; ?>
                    
                    <div class="col-md-2">
                        <label class="form-label">From Date</label>
                        <input type="date" name="date_from" class="form-control" 
                               value="<?php echo sanitizeOutput($filters['date_from']); ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">To Date</label>
                        <input type="date" name="date_to" class="form-control" 
                               value="<?php echo sanitizeOutput($filters['date_to']); ?>">
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="<?php echo url('quotes', 'list'); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quotes Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Quotes (<?php echo $totalQuotes; ?> total)</h5>
                <div class="d-flex align-items-center">
                    <select name="limit" class="form-select form-select-sm me-2" style="width: auto;" onchange="changeLimit(this.value)">
                        <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10 per page</option>
                        <option value="20" <?php echo $limit == 20 ? 'selected' : ''; ?>>20 per page</option>
                        <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50 per page</option>
                        <option value="100" <?php echo $limit == 100 ? 'selected' : ''; ?>>100 per page</option>
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($quotes)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-file-text display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">No quotes found</h5>
                        <p class="text-muted">Create your first quote to get started.</p>
                        <a href="<?php echo url('quotes', 'create'); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create Quote
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Quote #</th>
                                    <th>Client</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Issue Date</th>
                                    <th>Expiry Date</th>
                                    <?php if ($user['is_admin']): ?>
                                    <th>Created By</th>
                                    <?php endif; ?>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quotes as $quote): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo sanitizeOutput($quote['quote_number']); ?></strong>
                                    </td>
                                    <td><?php echo sanitizeOutput($quote['client_name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo match($quote['status']) {
                                                'DRAFT' => 'secondary',
                                                'SENT' => 'info',
                                                'APPROVED' => 'success',
                                                'REJECTED' => 'danger',
                                                default => 'secondary'
                                            };
                                        ?>">
                                            <?php echo sanitizeOutput($quote['status']); ?>
                                        </span>
                                    </td>
                                    <td>$<?php echo number_format($quote['total_amount'], 2); ?></td>
                                    <td><?php echo formatDate($quote['issue_date'], 'M d, Y'); ?></td>
                                    <td>
                                        <?php 
                                        $expiryDate = strtotime($quote['expiry_date']);
                                        $today = strtotime('today');
                                        $isExpired = $expiryDate < $today;
                                        $isExpiringSoon = $expiryDate <= strtotime('+3 days');
                                        ?>
                                        <span class="<?php echo $isExpired ? 'text-danger' : ($isExpiringSoon ? 'text-warning' : ''); ?>">
                                            <?php echo formatDate($quote['expiry_date'], 'M d, Y'); ?>
                                            <?php if ($isExpired): ?>
                                                <i class="bi bi-exclamation-triangle text-danger" title="Expired"></i>
                                            <?php elseif ($isExpiringSoon): ?>
                                                <i class="bi bi-clock text-warning" title="Expiring Soon"></i>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <?php if ($user['is_admin']): ?>
                                    <td><?php echo sanitizeOutput($quote['username']); ?></td>
                                    <?php endif; ?>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="<?php echo url('quotes', 'view', ['id' => $quote['quote_id']]); ?>">
                                                    <i class="bi bi-eye"></i> View
                                                </a></li>
                                                
                                                <?php if ($quote['status'] === 'DRAFT'): ?>
                                                <li><a class="dropdown-item" href="<?php echo url('quotes', 'edit', ['id' => $quote['quote_id']]); ?>">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus(<?php echo $quote['quote_id']; ?>, 'SENT')">
                                                    <i class="bi bi-send"></i> Send to Client
                                                </a></li>
                                                <?php endif; ?>
                                                
                                                <?php if ($quote['status'] === 'SENT'): ?>
                                                <li><a class="dropdown-item" href="<?php echo url('quotes', 'approve', ['id' => $quote['quote_id']]); ?>">
                                                    <i class="bi bi-check-circle"></i> Approve
                                                </a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus(<?php echo $quote['quote_id']; ?>, 'REJECTED')">
                                                    <i class="bi bi-x-circle"></i> Reject
                                                </a></li>
                                                <?php endif; ?>
                                                
                                                <?php if (in_array($quote['status'], ['APPROVED', 'REJECTED', 'SENT'])): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="<?php echo url('quotes', 'renew', ['id' => $quote['quote_id']]); ?>">
                                                    <i class="bi bi-arrow-repeat"></i> Renew
                                                </a></li>
                                                <?php endif; ?>
                                                
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="<?php echo url('quotes', 'pdf', ['id' => $quote['quote_id']]); ?>">
                                                    <i class="bi bi-file-pdf"></i> Download PDF
                                                </a></li>
                                                
                                                <?php if ($quote['status'] === 'DRAFT'): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteQuote(<?php echo $quote['quote_id']; ?>)">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <div class="card-footer">
                        <nav aria-label="Quotes pagination">
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo url('quotes', 'list', array_merge($_GET, ['page' => $page - 1])); ?>">
                                        Previous
                                    </a>
                                </li>

                                <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);
                                
                                if ($startPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo url('quotes', 'list', array_merge($_GET, ['page' => 1])); ?>">1</a>
                                    </li>
                                    <?php if ($startPage > 2): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif;
                                endif;
                                
                                for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo url('quotes', 'list', array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor;
                                
                                if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo url('quotes', 'list', array_merge($_GET, ['page' => $totalPages])); ?>"><?php echo $totalPages; ?></a>
                                    </li>
                                <?php endif; ?>

                                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo url('quotes', 'list', array_merge($_GET, ['page' => $page + 1])); ?>">
                                        Next
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Expiring Quotes Modal -->
    <?php if (!empty($expiringQuotes)): ?>
    <div class="modal fade" id="expiringQuotesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Expiring Quotes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Quote #</th>
                                    <th>Client</th>
                                    <th>Expiry Date</th>
                                    <th>Days Left</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expiringQuotes as $expiring): ?>
                                <tr>
                                    <td><?php echo sanitizeOutput($expiring['quote_number']); ?></td>
                                    <td><?php echo sanitizeOutput($expiring['client_name']); ?></td>
                                    <td><?php echo formatDate($expiring['expiry_date'], 'M d, Y'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $expiring['days_until_expiry'] <= 0 ? 'danger' : 'warning'; ?>">
                                            <?php echo $expiring['days_until_expiry'] <= 0 ? 'Expired' : $expiring['days_until_expiry'] . ' days'; ?>
                                        </span>
                                    </td>
                                    <td>$<?php echo number_format($expiring['total_amount'] ?? 0, 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/crm-project/public/assets/js/quotes.js"></script>
    <script>
        // Change items per page
        function changeLimit(limit) {
            const url = new URL(window.location);
            url.searchParams.set('limit', limit);
            url.searchParams.delete('page');
            window.location = url.toString();
        }
        
        // Update quote status
        function updateStatus(quoteId, status) {
            if (!confirm('Are you sure you want to change the status to ' + status + '?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('quote_id', quoteId);
            formData.append('status', status);
            formData.append('csrf_token', '<?php echo generateCSRFToken(); ?>');
            
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
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
        
        // Delete quote
        function deleteQuote(quoteId) {
            if (!confirm('Are you sure you want to delete this quote? This action cannot be undone.')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('quote_id', quoteId);
            formData.append('csrf_token', '<?php echo generateCSRFToken(); ?>');
            
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
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>
</html>