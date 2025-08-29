<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput(__('send_quote')) ?> - <?= sanitizeOutput(__('app_name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><?= sanitizeOutput(__('send_quote')) ?></h2>
                        <p class="text-muted"><?= sanitizeOutput(__('quote_number')) ?>: <strong><?= sanitizeOutput($quote['quote_number']) ?></strong></p>
                    </div>
                    <div class="btn-group">
                        <a href="<?= url('quotes', 'view', ['id' => $quote['quote_id']]) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> <?= sanitizeOutput(__('back_to_quote')) ?>
                        </a>
                        <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary">
                            <?= sanitizeOutput(__('back_to_list')) ?>
                        </a>
                    </div>
                </div>

                <!-- Quote Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-file-text"></i> <?= sanitizeOutput(__('quote_summary')) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('quote_number')) ?>:</td>
                                        <td><?= sanitizeOutput($quote['quote_number']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('client')) ?>:</td>
                                        <td><?= sanitizeOutput($client['company_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('contact')) ?>:</td>
                                        <td><?= sanitizeOutput($client['contact_name']) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('email')) ?>:</td>
                                        <td><?= sanitizeOutput($client['email']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('total_amount')) ?>:</td>
                                        <td class="fw-bold text-primary"><?= sanitizeOutput(formatCurrency($quote['total_amount'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold"><?= sanitizeOutput(__('expiry_date')) ?>:</td>
                                        <td><?= sanitizeOutput(formatDate($quote['expiry_date'], 'd/m/Y')) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Send Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-envelope"></i> <?= sanitizeOutput(__('email_details')) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="sendQuoteForm">
                            <input type="hidden" name="csrf_token" value="<?= sanitizeOutput($csrfToken) ?>">
                            <input type="hidden" name="quote_id" value="<?= sanitizeOutput($quote['quote_id']) ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="recipient_email" class="form-label">
                                            <?= sanitizeOutput(__('recipient_email')) ?>
                                        </label>
                                        <input type="email" class="form-control" id="recipient_email" 
                                               value="<?= sanitizeOutput($client['email']) ?>" readonly>
                                        <div class="form-text"><?= sanitizeOutput(__('email_will_be_sent_to')) ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="recipient_name" class="form-label">
                                            <?= sanitizeOutput(__('recipient_name')) ?>
                                        </label>
                                        <input type="text" class="form-control" id="recipient_name" 
                                               value="<?= sanitizeOutput($client['contact_name']) ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email_message" class="form-label">
                                    <?= sanitizeOutput(__('custom_message')) ?>
                                </label>
                                <textarea class="form-control" id="email_message" name="email_message" 
                                          rows="5" placeholder="<?= sanitizeOutput(__('optional_custom_message')) ?>"></textarea>
                                <div class="form-text">
                                    <?= sanitizeOutput(__('custom_message_help')) ?>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong><?= sanitizeOutput(__('what_will_happen')) ?>:</strong>
                                <ul class="mb-0 mt-2">
                                    <li><?= sanitizeOutput(__('pdf_will_be_generated')) ?></li>
                                    <li><?= sanitizeOutput(__('email_will_be_sent')) ?></li>
                                    <li><?= sanitizeOutput(__('quote_status_will_change')) ?></li>
                                    <li><?= sanitizeOutput(__('client_will_receive_notification')) ?></li>
                                </ul>
                            </div>
                            
                            <!-- Preview Email Template -->
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-eye"></i> <?= sanitizeOutput(__('email_preview')) ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p><strong><?= sanitizeOutput(__('subject')) ?>:</strong> Cotización #<?= sanitizeOutput($quote['quote_number']) ?></p>
                                    <hr>
                                    <div class="email-preview">
                                        <p>Estimado/a <?= sanitizeOutput($client['contact_name']) ?>,</p>
                                        
                                        <p>Adjunto encontrará la cotización #<?= sanitizeOutput($quote['quote_number']) ?> solicitada.</p>
                                        
                                        <div id="customMessagePreview" style="display: none;">
                                            <p class="bg-warning bg-opacity-25 p-2 rounded">
                                                <strong><?= sanitizeOutput(__('your_message')) ?>:</strong>
                                                <span id="messagePreviewText"></span>
                                            </p>
                                        </div>
                                        
                                        <p><strong>Detalles de la cotización:</strong></p>
                                        <ul>
                                            <li>Número: <?= sanitizeOutput($quote['quote_number']) ?></li>
                                            <li>Fecha: <?= sanitizeOutput(formatDate($quote['issue_date'], 'd/m/Y')) ?></li>
                                            <li>Válida hasta: <?= sanitizeOutput(formatDate($quote['expiry_date'], 'd/m/Y')) ?></li>
                                            <li>Total: <?= sanitizeOutput(formatCurrency($quote['total_amount'])) ?></li>
                                        </ul>
                                        
                                        <p>Si tiene alguna pregunta, no dude en contactarnos.</p>
                                        
                                        <p>Saludos cordiales.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="<?= url('quotes', 'view', ['id' => $quote['quote_id']]) ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> <?= sanitizeOutput(__('cancel')) ?>
                                </a>
                                <button type="submit" class="btn btn-primary" id="sendBtn">
                                    <i class="bi bi-envelope-arrow-up"></i> <?= sanitizeOutput(__('send_quote')) ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        const csrfToken = '<?= sanitizeOutput($csrfToken) ?>';
        
        document.addEventListener('DOMContentLoaded', function() {
            const emailMessageTextarea = document.getElementById('email_message');
            const customMessagePreview = document.getElementById('customMessagePreview');
            const messagePreviewText = document.getElementById('messagePreviewText');
            const sendForm = document.getElementById('sendQuoteForm');
            const sendBtn = document.getElementById('sendBtn');
            
            // Update preview when custom message changes
            emailMessageTextarea.addEventListener('input', function() {
                const message = this.value.trim();
                if (message) {
                    messagePreviewText.textContent = message;
                    customMessagePreview.style.display = 'block';
                } else {
                    customMessagePreview.style.display = 'none';
                }
            });
            
            // Handle form submission
            sendForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!confirm('¿Está seguro que desea enviar esta cotización por email?')) {
                    return;
                }
                
                // Disable button and show loading
                sendBtn.disabled = true;
                sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
                
                const formData = new FormData(this);
                
                fetch('<?= url('quotes', 'send') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showAlert(data.message || 'Cotización enviada exitosamente', 'success');
                        
                        // Redirect after delay
                        setTimeout(() => {
                            window.location.href = '<?= url('quotes', 'view', ['id' => $quote['quote_id']]) ?>';
                        }, 2000);
                    } else {
                        showAlert(data.error || 'Error al enviar la cotización', 'danger');
                        
                        // Re-enable button
                        sendBtn.disabled = false;
                        sendBtn.innerHTML = '<i class="bi bi-envelope-arrow-up"></i> <?= sanitizeOutput(__('send_quote')) ?>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error de conexión', 'danger');
                    
                    // Re-enable button
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = '<i class="bi bi-envelope-arrow-up"></i> <?= sanitizeOutput(__('send_quote')) ?>';
                });
            });
        });
        
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
    </script>
</body>
</html>