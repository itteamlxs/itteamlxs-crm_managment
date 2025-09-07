<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            /* Left Panel Colors */
            --left-primary: #00060c;
            --left-secondary: #00060c;
            --left-accent: #00060c;
            
            /* Right Panel Colors */
            --right-primary: #002fff;
            --right-secondary: #07006e;
            --right-light: #00060c;
            --right-accent: #00060c;
            
            /* Button & Interactive Elements */
            --button-color: #0400ff;
            --button-hover: #0104c5;
            
            /* Text Colors */
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.8);
            --text-muted: rgba(255, 255, 255, 0.6);
            
            /* Input Colors */
            --input-bg: rgba(255, 255, 255, 0.1);
            --input-border: rgba(255, 255, 255, 0.2);
            --input-focus: rgb(255, 255, 255);
        }
        
        /* Responsive Styles */
        @media (max-width: 1024px) {
          .main-container { flex-direction: column; }
          .left-panel, .right-panel { width: 100% !important; min-height: 50vh; }
          .left-panel { order: 2; padding: 2rem !important; }
          .right-panel { order: 1; padding: 1rem 2rem !important; }
          .form-container { max-width: 400px !important; }
          .main-title { font-size: 2.5rem !important; }
          .subtitle { font-size: 2rem !important; }
        }
        
        @media (max-width: 768px) {
          .left-panel, .right-panel { min-height: 40vh; }
          .left-panel { padding: 1.5rem !important; }
          .right-panel { padding: 1rem !important; }
          .main-title { font-size: 2rem !important; margin-bottom: 0.5rem !important; }
          .subtitle { font-size: 1.5rem !important; margin-bottom: 1rem !important; }
          .description { font-size: 0.75rem !important; }
          .form-container { max-width: 320px !important; }
          .browser-header { padding: 0.5rem !important; }
        }
        
        @media (max-width: 480px) {
          .left-panel, .right-panel { min-height: 35vh; }
          .left-panel { padding: 1rem !important; }
          .right-panel { padding: 0.75rem !important; }
          .main-title { font-size: 1.75rem !important; }
          .subtitle { font-size: 1.25rem !important; }
          .form-container { max-width: 280px !important; }
          .form-title { font-size: 1.5rem !important; }
          .input-field { padding: 0.75rem !important; font-size: 0.875rem !important; }
          .submit-button { padding: 0.75rem 1rem !important; }
          .browser-header { display: none !important; }
        }
        
        @media (max-width: 360px) {
          .left-panel, .right-panel { min-height: 30vh; }
          .main-title { font-size: 1.5rem !important; }
          .subtitle { font-size: 1.125rem !important; }
          .form-container { max-width: 250px !important; }
          .input-field { padding: 0.625rem !important; font-size: 0.8rem !important; }
        }
        
        /* Component Styles */
        .left-panel-gradient {
            background: linear-gradient(135deg, var(--left-primary) 0%, var(--left-secondary) 50%, var(--left-accent) 100%);
        }
        
        .right-panel-gradient {
            background: linear-gradient(180deg, var(--right-primary) 0%, var(--right-secondary) 30%, var(--right-light) 70%, var(--right-accent) 100%);
        }
        
        .custom-button {
            background-color: var(--button-color);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }
        
        .custom-button:hover {
            background-color: var(--button-hover);
            transform: scale(1.05);
        }
        
        .custom-input {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--text-primary);
            backdrop-filter: blur(10px);
        }
        
        .custom-input::placeholder {
            color: var(--text-muted);
        }
        
        .custom-input:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 2px var(--input-focus);
        }
        
        .text-primary-custom {
            color: var(--text-primary);
        }
        
        .text-secondary-custom {
            color: var(--text-secondary);
        }
        
        .text-muted-custom {
            color: var(--text-muted);
        }

        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        
        .alert-success {
            background-color: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }
        
        .alert-info {
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #93c5fd;
        }
    </style>
</head>
<body class="bg-black min-h-screen flex">
    <div class="w-full h-screen flex main-container">
        <!-- Left Panel - Image Section -->
        <div class="w-1/2 left-panel left-panel-gradient relative flex flex-col justify-end p-12 text-white">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-gradient-to-br from-black/40 via-transparent to-transparent flex items-center justify-center">
                <img src="assets/images/mtn.jpg" alt="Mountain landscape" class="absolute inset-0 w-full h-full object-cover">
            </div>
            
            <!-- Bottom Text Content -->
            <div class="relative z-10 text-center">
                <h1 class="main-title text-5xl font-light mb-2 text-primary-custom">
                    <?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?>
                </h1>
                <h2 class="subtitle text-4xl font-light mb-4 text-primary-custom">
                    <?php echo __('everything_you_need_to_grow') ?: 'Everything you need to Grow'; ?>
                </h2>
                <p class="description text-sm text-secondary-custom">
                    <?php echo __('powered_by_entropic') ?: 'Powered by <strong>Entropic Networks</strong>.'; ?>
                </p>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="w-1/2 right-panel right-panel-gradient flex flex-col justify-center items-center relative px-16">
            
            <!-- Browser-like header -->
            <div class="browser-header absolute top-0 left-0 right-0 bg-transparent p-3 flex items-center gap-2 z-20">
                <div class="flex-1 text-center">
                    <div class="bg-black/20 backdrop-blur-sm rounded px-4 py-1 text-white text-sm inline-block border border-white/10">
                        <?php echo __('manage_customers_boost_business') ?: 'Manage customers and boost your business'; ?>
                    </div>
                </div>
            </div>

            <!-- Login Form Content -->
            <div class="form-container w-full max-w-sm">
                <!-- Alert Messages -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger mb-4" role="alert">
                        <?php echo sanitizeOutput(__($error) ?: $error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success mb-4" role="alert">
                        <?php echo sanitizeOutput(__($success) ?: $success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['logout_success'])): ?>
                    <div class="alert alert-success mb-4" role="alert">
                        <?php echo sanitizeOutput($_SESSION['logout_success']); unset($_SESSION['logout_success']); ?>
                    </div>
                <?php endif; ?>

                <p class="text-sm mb-2 text-secondary-custom">
                    <?php echo __('login_your_account') ?: 'Login your account'; ?>
                </p>
                <h3 class="form-title text-3xl font-bold mb-6 text-primary-custom">
                    <?php echo __('welcome_back') ?: 'Welcome Back!'; ?>
                </h3>
                <p class="text-sm mb-8 text-secondary-custom">
                    <?php echo __('enter_email_password') ?: 'Enter your email and password'; ?>
                </p>

                <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <!-- Username/Email Input -->
                    <div>
                        <label class="text-sm block mb-2 text-secondary-custom">
                            <?php echo __('username_or_email') ?: 'Username or Email'; ?>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="username"
                                placeholder="<?php echo __('enter_username_email') ?: 'Enter your username or email'; ?>"
                                value="<?php echo sanitizeOutput($_POST['username'] ?? ''); ?>"
                                class="input-field custom-input w-full rounded-lg px-4 py-3"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="text-sm block mb-2 text-secondary-custom">
                            <?php echo __('password') ?: 'Password'; ?>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password"
                                placeholder="<?php echo __('enter_password') ?: 'Enter your password'; ?>"
                                class="input-field custom-input w-full rounded-lg px-4 py-3"
                                required
                            >
                        </div>
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-right">
                        <a href="/crm-project/public/index.php?module=auth&action=reset" 
                           class="text-sm hover:opacity-100 underline transition-opacity text-secondary-custom">
                            <?php echo __('forgot_password') ?: 'Forgot Password?'; ?>
                        </a>
                    </div>

                    <!-- Sign In Button -->
                    <button 
                        type="submit"
                        class="submit-button custom-button w-full font-medium py-3 px-4 rounded-lg shadow-lg hover:shadow-xl"
                    >
                        <?php echo __('sign_in') ?: 'Sign in'; ?>
                    </button>
                </form>

                <!-- Debug Info -->
                <?php if (APP_DEBUG): ?>
                    <div class="mt-4 text-center">
                        <small class="text-muted-custom text-xs">
                            <?php echo __('debug_mode_credentials') ?: 'Debug Mode: Use leon/temporal2024#'; ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="assets/js/auth.js"></script>
</body>
</html>