/**
 * Clients JavaScript Module
 * Handles client listing, forms, and AJAX operations
 */

// Clients List Management
const ClientsList = {
    currentPage: 1,
    perPage: 10,
    searchTerm: '',
    loading: false,

    init() {
        this.bindEvents();
        this.loadClients();
    },

    bindEvents() {
        // Search functionality
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('searchBtn');
        const clearBtn = document.getElementById('clearFilters');
        const perPageSelect = document.getElementById('perPage');

        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(() => {
                this.searchTerm = searchInput.value.trim();
                this.currentPage = 1;
                this.loadClients();
            }, 500));

            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.searchTerm = searchInput.value.trim();
                    this.currentPage = 1;
                    this.loadClients();
                }
            });
        }

        if (searchBtn) {
            searchBtn.addEventListener('click', () => {
                this.searchTerm = searchInput ? searchInput.value.trim() : '';
                this.currentPage = 1;
                this.loadClients();
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                if (perPageSelect) perPageSelect.value = '10';
                this.searchTerm = '';
                this.currentPage = 1;
                this.perPage = 10;
                this.loadClients();
            });
        }

        if (perPageSelect) {
            perPageSelect.addEventListener('change', () => {
                this.perPage = parseInt(perPageSelect.value);
                this.currentPage = 1;
                this.loadClients();
            });
        }

        // Delete modal events
        this.bindDeleteEvents();
    },

    bindDeleteEvents() {
        // Delete buttons in table
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-client') || e.target.closest('.delete-client')) {
                const btn = e.target.classList.contains('delete-client') ? e.target : e.target.closest('.delete-client');
                const clientId = btn.dataset.clientId;
                const clientName = btn.dataset.clientName;
                
                this.showDeleteModal(clientId, clientName);
            }
        });

        // Confirm delete button
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', () => {
                const clientId = confirmDeleteBtn.dataset.clientId;
                if (clientId) {
                    this.deleteClient(clientId);
                }
            });
        }
    },

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    async loadClients() {
        if (this.loading) return;
        
        this.loading = true;
        this.showLoading(true);

        try {
            const params = new URLSearchParams({
                page: this.currentPage,
                limit: this.perPage
            });

            if (this.searchTerm) {
                params.append('search', this.searchTerm);
            }

            const response = await fetch(`/?module=clients&action=list&${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            this.renderClientsTable(data);
            this.renderPagination(data);
            
        } catch (error) {
            console.error('Error loading clients:', error);
            this.showError('Error loading clients. Please try again.');
        } finally {
            this.loading = false;
            this.showLoading(false);
        }
    },

    showLoading(show) {
        const loadingEl = document.getElementById('loading');
        const tableContainer = document.getElementById('clientsTableContainer');
        const noResults = document.getElementById('noResults');

        if (loadingEl) loadingEl.style.display = show ? 'block' : 'none';
        if (tableContainer) tableContainer.style.display = show ? 'none' : 'block';
        if (noResults) noResults.style.display = 'none';
    },

    renderClientsTable(data) {
        const tbody = document.getElementById('clientsTableBody');
        const noResults = document.getElementById('noResults');
        const noResultsMessage = document.getElementById('noResultsMessage');

        if (!tbody) return;

        if (data.clients && data.clients.length > 0) {
            tbody.innerHTML = data.clients.map(client => `
                <tr>
                    <td>
                        <div class="fw-medium">${this.escapeHtml(client.company_name)}</div>
                    </td>
                    <td>${this.escapeHtml(client.contact_name)}</td>
                    <td>
                        <a href="mailto:${this.escapeHtml(client.email)}" class="text-decoration-none">
                            ${this.escapeHtml(client.email)}
                        </a>
                    </td>
                    <td>${client.phone ? this.escapeHtml(client.phone) : '-'}</td>
                    <td>
                        <small class="text-muted">${client.created_at}</small>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="/?module=clients&action=edit&id=${client.client_id}" 
                               class="btn btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger delete-client" 
                                    data-client-id="${client.client_id}"
                                    data-client-name="${this.escapeHtml(client.company_name)}"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            if (noResults) noResults.style.display = 'none';
        } else {
            tbody.innerHTML = '';
            if (noResults) {
                noResults.style.display = 'block';
                if (noResultsMessage) {
                    noResultsMessage.textContent = this.searchTerm ? 
                        'No clients match your search.' : 
                        'No clients available currently.';
                }
            }
        }
    },

    renderPagination(data) {
        const paginationEl = document.getElementById('pagination');
        const paginationContainer = document.getElementById('paginationContainer');
        
        if (!paginationEl || !data.total_pages) return;

        if (data.total_pages <= 1) {
            if (paginationContainer) paginationContainer.style.display = 'none';
            return;
        }

        if (paginationContainer) paginationContainer.style.display = 'block';

        let html = '';

        // Previous button
        html += `<li class="page-item ${data.page <= 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${data.page - 1}">Previous</a>
                 </li>`;

        // Page numbers
        const start = Math.max(1, data.page - 2);
        const end = Math.min(data.total_pages, data.page + 2);

        for (let i = start; i <= end; i++) {
            html += `<li class="page-item ${i === data.page ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                     </li>`;
        }

        // Next button
        html += `<li class="page-item ${data.page >= data.total_pages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${data.page + 1}">Next</a>
                 </li>`;

        paginationEl.innerHTML = html;

        // Bind pagination events
        paginationEl.addEventListener('click', (e) => {
            e.preventDefault();
            const pageLink = e.target.closest('.page-link');
            if (pageLink && !pageLink.closest('.page-item').classList.contains('disabled')) {
                const page = parseInt(pageLink.dataset.page);
                if (page && page !== this.currentPage) {
                    this.currentPage = page;
                    this.loadClients();
                }
            }
        });
    },

    showDeleteModal(clientId, clientName) {
        const modal = document.getElementById('deleteModal');
        const clientNameEl = document.getElementById('deleteClientName');
        const confirmBtn = document.getElementById('confirmDelete');

        if (modal && clientNameEl && confirmBtn) {
            clientNameEl.textContent = clientName;
            confirmBtn.dataset.clientId = clientId;
            
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    },

    async deleteClient(clientId) {
        try {
            const formData = new FormData();
            formData.append('id', clientId);
            formData.append('csrf_token', this.getCSRFToken());

            const response = await fetch('/?module=clients&action=delete', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showAlert('success', data.message || 'Client deleted successfully');
                this.loadClients();
                
                // Hide modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                if (modal) modal.hide();
            } else {
                this.showAlert('danger', data.error || 'Error deleting client');
            }

        } catch (error) {
            console.error('Error deleting client:', error);
            this.showAlert('danger', 'Error deleting client. Please try again.');
        }
    },

    showError(message) {
        this.showAlert('danger', message);
    },

    showAlert(type, message) {
        // Create alert element
        const alertEl = document.createElement('div');
        alertEl.className = `alert alert-${type} alert-dismissible fade show`;
        alertEl.innerHTML = `
            ${this.escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Insert at top of container
        const container = document.querySelector('.container-fluid, .container');
        if (container) {
            container.insertBefore(alertEl, container.firstChild);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alertEl.parentNode) {
                    alertEl.remove();
                }
            }, 5000);
        }
    },

    getCSRFToken() {
        const tokenEl = document.querySelector('input[name="csrf_token"]');
        return tokenEl ? tokenEl.value : '';
    },

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// Client Form Management
const ClientsForm = {
    formType: '',

    init(type = 'add') {
        this.formType = type;
        this.bindEvents();
        this.setupValidation();
    },

    bindEvents() {
        const form = document.getElementById(`${this.formType}ClientForm`);
        if (form) {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        // Real-time validation
        const inputs = form?.querySelectorAll('input, textarea, select');
        inputs?.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
    },

    setupValidation() {
        // Email validation
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', () => this.validateEmail(emailInput));
        }

        // Phone validation
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('blur', () => this.validatePhone(phoneInput));
        }
    },

    async handleSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn?.innerHTML;

        if (!this.validateForm(form)) {
            return;
        }

        try {
            // Show loading state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            }

            const formData = new FormData(form);
            const action = this.formType === 'add' ? 'add' : 'edit';
            
            const response = await fetch(`/?module=clients&action=${action}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showAlert('success', data.message);
                
                if (this.formType === 'add') {
                    // Reset form for new entry
                    form.reset();
                    this.clearAllErrors();
                }
            } else {
                this.showFormErrors(data.errors || ['An error occurred']);
            }

        } catch (error) {
            console.error('Form submission error:', error);
            this.showAlert('danger', 'Error submitting form. Please try again.');
        } finally {
            // Reset button state
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    },

    validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        // Additional validations
        const emailField = form.querySelector('#email');
        if (emailField && !this.validateEmail(emailField)) {
            isValid = false;
        }

        const phoneField = form.querySelector('#phone');
        if (phoneField && phoneField.value && !this.validatePhone(phoneField)) {
            isValid = false;
        }

        return isValid;
    },

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            errorMessage = `${this.getFieldLabel(field)} is required`;
            isValid = false;
        }

        this.setFieldError(field, errorMessage);
        return isValid;
    },

    validateEmail(field) {
        const email = field.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        let isValid = true;
        let errorMessage = '';

        if (email && !emailRegex.test(email)) {
            errorMessage = 'Please enter a valid email address';
            isValid = false;
        }

        this.setFieldError(field, errorMessage);
        return isValid;
    },

    validatePhone(field) {
        const phone = field.value.trim();
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{7,20}$/;
        
        let isValid = true;
        let errorMessage = '';

        if (phone && !phoneRegex.test(phone)) {
            errorMessage = 'Please enter a valid phone number';
            isValid = false;
        }

        this.setFieldError(field, errorMessage);
        return isValid;
    },

    setFieldError(field, message) {
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        
        if (message) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            if (feedback) feedback.textContent = message;
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            if (feedback) feedback.textContent = '';
        }
    },

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback) feedback.textContent = '';
    },

    clearAllErrors() {
        const form = document.getElementById(`${this.formType}ClientForm`);
        const fields = form?.querySelectorAll('.is-invalid, .is-valid');
        fields?.forEach(field => {
            field.classList.remove('is-invalid', 'is-valid');
        });

        const feedbacks = form?.querySelectorAll('.invalid-feedback');
        feedbacks?.forEach(feedback => {
            feedback.textContent = '';
        });
    },

    showFormErrors(errors) {
        let errorHtml = '<ul class="mb-0">';
        errors.forEach(error => {
            errorHtml += `<li>${this.escapeHtml(error)}</li>`;
        });
        errorHtml += '</ul>';

        this.showAlert('danger', errorHtml);
    },

    getFieldLabel(field) {
        const label = field.parentNode.querySelector('label');
        return label ? label.textContent.replace('*', '').trim() : field.name;
    },

    showAlert(type, message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        // Create new alert
        const alertEl = document.createElement('div');
        alertEl.className = `alert alert-${type} alert-dismissible fade show`;
        alertEl.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Insert at top of form container
        const container = document.querySelector('.container');
        if (container) {
            container.insertBefore(alertEl, container.firstChild);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alertEl.parentNode) {
                    alertEl.remove();
                }
            }, 5000);
        }
    },

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};