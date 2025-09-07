<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('password_reset') ?: 'Reset Password'; ?></title>
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
          .submit-button { padding: 0.75rem 1rem !important; }
          .browser-header { display: none !important; }
        }
        
        @media (max-width: 360px) {
          .left-panel, .right-panel { min-height: 30vh; }
          .main-title { font-size: 1.5rem !important; }
          .subtitle { font-size: 1.125rem !important; }
          .form-container { max-width: 250px !important; }
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
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0.75rem;
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: var(--text-secondary);
            backdrop-filter: blur(10px);
        }
        
        .alert h5 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .info-icon {
            width: 3rem;
            height: 3rem;
            background-color: rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
        }

        .info-icon svg {
            width: 1.5rem;
            height: 1.5rem;
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

        <!-- Right Panel - Reset Info -->
        <div class="w-1/2 right-panel right-panel-gradient flex flex-col justify-center items-center relative px-16">
            
            <!-- Browser-like header -->
            <div class="browser-header absolute top-0 left-0 right-0 bg-transparent p-3 flex items-center gap-2 z-20">
                <div class="flex-1 text-center">
                    <div class="bg-black/20 backdrop-blur-sm rounded px-4 py-1 text-white text-sm inline-block border border-white/10">
                        <?php echo __('password_reset_help') ?: 'Password reset assistance'; ?>
                    </div>
                </div>
            </div>

            <!-- Reset Info Content -->
            <div class="form-container w-full max-w-sm text-center">
                <p class="text-sm mb-2 text-secondary-custom">
                    <?php echo __('password_assistance') ?: 'Password assistance'; ?>
                </p>
                <h3 class="form-title text-3xl font-bold mb-6 text-primary-custom">
                    <?php echo __('password_reset') ?: 'Password Reset'; ?>
                </h3>
                <p class="text-sm mb-8 text-secondary-custom">
                    <?php echo __('contact_admin_for_help') ?: 'Contact your administrator for assistance'; ?>
                </p>

                <!-- Info Alert -->
                <div class="alert" role="alert">
                    <div class="info-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold">
                        <?php echo __('password_reset_not_available') ?: 'Password Reset Not Available'; ?>
                    </h5>
                    <p class="text-sm">
                        <?php echo __('contact_admin_password') ?: 'If you need to reset your password, please contact your system administrator.'; ?>
                    </p>
                </div>

                <!-- Back to Login Button -->
                <a href="/crm-project/public/index.php?module=auth&action=login" 
                   class="submit-button custom-button w-full font-medium py-3 px-4 rounded-lg shadow-lg hover:shadow-xl inline-block text-center no-underline">
                    <?php echo __('back_to_login') ?: 'Back to Login'; ?>
                </a>
            </div>
        </div>
    </div>
</body>
</html>