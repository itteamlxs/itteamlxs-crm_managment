/**
 * Roles Management JavaScript
 * Dynamic permission selection and form validation
 */

document.addEventListener('DOMContentLoaded', function() {
    // Role name validation
    const roleNameInput = document.getElementById('role_name');
    if (roleNameInput) {
        roleNameInput.addEventListener('input', function() {
            validateRoleName(this);
        });
    }
    
    // Form validation
    const roleForm = document.querySelector('form');
    if (roleForm) {
        roleForm.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    }
});

function validateRoleName(input) {
    const value = input.value.trim();
    const pattern = /^[a-zA-Z0-9_\s]{3,50}$/;
    
    if (!pattern.test(value) && value.length > 0) {
        input.classList.add('is-invalid');
        showValidationMessage(input, 'Formato de nombre de rol inválido');
    } else {
        input.classList.remove('is-invalid');
        hideValidationMessage(input);
    }
}

function validateForm() {
    const roleNameInput = document.getElementById('role_name');
    const descriptionInput = document.getElementById('description');
    let isValid = true;
    
    if (roleNameInput) {
        const roleName = roleNameInput.value.trim();
        if (!roleName) {
            roleNameInput.classList.add('is-invalid');
            showValidationMessage(roleNameInput, 'El nombre del rol es requerido');
            isValid = false;
        } else if (!/^[a-zA-Z0-9_\s]{3,50}$/.test(roleName)) {
            roleNameInput.classList.add('is-invalid');
            showValidationMessage(roleNameInput, 'Formato de nombre de rol inválido');
            isValid = false;
        }
    }
    
    if (descriptionInput) {
        const description = descriptionInput.value.trim();
        if (!description) {
            descriptionInput.classList.add('is-invalid');
            showValidationMessage(descriptionInput, 'La descripción es requerida');
            isValid = false;
        }
    }
    
    return isValid;
}

function showValidationMessage(input, message) {
    hideValidationMessage(input);
    
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    feedback.textContent = message;
    feedback.setAttribute('data-validation', 'true');
    
    input.parentNode.appendChild(feedback);
}

function hideValidationMessage(input) {
    const existingFeedback = input.parentNode.querySelector('.invalid-feedback[data-validation="true"]');
    if (existingFeedback) {
        existingFeedback.remove();
    }
}