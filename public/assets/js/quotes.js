/**
 * Quotes JavaScript Module
 * Handles quote form interactions, calculations, and item management
 */

class QuoteManager {
    constructor() {
        this.itemIndex = 0;
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.initializeForm();
    }
    
    bindEvents() {
        // Add item button
        const addItemBtn = document.getElementById('addItemBtn');
        if (addItemBtn) {
            addItemBtn.addEventListener('click', () => this.addItem());
        }
        
        // Form validation
        const quoteForm = document.getElementById('quoteForm');
        if (quoteForm) {
            quoteForm.addEventListener('submit', (e) => this.validateForm(e));
        }
        
        // Auto-calculate expiry date
        const issueDateInput = document.getElementById('issue_date');
        if (issueDateInput) {
            issueDateInput.addEventListener('change', () => this.updateExpiryDate());
        }
        
        // Client selection change
        const clientSelect = document.getElementById('client_id');
        if (clientSelect) {
            clientSelect.addEventListener('change', () => this.onClientChange());
        }
    }
    
    initializeForm() {
        // Add first item automatically if form is empty
        const itemsContainer = document.getElementById('itemsContainer');
        if (itemsContainer && itemsContainer.children.length === 0) {
            this.addItem();
        }
        
        // Update totals for existing items
        this.updateQuoteTotal();
    }
    
    addItem() {
        this.itemIndex++;
        const itemsContainer = document.getElementById('itemsContainer');
        const noItemsMessage = document.getElementById('noItemsMessage');
        const template = document.getElementById('itemTemplate');
        
        if (!template || !itemsContainer) return;
        
        const clone = template.content.cloneNode(true);
        
        // Update item number and index
        clone.querySelector('.item-number').textContent = this.itemIndex;
        clone.querySelector('.item-row').setAttribute('data-item-index', this.itemIndex);
        
        // Update form field names with proper array syntax
        this.updateFieldNames(clone, this.itemIndex - 1);
        
        // Add event listeners to new item
        this.bindItemEvents(clone);
        
        itemsContainer.appendChild(clone);
        
        if (noItemsMessage) {
            noItemsMessage.style.display = 'none';
        }
        
        this.updateItemNumbers();
        this.updateQuoteTotal();
    }
    
    updateFieldNames(clone, index) {
        const fields = [
            'product_id',
            'quantity', 
            'unit_price',
            'discount',
            'tax_rate'
        ];
        
        fields.forEach(field => {
            const element = clone.querySelector(`[name="items[][${field}]"]`);
            if (element) {
                element.name = `items[${index}][${field}]`;
            }
        });
    }
    
    bindItemEvents(clone) {
        // Remove item button
        const removeBtn = clone.querySelector('.remove-item');
        if (removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                this.removeItem(e.target.closest('.item-row'));
            });
        }
        
        // Product selection
        const productSelect = clone.querySelector('.product-select');
        if (productSelect) {
            productSelect.addEventListener('change', (e) => {
                this.onProductChange(e.target.closest('.item-row'));
            });
        }
        
        // Calculation inputs
        const calcInputs = clone.querySelectorAll('.quantity-input, .price-input, .discount-input');
        calcInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                this.updateItemCalculation(e.target.closest('.item-row'));
            });
        });
        
        // Quantity validation
        const quantityInput = clone.querySelector('.quantity-input');
        if (quantityInput) {
            quantityInput.addEventListener('blur', (e) => {
                this.validateQuantity(e.target);
            });
        }
    }
    
    removeItem(itemRow) {
        const itemsContainer = document.getElementById('itemsContainer');
        const noItemsMessage = document.getElementById('noItemsMessage');
        
        if (itemsContainer.children.length > 1) {
            itemRow.remove();
            this.updateItemNumbers();
            this.updateQuoteTotal();
            
            if (itemsContainer.children.length === 0 && noItemsMessage) {
                noItemsMessage.style.display = 'block';
            }
        } else {
            this.showAlert('warning', 'Al menos un artículo es requerido');
        }
    }
    
    updateItemNumbers() {
        const items = document.querySelectorAll('.item-row');
        items.forEach((item, index) => {
            const itemNumber = item.querySelector('.item-number');
            if (itemNumber) {
                itemNumber.textContent = index + 1;
            }
            
            // Update field names
            const fields = item.querySelectorAll('[name^="items["]');
            fields.forEach(field => {
                const name = field.name;
                const fieldType = name.match(/\[([^\]]+)\]$/)[1];
                field.name = `items[${index}][${fieldType}]`;
            });
        });
    }
    
    onProductChange(itemRow) {
        const productSelect = itemRow.querySelector('.product-select');
        const priceInput = itemRow.querySelector('.price-input');
        const taxRateInput = itemRow.querySelector('.tax-rate-input');
        const quantityInput = itemRow.querySelector('.quantity-input');
        
        if (productSelect.value) {
            const selectedOption = productSelect.selectedOptions[0];
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const taxRate = parseFloat(selectedOption.dataset.taxRate) || 0;
            const stock = parseInt(selectedOption.dataset.stock) || 0;
            
            priceInput.value = price.toFixed(2);
            taxRateInput.value = taxRate.toFixed(2);
            
            // Set max quantity to available stock
            quantityInput.max = stock;
            
            // Show stock warning if low
            if (stock < 10) {
                this.showStockWarning(itemRow, stock);
            }
        } else {
            priceInput.value = '';
            taxRateInput.value = '';
            quantityInput.max = '';
        }
        
        this.updateItemCalculation(itemRow);
    }
    
    validateQuantity(quantityInput) {
        const itemRow = quantityInput.closest('.item-row');
        const productSelect = itemRow.querySelector('.product-select');
        const quantity = parseInt(quantityInput.value) || 0;
        
        if (productSelect.value) {
            const selectedOption = productSelect.selectedOptions[0];
            const stock = parseInt(selectedOption.dataset.stock) || 0;
            
            if (quantity > stock) {
                this.showAlert('danger', `Cantidad excede el stock disponible (${stock})`);
                quantityInput.value = stock;
                quantityInput.focus();
            }
        }
    }
    
    showStockWarning(itemRow, stock) {
        const productSelect = itemRow.querySelector('.product-select');
        let warning = itemRow.querySelector('.stock-warning');
        
        if (!warning) {
            warning = document.createElement('small');
            warning.className = 'stock-warning text-warning d-block mt-1';
            productSelect.parentNode.appendChild(warning);
        }
        
        warning.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Stock bajo: ${stock} unidades`;
    }
    
    updateItemCalculation(itemRow) {
        const quantityInput = itemRow.querySelector('.quantity-input');
        const priceInput = itemRow.querySelector('.price-input');
        const discountInput = itemRow.querySelector('.discount-input');
        const taxRateInput = itemRow.querySelector('.tax-rate-input');
        const taxAmountDisplay = itemRow.querySelector('.tax-amount-display');
        const itemTotalDisplay = itemRow.querySelector('.item-total-display');
        
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(priceInput.value) || 0;
        const discountPercent = parseFloat(discountInput.value) || 0;
        const taxRate = parseFloat(taxRateInput.value) || 0;
        
        // Calculations
        const subtotalBeforeDiscount = quantity * unitPrice;
        const discountAmount = (subtotalBeforeDiscount * discountPercent) / 100;
        const subtotalAfterDiscount = subtotalBeforeDiscount - discountAmount;
        const taxAmount = (subtotalAfterDiscount * taxRate) / 100;
        const itemTotal = subtotalAfterDiscount + taxAmount;
        
        // Update displays
        if (taxAmountDisplay) {
            taxAmountDisplay.value = this.formatCurrency(taxAmount);
        }
        if (itemTotalDisplay) {
            itemTotalDisplay.value = this.formatCurrency(itemTotal);
        }
        
        this.updateQuoteTotal();
    }
    
    updateQuoteTotal() {
        let subtotal = 0;
        let totalDiscount = 0;
        let totalTax = 0;
        
        const items = document.querySelectorAll('.item-row');
        items.forEach(item => {
            const quantity = parseFloat(item.querySelector('.quantity-input')?.value) || 0;
            const unitPrice = parseFloat(item.querySelector('.price-input')?.value) || 0;
            const discountPercent = parseFloat(item.querySelector('.discount-input')?.value) || 0;
            const taxRate = parseFloat(item.querySelector('.tax-rate-input')?.value) || 0;
            
            if (quantity > 0 && unitPrice > 0) {
                const itemSubtotal = quantity * unitPrice;
                const itemDiscount = (itemSubtotal * discountPercent) / 100;
                const itemAfterDiscount = itemSubtotal - itemDiscount;
                const itemTax = (itemAfterDiscount * taxRate) / 100;
                
                subtotal += itemSubtotal;
                totalDiscount += itemDiscount;
                totalTax += itemTax;
            }
        });
        
        const total = subtotal - totalDiscount + totalTax;
        
        // Update summary displays
        const subtotalEl = document.getElementById('subtotalAmount');
        const discountEl = document.getElementById('discountAmount');
        const taxEl = document.getElementById('taxAmount');
        const totalEl = document.getElementById('totalAmount');
        
        if (subtotalEl) subtotalEl.textContent = this.formatCurrency(subtotal);
        if (discountEl) discountEl.textContent = this.formatCurrency(totalDiscount);
        if (taxEl) taxEl.textContent = this.formatCurrency(totalTax);
        if (totalEl) totalEl.textContent = this.formatCurrency(total);
    }
    
    updateExpiryDate() {
        const issueDateInput = document.getElementById('issue_date');
        const expiryDateInput = document.getElementById('expiry_date');
        
        if (issueDateInput && expiryDateInput && issueDateInput.value) {
            const issueDate = new Date(issueDateInput.value);
            if (!isNaN(issueDate.getTime())) {
                const expiryDate = new Date(issueDate);
                expiryDate.setDate(expiryDate.getDate() + 7);
                expiryDateInput.value = expiryDate.toISOString().split('T')[0];
            }
        }
    }
    
    onClientChange() {
        // Could be used to load client-specific data or preferences
        // For now, just ensure proper selection
        const clientSelect = document.getElementById('client_id');
        if (clientSelect && clientSelect.value) {
            // Remove any previous client warnings
            const warnings = document.querySelectorAll('.client-warning');
            warnings.forEach(w => w.remove());
        }
    }
    
    validateForm(e) {
        const errors = [];
        
        // Check if client is selected
        const clientSelect = document.getElementById('client_id');
        if (!clientSelect || !clientSelect.value) {
            errors.push('Debe seleccionar un cliente');
        }
        
        // Check if dates are valid
        const issueDate = document.getElementById('issue_date')?.value;
        const expiryDate = document.getElementById('expiry_date')?.value;
        
        if (!issueDate) {
            errors.push('La fecha de emisión es requerida');
        }
        
        if (!expiryDate) {
            errors.push('La fecha de vencimiento es requerida');
        }
        
        if (issueDate && expiryDate && new Date(expiryDate) <= new Date(issueDate)) {
            errors.push('La fecha de vencimiento debe ser posterior a la fecha de emisión');
        }
        
        // Check if at least one valid item exists
        const validItems = this.getValidItems();
        if (validItems.length === 0) {
            errors.push('Debe agregar al menos un artículo válido');
        }
        
        // Check stock availability for each item
        const stockErrors = this.validateStock();
        errors.push(...stockErrors);
        
        if (errors.length > 0) {
            e.preventDefault();
            this.showValidationErrors(errors);
            return false;
        }
        
        return true;
    }
    
    getValidItems() {
        const items = [];
        const itemRows = document.querySelectorAll('.item-row');
        
        itemRows.forEach(row => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const priceInput = row.querySelector('.price-input');
            
            if (productSelect?.value && 
                parseInt(quantityInput?.value) > 0 && 
                parseFloat(priceInput?.value) > 0) {
                items.push({
                    product_id: productSelect.value,
                    quantity: parseInt(quantityInput.value),
                    price: parseFloat(priceInput.value)
                });
            }
        });
        
        return items;
    }
    
    validateStock() {
        const errors = [];
        const itemRows = document.querySelectorAll('.item-row');
        
        itemRows.forEach((row, index) => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            
            if (productSelect?.value && quantityInput?.value) {
                const selectedOption = productSelect.selectedOptions[0];
                const stock = parseInt(selectedOption.dataset.stock) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                
                if (quantity > stock) {
                    const productName = selectedOption.textContent.split(' - ')[0];
                    errors.push(`Artículo ${index + 1} (${productName}): cantidad solicitada (${quantity}) excede stock disponible (${stock})`);
                }
            }
        });
        
        return errors;
    }
    
    showValidationErrors(errors) {
        // Remove previous error alerts
        const existingAlerts = document.querySelectorAll('.validation-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create error alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger validation-alert';
        alertDiv.innerHTML = `
            <h6>Por favor corrija los siguientes errores:</h6>
            <ul class="mb-0">
                ${errors.map(error => `<li>${error}</li>`).join('')}
            </ul>
        `;
        
        // Insert at top of form
        const form = document.getElementById('quoteForm');
        if (form) {
            form.insertBefore(alertDiv, form.firstChild);
            alertDiv.scrollIntoView({ behavior: 'smooth' });
        }
    }
    
    showAlert(type, message) {
        // Create toast-style alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    formatCurrency(amount) {
        return '$' + amount.toFixed(2);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('quoteForm')) {
        new QuoteManager();
    }
});

// Export for potential use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = QuoteManager;
}