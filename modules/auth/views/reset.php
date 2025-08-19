<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('password_reset') ?: 'Reset Password'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h4><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?></h4>
                        <p class="mb-0"><?php echo __('password_reset') ?: 'Password Reset'; ?></p>
                    </div>
                    <div class="card-body text-center">
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle"></i>
                            <h5><?php echo __('password_reset_not_available') ?: 'Password Reset Not Available'; ?></h5>
                            <p><?php echo __('contact_admin_password') ?: 'If you need to reset your password, please contact your system administrator.'; ?></p>
                        </div>
                        <a href="/crm-project/public/index.php?module=auth&action=login" class="btn btn-primary">
                            <?php echo __('back_to_login') ?: 'Back to Login'; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>