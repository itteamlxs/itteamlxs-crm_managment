/**
 * Users Management JavaScript
 * Handles user interactions and validations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initUserForm();
    initProfilePictureHandler();
    initPasswordValidation();
    initLanguageSwitch();
});

/**
 * Initialize user form validation and interactions
 */
function initUserForm() {
    const userForm = document.getElementById('userForm');
    if (!userForm) return;
    
    // Real-time username validation
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            validateUsername(this);
        });
        
        usernameInput.addEventListener('blur', function() {
            checkUsernameAvailability(this);
        });
    }
    
    // Real-time email validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            validateEmail(this);
        });
        
        emailInput.addEventListener('blur', function() {
            checkEmailAvailability(this);
        });
    }
    
    // Form submission validation
    userForm.addEventListener('submit', function(e) {
        if (!validateUserForm()) {
            e.preventDefault();
            return false;
        }
    });
}

/**
 * Initialize profile picture upload handler
 */
function initProfilePictureHandler() {
    const profileInput = document.getElementById('profilePicture');
    if (!profileInput) return;
    
    profileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Validate file
        if (!validateImageFile(file)) {
            this.value = '';
            return;
        }
        
        // Preview image
        previewProfileImage(file);
    });
}

/**
 * Initialize password validation for new users
 */
function initPasswordValidation() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            validatePasswordStrength(this);
        });
    }
    
    if (confirmInput) {
        confirmInput.addEventListener('input', function() {
            validatePasswordMatch();
        });
    }
}

/**
 * Initialize language switching functionality
 */
function initLanguageSwitch() {
    const languageSelect = document.getElementById('language');
    if (!languageSelect) return;
    
    languageSelect.addEventListener('change', function() {
        // Show confirmation if changing language
        if (this.dataset.original && this.value !== this.dataset.original) {
            showLanguageChangeNotice(this.value);
        }
    });
    
    // Store original value
    languageSelect.dataset.original = languageSelect.value;
}

/**
 * Validate username format
 */
function validateUsername(input) {
    const username = input.value.trim();
    const pattern = /^[a-zA-Z0-9_]{3,50}$/;
    
    clearValidationFeedback(input);
    
    if (username === '') return true;
    
    if (!pattern.test(username)) {
        showValidationError(input, 'Formato inválido. Solo letras, números y guiones bajos (3-50 caracteres)');
        return false;
    }
    
    showValidationSuccess(input);
    return true;
}

/**
 * Validate email format
 */
function validateEmail(input) {
    const email = input.value.trim();
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    clearValidationFeedback(input);
    
    if (email === '') return true;
    
    if (!pattern.test(email)) {
        showValidationError(input, 'Formato de email inválido');
        return false;
    }
    
    showValidationSuccess(input);
    return true;
}

/**
 * Validate password strength
 */
function validatePasswordStrength(input) {
    const password = input.value;
    const requirements = [
        { test: /.{8,}/, message: 'Mínimo 8 caracteres' },
        { test: /[A-Z]/, message: 'Una mayúscula' },
        { test: /[a-z]/, message: 'Una minúscula' },
        { test: /[0-9]/, message: 'Un número' },
        { test: /[^a-zA-Z0-9]/, message: 'Un símbolo' }
    ];
    
    clearValidationFeedback(input);
    
    if (password === '') return true;
    
    const failedRequirements = requirements.filter(req => !req.test.test(password));
    
    if (failedRequirements.length > 0) {
        const messages = failedRequirements.map(req => req.message).join(', ');
        showValidationError(input, `Falta: ${messages}`);
        return false;
    }
    
    showValidationSuccess(input);
    return true;
}

/**
 * Validate password confirmation match
 */
function validatePasswordMatch() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    
    if (!passwordInput || !confirmInput) return true;
    
    clearValidationFeedback(confirmInput);
    
    if (confirmInput.value === '') return true;
    
    if (passwordInput.value !== confirmInput.value) {
        showValidationError(confirmInput, 'Las contraseñas no coinciden');
        return false;
    }
    
    showValidationSuccess(confirmInput);
    return true;
}

/**
 * Validate image file
 */
function validateImageFile(file) {
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    const maxSize = 2 * 1024 * 1024; // 2MB
    
    if (!allowedTypes.includes(file.type)) {
        showAlert('Tipo de archivo no permitido. Solo JPG, PNG, GIF.', 'danger');
        return false;
    }
    
    if (file.size > maxSize) {
        showAlert('Archivo demasiado grande. Máximo 2MB.', 'danger');
        return false;
    }
    
    return true;
}

/**
 * Preview profile image
 */
function previewProfileImage(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('profilePreview');
        if (!preview) return;
        
        if (preview.tagName === 'IMG') {
            preview.src = e.target.result;
        } else {
            // Replace div with img
            const img = document.createElement('img');
            img.id = 'profilePreview';
            img.src = e.target.result;
            img.alt = 'Profile Picture';
            img.className = 'rounded-circle border';
            img.style.cssText = 'width: 120px; height: 120px; object-fit: cover;';
            preview.parentNode.replaceChild(img, preview);
        }
    };
    reader.readAsDataURL(file);
}

/**
 * Check username availability via AJAX
 */
function checkUsernameAvailability(input) {
    const username = input.value.trim();
    if (username === '' || username === input.dataset.original) return;
    
    // Debounce the request
    clearTimeout(input.availabilityTimer);
    input.availabilityTimer = setTimeout(() => {
        checkAvailability('username', username, input);
    }, 500);
}

/**
 * Check email availability via AJAX
 */
function checkEmailAvailability(input) {
    const email = input.value.trim();
    if (email === '' || email === input.dataset.original) return;
    
    // Debounce the request
    clearTimeout(input.availabilityTimer);
    input.availabilityTimer = setTimeout(() => {
        checkAvailability('email', email, input);
    }, 500);
}

/**
 * Check field availability via AJAX
 */
function checkAvailability(field, value, input) {
    const formData = new FormData();
    formData.append('action', 'check_availability');
    formData.append('field', field);
    formData.append('value', value);
    formData.append('user_id', document.querySelector('input[name="user_id"]')?.value || '');
    
    fetch('/?module=users&action=ajax', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.available === false) {
            showValidationError(input, data.message || `${field} no disponible`);
        } else if (data.available === true) {
            showValidationSuccess(input);
        }
    })
    .catch(error => {
        console.error('Error checking availability:', error);
    });
}

/**
 * Validate entire user form
 */
function validateUserForm() {
    const form = document.getElementById('userForm');
    if (!form) return true;
    
    let isValid = true;
    
    // Validate required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showValidationError(field, 'Campo requerido');
            isValid = false;
        }
    });
    
    // Validate specific fields
    const usernameInput = document.getElementById('username');
    if (usernameInput && !validateUsername(usernameInput)) {
        isValid = false;
    }
    
    const emailInput = document.getElementById('email');
    if (emailInput && !validateEmail(emailInput)) {
        isValid = false;
    }
    
    const passwordInput = document.getElementById('password');
    if (passwordInput && !validatePasswordStrength(passwordInput)) {
        isValid = false;
    }
    
    if (!validatePasswordMatch()) {
        isValid = false;
    }
    
    return isValid;
}

/**
 * Show validation error
 */
function showValidationError(input, message) {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
    
    let feedback = input.parentNode.querySelector('.invalid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        input.parentNode.appendChild(feedback);
    }
    feedback.textContent = message;
}

/**
 * Show validation success
 */
function showValidationSuccess(input) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
    
    const feedback = input.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.remove();
    }
}

/**
 * Clear validation feedback
 */
function clearValidationFeedback(input) {
    input.classList.remove('is-valid', 'is-invalid');
    const feedback = input.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.remove();
    }
}

/**
 * Show language change notice
 */
function showLanguageChangeNotice(newLanguage) {
    showAlert(
        `Idioma cambiado a ${newLanguage.toUpperCase()}. Los cambios se aplicarán después de guardar.`,
        'info'
    );
}

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    const alertContainer = document.querySelector('.container .alert') || document.querySelector('.container');
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        <i class="bi bi-info-circle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    if (alertContainer.classList.contains('alert')) {
        alertContainer.parentNode.insertBefore(alert, alertContainer.nextSibling);
    } else {
        alertContainer.insertBefore(alert, alertContainer.firstChild);
    }
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

/**
 * Reset user password (admin action)
 */
function resetUserPassword(userId, username) {
    if (!confirm(`¿Está seguro que desea restablecer la contraseña de ${username}?`)) {
        return;
    }
    
    const form = document.getElementById('actionForm') || createActionForm();
    form.querySelector('[name="action"]').value = 'reset_password';
    form.querySelector('[name="user_id"]').value = userId;
    form.submit();
}

/**
 * Deactivate user (admin action)
 */
function deactivateUser(userId, username) {
    if (!confirm(`¿Está seguro que desea desactivar a ${username}?`)) {
        return;
    }
    
    const form = document.getElementById('actionForm') || createActionForm();
    form.querySelector('[name="action"]').value = 'deactivate';
    form.querySelector('[name="user_id"]').value = userId;
    form.submit();
}

/**
 * Create action form for AJAX-like actions
 */
function createActionForm() {
    const form = document.createElement('form');
    form.id = 'actionForm';
    form.method = 'POST';
    form.style.display = 'none';
    
    form.innerHTML = `
        <input type="hidden" name="csrf_token" value="${document.querySelector('[name="csrf_token"]').value}">
        <input type="hidden" name="action">
        <input type="hidden" name="user_id">
    `;
    
    document.body.appendChild(form);
    return form;
}