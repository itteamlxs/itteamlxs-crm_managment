<?php
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('add_client') ?: 'Add Client'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-plus-circle"></i> <?php echo __('add_client') ?: 'Add Client'; ?></h2>
            <a href="<?php echo url('clients', 'list'); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> <?php echo __('back_to_list') ?: 'Back to List'; ?>
            </a>
        </div>

        <!-- Messages -->
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?php echo sanitizeOutput($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Client Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo __('client_details') ?: 'Client Details'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" id="clientForm">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="row">
                        <!-- Company Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><?php echo __('company_information') ?: 'Company Information'; ?></h6>
                            
                            <div class="mb-3">
                                <label for="company_name" class="form-label"><?php echo __('company_name') ?: 'Company Name'; ?> *</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" 
                                       value="<?php echo sanitizeOutput($formData['company_name']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tax_id" class="form-label"><?php echo __('tax_id') ?: 'Tax ID'; ?></label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                       value="<?php echo sanitizeOutput($formData['tax_id']); ?>">
                                <div class="form-text"><?php echo __('tax_id_help') ?: 'Optional tax identification number'; ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label"><?php echo __('address') ?: 'Address'; ?></label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo sanitizeOutput($formData['address']); ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><?php echo __('contact_information') ?: 'Contact Information'; ?></h6>
                            
                            <div class="mb-3">
                                <label for="contact_name" class="form-label"><?php echo __('contact_name') ?: 'Contact Name'; ?> *</label>
                                <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                       value="<?php echo sanitizeOutput($formData['contact_name']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label"><?php echo __('email') ?: 'Email'; ?> *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo sanitizeOutput($formData['email']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label"><?php echo __('phone') ?: 'Phone'; ?></label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo sanitizeOutput($formData['phone']); ?>">
                                <div class="form-text"><?php echo __('phone_help') ?: 'Include country code if international'; ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end pt-3 border-top">
                        <a href="<?php echo url('clients', 'list'); ?>" class="btn btn-secondary me-2">
                            <?php echo __('cancel') ?: 'Cancel'; ?>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> <?php echo __('create_client') ?: 'Create Client'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/crm-project/public/assets/js/clients.js"></script>
</body>
</html>