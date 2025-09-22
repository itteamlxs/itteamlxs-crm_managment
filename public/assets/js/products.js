/**
 * Products JavaScript
 * Client-side functionality for products module
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Stock quantity color coding
    updateStockQuantityColors();
    
    // Price formatting helpers
    initializePriceFormatting();
    
    // SKU validation helpers
    initializeSKUValidation();
    
    // Category management helpers
    initializeCategoryManagement();
});

/**
 * Update stock quantity visual indicators
 */
function updateStockQuantityColors() {
    const stockElements = document.querySelectorAll('.badge');
    
    stockElements.forEach(function(element) {
        const stockValue = parseInt(element.textContent.replace(/,/g, ''));
        
        if (!isNaN(stockValue)) {
            element.classList.remove('bg-success', 'bg-warning', 'bg-danger');
            
            if (stockValue === 0) {
                element.classList.add('bg-danger');
                element.setAttribute('title', 'Out of stock');
            } else if (stockValue < 10) {
                element.classList.add('bg-warning');
                element.setAttribute('title', 'Low stock');
            } else {
                element.classList.add('bg-success');
                element.setAttribute('title', 'In stock');
            }
        }
    });
}

/**
 * Initialize price formatting
 */
function initializePriceFormatting() {
    const priceInputs = document.querySelectorAll('input[name="price"]');
    
    priceInputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value) && value >= 0) {
                this.value = value.toFixed(2);
            }
        });
        
        input.addEventListener('input', function() {
            // Remove any non-numeric characters except decimal point
            this.value = this.value.replace(/[^0-9.]/g, '');
            
            // Ensure only one decimal point
            const parts = this.value.split('.');
            if (parts.length > 2) {
                this.value = parts[0] + '.' + parts.slice(1).join('');
            }
        });
    });
}

/**
 * Initialize SKU validation
 */
function initializeSKUValidation() {
    const skuInputs = document.querySelectorAll('input[name="sku"]');
    
    skuInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            // Convert to uppercase and remove invalid characters
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
            
            // Limit length
            if (this.value.length > 50) {
                this.value = this.value.substring(0, 50);
            }
            
            // Visual feedback
            if (this.value.length > 0 && /^[A-Z0-9_-]{1,50}$/.test(this.value)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (this.value.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
        
        input.addEventListener('blur', function() {
            if (this.value && !/^[A-Z0-9_-]{1,50}$/.test(this.value)) {
                this.focus();
            }
        });
    });
}

/**
 * Initialize category management
 */
function initializeCategoryManagement() {
    // Category name validation
    const categoryNameInputs = document.querySelectorAll('input[name="category_name"]');
    
    categoryNameInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            // Basic validation
            const value = this.value.trim();
            
            if (value.length > 0 && value.length >= 3 && value.length <= 100) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (value.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    });
}

/**
 * Validate product form before submission
 */
function validateProductForm(form) {
    const errors = [];
    
    const productName = form.querySelector('input[name="product_name"]')?.value.trim();
    const sku = form.querySelector('input[name="sku"]')?.value.trim();
    const price = parseFloat(form.querySelector('input[name="price"]')?.value || 0);
    const stockQuantity = parseInt(form.querySelector('input[name="stock_quantity"]')?.value || 0);
    const categoryId = form.querySelector('select[name="category_id"]')?.value;
    
    if (!productName) {
        errors.push('Product name is required');
    }
    
    if (!sku) {
        errors.push('SKU is required');
    } else if (!/^[A-Z0-9_-]{1,50}$/.test(sku)) {
        errors.push('SKU format is invalid');
    }
    
    if (!categoryId) {
        errors.push('Category is required');
    }
    
    if (price <= 0) {
        errors.push('Price must be greater than 0');
    }
    
    if (stockQuantity < 0) {
        errors.push('Stock quantity cannot be negative');
    }
    
    return errors;
}

/**
 * Show loading state for buttons
 */
function showLoadingState(button, originalText = null) {
    if (!originalText) {
        originalText = button.innerHTML;
    }
    
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
    button.dataset.originalText = originalText;
}

/**
 * Hide loading state for buttons
 */
function hideLoadingState(button) {
    button.disabled = false;
    button.innerHTML = button.dataset.originalText || 'Submit';
}

/**
 * Format number for display
 */
function formatNumber(number) {
    return new Intl.NumberFormat().format(number);
}

/**
 * Format currency for display
 */
function formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

/**
 * Debounce function for search inputs
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Live search functionality
 */
function initializeLiveSearch() {
    const searchInput = document.querySelector('input[name="search"]');
    
    if (searchInput) {
        const debouncedSearch = debounce(function(value) {
            if (value.length >= 2 || value.length === 0) {
                // Trigger search
                searchInput.closest('form').submit();
            }
        }, 500);
        
        searchInput.addEventListener('input', function() {
            debouncedSearch(this.value);
        });
    }
}

/**
 * Bulk actions functionality
 */
function initializeBulkActions() {
    const selectAllCheckbox = document.querySelector('#selectAll');
    const itemCheckboxes = document.querySelectorAll('input[name="selected_items[]"]');
    const bulkActionSelect = document.querySelector('#bulkAction');
    const bulkActionButton = document.querySelector('#bulkActionButton');
    
    if (selectAllCheckbox && itemCheckboxes.length > 0) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });
        
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionButton);
        });
    }
    
    function updateBulkActionButton() {
        const checkedItems = document.querySelectorAll('input[name="selected_items[]"]:checked');
        
        if (bulkActionButton) {
            if (checkedItems.length > 0) {
                bulkActionButton.disabled = false;
                bulkActionButton.textContent = `Apply to ${checkedItems.length} items`;
            } else {
                bulkActionButton.disabled = true;
                bulkActionButton.textContent = 'Select items';
            }
        }
    }
}

/**
 * Export functionality
 */
function exportData(format, filters = {}) {
    const params = new URLSearchParams();
    params.append('export', format);
    
    Object.entries(filters).forEach(([key, value]) => {
        if (value) {
            params.append(key, value);
        }
    });
    
    window.location.href = `?module=products&action=export&${params.toString()}`;
}

/**
 * Print functionality
 */
function printTable() {
    const printWindow = window.open('', '_blank');
    const table = document.querySelector('.table').cloneNode(true);
    
    // Remove action columns
    table.querySelectorAll('th:last-child, td:last-child').forEach(cell => {
        cell.remove();
    });
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Products List</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @media print {
                    .no-print { display: none !important; }
                    body { font-size: 12px; }
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <h2>Products List</h2>
                <p>Generated on: ${new Date().toLocaleDateString()}</p>
                ${table.outerHTML}
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}