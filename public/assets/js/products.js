/**
 * Products Management JavaScript
 * Handles product and category interactions, validations, and AJAX calls
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeProductModule();
});

/**
 * Initialize product module components
 */
function initializeProductModule() {
    initProductForm();
    initCategoryForm();
    initStockAlerts();
    initSearchFilters();
    initBulkActions();
    initFormValidations();
}

/**
 * Initialize product form functionality
 */
function initProductForm() {
    const productForm = document.getElementById('productForm');
    if (!productForm) return;
    
    // SKU validation and generation
    const nameInput = document.getElementById('product_name');
    const skuInput = document.getElementById('sku');
    
    if (nameInput && skuInput) {
        nameInput.addEventListener('blur', function() {
            if (!skuInput.value && nameInput.value) {
                generateSKU(nameInput.value, skuInput);
            }
        });
        
        skuInput.addEventListener('blur', function() {
            validateSKU(this);
        });
    }
    
    // Price validation
    const priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            validatePrice(this);
        });
    }
    
    // Stock quantity warnings
    const stockInput = document.getElementById('stock_quantity');
    const minStockInput = document.getElementById('min_stock_level');
    
    if (stockInput && minStockInput) {
        stockInput.addEventListener('input', function() {
            checkStockLevel(stockInput, minStockInput);
        });
        
        minStockInput.addEventListener('input', function() {
            checkStockLevel(stockInput, minStockInput);
        });
    }
    
    // Form submission
    productForm.addEventListener('submit', function(e) {
        if (!validateProductForm()) {
            e.preventDefault();
            return false;
        }
    });
}

/**
 * Initialize category form functionality
 */
function initCategoryForm() {
    const categoryForm = document.getElementById('categoryForm');
    if (!categoryForm) return;
    
    const nameInput = document.getElementById('category_name');
    const descInput = document.getElementById('description');
    
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            validateCategoryName(this);
            updateCategoryPreview();
        });
    }
    
    if (descInput) {
        descInput.addEventListener('input', function() {
            updateCharacterCount(this);
            updateCategoryPreview();
        });
    }
    
    // Form submission
    categoryForm.addEventListener('submit', function(e) {
        if (!validateCategoryForm()) {
            e.preventDefault();
            return false;
        }
    });
}

/**
 * Initialize stock alerts and low stock monitoring
 */
function initStockAlerts() {
    // Check for low stock products on page load
    checkLowStockProducts();
    
    // Monitor stock changes in real-time
    const stockInputs = document.querySelectorAll('input[name="stock_quantity"]');
    stockInputs.forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const newStock = parseInt(this.value);
            const minStock = parseInt(this.dataset.minStock || 10);
            
            if (newStock <= minStock) {
                showStockWarning(productId, newStock, minStock);
            }
        });
    });
}

/**
 * Initialize search and filter functionality
 */
function initSearchFilters() {
    const searchForm = document.querySelector('form[method="GET"]');
    if (!searchForm) return;
    
    const searchInput = document.querySelector('input[name="search"]');
    const categoryFilter = document.querySelector('select[name="category_id"]');
    
    // Auto-submit search after typing stops
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    searchForm.submit();
                }
            }, 500);
        });
    }
    
    // Auto-submit on category change
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            searchForm.submit();
        });
    }
    
    // Clear filters functionality
    const clearBtn = document.querySelector('.btn-outline-secondary[href*="clear"]');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
    }
}

/**
 * Initialize bulk actions for products
 */
function initBulkActions() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('input[name="selected_products[]"]');
    const bulkActionsDiv = document.getElementById('bulkActions');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkActions();
        });
    }
    
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('input[name="selected_products[]"]:checked').length;
            selectAllCheckbox.checked = checkedCount === productCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < productCheckboxes.length;
            toggleBulkActions();
        });
    });
}

/**
 * Initialize form validations
 */
function initFormValidations() {
    // Real-time validation for all inputs
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearValidationError(this);
        });
    });
}

/**
 * Generate SKU from product name
 */
function generateSKU(productName, skuInput) {
    const sku = productName
        .toUpperCase()
        .replace(/[^A-Z0-9]/g, '')
        .substring(0, 6) + 
        '-' + 
        Math.random().toString(36).substring(2, 5).toUpperCase();
    
    skuInput.value = sku;
    showToast('SKU generated automatically', 'info');
}

/**
 * Validate SKU uniqueness via AJAX
 */
function validateSKU(input) {
    const sku = input.value.trim();
    if (!sku) return;
    
    const productId = document.querySelector('input[name="product_id"]')?.value || '';
    
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'validate_sku',
            sku: sku,
            product_id: productId,
            csrf_token: document.querySelector('input[name="csrf_token"]').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid === false) {
            showValidationError(input, 'SKU already exists');
        } else {
            showValidationSuccess(input);
        }
    })
    .catch(error => {
        console.error('SKU validation error:', error);
    });
}

/**
 * Validate price input
 */
function validatePrice(input) {
    const price = parseFloat(input.value);
    
    if (isNaN(price) || price < 0) {
        showValidationError(input, 'Price must be a positive number');
        return false;
    }
    
    if (price > 999999.99) {
        showValidationError(input, 'Price cannot exceed $999,999.99');
        return false;
    }
    
    showValidationSuccess(input);
    return true;
}

/**
 * Check stock level against minimum
 */
function checkStockLevel(stockInput, minStockInput) {
    const stock = parseInt(stockInput.value) || 0;
    const minStock = parseInt(minStockInput.value) || 10;
    
    const stockBadge = document.querySelector('.stock-badge');
    if (stockBadge) {
        stockBadge.textContent = stock;
        stockBadge.className = 'badge ' + (stock <= minStock ? 'bg-warning text-dark' : 'bg-success');
    }
    
    if (stock <= minStock && stock > 0) {
        showValidationWarning(stockInput, `Stock is at or below minimum level (${minStock})`);
    } else if (stock === 0) {
        showValidationWarning(stockInput, 'Product will be out of stock');
    } else {
        clearValidationError(stockInput);
    }
}

/**
 * Validate category name
 */
function validateCategoryName(input) {
    const name = input.value.trim();
    
    if (name.length < 2) {
        showValidationError(input, 'Category name must be at least 2 characters');
        return false;
    }
    
    if (name.length > 100) {
        showValidationError(input, 'Category name cannot exceed 100 characters');
        return false;
    }
    
    showValidationSuccess(input);
    return true;
}

/**
 * Update character count for description
 */
function updateCharacterCount(textarea) {
    const count = textarea.value.length;
    const maxLength = textarea.getAttribute('maxlength') || 500;
    const counter = document.querySelector('.character-count');
    
    if (counter) {
        counter.textContent = `${count}/${maxLength}`;
        counter.className = 'character-count ' + 
            (count > maxLength * 0.9 ? 'text-warning' : 
             count > maxLength * 0.95 ? 'text-danger' : 'text-muted');
    }
}

/**
 * Update category preview
 */
function updateCategoryPreview() {
    const nameInput = document.getElementById('category_name');
    const descInput = document.getElementById('description');
    const previewName = document.querySelector('.category-name');
    const previewDesc = document.querySelector('.category-description');
    
    if (nameInput && previewName) {
        previewName.textContent = nameInput.value || 'Category Name';
    }
    
    if (descInput && previewDesc) {
        previewDesc.textContent = descInput.value || 'Category description will appear here...';
    }
}

/**
 * Validate entire product form
 */
function validateProductForm() {
    const form = document.getElementById('productForm');
    if (!form) return true;
    
    let isValid = true;
    const requiredFields = ['product_name', 'sku', 'category_id', 'price', 'stock_quantity'];
    
    requiredFields.forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field && !field.value.trim()) {
            showValidationError(field, 'This field is required');
            isValid = false;
        }
    });
    
    // Validate price
    const priceInput = form.querySelector('[name="price"]');
    if (priceInput && !validatePrice(priceInput)) {
        isValid = false;
    }
    
    return isValid;
}

/**
 * Validate entire category form
 */
function validateCategoryForm() {
    const form = document.getElementById('categoryForm');
    if (!form) return true;
    
    const nameInput = form.querySelector('[name="category_name"]');
    return validateCategoryName(nameInput);
}

/**
 * Check for low stock products and show alerts
 */
function checkLowStockProducts() {
    const lowStockModal = document.getElementById('lowStockModal');
    if (lowStockModal) {
        const lowStockCount = document.querySelectorAll('#lowStockModal tbody tr').length;
        if (lowStockCount > 0) {
            setTimeout(() => {
                showToast(`${lowStockCount} products are running low on stock`, 'warning');
            }, 1000);
        }
    }
}

/**
 * Show stock warning for specific product
 */
function showStockWarning(productId, currentStock, minStock) {
    showToast(`Product stock is low: ${currentStock} (minimum: ${minStock})`, 'warning');
}

/**
 * Toggle bulk actions visibility
 */
function toggleBulkActions() {
    const checkedCount = document.querySelectorAll('input[name="selected_products[]"]:checked').length;
    const bulkActionsDiv = document.getElementById('bulkActions');
    
    if (bulkActionsDiv) {
        bulkActionsDiv.style.display = checkedCount > 0 ? 'block' : 'none';
        const countSpan = bulkActionsDiv.querySelector('.selected-count');
        if (countSpan) {
            countSpan.textContent = checkedCount;
        }
    }
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
 * Show validation warning
 */
function showValidationWarning(input, message) {
    input.classList.remove('is-invalid', 'is-valid');
    input.classList.add('border-warning');
    
    let feedback = input.parentNode.querySelector('.text-warning');
    if (!feedback) {
        feedback = document.createElement('small');
        feedback.className = 'text-warning';
        input.parentNode.appendChild(feedback);
    }
    feedback.textContent = message;
}

/**
 * Clear validation error/success states
 */
function clearValidationError(input) {
    input.classList.remove('is-invalid', 'is-valid', 'border-warning');
    const feedback = input.parentNode.querySelector('.invalid-feedback');
    const warningText = input.parentNode.querySelector('.text-warning');
    
    if (feedback) feedback.remove();
    if (warningText) warningText.remove();
}

/**
 * Validate individual field
 */
function validateField(input) {
    const name = input.getAttribute('name');
    const value = input.value.trim();
    
    switch (name) {
        case 'product_name':
        case 'category_name':
            if (!value) {
                showValidationError(input, 'This field is required');
            } else if (value.length < 2) {
                showValidationError(input, 'Must be at least 2 characters');
            } else {
                showValidationSuccess(input);
            }
            break;
            
        case 'sku':
            if (!value) {
                showValidationError(input, 'SKU is required');
            } else if (!/^[A-Z0-9-]+$/.test(value)) {
                showValidationError(input, 'SKU can only contain letters, numbers, and hyphens');
            } else {
                validateSKU(input);
            }
            break;
            
        case 'price':
            validatePrice(input);
            break;
            
        case 'stock_quantity':
            const stock = parseInt(value);
            if (isNaN(stock) || stock < 0) {
                showValidationError(input, 'Stock must be a non-negative number');
            } else {
                showValidationSuccess(input);
            }
            break;
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    // Add to toast container or create one
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    container.appendChild(toast);
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast, { delay: 5000 });
    bsToast.show();
    
    // Remove from DOM after hiding
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}