<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(APP_NAME); ?> - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h4><?php echo sanitizeOutput(APP_NAME); ?></h4>
                        <p class="mb-0">Please sign in</p>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo sanitizeOutput($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo sanitizeOutput($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['logout_success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo sanitizeOutput($_SESSION['logout_success']); unset($_SESSION['logout_success']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="/crm-project/public/index.php?module=auth&action=login">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username or Email</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username" 
                                       value="<?php echo sanitizeOutput($_POST['username'] ?? ''); ?>"
                                       required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Sign In</button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="/crm-project/public/index.php?module=auth&action=reset" class="text-muted">
                                Forgot your password?
                            </a>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <small class="text-muted">
                            <?php if (APP_DEBUG): ?>
                                Debug Mode: Use leon/temporal2024#
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>