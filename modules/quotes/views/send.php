<!DOCTYPE html>
<html lang="<?= getUserLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('send_quote') ?> - <?= sanitizeOutput($quote['quote_number']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><?= __('send_quote') ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('dashboard', 'index') ?>"><?= __('dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= url('quotes', 'list') ?>"><?= __('quotes') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= url('quotes', 'view', ['id' => $quoteId]) ?>"><?= sanitizeOutput($quote['quote_number']) ?></a></li>
                        <li class="breadcrumb-item active"><?= __('send_quote') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?= url('quotes', 'view', ['id' => $quoteId]) ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> <?= __('back_to_quote') ?>
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <h6><?= __('please_correct_errors') ?>:</h6>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= sanitizeOutput($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> <?= __('quote_sent_successfully') ?>
            </div>
        <?php endif; ?>

        <!-- SMTP Configuration Warning -->
        <?php if (!$smtpConfigured): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong><?= __('smtp_not_configured') ?>:</strong>
                <?= __('contact_admin_to_configure_email') ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Email Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-envelope"></i> <?= __('email_details') ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="sendEmailForm">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            
                            <!-- Recipients -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="to_email" class="form-label"><?= __('to') ?> *</label>
                                    <input type="email" class="form-control" id="to_email" name="to_email" 
                                           value="<?= sanitizeOutput($emailData['to_email']) ?>" required>
                                    <div class="form-text"><?= __('primary_recipient_email') ?></div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="cc_email" class="form-label"><?= __('cc') ?></label>
                                    <input type="email" class="form-control" id="cc_email" name="cc_email" 
                                           value="<?= sanitizeOutput($emailData['cc_email']) ?>">
                                    <div class="form-text"><?= __('optional_cc_recipient') ?></div>
                                </div>
                            </div>
                            
                            <!-- Subject -->
                            <div class="mb-3">
                                <label for="subject" class="form-label"><?= __('subject') ?> *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       value="<?= sanitizeOutput($emailData['subject']) ?>" required>
                            </div>
                            
                            <!-- Message -->
                            <div class="mb-3">
                                <label for="message" class="form-label"><?= __('message') ?> *</label>
                                <textarea class="form-control" id="message" name="message" 
                                          rows="8" required><?= sanitizeOutput($emailData['message']) ?></textarea>
                                <div class="form-text"><?= __('email_message_will_be_sent_as_html') ?></div>
                            </div>
                            
                            <!-- Options -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="attach_pdf" name="attach_pdf" 
                                           <?= $emailData['attach_pdf'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="attach_pdf">
                                        <?= __('attach_pdf_quote') ?>
                                    </label>
                                    <div class="form-text"><?= __('pdf_will_be_generated_and_attached') ?></div>
                                </div>
                            </div>
                            
                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" <?= !$smtpConfigured ? 'disabled' : '' ?>>
                                    <i class="bi bi-send"></i> <?= __('send_email') ?>
                                </button>
                                
                                <button type="button" class="btn btn-outline-secondary" id="previewBtn">
                                    <i class="bi bi-eye"></i> <?= __('preview_message') ?>
                                </button>
                                
                                <a href="<?= url('quotes', 'view', ['id' => $quoteId]) ?>" class="btn btn-outline-secondary">
                                    <?= __('cancel') ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Quote Summary Sidebar -->
            <div class="col-lg-4">
                <!-- Quote Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-file-earmark-text"></i> <?= __('quote_information') ?></h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong><?= __('quote_number') ?>:</strong></td>
                                <td><?= sanitizeOutput($quote['quote_number']) ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= __('client') ?>:</strong></td>
                                <td><?= sanitizeOutput($quote['company_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= __('contact') ?>:</strong></td>
                                <td><?= sanitizeOutput($quote['contact_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= __('total_amount') ?>:</strong></td>
                                <td><?= formatCurrency($quote['total_amount']) ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= __('expiry_date') ?>:</strong></td>
                                <td><?= formatDate($quote['expiry_date'], 'Y-m-d') ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= __('status') ?>:</strong></td>
                                <td>
                                    <span class="badge bg-secondary"><?= __('status_draft') ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Email Templates -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-file-text"></i> <?= __('email_templates') ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="useTemplate('standard')">
                                <?= __('standard_template') ?>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="useTemplate('formal')">
                                <?= __('formal_template') ?>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="useTemplate('friendly')">
                                <?= __('friendly_template') ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-lightning"></i> <?= __('quick_actions') ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= url('quotes', 'pdf', ['id' => $quoteId]) ?>" class="btn btn-outline-danger btn-sm" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> <?= __('preview_pdf') ?>
                            </a>
                            <a href="<?= url('quotes', 'edit', ['id' => $quoteId]) ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-pencil"></i> <?= __('edit_quote') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('email_preview') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong><?= __('to') ?>:</strong> <span id="previewTo"></span>
                    </div>
                    <div class="mb-3">
                        <strong><?= __('subject') ?>:</strong> <span id="previewSubject"></span>
                    </div>
                    <div class="mb-3">
                        <strong><?= __('message') ?>:</strong>
                        <div id="previewMessage" class="border p-3 mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('close') ?></button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Email templates
        const templates = {
            standard: {
                subject: '<?= __('quote_email_subject', ['quote_number' => $quote['quote_number']]) ?>',
                message: `<?= __('quote_email_standard_template', [
                    'client_name' => $quote['contact_name'],
                    'quote_number' => $quote['quote_number'],
                    'total_amount' => formatCurrency($quote['total_amount']),
                    'expiry_date' => formatDate($quote['expiry_date'], 'Y-m-d')
                ]) ?>`
            },
            formal: {
                subject: '<?= __('quote_email_subject_formal', ['quote_number' => $quote['quote_number']]) ?>',
                message: `<?= __('quote_email_formal_template', [
                    'client_name' => $quote['contact_name'],
                    'company_name' => $quote['company_name'],
                    'quote_number' => $quote['quote_number'],
                    'total_amount' => formatCurrency($quote['total_amount']),
                    'expiry_date' => formatDate($quote['expiry_date'], 'Y-m-d')
                ]) ?>`
            },
            friendly: {
                subject: '<?= __('quote_email_subject_friendly', ['quote_number' => $quote['quote_number']]) ?>',
                message: `<?= __('quote_email_friendly_template', [
                    'client_name' => $quote['contact_name'],
                    'quote_number' => $quote['quote_number'],
                    'total_amount' => formatCurrency($quote['total_amount']),
                    'expiry_date' => formatDate($quote['expiry_date'], 'Y-m-d')
                ]) ?>`
            }
        };
        
        function useTemplate(templateName) {
            if (templates[templateName]) {
                document.getElementById('subject').value = templates[templateName].subject;
                document.getElementById('message').value = templates[templateName].message;
            }
        }
        
        // Preview functionality
        document.getElementById('previewBtn').addEventListener('click', function() {
            const to = document.getElementById('to_email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            document.getElementById('previewTo').textContent = to;
            document.getElementById('previewSubject').textContent = subject;
            document.getElementById('previewMessage').innerHTML = message.replace(/\n/g, '<br>');
            
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        });
        
        // Form validation
        document.getElementById('sendEmailForm').addEventListener('submit', function(e) {
            const toEmail = document.getElementById('to_email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            if (!toEmail || !subject || !message) {
                e.preventDefault();
                alert('<?= __('please_fill_required_fields') ?>');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(toEmail)) {
                e.preventDefault();
                alert('<?= __('invalid_email_format') ?>');
                return;
            }
            
            const ccEmail = document.getElementById('cc_email').value;
            if (ccEmail && !emailRegex.test(ccEmail)) {
                e.preventDefault();
                alert('<?= __('invalid_cc_email_format') ?>');
                return;
            }
            
            if (!confirm('<?= __('confirm_send_quote_email') ?>')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>