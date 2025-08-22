<?php
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('clients') ?: 'Clients'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-building"></i> <?php echo __('clients_management') ?: 'Clients Management'; ?></h2>
            <div>
                <a href="<?php echo dashboardUrl(); ?>" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> <?php echo __('back_to_dashboard') ?: 'Dashboard'; ?>
                </a>
                <a href="<?php echo url('clients', 'add'); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> <?php echo __('add_client') ?: 'Add Client'; ?>
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?php echo sanitizeOutput($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?php echo sanitizeOutput($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?php echo sanitizeOutput($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?php echo sanitizeOutput($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Search and Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="module" value="clients">
                    <input type="hidden" name="action" value="list">
                    
                    <div class="col-md-6">
                        <label for="search" class="form-label"><?php echo __('search') ?: 'Search'; ?></label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo sanitizeOutput($search); ?>"
                               placeholder="<?php echo __('search_clients_placeholder') ?: 'Company name, contact, or email...'; ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="limit" class="form-label"><?php echo __('per_page') ?: 'Per Page'; ?></label>
                        <select class="form-select" id="limit" name="limit">
                            <option value="10" <?php echo $pagination['limit'] == 10 ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?php echo $pagination['limit'] == 25 ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?php echo $pagination['limit'] == 50 ? 'selected' : ''; ?>>50</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search"></i> <?php echo __('search') ?: 'Search'; ?>
                        </button>
                        <a href="<?php echo url('clients', 'list'); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> <?php echo __('clear') ?: 'Clear'; ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo __('clients_list') ?: 'Clients List'; ?> (<?php echo $totalClients; ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($clients)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo __('company_name') ?: 'Company'; ?></th>
                                <th><?php echo __('contact_name') ?: 'Contact'; ?></th>
                                <th><?php echo __('email') ?: 'Email'; ?></th>
                                <th><?php echo __('phone') ?: 'Phone'; ?></th>
                                <th><?php echo __('created_at') ?: 'Created'; ?></th>
                                <th><?php echo __('actions') ?: 'Actions'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><strong><?php echo sanitizeOutput($client['company_name']); ?></strong></td>
                                <td><?php echo sanitizeOutput($client['contact_name']); ?></td>
                                <td><?php echo sanitizeOutput($client['email']); ?></td>
                                <td><?php echo sanitizeOutput($client['phone'] ?? ''); ?></td>
                                <td><?php echo formatDate($client['created_at'], 'Y-m-d'); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo url('clients', 'edit', ['id' => $client['client_id']]); ?>" 
                                           class="btn btn-outline-primary" title="<?php echo __('edit') ?: 'Edit'; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete(<?php echo $client['client_id']; ?>, '<?php echo sanitizeOutput($client['company_name']); ?>')"
                                                title="<?php echo __('delete') ?: 'Delete'; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $pagination['page'] ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo url('clients', 'list', ['page' => $i, 'limit' => $pagination['limit'], 'search' => $search]); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
                
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">
                        <?php if (!empty($search)): ?>
                            <?php echo __('no_clients_match_search') ?: 'No clients match your search'; ?>
                        <?php else: ?>
                            <?php echo __('no_clients_available') ?: 'No clients available'; ?>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form method="POST" id="deleteForm" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="client_id" id="deleteClientId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(clientId, companyName) {
            if (confirm('Are you sure you want to delete "' + companyName + '"? This action cannot be undone.')) {
                document.getElementById('deleteClientId').value = clientId;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>