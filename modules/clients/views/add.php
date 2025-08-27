<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('add_client')); ?> - <?php echo sanitizeOutput(APP_NAME); ?></title>
    
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
                <h1 class="h3 mb-0"><?php echo sanitizeOutput(__('add_client')); ?></h1>
                <p class="text-muted"><?php echo sanitizeOutput(__('add_new_client_description')); ?></p>
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
                            <?php echo sanitizeOutput(__('client_created_successfully')); ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" id="addClientForm" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="row">
                                <!-- Company Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label">
                                        <?php echo sanitizeOutput(__('company_name')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           value="<?php echo sanitizeOutput($_POST['company_name'] ?? ''); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Contact Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="contact_name" class="form-label">
                                        <?php echo sanitizeOutput(__('contact_name')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                           value="<?php echo sanitizeOutput($_POST['contact_name'] ?? ''); ?>" required>
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
                                           value="<?php echo sanitizeOutput($_POST['email'] ?? ''); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label"><?php echo sanitizeOutput(__('phone')); ?></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo sanitizeOutput($_POST['phone'] ?? ''); ?>">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Tax ID -->
                            <div class="mb-3">
                                <label for="tax_id" class="form-label"><?php echo sanitizeOutput(__('tax_id')); ?></label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                       value="<?php echo sanitizeOutput($_POST['tax_id'] ?? ''); ?>">
                                <div class="form-text"><?php echo sanitizeOutput(__('tax_id_help')); ?></div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label for="address" class="form-label"><?php echo sanitizeOutput(__('address')); ?></label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo sanitizeOutput($_POST['address'] ?? ''); ?></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between">
                                <a href="/?module=clients&action=list" class="btn btn-outline-secondary">
                                    <?php echo sanitizeOutput(__('cancel')); ?>
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i><?php echo sanitizeOutput(__('create_client')); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Form Help -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i><?php echo sanitizeOutput(__('form_help')); ?></h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <?php echo sanitizeOutput(__('required_fields_marked')); ?>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <?php echo sanitizeOutput(__('email_must_be_unique')); ?>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <?php echo sanitizeOutput(__('phone_format_help')); ?>
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                <?php echo sanitizeOutput(__('all_fields_validated')); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Clients JS -->
    <script src="assets/js/clients.js"></script>
    <script>
        // Initialize add client form
        document.addEventListener('DOMContentLoaded', function() {
            ClientsForm.init('add');
        });
    </script>
</body>
</html>