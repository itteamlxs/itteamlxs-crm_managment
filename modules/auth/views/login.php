<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --left-primary: #00060c;
            --left-secondary: #00060c;
            --left-accent: #00060c;
            --right-primary: #002fff;
            --right-secondary: #07006e;
            --right-light: #00060c;
            --right-accent: #00060c;
            --button-color: #0400ff;
            --button-hover: #0104c5;
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.8);
            --text-muted: rgba(255, 255, 255, 0.6);
            --input-bg: rgba(255, 255, 255, 0.1);
            --input-border: rgba(255, 255, 255, 0.2);
            --input-focus: rgb(255, 255, 255);
        }
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
        .custom-input::placeholder { color: var(--text-muted); }
        .custom-input:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 2px var(--input-focus);
        }
        .text-primary-custom { color: var(--text-primary); }
        .text-secondary-custom { color: var(--text-secondary); }
    </style>
</head>
<body class="bg-black min-h-screen flex">
    <div class="w-full h-screen flex main-container">
        <!-- Left Panel -->
        <div class="w-1/2 left-panel left-panel-gradient relative flex flex-col justify-end p-12 text-white">
            <div class="absolute inset-0 bg-gradient-to-br from-black/40 via-transparent to-transparent flex items-center justify-center">
                <div class="text-center text-gray-300 text-lg">
                    <img src="#" alt="Mountain landscape" class="absolute inset-0 w-full h-full object-cover"> <!-- ruta original: mtn.jpg -->
                </div>
            </div>
            <div class="relative z-10 text-center">
                <h1 class="main-title text-5xl font-light mb-2 text-primary-custom"><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?></h1>
                <h2 class="subtitle text-4xl font-light mb-4 text-primary-custom">Everything you need to Grow</h2>
                <p class="description text-sm text-secondary-custom">Powered by <strong>Entropic Networks</strong>.</p>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="w-1/2 right-panel right-panel-gradient flex flex-col justify-center items-center relative px-16">
            <div class="browser-header absolute top-0 left-0 right-0 bg-transparent p-3 flex items-center gap-2 z-20">
                <div class="flex-1 text-center">
                    <div class="bg-black/20 backdrop-blur-sm rounded px-4 py-1 text-white text-sm inline-block border border-white/10">
                        Manage customers and boost your business
                    </div>
                </div>
            </div>

            <!-- Login Form -->
            <div class="form-container w-full max-w-sm">
                <?php if (!empty($error)): ?>
                    <div class="mb-4 text-red-400 text-sm">
                        <?php echo sanitizeOutput($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="mb-4 text-green-400 text-sm">
                        <?php echo sanitizeOutput($success); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['logout_success'])): ?>
                    <div class="mb-4 text-green-400 text-sm">
                        <?php echo sanitizeOutput($_SESSION['logout_success']); unset($_SESSION['logout_success']); ?>
                    </div>
                <?php endif; ?>

                <p class="text-sm mb-2 text-secondary-custom"><?php echo __('please_sign_in') ?: 'Please sign in'; ?></p>
                <h3 class="form-title text-3xl font-bold mb-6 text-primary-custom">Welcome Back!</h3>
                <p class="text-sm mb-8 text-secondary-custom">Enter your username/email and password</p>

                <form method="POST" action="/crm-project/public/index.php?module=auth&action=login" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                    <div>
                        <label class="text-sm block mb-2 text-secondary-custom"><?php echo __('username_or_email') ?: 'Username or Email'; ?></label>
                        <input 
                            type="text" 
                            id="username"
                            name="username"
                            value="<?php echo sanitizeOutput($_POST['username'] ?? ''); ?>"
                            placeholder="Enter your username or email"
                            class="input-field custom-input w-full rounded-lg px-4 py-3"
                            required
                        >
                    </div>

                    <div>
                        <label class="text-sm block mb-2 text-secondary-custom"><?php echo __('password') ?: 'Password'; ?></label>
                        <input 
                            type="password" 
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            class="input-field custom-input w-full rounded-lg px-4 py-3"
                            required
                        >
                    </div>

                    <div class="text-right">
                        <a href="/crm-project/public/index.php?module=auth&action=reset" class="text-sm hover:opacity-100 underline transition-opacity text-secondary-custom">
                            <?php echo __('forgot_password') ?: 'Forgot your password?'; ?>
                        </a>
                    </div>

                    <button 
                        type="submit"
                        class="submit-button custom-button w-full font-medium py-3 px-4 rounded-lg shadow-lg hover:shadow-xl"
                    >
                        <?php echo __('sign_in') ?: 'Sign In'; ?>
                    </button>
                </form>
            </div>

            <div class="text-center mt-6">
                <small class="text-muted-custom">
                    <?php if (APP_DEBUG): ?>
                        <?php echo __('debug_mode_credentials') ?: 'Debug Mode: Use leon/temporal2024#'; ?>
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</body>
</html>
