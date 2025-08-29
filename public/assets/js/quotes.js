/**
 * Quotes Module JavaScript
 * Handles quote creation, item management, and actions
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeQuotesList();
    initializeQuoteForm();
});

// Quote List Functions
function initializeQuotesList() {
    // Approve quote buttons
    document.querySelectorAll('.approve-quote').forEach(button => {
        button.addEventListener('click', function() {
            const quoteId = this.dataset.quoteId;
            const quoteNumber = this.dataset.quoteNumber;
            
            if (confirm(translations?.confirm_approve_quote || `¿Está seguro que desea aprobar la cotización ${quoteNumber}?`)) {
                approveQuote(quoteId, quoteNumber);
            }
        });
    });
    
    // Reject quote buttons
    document.querySelectorAll('.reject-quote').forEach(button => {
        button.addEventListener('click', function() {
            const quoteId = this.dataset.quoteId;
            const quoteNumber = this.dataset.quoteNumber;
            
            if (confirm(translations?.confirm_reject_quote || `¿Está seguro que desea rechazar la cotización ${quoteNumber}?`)) {
                rejectQuote(quoteId, quoteNumber);
            }
        });
    });
    
    // Duplicate quote buttons
    document.querySelectorAll('.duplicate-quote').forEach(button => {
        button.addEventListener('click', function() {
            const quoteId = this.dataset.quoteId;
            const quoteNumber = this.dataset.quoteNumber;
            
            if (confirm(`¿Está seguro que desea duplicar la cotización ${quoteNumber}?`)) {
                duplicateQuoteAction(quoteId, quoteNumber);
            }
        });
    });
}

// Quote Form Functions
function initializeQuoteForm() {
    if (!document.getElementById('quoteForm')) return;
    
    let itemCounter = 0;
    
    // Add item button
    const addItemBtn = document.getElementById('addItemBtn');
    if (addItemBtn) {
        addItemBtn.addEventListener('click', addQuoteItem);
    }
    
    // Form submission
    const quoteForm = document.getElementById('quoteForm');
    if (quoteForm) {
        quoteForm.addEventListener('submit', function(e) {
            if (!validateQuoteForm()) {
                e.preventDefault();
                return false;
            }
            
            const saveBtn = document.getElementById('saveQuoteBtn');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
            }
        });
    }
    
    // Initialize first item if no items exist
    if (document.getElementById('noItemsMessage') && document.getElementById('noItemsMessage').style.display !== 'none') {
        // Show the no items message initially
    }
}

// Add new quote item
function addQuoteItem() {
    const template = document.getElementById('itemTemplate');
    if (!template) return;
    
    const itemsContainer = document.getElementById('quoteItems');
    const noItemsMessage = document.getElementById('noItemsMessage');
    
    // Hide no items message
    if (noItemsMessage) {
        noItemsMessage.style.display = 'none';
    }
    
    // Clone template
    const newItem = template.content.cloneNode(true);
    const itemDiv = newItem.querySelector('.quote-item');
    
    // Set item number
    const itemNumber = itemsContainer.children.length + 1;
    newItem.querySelector('.item-number').textContent = itemNumber;
    
    // Update form names to use array notation
    const productSelect = newItem.querySelector('.product-select');
    const quantityInput = newItem.querySelector('.quantity-input');
    const discountInput = newItem.querySelector('.discount-input');
    
    productSelect.name = `items[${itemNumber - 1}][product_id]`;
    quantityInput.name = `items[${itemNumber - 1}][quantity]`;
    discountInput.name = `items[${itemNumber - 1}][discount]`;
    
    // Add event listeners
    setupItemEvents(itemDiv);
    
    // Append to container
    itemsContainer.appendChild(newItem);
    
    // Focus on product select
    productSelect.focus();
}

// Setup event listeners for item
function setupItemEvents(itemElement) {
    const productSelect = itemElement.querySelector('.product-select');
    const quantityInput = itemElement.querySelector('.quantity-input');
    const discountInput = itemElement.querySelector('.discount-input');
    const removeBtn = itemElement.querySelector('.remove-item');
    
    if (productSelect) {
        productSelect.addEventListener('change', function() {
            updateItemCalculations(itemElement);
            checkStockAvailability(itemElement);
        });
    }
    
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            updateItemCalculations(itemElement);
            checkStockAvailability(itemElement);
        });
    }
    
    if (discountInput) {
        discountInput.addEventListener('input', function() {
            updateItemCalculations(itemElement);
        });
    }
    
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            if (confirm(translations?.confirm_remove_item || '¿Está seguro que desea eliminar este artículo?')) {
                removeQuoteItem(itemElement);
            }
        });
    }
}

// Remove quote item
function removeQuoteItem(itemElement) {
    itemElement.remove();
    
    // Renumber items
    renumberItems();
    
    // Update calculations
    updateQuoteSummary();
    
    // Show no items message if no items left
    const itemsContainer = document.getElementById('quoteItems');
    const noItemsMessage = document.getElementById('noItemsMessage');
    
    if (itemsContainer.children.length === 0 && noItemsMessage) {
        noItemsMessage.style.display = 'block';
    }
}

// Renumber items after removal
function renumberItems() {
    const items = document.querySelectorAll('.quote-item');
    
    items.forEach((item, index) => {
        const itemNumber = index + 1;
        
        // Update item number display
        const itemNumberSpan = item.querySelector('.item-number');
        if (itemNumberSpan) {
            itemNumberSpan.textContent = itemNumber;
        }
        
        // Update form field names
        const productSelect = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');
        const discountInput = item.querySelector('.discount-input');
        
        if (productSelect) productSelect.name = `items[${index}][product_id]`;
        if (quantityInput) quantityInput.name = `items[${index}][quantity]`;
        if (discountInput) discountInput.name = `items[${index}][discount]`;
    });
}

// Update item calculations
function updateItemCalculations(itemElement) {
    const productSelect = itemElement.querySelector('.product-select');
    const quantityInput = itemElement.querySelector('.quantity-input');
    const discountInput = itemElement.querySelector('.discount-input');
    const unitPriceSpan = itemElement.querySelector('.unit-price');
    const subtotalSpan = itemElement.querySelector('.item-subtotal');
    
    if (!productSelect.value || !quantityInput.value) {
        unitPriceSpan.textContent = '$0.00';
        subtotalSpan.textContent = '$0.00';
        updateQuoteSummary();
        return;
    }
    
    const selectedOption = productSelect.selectedOptions[0];
    const unitPrice = parseFloat(selectedOption.dataset.price) || 0;
    const taxRate = parseFloat(selectedOption.dataset.taxRate) || 0;
    const quantity = parseInt(quantityInput.value) || 0;
    const discount = parseFloat(discountInput.value) || 0;
    
    // Calculate subtotal
    const subtotalBeforeDiscount = unitPrice * quantity;
    const discountAmount = subtotalBeforeDiscount * (discount / 100);
    const subtotalAfterDiscount = subtotalBeforeDiscount - discountAmount;
    const taxAmount = subtotalAfterDiscount * (taxRate / 100);
    const subtotal = subtotalAfterDiscount + taxAmount;
    
    // Update display
    unitPriceSpan.textContent = formatCurrency(unitPrice);
    subtotalSpan.textContent = formatCurrency(subtotal);
    
    // Update quote summary
    updateQuoteSummary();
}

// Check stock availability
function checkStockAvailability(itemElement) {
    const productSelect = itemElement.querySelector('.product-select');
    const quantityInput = itemElement.querySelector('.quantity-input');
    const stockInfo = itemElement.querySelector('.stock-info');
    
    if (!productSelect.value || !quantityInput.value) {
        stockInfo.textContent = '';
        stockInfo.className = 'form-text text-muted stock-info';
        return;
    }
    
    const selectedOption = productSelect.selectedOptions[0];
    const stock = parseInt(selectedOption.dataset.stock) || 0;
    const quantity = parseInt(quantityInput.value) || 0;
    
    if (quantity > stock) {
        stockInfo.textContent = `${translations?.insufficient_stock || 'Stock insuficiente'}: ${stock} ${translations?.available || 'disponibles'}`;
        stockInfo.className = 'form-text text-danger stock-info';
        quantityInput.classList.add('is-invalid');
    } else {
        stockInfo.textContent = `${translations?.stock_available || 'Stock disponible'}: ${stock}`;
        stockInfo.className = 'form-text text-success stock-info';
        quantityInput.classList.remove('is-invalid');
    }
}

// Update quote summary
function updateQuoteSummary() {
    let totalSubtotal = 0;
    let totalDiscount = 0;
    let totalTax = 0;
    let grandTotal = 0;
    
    const items = document.querySelectorAll('.quote-item');
    
    items.forEach(item => {
        const productSelect = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');
        const discountInput = item.querySelector('.discount-input');
        
        if (!productSelect.value || !quantityInput.value) return;
        
        const selectedOption = productSelect.selectedOptions[0];
        const unitPrice = parseFloat(selectedOption.dataset.price) || 0;
        const taxRate = parseFloat(selectedOption.dataset.taxRate) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        
        const subtotalBeforeDiscount = unitPrice * quantity;
        const discountAmount = subtotalBeforeDiscount * (discount / 100);
        const subtotalAfterDiscount = subtotalBeforeDiscount - discountAmount;
        const taxAmount = subtotalAfterDiscount * (taxRate / 100);
        const itemTotal = subtotalAfterDiscount + taxAmount;
        
        totalSubtotal += subtotalBeforeDiscount;
        totalDiscount += discountAmount;
        totalTax += taxAmount;
        grandTotal += itemTotal;
    });
    
    // Update summary display
    const subtotalElement = document.getElementById('subtotalAmount');
    const discountElement = document.getElementById('discountAmount');
    const taxElement = document.getElementById('taxAmount');
    const totalElement = document.getElementById('totalAmount');
    
    if (subtotalElement) subtotalElement.textContent = formatCurrency(totalSubtotal);
    if (discountElement) discountElement.textContent = formatCurrency(totalDiscount);
    if (taxElement) taxElement.textContent = formatCurrency(totalTax);
    if (totalElement) totalElement.textContent = formatCurrency(grandTotal);
}

// Validate quote form
function validateQuoteForm() {
    const clientId = document.getElementById('client_id')?.value;
    const items = document.querySelectorAll('.quote-item');
    
    if (!clientId) {
        alert('Por favor seleccione un cliente.');
        return false;
    }
    
    if (items.length === 0) {
        alert(translations?.at_least_one_item || 'Debe agregar al menos un artículo a la cotización.');
        return false;
    }
    
    // Validate each item
    let validItems = 0;
    let hasStockIssues = false;
    
    items.forEach(item => {
        const productSelect = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');
        
        if (productSelect.value && quantityInput.value && parseInt(quantityInput.value) > 0) {
            validItems++;
            
            // Check stock
            const selectedOption = productSelect.selectedOptions[0];
            const stock = parseInt(selectedOption.dataset.stock) || 0;
            const quantity = parseInt(quantityInput.value) || 0;
            
            if (quantity > stock) {
                hasStockIssues = true;
            }
        }
    });
    
    if (validItems === 0) {
        alert(translations?.at_least_one_item || 'Debe agregar al menos un artículo válido a la cotización.');
        return false;
    }
    
    if (hasStockIssues) {
        return confirm('Algunos artículos tienen problemas de stock. ¿Desea continuar de todos modos?');
    }
    
    return true;
}

// Approve quote
function approveQuote(quoteId, quoteNumber) {
    const formData = new FormData();
    formData.append('quote_id', quoteId);
    formData.append('csrf_token', csrfToken);
    
    fetch('?module=quotes&action=approve', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Cotización aprobada exitosamente', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            if (data.stock_errors) {
                let errorMessage = data.error + '\n\nDetalles:\n' + data.stock_errors.join('\n');
                showAlert(errorMessage, 'danger');
            } else {
                showAlert(data.error || 'Error al aprobar la cotización', 'danger');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error de conexión', 'danger');
    });
}

// Reject quote
function rejectQuote(quoteId, quoteNumber) {
    const formData = new FormData();
    formData.append('quote_id', quoteId);
    formData.append('action', 'reject');
    formData.append('csrf_token', csrfToken);
    
    fetch('?module=quotes&action=approve', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Cotización rechazada exitosamente', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert(data.error || 'Error al rechazar la cotización', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error de conexión', 'danger');
    });
}

// Duplicate quote action
function duplicateQuoteAction(quoteId, quoteNumber) {
    const formData = new FormData();
    formData.append('quote_id', quoteId);
    formData.append('csrf_token', csrfToken);
    
    fetch('?module=quotes&action=duplicate', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Cotización duplicada exitosamente', 'success');
            
            // Redirect to edit the new quote if URL provided
            if (data.redirect_url) {
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1500);
            } else {
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        } else {
            showAlert(data.error || 'Error al duplicar la cotización', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error de conexión', 'danger');
    });
}

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    }).format(amount);
}

function showAlert(message, type = 'info') {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at top of container
    const container = document.querySelector('.container-fluid');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
    }
    
    // Auto-dismiss after 5 seconds for success messages
    if (type === 'success') {
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}