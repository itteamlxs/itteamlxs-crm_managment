/**
 * Security Manager
 * Client-side security protections for CRM system
 */

window.SecurityManager = {
    
    // Configuration
    config: {
        // Page Reload Protection
        maxReloads: 5,
        reloadWindow: 60000, // 1 minute
        blockDuration: 300000, // 5 minutes
        
        // Form Submission Protection
        maxFormSubmits: 5,
        submitWindow: 300000, // 5 minutes
        
        // General Rate Limiting
        maxRequests: 10,
        requestWindow: 60000, // 1 minute
        
        // Warning thresholds
        warningThreshold: 0.8 // 80% of limit
    },
    
    // Storage keys
    storageKeys: {
        reloads: 'security_reloads',
        formSubmits: 'security_form_submits',
        requests: 'security_requests',
        blocked: 'security_blocked'
    },
    
    // Anti-DOS Protection
    antiDOS: {
        
        /**
         * Initialize page reload protection
         */
        initReloadProtection() {
            const self = SecurityManager.antiDOS;
            
            // Detect page reload
            const navigation = performance.getEntriesByType('navigation')[0];
            if (navigation && navigation.type === 'reload') {
                self.handleReload();
            }
            
            // Listen for manual reload attempts
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey && e.key === 'r') || 
                    (e.ctrlKey && e.key === 'R') || 
                    e.key === 'F5') {
                    self.handleReload();
                }
            });
            
            // Initialize developer tools protection
            SecurityManager.devToolsProtection.init();
            
            // Check if already blocked
            self.checkBlocked();
        },
        
        /**
         * Handle page reload detection
         */
        handleReload() {
            const now = Date.now();
            const reloads = SecurityManager.utils.getStorageData(SecurityManager.storageKeys.reloads, []);
            
            // Clean old entries
            const cleanReloads = reloads.filter(time => now - time < SecurityManager.config.reloadWindow);
            
            // Add current reload
            cleanReloads.push(now);
            
            // Save updated data
            SecurityManager.utils.setStorageData(SecurityManager.storageKeys.reloads, cleanReloads);
            
            // Check if limit exceeded
            if (cleanReloads.length >= SecurityManager.config.maxReloads) {
                SecurityManager.antiDOS.triggerBlock('Intentos de petición saturada');
                return;
            }
            
            // Show warning if approaching limit
            if (cleanReloads.length >= Math.floor(SecurityManager.config.maxReloads * SecurityManager.config.warningThreshold)) {
                SecurityManager.ui.showWarning(
                    `Advertencia: ${cleanReloads.length}/${SecurityManager.config.maxReloads} recargas detectadas. Evita recargar constantemente.`
                );
            }
        },
        
        /**
         * Check if user is currently blocked
         */
        checkBlocked() {
            const blocked = SecurityManager.utils.getStorageData(SecurityManager.storageKeys.blocked);
            
            if (blocked && Date.now() < blocked.until) {
                const timeLeft = Math.ceil((blocked.until - Date.now()) / 60000);
                SecurityManager.ui.showBlockOverlay(blocked.reason, timeLeft);
                return true;
            }
            
            // Clean expired block
            if (blocked) {
                SecurityManager.utils.removeStorageData(SecurityManager.storageKeys.blocked);
            }
            
            return false;
        },
        
        /**
         * Trigger security block
         */
        triggerBlock(reason = 'Actividad sospechosa detectada') {
            const until = Date.now() + SecurityManager.config.blockDuration;
            const timeLeft = Math.ceil(SecurityManager.config.blockDuration / 60000);
            
            SecurityManager.utils.setStorageData(SecurityManager.storageKeys.blocked, {
                reason: reason,
                until: until,
                timestamp: Date.now()
            });
            
            // Log security event
            SecurityManager.logger.logEvent('SECURITY_BLOCK', {
                reason: reason,
                duration: SecurityManager.config.blockDuration,
                timestamp: Date.now()
            });
            
            // Show block overlay
            SecurityManager.ui.showBlockOverlay(reason, timeLeft);
        },
        
        /**
         * Form submission protection
         */
        initFormProtection() {
            document.addEventListener('submit', (e) => {
                if (!SecurityManager.antiDOS.checkFormSubmitLimit()) {
                    e.preventDefault();
                    SecurityManager.ui.showWarning('Demasiados envíos de formulario. Espera un momento.');
                    return false;
                }
            });
        },
        
        /**
         * Check form submission limits
         */
        checkFormSubmitLimit() {
            const now = Date.now();
            const submits = SecurityManager.utils.getStorageData(SecurityManager.storageKeys.formSubmits, []);
            
            // Clean old entries
            const cleanSubmits = submits.filter(time => now - time < SecurityManager.config.submitWindow);
            
            // Check limit
            if (cleanSubmits.length >= SecurityManager.config.maxFormSubmits) {
                SecurityManager.antiDOS.triggerBlock('Demasiados envíos de formulario');
                return false;
            }
            
            // Add current submit
            cleanSubmits.push(now);
            SecurityManager.utils.setStorageData(SecurityManager.storageKeys.formSubmits, cleanSubmits);
            
            return true;
        }
    },
    
    // Developer Tools Protection
    devToolsProtection: {
        
        /**
         * Initialize developer tools blocking
         */
        init() {
            const self = SecurityManager.devToolsProtection;
            
            // Block common developer shortcuts
            self.blockKeyboardShortcuts();
            
            // Block right-click context menu
            self.blockContextMenu();
            
            // Detect DevTools opening (basic detection)
            self.detectDevTools();
            
            // Block text selection (optional)
            self.blockTextSelection();
        },
        
        /**
         * Block keyboard shortcuts for developer tools
         */
        blockKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                const blockedShortcuts = [
                    // View Source
                    { ctrl: true, key: 'u' },
                    { ctrl: true, key: 'U' },
                    
                    // Developer Tools
                    { key: 'F12' },
                    { ctrl: true, shift: true, key: 'I' },
                    { ctrl: true, shift: true, key: 'i' },
                    { ctrl: true, shift: true, key: 'J' },
                    { ctrl: true, shift: true, key: 'j' },
                    { ctrl: true, shift: true, key: 'C' },
                    { ctrl: true, shift: true, key: 'c' },
                    
                    // Console
                    { ctrl: true, shift: true, key: 'K' },
                    { ctrl: true, shift: true, key: 'k' },
                    
                    // Network tab
                    { ctrl: true, shift: true, key: 'E' },
                    { ctrl: true, shift: true, key: 'e' },
                    
                    // Sources/Debugger
                    { ctrl: true, shift: true, key: 'S' },
                    { ctrl: true, shift: true, key: 's' },
                    
                    // Application/Storage
                    { ctrl: true, shift: true, key: 'A' },
                    { ctrl: true, shift: true, key: 'a' },
                    
                    // Print (could be used to see source)
                    { ctrl: true, key: 'p' },
                    { ctrl: true, key: 'P' },
                    
                    // Save page
                    { ctrl: true, key: 's' },
                    { ctrl: true, key: 'S' }
                ];
                
                const isBlocked = blockedShortcuts.some(shortcut => {
                    return (!shortcut.ctrl || e.ctrlKey) &&
                           (!shortcut.shift || e.shiftKey) &&
                           (!shortcut.alt || e.altKey) &&
                           e.key === shortcut.key;
                });
                
                if (isBlocked) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    SecurityManager.ui.showWarning('Acceso a herramientas de desarrollador bloqueado por seguridad.');
                    
                    // Log attempt
                    SecurityManager.logger.logEvent('DEV_TOOLS_ATTEMPT', {
                        key: e.key,
                        ctrlKey: e.ctrlKey,
                        shiftKey: e.shiftKey,
                        altKey: e.altKey
                    });
                    
                    return false;
                }
            });
        },
        
        /**
         * Block right-click context menu
         */
        blockContextMenu() {
            document.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                
                SecurityManager.ui.showWarning('Menú contextual deshabilitado por seguridad.');
                
                SecurityManager.logger.logEvent('CONTEXT_MENU_ATTEMPT', {
                    target: e.target.tagName,
                    x: e.clientX,
                    y: e.clientY
                });
                
                return false;
            });
        },
        
        /**
         * Basic DevTools detection
         */
        detectDevTools() {
            let devtools = {
                open: false,
                orientation: null
            };
            
            const threshold = 160;
            
            setInterval(() => {
                if (window.outerHeight - window.innerHeight > threshold || 
                    window.outerWidth - window.innerWidth > threshold) {
                    
                    if (!devtools.open) {
                        devtools.open = true;
                        
                        SecurityManager.ui.showWarning('Herramientas de desarrollador detectadas. El acceso puede estar limitado.');
                        
                        SecurityManager.logger.logEvent('DEV_TOOLS_DETECTED', {
                            outerHeight: window.outerHeight,
                            innerHeight: window.innerHeight,
                            outerWidth: window.outerWidth,
                            innerWidth: window.innerWidth
                        });
                        
                        // Optional: Blur content or show overlay
                        SecurityManager.devToolsProtection.obfuscateContent();
                    }
                } else {
                    devtools.open = false;
                    SecurityManager.devToolsProtection.restoreContent();
                }
            }, 500);
        },
        
        /**
         * Block text selection (optional)
         */
        blockTextSelection() {
            // CSS to prevent selection - MORE SPECIFIC to avoid affecting images
            const style = document.createElement('style');
            style.textContent = `
                body, p, h1, h2, h3, h4, h5, h6, div, span, a {
                    -webkit-user-select: none !important;
                    -moz-user-select: none !important;
                    -ms-user-select: none !important;
                    user-select: none !important;
                }
                
                /* Explicitly allow images and media */
                img, video, canvas, svg {
                    -webkit-user-select: auto !important;
                    -moz-user-select: auto !important;
                    -ms-user-select: auto !important;
                    user-select: auto !important;
                }
                
                /* Allow selection in input fields */
                input, textarea, [contenteditable="true"] {
                    -webkit-user-select: text !important;
                    -moz-user-select: text !important;
                    -ms-user-select: text !important;
                    user-select: text !important;
                }
            `;
            document.head.appendChild(style);
            
            // Block drag operations
            document.addEventListener('dragstart', (e) => {
                e.preventDefault();
                return false;
            });
            
            // Block select all
            document.addEventListener('keydown', (e) => {
                if (e.ctrlKey && (e.key === 'a' || e.key === 'A')) {
                    const target = e.target;
                    if (target.tagName !== 'INPUT' && 
                        target.tagName !== 'TEXTAREA' && 
                        !target.contentEditable) {
                        e.preventDefault();
                        return false;
                    }
                }
            });
        },
        
        /**
         * Obfuscate content when dev tools detected
         */
        obfuscateContent() {
            if (!document.querySelector('#devToolsOverlay')) {
                const overlay = document.createElement('div');
                overlay.id = 'devToolsOverlay';
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    backdrop-filter: blur(10px);
                    pointer-events: none;
                `;
                
                overlay.innerHTML = `
                    <div style="color: white; text-align: center; font-size: 18px;">
                        <div style="font-size: 48px; margin-bottom: 20px;">🔒</div>
                        <div>Contenido protegido</div>
                        <div style="font-size: 14px; opacity: 0.7; margin-top: 10px;">Cierra las herramientas de desarrollador</div>
                    </div>
                `;
                
                document.body.appendChild(overlay);
            }
        },
        
        /**
         * Restore content when dev tools closed
         */
        restoreContent() {
            const overlay = document.querySelector('#devToolsOverlay');
            if (overlay) {
                overlay.remove();
            }
        },
        
        /**
         * Console warning message
         */
        showConsoleWarning() {
            if (typeof console !== 'undefined') {
                console.clear();
                console.log('%c🚫 ALTO - ZONA RESTRINGIDA', 
                    'color: red; font-size: 40px; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);');
                console.log('%c⚠️  ADVERTENCIA DE SEGURIDAD', 
                    'color: orange; font-size: 20px; font-weight: bold;');
                console.log('%cSi alguien te pidió que copies y pegues algo aquí, es una estafa.', 
                    'color: white; font-size: 16px;');
                console.log('%cEsta función es para desarrolladores. El acceso no autorizado puede comprometer tu cuenta.', 
                    'color: white; font-size: 14px;');
                console.log('%c❌ El acceso a esta consola ha sido registrado por seguridad.', 
                    'color: red; font-size: 14px; font-weight: bold;');
            }
        }
    },
    rateLimit: {
        
        /**
         * Check general rate limit
         */
        checkLimit(key = 'general') {
            const now = Date.now();
            const requests = SecurityManager.utils.getStorageData(SecurityManager.storageKeys.requests, {});
            
            if (!requests[key]) {
                requests[key] = [];
            }
            
            // Clean old entries
            requests[key] = requests[key].filter(time => now - time < SecurityManager.config.requestWindow);
            
            // Check limit
            if (requests[key].length >= SecurityManager.config.maxRequests) {
                return false;
            }
            
            // Add current request
            requests[key].push(now);
            SecurityManager.utils.setStorageData(SecurityManager.storageKeys.requests, requests);
            
            return true;
        },
        
        /**
         * Get remaining requests for key
         */
        getRemainingRequests(key = 'general') {
            const now = Date.now();
            const requests = SecurityManager.utils.getStorageData(SecurityManager.storageKeys.requests, {});
            
            if (!requests[key]) {
                return SecurityManager.config.maxRequests;
            }
            
            // Clean old entries
            const activeRequests = requests[key].filter(time => now - time < SecurityManager.config.requestWindow);
            
            return Math.max(0, SecurityManager.config.maxRequests - activeRequests.length);
        }
    },
    
    // Input Validation
    inputValidation: {
        
        /**
         * Sanitize input to prevent XSS
         */
        sanitizeInput(input) {
            if (typeof input !== 'string') return input;
            
            return input
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#x27;')
                .replace(/\//g, '&#x2F;');
        },
        
        /**
         * Validate email format
         */
        validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        /**
         * Validate password strength
         */
        validatePassword(password) {
            const errors = [];
            
            if (password.length < 8) {
                errors.push('La contraseña debe tener al menos 8 caracteres');
            }
            
            if (!/[A-Z]/.test(password)) {
                errors.push('Debe contener al menos una mayúscula');
            }
            
            if (!/[a-z]/.test(password)) {
                errors.push('Debe contener al menos una minúscula');
            }
            
            if (!/[0-9]/.test(password)) {
                errors.push('Debe contener al menos un número');
            }
            
            if (!/[^a-zA-Z0-9]/.test(password)) {
                errors.push('Debe contener al menos un carácter especial');
            }
            
            return {
                valid: errors.length === 0,
                errors: errors
            };
        }
    },
    
    // Session Security
    sessionSecurity: {
        
        /**
         * Initialize session monitoring
         */
        init() {
            // Check for multiple tabs
            SecurityManager.sessionSecurity.checkMultipleTabs();
            
            // Monitor session timeout
            SecurityManager.sessionSecurity.monitorTimeout();
            
            // Detect session hijacking attempts
            SecurityManager.sessionSecurity.detectHijacking();
        },
        
        /**
         * Check for multiple tabs
         */
        checkMultipleTabs() {
            const tabId = 'tab_' + Date.now() + '_' + Math.random();
            sessionStorage.setItem('currentTab', tabId);
            
            // Check periodically for other tabs
            setInterval(() => {
                if (sessionStorage.getItem('currentTab') !== tabId) {
                    SecurityManager.ui.showWarning('Sesión detectada en otra pestaña. Por seguridad, algunas funciones pueden estar limitadas.');
                }
            }, 5000);
        },
        
        /**
         * Monitor session timeout
         */
        monitorTimeout() {
            let lastActivity = Date.now();
            const timeout = 3600000; // 1 hour
            
            // Update last activity on user interaction
            ['click', 'keydown', 'mousemove', 'scroll'].forEach(event => {
                document.addEventListener(event, () => {
                    lastActivity = Date.now();
                });
            });
            
            // Check timeout periodically
            setInterval(() => {
                if (Date.now() - lastActivity > timeout) {
                    SecurityManager.ui.showWarning('Sesión inactiva. La sesión expirará pronto.');
                    
                    // Redirect to login after additional time
                    setTimeout(() => {
                        window.location.href = '/crm-project/public/index.php?module=auth&action=login';
                    }, 60000);
                }
            }, 60000);
        },
        
        /**
         * Detect potential session hijacking
         */
        detectHijacking() {
            const userAgent = navigator.userAgent;
            const screenResolution = screen.width + 'x' + screen.height;
            const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            
            const fingerprint = btoa(userAgent + screenResolution + timezone);
            
            const storedFingerprint = sessionStorage.getItem('browserFingerprint');
            
            if (storedFingerprint && storedFingerprint !== fingerprint) {
                SecurityManager.logger.logEvent('POTENTIAL_HIJACKING', {
                    stored: storedFingerprint,
                    current: fingerprint,
                    timestamp: Date.now()
                });
                
                SecurityManager.ui.showWarning('Cambios de entorno detectados. Por seguridad, verifica tu identidad.');
            } else {
                sessionStorage.setItem('browserFingerprint', fingerprint);
            }
        }
    },
    
    // UI Components
    ui: {
        
        /**
         * Show warning message
         */
        showWarning(message, duration = 5000) {
            // Remove existing warnings
            const existing = document.querySelectorAll('.security-warning');
            existing.forEach(el => el.remove());
            
            const warning = document.createElement('div');
            warning.className = 'security-warning';
            warning.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: rgba(255, 193, 7, 0.95);
                color: #000;
                padding: 15px 20px;
                border-radius: 8px;
                border-left: 4px solid #ff9800;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                z-index: 10000;
                max-width: 400px;
                font-size: 14px;
                backdrop-filter: blur(10px);
                animation: slideIn 0.3s ease;
            `;
            
            warning.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="font-size: 18px;">⚠️</div>
                    <div>${SecurityManager.utils.escapeHtml(message)}</div>
                    <button onclick="this.parentElement.parentElement.remove()" style="margin-left: auto; background: none; border: none; font-size: 18px; cursor: pointer;">×</button>
                </div>
            `;
            
            // Add animation styles if not present
            if (!document.querySelector('#securityAnimations')) {
                const style = document.createElement('style');
                style.id = 'securityAnimations';
                style.textContent = `
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes fadeOut {
                        from { opacity: 1; }
                        to { opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
            
            document.body.appendChild(warning);
            
            // Auto-remove after duration
            setTimeout(() => {
                if (warning.parentElement) {
                    warning.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => warning.remove(), 300);
                }
            }, duration);
        },
        
        /**
         * Show blocking overlay
         */
        showBlockOverlay(reason, minutesLeft) {
            // Remove existing overlay
            const existing = document.querySelector('#securityBlockOverlay');
            if (existing) existing.remove();
            
            const overlay = document.createElement('div');
            overlay.id = 'securityBlockOverlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.95);
                z-index: 99999;
                display: flex;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(5px);
            `;
            
            overlay.innerHTML = `
                <div style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 40px; border-radius: 15px; text-align: center; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <div style="font-size: 64px; margin-bottom: 20px;">🚫</div>
                    <h2 style="margin: 0 0 15px 0; font-size: 24px;">Acceso Temporalmente Bloqueado</h2>
                    <p style="margin: 0 0 20px 0; font-size: 16px; opacity: 0.9;">${SecurityManager.utils.escapeHtml(reason)}</p>
                    <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <div style="font-size: 18px; font-weight: bold;" id="countdown">Vuelve en ${minutesLeft} minutos</div>
                    </div>
                    <p style="margin: 0; font-size: 14px; opacity: 0.8;">Si crees que esto es un error, contacta al administrador del sistema.</p>
                </div>
            `;
            
            document.body.appendChild(overlay);
            
            // Disable all interactions
            document.body.style.pointerEvents = 'none';
            overlay.style.pointerEvents = 'all';
            
            // Update countdown
            SecurityManager.ui.startCountdown(minutesLeft);
        },
        
        /**
         * Start countdown timer
         */
        startCountdown(minutes) {
            let totalSeconds = minutes * 60;
            
            const updateCountdown = () => {
                const mins = Math.floor(totalSeconds / 60);
                const secs = totalSeconds % 60;
                
                const countdownEl = document.getElementById('countdown');
                if (countdownEl) {
                    if (totalSeconds > 0) {
                        countdownEl.textContent = `Vuelve en ${mins}:${secs.toString().padStart(2, '0')}`;
                        totalSeconds--;
                        setTimeout(updateCountdown, 1000);
                    } else {
                        // Unblock and reload page
                        SecurityManager.utils.removeStorageData(SecurityManager.storageKeys.blocked);
                        window.location.reload();
                    }
                }
            };
            
            updateCountdown();
        }
    },
    
    // Utilities
    utils: {
        
        /**
         * Get data from sessionStorage
         */
        getStorageData(key, defaultValue = null) {
            try {
                const data = sessionStorage.getItem(key);
                return data ? JSON.parse(data) : defaultValue;
            } catch (e) {
                return defaultValue;
            }
        },
        
        /**
         * Set data to sessionStorage
         */
        setStorageData(key, value) {
            try {
                sessionStorage.setItem(key, JSON.stringify(value));
                return true;
            } catch (e) {
                console.error('Error saving to sessionStorage:', e);
                return false;
            }
        },
        
        /**
         * Remove data from sessionStorage
         */
        removeStorageData(key) {
            try {
                sessionStorage.removeItem(key);
                return true;
            } catch (e) {
                return false;
            }
        },
        
        /**
         * Escape HTML to prevent XSS
         */
        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },
        
        /**
         * Generate random ID
         */
        generateId() {
            return Date.now().toString(36) + Math.random().toString(36).substr(2);
        }
    },
    
    // Logger
    logger: {
        
        /**
         * Log security event
         */
        logEvent(type, data = {}) {
            const event = {
                type: type,
                timestamp: new Date().toISOString(),
                url: window.location.href,
                userAgent: navigator.userAgent,
                data: data
            };
            
            // Store locally for debugging
            const logs = SecurityManager.utils.getStorageData('security_logs', []);
            logs.push(event);
            
            // Keep only last 50 events
            if (logs.length > 50) {
                logs.splice(0, logs.length - 50);
            }
            
            SecurityManager.utils.setStorageData('security_logs', logs);
            
            // Send to server if needed (uncomment for production)
            /*
            fetch('/crm-project/public/api/security-log.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(event)
            }).catch(e => console.error('Failed to log security event:', e));
            */
        },
        
        /**
         * Get security logs
         */
        getLogs() {
            return SecurityManager.utils.getStorageData('security_logs', []);
        }
    },
    
    // Main initialization
    init() {
        // Initialize all security modules
        SecurityManager.antiDOS.initReloadProtection();
        SecurityManager.antiDOS.initFormProtection();
        SecurityManager.sessionSecurity.init();
        
        // Show console warning
        SecurityManager.devToolsProtection.showConsoleWarning();
        
        // Log initialization
        SecurityManager.logger.logEvent('SECURITY_INIT', {
            url: window.location.href,
            timestamp: Date.now()
        });
        
        console.log('%cSecurity Manager initialized', 'color: #00ff00; font-weight: bold;');
        console.log('%cProtections active: Anti-DOS, Rate Limiting, Session Security, DevTools Protection', 'color: #888;');
    }
};

// Auto-initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', SecurityManager.init);
} else {
    SecurityManager.init();
}

// Export for use in other modules
window.SecurityManager = SecurityManager;