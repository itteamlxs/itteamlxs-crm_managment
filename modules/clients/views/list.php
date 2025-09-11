<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

requireLogin();
requirePermission('view_clients');
?>
<!DOCTYPE html>
<html lang="<?= getUserLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('clients_management'); ?> - <?= APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/crm-project/public/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <div class="container-fluid">
            <!-- Header with Breadcrumbs -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">
                        <i class="bi bi-building"></i> <?= __('clients_management'); ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?= url('dashboard', 'index') ?>">
                                    <i class="bi bi-house-door"></i> <?= __('dashboard') ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="bi bi-building"></i> <?= __('clients_management') ?>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="<?= url('dashboard', 'index'); ?>" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-house"></i> <?= __('dashboard'); ?>
                        </a>
                        <?php if (hasPermission('add_client')): ?>
                            <a href="<?= url('clients', 'add'); ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus"></i> <?= __('add_client'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
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
                                <i class="bi bi-search me-1"></i><?= __('search'); ?>
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
                                                <?= __('company_name'); ?> <i class="bi bi-arrow-down-up"></i>
                                            </a>
                                        </th>
                                        <th>
                                            <a href="<?= url('clients', 'list', ['order' => 'contact_name ASC', 'search' => $search, 'limit' => $pagination['limit']]); ?>" class="text-decoration-none">
                                                <?= __('contact_name'); ?> <i class="bi bi-arrow-down-up"></i>
                                            </a>
                                        </th>
                                        <th>
                                            <a href="<?= url('clients', 'list', ['order' => 'email ASC', 'search' => $search, 'limit' => $pagination['limit']]); ?>" class="text-decoration-none">
                                                <?= __('email'); ?> <i class="bi bi-arrow-down-up"></i>
                                            </a>
                                        </th>
                                        <th><?= __('phone'); ?></th>
                                        <th>
                                            <a href="<?= url('clients', 'list', ['order' => 'created_at DESC', 'search' => $search, 'limit' => $pagination['limit']]); ?>" class="text-decoration-none">
                                                <?= __('created_at'); ?> <i class="bi bi-arrow-down-up"></i>
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
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if (hasPermission('delete_client')): ?>
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                onclick="confirmDelete(<?= $client['client_id']; ?>, '<?= sanitizeOutput($client['company_name']); ?>')"
                                                                title="<?= __('delete'); ?>">
                                                            <i class="bi bi-trash"></i>
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
                            <i class="bi bi-building display-1 text-muted mb-3"></i>
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