<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('edit_client')); ?> - <?php echo sanitizeOutput(APP_NAME); ?></title>
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0"><?php echo sanitizeOutput(__('edit_client')); ?></h1>
                <p class="text-muted"><?php echo sanitizeOutput(__('edit_client_description')); ?></p>
            </div>
            <a href="/?module=clients&action=list" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i><?php echo sanitizeOutput(__('back_to_list')); ?>
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo sanitizeOutput(__('client_details')); ?></h5>
                    </div>
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                <li><?php echo sanitizeOutput($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo sanitizeOutput(__('client_updated_successfully')); ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" id="editClientForm" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="id" value="<?php echo $clientId; ?>">
                            
                            <div class="row">
                                <!-- Company Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label">
                                        <?php echo sanitizeOutput(__('company_name')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           value="<?php echo sanitizeOutput($_POST['company_name'] ?? $client['company_name'] ?? ''); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Contact Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="contact_name" class="form-label">
                                        <?php echo sanitizeOutput(__('contact_name')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                           value="<?php echo sanitizeOutput($_POST['contact_name'] ?? $client['contact_name'] ?? ''); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <?php echo sanitizeOutput(__('email')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo sanitizeOutput($_POST['email'] ?? $client['email'] ?? ''); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label"><?php echo sanitizeOutput(__('phone')); ?></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo sanitizeOutput($_POST['phone'] ?? $client['phone'] ?? ''); ?>">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Tax ID -->
                            <div class="mb-3">
                                <label for="tax_id" class="form-label"><?php echo sanitizeOutput(__('tax_id')); ?></label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                       value="<?php echo sanitizeOutput($_POST['tax_id'] ?? $client['tax_id'] ?? ''); ?>">
                                <div class="form-text"><?php echo sanitizeOutput(__('tax_id_help')); ?></div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label for="address" class="form-label"><?php echo sanitizeOutput(__('address')); ?></label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo sanitizeOutput($_POST['address'] ?? $client['address'] ?? ''); ?></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between">
                                <a href="/?module=clients&action=list" class="btn btn-outline-secondary">
                                    <?php echo sanitizeOutput(__('cancel')); ?>
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i><?php echo sanitizeOutput(__('update_client')); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card border-danger mt-4">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i><?php echo sanitizeOutput(__('danger_zone')); ?></h6>
                    </div>
                    <div class="card-body">
                        <h6><?php echo sanitizeOutput(__('delete_client')); ?></h6>
                        <p class="text-muted small mb-3"><?php echo sanitizeOutput(__('delete_client_help')); ?></p>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i><?php echo sanitizeOutput(__('delete_client')); ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Client Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i><?php echo sanitizeOutput(__('client_statistics')); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="text-primary">
                                    <h4 class="mb-0"><?php echo $clientStats['total_quotes']; ?></h4>
                                    <small class="text-muted"><?php echo sanitizeOutput(__('total_quotes')); ?></small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-success">
                                    <h4 class="mb-0"><?php echo $clientStats['approved_quotes']; ?></h4>
                                    <small class="text-muted"><?php echo sanitizeOutput(__('approved_quotes')); ?></small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-info">
                                    <h4 class="mb-0"><?php echo formatCurrency($clientStats['total_spent']); ?></h4>
                                    <small class="text-muted"><?php echo sanitizeOutput(__('total_spent')); ?></small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-warning">
                                    <h4 class="mb-0"><?php echo $clientStats['conversion_rate']; ?>%</h4>
                                    <small class="text-muted"><?php echo sanitizeOutput(__('conversion_rate')); ?></small>
                                </div>
                            </div>
                        </div>
                        <?php if ($clientStats['last_quote_date']): ?>
                        <hr>
                        <small class="text-muted">
                            <?php echo sanitizeOutput(__('last_quote')); ?>: 
                            <?php echo formatDate($clientStats['last_quote_date'], 'd/m/Y'); ?>
                        </small>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Activities -->
                <?php if (!empty($clientActivities)): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i><?php echo sanitizeOutput(__('recent_activities')); ?></h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($clientActivities as $activity): ?>
                        <div class="d-flex align-items-start mb-2">
                            <div class="flex-shrink-0">
                                <?php if ($activity['activity_type'] === 'QUOTE_CREATED'): ?>
                                <i class="fas fa-file-alt text-primary"></i>
                                <?php elseif ($activity['activity_type'] === 'QUOTE_APPROVED'): ?>
                                <i class="fas fa-check-circle text-success"></i>
                                <?php else: ?>
                                <i class="fas fa-phone text-info"></i>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <div class="small">
                                    <?php echo sanitizeOutput(__('activity_' . strtolower($activity['activity_type']))); ?>
                                    <?php if ($activity['quote_number']): ?>
                                    <strong><?php echo sanitizeOutput($activity['quote_number']); ?></strong>
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted small">
                                    <?php echo formatDate($activity['activity_date'], 'd/m/Y H:i'); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Client Info -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i><?php echo sanitizeOutput(__('client_info')); ?></h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <?php echo sanitizeOutput(__('created_by')); ?>: <?php echo sanitizeOutput($client['created_by_name']); ?><br>
                            <?php echo sanitizeOutput(__('created_at')); ?>: <?php echo formatDate($client['created_at'], 'd/m/Y H:i'); ?><br>
                            <?php echo sanitizeOutput(__('updated_at')); ?>: <?php echo formatDate($client['updated_at'], 'd/m/Y H:i'); ?>
                        </small>
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
                    <h5 class="modal-title"><?php echo sanitizeOutput(__('confirm_delete')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><?php echo sanitizeOutput(__('confirm_delete_client')); ?> <strong><?php echo sanitizeOutput($client['company_name']); ?></strong>?</p>
                    <p class="text-danger small"><?php echo sanitizeOutput(__('delete_client_warning')); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <?php echo sanitizeOutput(__('cancel')); ?>
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDelete" data-client-id="<?php echo $clientId; ?>">
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
        // Initialize edit client form
        document.addEventListener('DOMContentLoaded', function() {
            ClientsForm.init('edit');
        });
    </script>
</body>
</html>