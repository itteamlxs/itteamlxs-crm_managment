<?php
/**
 * Send Quote View with Navigation Integration
 */

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../../../config/db.php';

requireLogin();
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="<?= getUserLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Cotización - <?= sanitizeOutput($quote['quote_number']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Enviar Cotización</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= url('dashboard', 'index') ?>"><?= __('dashboard') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= url('quotes', 'list') ?>">Cotizaciones</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= url('quotes', 'view', ['id' => $quoteId]) ?>"><?= sanitizeOutput($quote['quote_number']) ?></a>
                        </li>
                        <li class="breadcrumb-item active">Enviar</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?= url('quotes', 'view', ['id' => $quoteId]) ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a Cotización
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <h6>Errores encontrados:</h6>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= sanitizeOutput($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- SMTP Configuration Warning -->
        <?php if (!$smtpConfigured): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>SMTP no configurado:</strong>
                Contacte al administrador para configurar el envío de correos.
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Send Confirmation -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-envelope-paper"></i> Confirmar Envío de Cotización</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Información:</strong> La cotización será enviada automáticamente con toda la información necesaria al cliente.
                        </div>

                        <div class="mb-4">
                            <h6>El correo incluirá:</h6>
                            <ul class="list-unstyled ms-3">
                                <li><i class="bi bi-check text-success"></i> Información de la empresa (<?= sanitizeOutput($settings['company_display_name'] ?? 'CRM System') ?>)</li>
                                <li><i class="bi bi-check text-success"></i> Datos del vendedor (<?= sanitizeOutput($user['display_name']) ?>)</li>
                                <li><i class="bi bi-check text-success"></i> Información completa del cliente</li>
                                <li><i class="bi bi-check text-success"></i> Lista detallada de productos cotizados</li>
                                <li><i class="bi bi-check text-success"></i> Número de cotización: <?= sanitizeOutput($quote['quote_number']) ?></li>
                                <li><i class="bi bi-check text-success"></i> Fecha de envío: <?= date('Y-m-d') ?></li>
                                <li><i class="bi bi-check text-success"></i> Fecha de vencimiento: <?= formatDate($quote['expiry_date'], 'Y-m-d') ?></li>
                                <li><i class="bi bi-check text-success"></i> Total de la cotización: <?= formatCurrency($quote['total_amount']) ?></li>
                            </ul>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6><i class="bi bi-envelope-at"></i> Destinatario</h6>
                                <div class="bg-light p-3 rounded">
                                    <strong><?= sanitizeOutput($quote['contact_name']) ?></strong><br>
                                    <span class="text-muted"><?= sanitizeOutput($quote['company_name']) ?></span><br>
                                    <i class="bi bi-envelope"></i> <?= sanitizeOutput($quote['client_email']) ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6><i class="bi bi-person-badge"></i> Remitente</h6>
                                <div class="bg-light p-3 rounded">
                                    <strong><?= sanitizeOutput($settings['from_name'] ?? $user['display_name']) ?></strong><br>
                                    <span class="text-muted"><?= sanitizeOutput($settings['company_display_name'] ?? 'CRM System') ?></span><br>
                                    <i class="bi bi-envelope"></i> <?= sanitizeOutput($settings['from_email'] ?? 'no-reply@empresa.com') ?>
                                </div>
                            </div>
                        </div>

                        <form method="POST" id="sendQuoteForm">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="submit" class="btn btn-primary btn-lg" <?= !$smtpConfigured ? 'disabled' : '' ?>>
                                    <i class="bi bi-send"></i> Enviar Cotización Ahora
                                </button>
                                
                                <a href="<?= url('quotes', 'view', ['id' => $quoteId]) ?>" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-x-circle"></i> Cancelar
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
                        <h6 class="mb-0"><i class="bi bi-file-earmark-text"></i> Resumen de Cotización</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Número:</strong></td>
                                <td><?= sanitizeOutput($quote['quote_number']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Cliente:</strong></td>
                                <td><?= sanitizeOutput($quote['company_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Contacto:</strong></td>
                                <td><?= sanitizeOutput($quote['contact_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?= sanitizeOutput($quote['client_email']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td class="fw-bold text-primary"><?= formatCurrency($quote['total_amount']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Vence:</strong></td>
                                <td class="text-danger"><?= formatDate($quote['expiry_date'], 'Y-m-d') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Estado:</strong></td>
                                <td>
                                    <span class="badge bg-secondary">BORRADOR</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Items Preview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-box-seam"></i> Productos (<?= count($quoteItems) ?>)</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach (array_slice($quoteItems, 0, 3) as $item): ?>
                            <div class="mb-2 pb-2 <?= $item !== end(array_slice($quoteItems, 0, 3)) ? 'border-bottom' : '' ?>">
                                <small class="fw-bold"><?= sanitizeOutput($item['product_name']) ?></small><br>
                                <small class="text-muted">
                                    Cant: <?= $item['quantity'] ?> × <?= formatCurrency($item['unit_price']) ?> = <?= formatCurrency($item['subtotal']) ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($quoteItems) > 3): ?>
                            <small class="text-muted">... y <?= count($quoteItems) - 3 ?> producto(s) más</small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-lightning"></i> Acciones Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= url('quotes', 'view', ['id' => $quoteId]) ?>" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-eye"></i> Ver Cotización Completa
                            </a>
                            <a href="<?= url('quotes', 'edit', ['id' => $quoteId]) ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Editar Antes de Enviar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form confirmation
        document.getElementById('sendQuoteForm').addEventListener('submit', function(e) {
            if (!confirm('¿Está seguro que desea enviar esta cotización por correo electrónico?\n\nLa cotización cambiará automáticamente a estado "ENVIADO" y se enviará el correo al cliente.')) {
                e.preventDefault();
            }
        });
        
        // Auto-focus on send button after page load
        document.addEventListener('DOMContentLoaded', function() {
            const sendBtn = document.querySelector('button[type="submit"]');
            if (sendBtn && !sendBtn.disabled) {
                sendBtn.focus();
            }
        });
    </script>
</body>
</html>