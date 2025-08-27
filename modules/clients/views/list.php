<!DOCTYPE html>
<html lang="<?= getUserLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('clients_management'); ?> - <?= APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-users me-2"></i><?= __('clients_management'); ?></h2>
                    <div>
                        <a href="<?= url('dashboard', 'index'); ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-home me-1"></i><?= __('dashboard'); ?>
                        </a>
                        <?php if (hasPermission('view_clients')): ?>
                            <a href="<?= url('clients', 'add'); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i><?= __('add_client'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3" id="searchForm">
                            <input type="hidden" name="module" value="clients">
                            <input type="hidden" name="action" value="list">
                            
                            <div class="col-md-6">
                                <label for="search" class="form-label"><?= __('search'); ?></label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="<?= sanitizeOutput($search); ?>" 
                                       placeholder="<?= __('search_clients_placeholder'); ?>">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="limit" class="form-label"><?= __('per_page'); ?></label>
                                <select class="form-select" id="limit" name="limit">
                                    <option value="10" <?= $pagination['limit'] == 10 ? 'selected' : ''; ?>>10</option>
                                    <option value="25" <?= $pagination['limit'] == 25 ? 'selected' : ''; ?>>25</option>
                                    <option value="50" <?= $pagination['limit'] == 50 ? 'selected' : ''; ?>>50</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-search me-1"></i><?= __('search'); ?>
                                </button>
                                <a href="<?= url('clients', 'list'); ?>" class="btn btn-outline-secondary">
                                    <?= __('clear'); ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Clients Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?= __('clients_list'); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($result['data'])): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <a href="<?= url('clients', 'list', ['order' => 'company_name ASC', 'search' => $search, 'limit' => $pagination['limit']]); ?>" class="text-decoration-none">
                                                    <?= __('company_name'); ?> <i class="fas fa-sort"></i>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="<?= url('clients', 'list', ['order' => 'contact_name ASC', 'search' => $search, 'limit' => $pagination['limit']]); ?>" class="text-decoration-none">
                                                    <?= __('contact_name'); ?> <i class="fas fa-sort"></i>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="<?= url('clients', 'list', ['order' => 'email ASC', 'search' => $search, 'limit' => $pagination['limit']]); ?>" class="text-decoration-none">
                                                    <?= __('email'); ?> <i class="fas fa-sort"></i>
                                                </a>
                                            </th>
                                            <th><?= __('phone'); ?></th>
                                            <th>
                                                <a href="<?= url('clients', 'list', ['order' => 'created_at DESC', 'search' => $search, 'limit' => $pagination['limit']]); ?>" class="text-decoration-none">
                                                    <?= __('created_at'); ?> <i class="fas fa-sort"></i>
                                                </a>
                                            </th>
                                            <th><?= __('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($result['data'] as $client): ?>
                                            <tr>
                                                <td><?= sanitizeOutput($client['company_name']); ?></td>
                                                <td><?= sanitizeOutput($client['contact_name']); ?></td>
                                                <td><?= sanitizeOutput($client['email']); ?></td>
                                                <td><?= sanitizeOutput($client['phone'] ?? '-'); ?></td>
                                                <td><?= formatDate($client['created_at'], 'Y-m-d'); ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="<?= url('clients', 'edit', ['id' => $client['client_id']]); ?>" 
                                                           class="btn btn-outline-primary" title="<?= __('edit'); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if (hasPermission('view_clients')): ?>
                                                            <button type="button" class="btn btn-outline-danger" 
                                                                    onclick="confirmDelete(<?= $client['client_id']; ?>, '<?= sanitizeOutput($client['company_name']); ?>')"
                                                                    title="<?= __('delete'); ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($result['pages'] > 1): ?>
                                <nav aria-label="<?= __('navigation'); ?>">
                                    <ul class="pagination justify-content-center">
                                        <?php if ($result['page'] > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= url('clients', 'list', ['page' => $result['page'] - 1, 'search' => $search, 'limit' => $pagination['limit']]); ?>">
                                                    <?= __('previous'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = max(1, $result['page'] - 2); $i <= min($result['pages'], $result['page'] + 2); $i++): ?>
                                            <li class="page-item <?= $i == $result['page'] ? 'active' : ''; ?>">
                                                <a class="page-link" href="<?= url('clients', 'list', ['page' => $i, 'search' => $search, 'limit' => $pagination['limit']]); ?>">
                                                    <?= $i; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($result['page'] < $result['pages']): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= url('clients', 'list', ['page' => $result['page'] + 1, 'search' => $search, 'limit' => $pagination['limit']]); ?>">
                                                    <?= __('next'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>

                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5><?= __('no_clients_found'); ?></h5>
                                <p class="text-muted">
                                    <?php if (!empty($search)): ?>
                                        <?= __('no_clients_match_search'); ?>
                                    <?php else: ?>
                                        <?= __('no_clients_available'); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('confirm_delete'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('cancel'); ?></button>
                    <form method="POST" id="deleteForm" style="display: inline;">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                        <input type="hidden" name="id" id="deleteClientId">
                        <button type="submit" class="btn btn-danger"><?= __('delete'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(clientId, clientName) {
            document.getElementById('deleteClientId').value = clientId;
            document.getElementById('deleteMessage').textContent = 
                '<?= __('confirm_delete_client'); ?> "' + clientName + '"?';
            document.getElementById('deleteForm').action = '<?= url('clients', 'delete'); ?>';
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>