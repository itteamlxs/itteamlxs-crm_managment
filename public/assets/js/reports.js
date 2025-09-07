/**
 * Reports JavaScript
 * Chart.js integration and interactive report functionality
 */

// Chart configurations
const chartColors = {
    primary: '#007bff',
    secondary: '#6c757d',
    success: '#28a745',
    danger: '#dc3545',
    warning: '#ffc107',
    info: '#17a2b8',
    light: '#f8f9fa',
    dark: '#343a40'
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'top'
        }
    },
    scales: {
        y: {
            beginAtZero: true
        }
    }
};

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    setupEventListeners();
    fixFormLabels();
});

function fixFormLabels() {
    // Fix label associations for compliance report filters
    const entityTypeLabel = document.querySelector('label[for="entityTypeFilter"]');
    if (!entityTypeLabel) {
        const entityTypeSelect = document.getElementById('entityTypeFilter');
        if (entityTypeSelect) {
            const label = entityTypeSelect.previousElementSibling;
            if (label && label.tagName === 'LABEL') {
                label.setAttribute('for', 'entityTypeFilter');
            }
        }
    }
    
    const actionLabel = document.querySelector('label[for="actionFilter"]');
    if (!actionLabel) {
        const actionSelect = document.getElementById('actionFilter');
        if (actionSelect) {
            const label = actionSelect.previousElementSibling;
            if (label && label.tagName === 'LABEL') {
                label.setAttribute('for', 'actionFilter');
            }
        }
    }
    
    const startDateLabel = document.querySelector('label[for="startDate"]');
    if (!startDateLabel) {
        const startDateInput = document.getElementById('startDate');
        if (startDateInput) {
            const label = startDateInput.previousElementSibling;
            if (label && label.tagName === 'LABEL') {
                label.setAttribute('for', 'startDate');
            }
        }
    }
    
    const endDateLabel = document.querySelector('label[for="endDate"]');
    if (!endDateLabel) {
        const endDateInput = document.getElementById('endDate');
        if (endDateInput) {
            const label = endDateInput.previousElementSibling;
            if (label && label.tagName === 'LABEL') {
                label.setAttribute('for', 'endDate');
            }
        }
    }
    
    // Fix any other form labels that might be missing associations
    const allLabels = document.querySelectorAll('label:not([for])');
    allLabels.forEach(label => {
        const nextElement = label.nextElementSibling;
        if (nextElement && (nextElement.tagName === 'INPUT' || nextElement.tagName === 'SELECT' || nextElement.tagName === 'TEXTAREA')) {
            if (nextElement.id) {
                label.setAttribute('for', nextElement.id);
            } else {
                // Generate an ID if one doesn't exist
                const fieldId = 'field_' + Math.random().toString(36).substr(2, 9);
                nextElement.id = fieldId;
                label.setAttribute('for', fieldId);
            }
        }
    });
}

function initializeCharts() {
    // Sales Performance Chart
    const salesCtx = document.getElementById('salesPerformanceChart');
    if (salesCtx) {
        createSalesPerformanceChart(salesCtx);
    }
    
    // Sales Trends Chart
    const trendsCtx = document.getElementById('salesTrendsChart');
    if (trendsCtx) {
        createSalesTrendsChart(trendsCtx);
    }
    
    // Client Activity Chart
    const clientCtx = document.getElementById('clientActivityChart');
    if (clientCtx) {
        createClientActivityChart(clientCtx);
    }
    
    // Product Performance Chart
    const productCtx = document.getElementById('productPerformanceChart');
    if (productCtx) {
        createProductPerformanceChart(productCtx);
    }
    
    // Category Summary Chart
    const categoryCtx = document.getElementById('categorySummaryChart');
    if (categoryCtx) {
        createCategorySummaryChart(categoryCtx);
    }
}

function createSalesPerformanceChart(ctx) {
    if (typeof salesPerformanceData === 'undefined') return;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: salesPerformanceData.map(item => item.username),
            datasets: [{
                label: 'Total Amount',
                data: salesPerformanceData.map(item => parseFloat(item.total_amount)),
                backgroundColor: chartColors.primary,
                borderColor: chartColors.primary,
                borderWidth: 1
            }, {
                label: 'Total Quotes',
                data: salesPerformanceData.map(item => parseInt(item.total_quotes)),
                backgroundColor: chartColors.secondary,
                borderColor: chartColors.secondary,
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            ...chartOptions,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
}

function createSalesTrendsChart(ctx) {
    if (typeof salesTrendsData === 'undefined') return;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesTrendsData.map(item => item.month),
            datasets: [{
                label: 'Total Amount',
                data: salesTrendsData.map(item => parseFloat(item.total_amount)),
                borderColor: chartColors.primary,
                backgroundColor: chartColors.primary + '20',
                fill: true,
                tension: 0.4
            }]
        },
        options: chartOptions
    });
}

function createClientActivityChart(ctx) {
    if (typeof clientActivityData === 'undefined') return;
    
    const topClients = clientActivityData.slice(0, 10);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: topClients.map(item => item.company_name),
            datasets: [{
                data: topClients.map(item => parseFloat(item.total_amount || 0)),
                backgroundColor: [
                    chartColors.primary,
                    chartColors.secondary,
                    chartColors.success,
                    chartColors.danger,
                    chartColors.warning,
                    chartColors.info,
                    chartColors.light,
                    chartColors.dark,
                    '#6f42c1',
                    '#e83e8c'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
}

function createProductPerformanceChart(ctx) {
    if (typeof productPerformanceData === 'undefined') return;
    
    const topProducts = productPerformanceData.slice(0, 10);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: topProducts.map(item => item.product_name),
            datasets: [{
                label: 'Total Sold',
                data: topProducts.map(item => parseInt(item.total_sold || 0)),
                backgroundColor: chartColors.success,
                borderColor: chartColors.success,
                borderWidth: 1
            }]
        },
        options: {
            ...chartOptions,
            indexAxis: 'y'
        }
    });
}

function createCategorySummaryChart(ctx) {
    if (typeof categorySummaryData === 'undefined') return;
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: categorySummaryData.map(item => item.category_name),
            datasets: [{
                data: categorySummaryData.map(item => parseInt(item.product_count)),
                backgroundColor: [
                    chartColors.primary,
                    chartColors.secondary,
                    chartColors.success,
                    chartColors.danger,
                    chartColors.warning,
                    chartColors.info
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function setupEventListeners() {
    // Date range filters
    const dateRangeForm = document.getElementById('dateRangeForm');
    if (dateRangeForm) {
        dateRangeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyDateFilter();
        });
    }
    
    // Report type switcher
    const reportTypeSelect = document.getElementById('reportType');
    if (reportTypeSelect) {
        reportTypeSelect.addEventListener('change', function() {
            switchReportType(this.value);
        });
    }
    
    // Export buttons
    setupExportButtons();
    
    // Refresh button
    const refreshBtn = document.getElementById('refreshReports');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            refreshReports();
        });
    }
    
    // Compliance report filters
    setupComplianceFilters();
}

function setupComplianceFilters() {
    // Entity type filter
    const entityTypeFilter = document.getElementById('entityTypeFilter');
    if (entityTypeFilter) {
        entityTypeFilter.addEventListener('change', function() {
            // Optional: Auto-apply filter on change
            // applyFilters();
        });
    }
    
    // Action filter
    const actionFilter = document.getElementById('actionFilter');
    if (actionFilter) {
        actionFilter.addEventListener('change', function() {
            // Optional: Auto-apply filter on change
            // applyFilters();
        });
    }
    
    // Date filters
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    
    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            validateDateRange();
        });
    }
    
    if (endDateInput) {
        endDateInput.addEventListener('change', function() {
            validateDateRange();
        });
    }
}

function validateDateRange() {
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    
    if (startDate && endDate && startDate.value && endDate.value) {
        if (new Date(startDate.value) > new Date(endDate.value)) {
            alert('La fecha de inicio no puede ser posterior a la fecha de fin');
            startDate.value = '';
        }
    }
}

function applyDateFilter() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }
    
    // Show loading
    showLoading(true);
    
    // Reload current page with date parameters
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('start_date', startDate);
    urlParams.set('end_date', endDate);
    
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

function switchReportType(reportType) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('type', reportType);
    window.location.href = currentUrl.toString();
}

function setupExportButtons() {
    // CSV Export
    const csvBtn = document.getElementById('exportCSV');
    if (csvBtn) {
        csvBtn.addEventListener('click', function() {
            exportToCSV();
        });
    }
    
    // PDF Export
    const pdfBtn = document.getElementById('exportPDF');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', function() {
            exportToPDF();
        });
    }
}

function exportToCSV() {
    const table = document.querySelector('.report-table');
    if (!table) {
        alert('No hay tabla disponible para exportar');
        return;
    }
    
    try {
        let csv = [];
        const rows = table.querySelectorAll('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const row = [];
            const cols = rows[i].querySelectorAll('td, th');
            
            for (let j = 0; j < cols.length; j++) {
                // Clean text content and escape quotes
                let cellText = cols[j].innerText || cols[j].textContent || '';
                cellText = cellText.replace(/"/g, '""'); // Escape quotes
                row.push('"' + cellText.trim() + '"');
            }
            
            if (row.length > 0) {
                csv.push(row.join(','));
            }
        }
        
        if (csv.length > 0) {
            downloadCSV(csv.join('\n'), 'report_' + new Date().toISOString().split('T')[0] + '.csv');
        } else {
            alert('No hay datos para exportar');
        }
    } catch (error) {
        console.error('Error exporting CSV:', error);
        alert('Error al exportar CSV: ' + error.message);
    }
}

function downloadCSV(csv, filename) {
    try {
        // Add BOM for UTF-8 compatibility
        const BOM = '\uFEFF';
        const csvFile = new Blob([BOM + csv], { type: 'text/csv;charset=utf-8' });
        const downloadLink = document.createElement('a');
        
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
        
        // Clean up object URL
        window.URL.revokeObjectURL(downloadLink.href);
    } catch (error) {
        console.error('Error downloading CSV:', error);
        alert('Error al descargar el archivo CSV');
    }
}

function exportToPDF() {
    showLoading(true);
    
    try {
        // Get current page parameters
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('export', 'pdf');
        
        // Open PDF in new window
        const pdfWindow = window.open(window.location.pathname + '?' + urlParams.toString(), '_blank');
        
        if (!pdfWindow) {
            alert('Por favor permita las ventanas emergentes para descargar el PDF');
        }
        
        showLoading(false);
    } catch (error) {
        console.error('Error exporting PDF:', error);
        alert('Error al exportar PDF: ' + error.message);
        showLoading(false);
    }
}

function refreshReports() {
    showLoading(true);
    
    // Make AJAX request to refresh materialized views
    fetch(window.location.pathname + '?action=refresh', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to refresh reports: ' + (data.error || 'Unknown error'));
            showLoading(false);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while refreshing reports: ' + error.message);
        showLoading(false);
    });
}

function showLoading(show) {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) {
        spinner.style.display = show ? 'block' : 'none';
    }
    
    // Disable/enable buttons during loading
    const buttons = document.querySelectorAll('button, .btn');
    buttons.forEach(btn => {
        if (show) {
            btn.disabled = true;
            btn.classList.add('loading');
        } else {
            btn.disabled = false;
            btn.classList.remove('loading');
        }
    });
}

// Global functions for compliance filters (called from HTML)
window.applyFilters = function() {
    const entityType = document.getElementById('entityTypeFilter')?.value || '';
    const action = document.getElementById('actionFilter')?.value || '';
    const startDate = document.getElementById('startDate')?.value || '';
    const endDate = document.getElementById('endDate')?.value || '';
    
    // Validate date range
    if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
        alert('La fecha de inicio no puede ser posterior a la fecha de fin');
        return;
    }
    
    const params = new URLSearchParams();
    params.set('module', 'reports');
    params.set('action', 'compliance');
    
    if (entityType) params.set('entity_type', entityType);
    if (action) params.set('action', action);
    if (startDate) params.set('start_date', startDate);
    if (endDate) params.set('end_date', endDate);
    
    showLoading(true);
    window.location.href = '?' + params.toString();
};

window.clearFilters = function() {
    showLoading(true);
    window.location.href = '?module=reports&action=compliance';
};

// Utility functions for data formatting
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

function formatNumber(number) {
    return new Intl.NumberFormat().format(number);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function formatPercentage(value) {
    return (parseFloat(value) * 100).toFixed(2) + '%';
}