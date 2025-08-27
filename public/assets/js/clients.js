/**
 * Clients Management JavaScript
 * Handles client-related frontend functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-submit search form on input change (with debounce)
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    document.getElementById('searchForm').submit();
                }
            }, 500);
        });
    }

    // Auto-submit when changing per-page limit
    const limitSelect = document.getElementById('limit');
    if (limitSelect) {
        limitSelect.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    }

    // Form validation for client forms
    const clientForms = document.querySelectorAll('#addClientForm, #editClientForm');
    clientForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateClientForm(this)) {
                e.preventDefault();
            }
        });
    });

    // Real-time email validation
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateEmail(this);
        });
    });

    // Real-time phone validation
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validatePhone(this);
        });
    });

    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

/**
 * Validate client form before submission
 */
function validateClientForm(form) {
    let isValid = true;
    const errors = [];

    // Company name validation
    const companyName = form.querySelector('#company_name');
    if (!companyName.value.trim()) {
        errors.push('Company name is required');
        markFieldError(companyName);
        isValid = false;
    } else {
        markFieldValid(companyName);
    }

    // Contact name validation
    const contactName = form.querySelector('#contact_name');
    if (!contactName.value.trim()) {
        errors.push('Contact name is required');
        markFieldError(contactName);
        isValid = false;
    } else {
        markFieldValid(contactName);
    }

    // Email validation
    const email = form.querySelector('#email');
    if (!email.value.trim()) {
        errors.push('Email is required');
        markFieldError(email);
        isValid = false;
    } else if (!isValidEmail(email.value)) {
        errors.push('Please enter a valid email address');
        markFieldError(email);
        isValid = false;
    } else {
        markFieldValid(email);
    }

    // Phone validation (if provided)
    const phone = form.querySelector('#phone');
    if (phone && phone.value.trim() && !isValidPhone(phone.value)) {
        errors.push('Please enter a valid phone number');
        markFieldError(phone);
        isValid = false;
    } else if (phone) {
        markFieldValid(phone);
    }

    // Show errors if any
    if (!isValid) {
        showValidationErrors(errors);
    }

    return isValid;
}

/**
 * Email validation
 */
function validateEmail(input) {
    if (input.value.trim() && !isValidEmail(input.value)) {
        markFieldError(input, 'Please enter a valid email address');
        return false;
    } else {
        markFieldValid(input);
        return true;
    }
}

/**
 * Phone validation
 */
function validatePhone(input) {
    if (input.value.trim() && !isValidPhone(input.value)) {
        markFieldError(input, 'Please enter a valid phone number');
        return false;
    } else {
        markFieldValid(input);
        return true;
    }
}

/**
 * Check if email is valid
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Check if phone is valid
 */
function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{7,20}$/;
    return phoneRegex.test(phone);
}

/**
 * Mark field as invalid
 */
function markFieldError(field, message = '') {
    field.classList.remove('is-valid');
    field.classList.add('is-invalid');
    
    // Remove existing feedback
    const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // Add error message if provided
    if (message) {
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = message;
        field.parentNode.appendChild(feedback);
    }
}

/**
 * Mark field as valid
 */
function markFieldValid(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    
    // Remove error feedback
    const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
}

/**
 * Clear all field validations
 */
function clearFieldValidations() {
    const fields = document.querySelectorAll('.is-valid, .is-invalid');
    fields.forEach(field => {
        field.classList.remove('is-valid', 'is-invalid');
    });
    
    const feedbacks = document.querySelectorAll('.invalid-feedback');
    feedbacks.forEach(feedback => feedback.remove());
}

/**
 * Show validation errors
 */
function showValidationErrors(errors) {
    // Try to find existing alert container or create one
    let alertContainer = document.querySelector('.validation-errors');
    
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.className = 'alert alert-danger alert-dismissible fade show validation-errors';
        
        // Insert at the top of the form
        const form = document.querySelector('#addClientForm, #editClientForm');
        if (form) {
            form.insertBefore(alertContainer, form.firstChild);
        }
    }
    
    let errorHTML = '<i class="fas fa-exclamation-triangle me-2"></i><ul class="mb-0">';
    errors.forEach(error => {
        errorHTML += `<li>${error}</li>`;
    });
    errorHTML += '</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    
    alertContainer.innerHTML = errorHTML;
}

/**
 * Confirm client deletion
 */
function confirmDelete(clientId, clientName) {
    if (confirm(`Are you sure you want to delete client "${clientName}"? This action cannot be undone.`)) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?module=clients&action=delete';
        
        // Add CSRF token
        const csrfToken = document.querySelector('input[name="csrf_token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = csrfToken.value;
            form.appendChild(csrfInput);
        }
        
        // Add client ID
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = clientId;
        form.appendChild(idInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

/**
 * Format phone number as user types
 */
function formatPhoneInput(input) {
    input.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // Remove non-digits
        
        if (value.length >= 10) {
            // Format as (XXX) XXX-XXXX for US numbers
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
        } else if (value.length >= 6) {
            // Format as XXX-XXXX
            value = value.replace(/(\d{3})(\d{3})/, '$1-$2');
        }
        
        this.value = value;
    });
}

/**
 * Auto-capitalize names
 */
function autoCapitalize(input) {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    });
}

/**
 * Initialize form enhancements
 */
function initializeFormEnhancements() {
    // Auto-capitalize company and contact names
    const nameInputs = document.querySelectorAll('#company_name, #contact_name');
    nameInputs.forEach(autoCapitalize);
    
    // Format phone inputs
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(formatPhoneInput);
    
    // Auto-focus first input
    const firstInput = document.querySelector('#company_name');
    if (firstInput) {
        firstInput.focus();
    }
}

// Initialize enhancements when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeFormEnhancements);