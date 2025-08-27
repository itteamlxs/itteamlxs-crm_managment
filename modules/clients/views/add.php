<!DOCTYPE html>
<html lang="<?= getUserLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('add_client'); ?> - <?= APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-user-plus me-2"></i><?= __('add_client'); ?></h2>
                    <a href="<?= url('clients', 'list'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i><?= __('back_to_list'); ?>
                    </a>
                </div>

                <!-- Success Message -->
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= sanitizeOutput($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= sanitizeOutput($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Add Client Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?= __('client_details'); ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="addClientForm">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

                            <!-- Company Information -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label">
                                        <?= __('company_name'); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           value="<?= sanitizeOutput($_POST['company_name'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="contact_name" class="form-label">
                                        <?= __('contact_name'); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                           value="<?= sanitizeOutput($_POST['contact_name'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        <?= __('email'); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= sanitizeOutput($_POST['email'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label"><?= __('phone'); ?></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?= sanitizeOutput($_POST['phone'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tax_id" class="form-label"><?= __('tax_id'); ?></label>
                                    <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                           value="<?= sanitizeOutput($_POST['tax_id'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label"><?= __('address'); ?></label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?= sanitizeOutput($_POST['address'] ?? ''); ?></textarea>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= url('clients', 'list'); ?>" class="btn btn-secondary">
                                    <?= __('cancel'); ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i><?= __('create_client'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('addClientForm');
            
            // Basic client-side validation
            form.addEventListener('submit', function(e) {
                const companyName = document.getElementById('company_name').value.trim();
                const contactName = document.getElementById('contact_name').value.trim();
                const email = document.getElementById('email').value.trim();
                
                if (!companyName || !contactName || !email) {
                    e.preventDefault();
                    alert('<?= __('please_fill_required_fields'); ?>');
                    return;
                }
                
                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('<?= __('invalid_email_format'); ?>');
                    return;
                }
            });
            
            // Auto-focus first field
            document.getElementById('company_name').focus();
        });
    </script>
</body>
</html>