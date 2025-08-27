<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('clients_management')); ?> - <?php echo sanitizeOutput(APP_NAME); ?></title>
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0"><?php echo sanitizeOutput(__('clients_management')); ?></h1>
                <p class="text-muted"><?php echo sanitizeOutput(__('manage_clients_description')); ?></p>
            </div>
            <?php if (hasPermission('view_clients')): ?>
            <a href="/?module=clients&action=add" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i><?php echo sanitizeOutput(__('add_client')); ?>
            </a>
            <?php endif; ?>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label"><?php echo sanitizeOutput(__('search')); ?></label>
                        <input type="text" class="form-control" id="search" placeholder="<?php echo sanitizeOutput(__('search_clients_placeholder')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="perPage" class="form-label"><?php echo sanitizeOutput(__('per_page')); ?></label>
                        <select class="form-select" id="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary me-2" id="clearFilters">
                            <?php echo sanitizeOutput(__('clear')); ?>
                        </button>
                        <button type="button" class="btn btn-primary" id="searchBtn">
                            <i class="fas fa-search me-2"></i><?php echo sanitizeOutput(__('search')); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo sanitizeOutput(__('clients_list')); ?></h5>
            </div>
            <div class="card-body">
                <!-- Loading indicator -->
                <div id="loading" class="text-center py-4" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden"><?php echo sanitizeOutput(__('loading')); ?>...</span>
                    </div>
                </div>

                <!-- Clients table -->
                <div class="table-responsive" id="clientsTableContainer">
                    <table class="table table-striped table-hover" id="clientsTable">
                        <thead class="table-dark">
                            <tr>
                                <th><?php echo sanitizeOutput(__('company_name')); ?></th>
                                <th><?php echo sanitizeOutput(__('contact_name')); ?></th>
                                <th><?php echo sanitizeOutput(__('email')); ?></th>
                                <th><?php echo sanitizeOutput(__('phone')); ?></th>
                                <th><?php echo sanitizeOutput(__('created_at')); ?></th>
                                <th><?php echo sanitizeOutput(__('actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody id="clientsTableBody">
                            <!-- Content loaded via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- No results message -->
                <div id="noResults" class="text-center py-4" style="display: none;">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?php echo sanitizeOutput(__('no_clients_found')); ?></h5>
                    <p class="text-muted mb-0" id="noResultsMessage">
                        <?php echo sanitizeOutput(__('no_clients_match_search')); ?>
                    </p>
                </div>

                <!-- Pagination -->
                <nav aria-label="<?php echo sanitizeOutput(__('clients_pagination')); ?>" id="paginationContainer">
                    <ul class="pagination justify-content-center" id="pagination">
                        <!-- Pagination loaded via AJAX -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo sanitizeOutput(__('confirm_delete')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><?php echo sanitizeOutput(__('confirm_delete_client')); ?> <strong id="deleteClientName"></strong>?</p>
                    <p class="text-danger small"><?php echo sanitizeOutput(__('delete_client_warning')); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <?php echo sanitizeOutput(__('cancel')); ?>
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <?php echo sanitizeOutput(__('delete')); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Clients JS -->
    <script src="assets/js/clients.js"></script>
    <script>
        // Initialize clients list
        document.addEventListener('DOMContentLoaded', function() {
            ClientsList.init();
        });
    </script>
</body>
</html>