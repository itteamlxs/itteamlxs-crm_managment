<?php
/**
 * Spanish translations - Complete with Login additions
 */
return [
// Títulos y textos principales
'login_your_account' => 'Ingresa a tu cuenta',
'welcome_back' => '¡Bienvenido de vuelta!',
'enter_email_password' => 'Ingresa tu email y contraseña',
'username_or_email' => 'Usuario o Email',
'enter_username_email' => 'Ingresa tu usuario o email',
'password' => 'Contraseña',
'enter_password' => 'Ingresa tu contraseña',
'sign_in' => 'Iniciar Sesión',
'forgot_password' => '¿Olvidó su contraseña?',
'debug_mode_credentials' => 'Modo Debug: Use leon/temporal2024#',

// Mensajes de error/éxito (variables PHP)
'invalid_security_token' => 'Token de seguridad inválido',
'username_password_required' => 'Usuario y contraseña son requeridos',
'too_many_attempts' => 'Demasiados intentos de login. Intente más tarde.',
'invalid_credentials' => 'Usuario o contraseña incorrectos',
'logout_success' => 'Ha cerrado sesión exitosamente.',

// Textos de branding (compartidos con reset)
'app_name' => 'Athena CRM',
'everything_you_need_to_grow' => 'Todo lo que necesitas para Crecer',
'powered_by_entropic' => 'Desarrollado por <strong>Entropic Networks</strong>.',
'manage_customers_boost_business' => 'Gestiona clientes e impulsa tu negocio',

// Título de la página
'password_reset' => 'Restablecer Contraseña',

// Textos de la interfaz
'password_assistance' => 'Asistencia de contraseña',
'password_reset_help' => 'Ayuda para restablecer contraseña',
'contact_admin_for_help' => 'Contacte a su administrador para obtener ayuda',
'password_reset_not_available' => 'Restablecimiento de Contraseña No Disponible',
'contact_admin_password' => 'Si necesita restablecer su contraseña, por favor contacte a su administrador del sistema.',
'back_to_login' => 'Volver al Login',

// Textos de branding (compartidos con login)
'app_name' => 'Athena CRM',
'everything_you_need_to_grow' => 'Todo lo que necesitas para Crecer',
'powered_by_entropic' => 'Desarrollado por <strong>Entropic Networks</strong>.',
'manage_customers_boost_business' => 'Gestiona clientes e impulsa tu negocio',

// Títulos y textos principales
'dashboard' => 'Panel de Control',
'welcome' => 'Bienvenido',
'role' => 'Rol',
'login_date' => 'Fecha de Ingreso',
'company' => 'Empresa',
'navigation' => 'Navegación',

// Gestión de usuarios
'users_management' => 'Gestión de Usuarios',
'manage_users_description' => 'Gestionar usuarios, roles y permisos',

// Gestión de roles
'roles_management' => 'Gestión de Roles',
'manage_roles_description' => 'Gestionar roles y permisos',

// Perfil de usuario
'my_profile' => 'Mi Perfil',
'edit_profile_description' => 'Editar su información personal',

// Módulo de clientes
'clients' => 'Clientes',
'manage_clients_description' => 'Gestionar información de clientes',

// Módulo de productos
'products' => 'Productos',
'manage_products_description' => 'Gestionar productos y categorías',

// Módulo de cotizaciones
'quotes' => 'Cotizaciones',
'manage_quotes_description' => 'Crear y gestionar cotizaciones',

// Reportes de cumplimiento
'compliance_reports' => 'Reportes de Cumplimiento',
'view_compliance_reports_description' => 'Ver reportes de cumplimiento y auditoría',

// Reportes de ventas
'sales_reports' => 'Reportes de Ventas',
'view_sales_reports_description' => 'Ver reportes de desempeño de ventas',

// Reportes de clientes
'client_reports' => 'Reportes de Clientes',
'view_client_reports_description' => 'Ver reportes de actividad de clientes',

// Reportes de productos
'product_reports' => 'Reportes de Productos',
'view_product_reports_description' => 'Ver reportes de desempeño de productos',

// Configuración del sistema
'settings' => 'Configuración',
'manage_settings_description' => 'Gestionar configuraciones del sistema',

// Acceso general
'access' => 'Acceder',

// add_edit_role.php
'edit_role' => 'Editar Rol',
'add_role' => 'Agregar Rol',
'back_to_roles' => 'Volver a Roles',
'role_name' => 'Nombre del Rol',
'description' => 'Descripción',
'cancel' => 'Cancelar',
'update_role' => 'Actualizar Rol',
'create_role' => 'Crear Rol',
'additional_actions' => 'Acciones Adicionales',
'roles_list' => 'Lista de Roles',

// roles.php
'roles_management' => 'Gestión de Roles',
'users' => 'Usuarios',
'created_at' => 'Creado',
'actions' => 'Acciones',
'edit' => 'Editar',
'delete' => 'Eliminar',
'confirm_delete' => 'Confirmar Eliminación',
'confirm_delete_role' => '¿Está seguro que desea eliminar el rol',
'this_action_cannot_be_undone' => 'Esta acción no se puede deshacer.',
'cannot_delete_role_with_users' => 'No se puede eliminar el rol porque tiene usuarios asignados. Primero reasigne los usuarios a otro rol.',

// assign.php
'assign_permissions' => 'Asignar Permisos',
'select_all' => 'Seleccionar Todo',
'select_none' => 'Seleccionar Nada',
'save_permissions' => 'Guardar Permisos',
'no_permissions_available' => 'No hay permisos disponibles',
'permissions_updated_successfully' => 'Permisos actualizados exitosamente',

// edit_product.php
'edit_product' => 'Editar Producto',
'product_details' => 'Detalles del Producto',
'product_name' => 'Nombre del Producto',
'sku' => 'SKU',
'sku_format_help' => 'Formato: letras, números, guiones y guiones bajos. Máx. 50 caracteres.',
'category' => 'Categoría',
'select_category' => 'Seleccionar Categoría',
'price' => 'Precio',
'tax_rate' => 'Tasa de Impuesto',
'stock_quantity' => 'Cantidad en Stock',
'current_stock_help' => 'Stock actual del producto',
'product_information' => 'Información del Producto',
'updated_at' => 'Actualizado',
'update_product' => 'Actualizar Producto',
'product_name_required' => 'El nombre del producto es requerido',
'sku_required' => 'El SKU es requerido',
'invalid_sku_format' => 'Formato de SKU inválido',
'price_required' => 'El precio es requerido',
'invalid_stock_quantity' => 'Cantidad de stock inválida',
'category_required' => 'La categoría es requerida',
'low_stock_warning' => '¡Stock bajo! Considere reabastecer.',
'stock_decrease_warning' => 'Está reduciendo el stock actual.',

// add_product.php
'add_product' => 'Agregar Producto',
'new_product_details' => 'Detalles del Nuevo Producto',
'initial_stock_help' => 'Stock inicial del producto',
'create_product' => 'Crear Producto',
'no_categories_available' => 'No hay categorías disponibles',
'create_category_first' => 'Crear categoría primero',
'create_category_first_message' => 'Debe crear al menos una categoría antes de agregar productos.',

// products.php
'products' => 'Productos',
'manage_categories' => 'Gestionar Categorías',
'search' => 'Buscar',
'search_products_placeholder' => 'Buscar productos...',
'all_categories' => 'Todas las Categorías',
'products_list' => 'Lista de Productos',
'low_stock' => 'Stock Bajo',
'previous' => 'Anterior',
'next' => 'Siguiente',
'no_products_found' => 'No se encontraron productos',
'no_products_match_search' => 'No hay productos que coincidan con su búsqueda.',
'no_products_available' => 'No hay productos disponibles actualmente.',
'add_first_product' => 'Agregar Primer Producto',
'confirm_delete_product' => '¿Está seguro que desea eliminar el producto',
'low_stock_warning' => 'Advertencia de Stock Bajo',
'low_stock_products_found' => 'Se encontraron productos con stock bajo',

// categories.php
'categories' => 'Categorías',
'back_to_products' => 'Volver a Productos',
'add_category' => 'Agregar Categoría',
'category_name' => 'Nombre de Categoría',
'create_category' => 'Crear Categoría',
'edit_category' => 'Editar Categoría',
'update_category' => 'Actualizar Categoría',
'no_categories_found' => 'No se encontraron categorías',
'no_categories_available' => 'No hay categorías disponibles actualmente.',
'add_first_category' => 'Agregar Primera Categoría',
'confirm_delete_category' => '¿Está seguro que desea eliminar la categoría',
'category_name_required' => 'El nombre de la categoría es requerido', 

// list.php (users)
'users_management' => 'Gestión de Usuarios',
'users' => 'Usuarios',
'add_user' => 'Agregar Usuario',
'my_profile' => 'Mi Perfil',
'search' => 'Buscar',
'search_users_placeholder' => 'Usuario, email, o nombre completo...',
'per_page' => 'Por Página',
'clear' => 'Limpiar',
'users_list' => 'Lista de Usuarios',
'profile' => 'Perfil',
'username' => 'Usuario',
'email' => 'Email',
'display_name' => 'Nombre Completo',
'language' => 'Idioma',
'status' => 'Estado',
'last_login' => 'Último Acceso',
'actions' => 'Acciones',
'active' => 'Activo',
'inactive' => 'Inactivo',
'never' => 'Nunca',
'edit' => 'Editar',
'reset_password' => 'Restablecer Contraseña',
'deactivate' => 'Desactivar',
'previous' => 'Anterior',
'next' => 'Siguiente',
'no_users_found' => 'No se encontraron usuarios',
'no_users_match_search' => 'No hay usuarios que coincidan con su búsqueda.',
'no_users_available' => 'No hay usuarios disponibles actualmente.',
'confirm_reset_password' => '¿Está seguro que desea restablecer la contraseña de',
'confirm_deactivate_user' => '¿Está seguro que desea desactivar a',

// edit.php (users)
'edit_user' => 'Editar Usuario',
'add_user' => 'Agregar Usuario',
'back_to_list' => 'Volver a la Lista',
'user_details' => 'Detalles del Usuario',
'new_user_details' => 'Detalles del Nuevo Usuario',
'profile_picture_help' => 'JPG, JPEG, PNG. Máximo 2MB.',
'username_format_help' => '3-50 caracteres. Solo letras, números y guiones bajos.',
'password_settings' => 'Configuración de Contraseña',
'confirm_password' => 'Confirmar Contraseña',
'password_requirements' => 'Mínimo 8 caracteres con mayúscula, minúscula, número y símbolo.',
'change_password' => 'Cambiar Contraseña',
'leave_blank_to_keep_current_password' => 'Dejar en blanco para mantener la contraseña actual.',
'current_password' => 'Contraseña Actual',
'required_to_change_password' => 'Requerida para cambiar la contraseña',
'new_password' => 'Nueva Contraseña',
'confirm_new_password' => 'Confirmar Nueva Contraseña',
'administrative_settings' => 'Configuración Administrativa',
'select_role' => 'Seleccionar Rol',
'admin_user' => 'Usuario Administrador',
'admin_user_help' => 'Tiene acceso completo al sistema.',
'active_user' => 'Usuario Activo',
'active_user_help' => 'Puede iniciar sesión en el sistema.',
'danger_zone' => 'Zona de Peligro',
'reset_password_help' => 'Genera una nueva contraseña temporal.',
'deactivate_user' => 'Desactivar Usuario',
'deactivate_user_help' => 'El usuario no podrá acceder al sistema.',
'cancel' => 'Cancelar',
'update_user' => 'Actualizar Usuario',
'create_user' => 'Crear Usuario',
'confirm_reset_password_action' => '¿Está seguro que desea restablecer la contraseña de este usuario?',
'confirm_deactivate_action' => '¿Está seguro que desea desactivar este usuario?',
'passwords_do_not_match' => 'Las contraseñas no coinciden',
'current_password_required' => 'La contraseña actual es requerida',

// list.php (clients)
'clients_management' => 'Gestión de Clientes',
'clients' => 'Clientes',
'add_client' => 'Agregar Cliente',
'clients_list' => 'Lista de Clientes',
'company_name' => 'Nombre de la Empresa',
'contact_name' => 'Nombre de Contacto',
'phone' => 'Teléfono',
'created_at' => 'Creado',
'delete' => 'Eliminar',
'confirm_delete' => 'Confirmar Eliminación',
'confirm_delete_client' => '¿Está seguro que desea eliminar el cliente',
'no_clients_found' => 'No se encontraron clientes',
'no_clients_match_search' => 'No hay clientes que coincidan con su búsqueda.',
'no_clients_available' => 'No hay clientes disponibles actualmente.',

// edit.php (clients)
'edit_client' => 'Editar Cliente',
'client_details' => 'Detalles del Cliente',
'tax_id' => 'ID Fiscal',
'address' => 'Dirección',
'update_client' => 'Actualizar Cliente',
'recent_activity' => 'Actividad Reciente',
'recent_quotes' => 'Cotizaciones Recientes',
'quote' => 'Cotización',
'no_activity_found' => 'No se encontró actividad',
'no_quotes_found' => 'No se encontraron cotizaciones',
'please_fill_required_fields' => 'Por favor complete los campos requeridos',
'invalid_email_format' => 'Formato de email inválido',

// add.php (clients)
'add_client' => 'Agregar Cliente',
'create_client' => 'Crear Cliente',

// Agregar estas traducciones al archivo es.php

// Títulos y textos de cotizaciones
'quotes_management' => 'Gestión de Cotizaciones',
'quotes' => 'Cotizaciones',
'quote' => 'Cotización',
'quote_details' => 'Detalles de Cotización',
'quote_number' => 'Número de Cotización',
'quote_summary' => 'Resumen de Cotización',
'quote_info' => 'Información de Cotización',
'quote_items' => 'Artículos de Cotización',

// Estados de cotización
'status_draft' => 'Borrador',
'status_sent' => 'Enviado',
'status_approved' => 'Aprobado',
'status_rejected' => 'Rechazado',
'draft' => 'Borrador',
'sent' => 'Enviado',
'approved' => 'Aprobado',
'rejected' => 'Rechazado',

// Acciones de cotización
'create_quote' => 'Crear Cotización',
'edit_quote' => 'Editar Cotización',
'duplicate_quote' => 'Duplicar Cotización',
'renew_quote' => 'Renovar Cotización',
'update_quote' => 'Actualizar Cotización',
'send_to_client' => 'Enviar al Cliente',
'back_to_quote' => 'Volver a Cotización',
'back_to_original_quote' => 'Volver a Cotización Original',
'back_to_list' => 'Volver al Listado',

// Mensajes informativos
'duplicating_quote' => 'Duplicando cotización',
'renewing_quote' => 'Renovando cotización',
'for_client' => 'para el cliente',
'no_items_added' => 'No hay artículos agregados',
'click_add_item_to_start' => 'Haga clic en "Agregar Artículo" para comenzar',
'duplicate_items_info' => 'Los artículos se han duplicado de la cotización original. Puede modificarlos según sea necesario.',
'renewal_items_warning' => 'Verifique los precios actuales de los productos, ya que pueden haber cambiado desde la cotización original.',

// Textos de formulario
'client_information' => 'Información del Cliente',
'quote_information' => 'Información de Cotización',
'issue_date' => 'Fecha de Emisión',
'expiry_date' => 'Fecha de Vencimiento',
'expired' => 'Vencido',
'unit_price' => 'Precio Unitario',
'discount_percent' => 'Descuento %',
'tax_rate_percent' => 'Tasa de Impuesto %',
'tax_amount' => 'Monto de Impuesto',
'item_total' => 'Total del Artículo',
'subtotal' => 'Subtotal',
'total' => 'Total',
'total_amount' => 'Monto Total',
'current_price' => 'Precio Actual',
'original_total' => 'Total Original',
'calculated_total_mismatch' => 'Discrepancia en total calculado',

// Botones y acciones
'add_item' => 'Agregar Artículo',
'remove_item' => 'Eliminar Artículo',
'update_current_prices' => 'Actualizar Precios Actuales',
'create_duplicate' => 'Crear Duplicado',
'create_renewal' => 'Crear Renovación',
'update_status' => 'Actualizar Estado',
'mark_as_sent' => 'Marcar como Enviado',
'download_pdf' => 'Descargar PDF',
'print' => 'Imprimir',
'duplicate' => 'Duplicar',
'renew' => 'Renovar',

// Mensajes de éxito/error
'quote_created_successfully' => 'Cotización creada exitosamente',
'please_correct_errors' => 'Por favor corrija los siguientes errores',
'confirm_status_change' => '¿Está seguro que desea cambiar el estado?',
'insufficient_stock_details' => 'Detalles de stock insuficiente',
'network_error' => 'Error de red',

// Textos de lista y búsqueda
'search_quotes_placeholder' => 'Buscar por número, cliente...',
'all_statuses' => 'Todos los estados',
'all_clients' => 'Todos los clientes',
'no_quotes_found' => 'No se encontraron cotizaciones',
'no_quotes_match_search' => 'No hay cotizaciones que coincidan con su búsqueda',
'no_quotes_available' => 'No hay cotizaciones disponibles actualmente',
'create_first_quote' => 'Crear Primera Cotización',
'quotes_pagination' => 'Navegación de cotizaciones',

// Información adicional
'created_by' => 'Creado por',
'created_at' => 'Creado el',
'updated_at' => 'Actualizado el',
'current_status' => 'Estado Actual',
'original_dates' => 'Fechas Originales',
'issued' => 'Emitido',
'expired' => 'Vencido',
'original_quote_info' => 'Información de Cotización Original',
'original_quote_comparison' => 'Comparación con Original',
'renewal_quote_details' => 'Detalles de Cotización de Renovación',
'renewal_quote_summary' => 'Resumen de Cotización de Renovación',
'renewed_quote_items' => 'Artículos de Cotización Renovada',

// Mensajes descriptivos
'duplicate_creates_new_draft_quote' => 'La duplicación crea una nueva cotización en estado borrador con los mismos artículos.',
'renewal_creates_new_quote_with_reference' => 'La renovación crea una nueva cotización haciendo referencia a la original.',
'same_client_recommended' => 'Se recomienda mantener el mismo cliente para renovaciones.',

// Textos de comparación
'more' => 'más',
'less' => 'menos',
'no_changes' => 'sin cambios',
'calculating' => 'calculando...',

// Textos de email/envió
'this_is_renewal_of_quote' => 'Esta es una renovación de la cotización',
'contact' => 'Contacto',
'required' => 'Requerido',
'available' => 'Disponible',
'showing_results' => 'Mostrando {start} a {end} de {total} resultados',


// Configuración de empresa
'setting_company_name' => 'Nombre de la Empresa',
'setting_company_display_name' => 'Nombre para Mostrar',
'setting_company_address' => 'Dirección de la Empresa',
'setting_company_phone' => 'Teléfono de la Empresa',
'setting_company_email' => 'Email de la Empresa',
'setting_company_website' => 'Sitio Web',
'setting_company_tax_id' => 'ID Fiscal/RFC',

// Configuración de email
'setting_smtp_host' => 'Servidor SMTP',
'setting_smtp_port' => 'Puerto SMTP',
'setting_smtp_username' => 'Usuario SMTP',
'setting_smtp_password' => 'Contraseña SMTP',
'setting_smtp_encryption' => 'Encriptación SMTP',
'setting_from_email' => 'Email Remitente',
'setting_from_name' => 'Nombre Remitente',
'setting_email_signature' => 'Firma de Email',

// Configuración de cotizaciones
'setting_default_tax_rate' => 'Tasa de Impuesto Predeterminada',
'setting_quote_validity_days' => 'Días de Validez de Cotizaciones',
'setting_quote_prefix' => 'Prefijo de Número de Cotización',
'setting_quote_next_number' => 'Próximo Número de Cotización',
'setting_quote_terms' => 'Términos y Condiciones',
'setting_quote_notes' => 'Notas de Cotización',

// Configuración de productos
'setting_low_stock_threshold' => 'Umbral de Stock Bajo',
'setting_default_currency' => 'Moneda Predeterminada',
'setting_currency_symbol' => 'Símbolo de Moneda',
'setting_product_code_prefix' => 'Prefijo de Código de Producto',

// Configuración del sistema
'setting_timezone' => 'Zona Horaria',
'setting_date_format' => 'Formato de Fecha',
'setting_time_format' => 'Formato de Hora',
'setting_items_per_page' => 'Elementos por Página',
'setting_available_languages' => 'Idiomas Disponibles',
'setting_backup_time' => 'Hora de Copia de Seguridad',
'setting_backup_retention_days' => 'Días de Retención de Copias',
'setting_session_timeout' => 'Tiempo de Espera de Sesión (minutos)',

// Textos de ayuda para configuraciones
'setting_company_name_help' => 'Nombre legal de la empresa',
'setting_company_display_name_help' => 'Nombre que se muestra a los clientes',
'setting_default_tax_rate_help' => 'Tasa de impuesto predeterminada aplicada a las cotizaciones (%)',
'setting_quote_validity_days_help' => 'Número de días que las cotizaciones son válidas por defecto',
'setting_low_stock_threshold_help' => 'Cantidad mínima para alertas de stock bajo',
'setting_items_per_page_help' => 'Número de elementos mostrados en listas',
'setting_backup_retention_days_help' => 'Días que se conservan las copias de seguridad',
'setting_session_timeout_help' => 'Minutos de inactividad antes de cerrar sesión',

// Opciones de encriptación SMTP (ya existentes, pero para completar)
'smtp_encryption_none' => 'Ninguna',
'smtp_encryption_ssl' => 'SSL',
'smtp_encryption_tls' => 'TLS',

// Textos de formulario de configuración
'company_settings' => 'Configuración de Empresa',
'email_settings' => 'Configuración de Email',
'quote_settings' => 'Configuración de Cotizaciones',
'product_settings' => 'Configuración de Productos',
'system_settings' => 'Configuración del Sistema',
'other_settings' => 'Otras Configuraciones',
'settings_management' => 'Gestión de Configuración',
'save_settings' => 'Guardar Configuración',
'saving' => 'Guardando',
'leave_blank_keep_current' => 'Dejar en blanco para mantener actual',
'json_format_example' => 'Ejemplo de formato JSON',
'last_updated' => 'Última actualización',

// Textos de ejemplo para formato JSON
'json_language_example' => 'Ejemplo: ["es", "en", "fr"]',

// Textos de botones y acciones
'test_smtp_connection' => 'Probar Conexión SMTP',
'smtp_test_success' => 'Conexión SMTP exitosa',
'smtp_test_failed' => 'Error en conexión SMTP',
'back_to_dashboard' => 'Volver al Panel de Control',
'cancel' => 'Cancelar',

// Textos de validación
'invalid_email_format' => 'Formato de email inválido',
'invalid_port_number' => 'Número de puerto inválido',
'invalid_percentage' => 'Porcentaje inválido',
'invalid_number' => 'Número inválido',

// Textos de zonas horarias (ejemplos comunes)
'timezone_utc' => 'UTC',
'timezone_est' => 'Este (EST)',
'timezone_cst' => 'Central (CST)',
'timezone_mst' => 'Montaña (MST)',
'timezone_pst' => 'Pacífico (PST)',
'timezone_mexico' => 'Ciudad de México',

// Formatos de fecha
'date_format_ymd' => 'AAAA-MM-DD',
'date_format_dmy' => 'DD/MM/AAAA',
'date_format_mdy' => 'MM/DD/AAAA',

// Formatos de hora
'time_format_24h' => '24 horas (HH:MM)',
'time_format_12h' => '12 horas (HH:MM AM/PM)',

// Monedas
'currency_usd' => 'Dólar Americano (USD)',
'currency_eur' => 'Euro (EUR)',
'currency_mxn' => 'Peso Mexicano (MXN)',
'currency_symbol_usd' => '$',
'currency_symbol_eur' => '€',
'currency_symbol_mxn' => '$',

// Textos de éxito/error
'settings_saved_success' => 'Configuración guardada exitosamente',
'settings_save_error' => 'Error al guardar la configuración',
'required_field' => 'Campo requerido',

// Textos de prueba SMTP
'smtp_test_in_progress' => 'Probando conexión SMTP...',
'smtp_test_credentials' => 'Verificando credenciales...',
'smtp_test_connection' => 'Conectando al servidor...',
'smtp_test_authentication' => 'Autenticando...',

// Textos de seguridad
'security_settings' => 'Configuración de Seguridad',
'password_policy' => 'Política de Contraseñas',
'login_security' => 'Seguridad de Login',
'api_security' => 'Seguridad de API',

// Textos de backup
'backup_settings' => 'Configuración de Copias de Seguridad',
'auto_backup' => 'Copia Automática',
'backup_frequency' => 'Frecuencia de Copias',
'backup_location' => 'Ubicación de Copias',

// Textos de notificaciones
'notification_settings' => 'Configuración de Notificaciones',
'email_notifications' => 'Notificaciones por Email',
'alert_notifications' => 'Alertas del Sistema',

// Textos de integraciones
'integration_settings' => 'Configuración de Integraciones',
'api_settings' => 'Configuración de API',
'third_party_integrations' => 'Integraciones de Terceros',

//=========================== Traducciones para configuraciones =======================

// Configuración de empresa

    'setting_company_name' => 'Nombre de la Empresa',
    'setting_company_display_name' => 'Nombre para Mostrar',
    'setting_company_address' => 'Dirección de la Empresa',
    'setting_company_phone' => 'Teléfono de la Empresa',
    'setting_company_email' => 'Email de la Empresa',
    'setting_company_website' => 'Sitio Web',
    'setting_company_tax_id' => 'ID Fiscal/RFC',

    // Configuración de email
    'setting_smtp_host' => 'Servidor SMTP',
    'setting_smtp_port' => 'Puerto SMTP',
    'setting_smtp_username' => 'Usuario SMTP',
    'setting_smtp_password' => 'Contraseña SMTP',
    'setting_smtp_encryption' => 'Encriptación SMTP',
    'setting_from_email' => 'Email Remitente',
    'setting_from_name' => 'Nombre Remitente',
    'setting_email_signature' => 'Firma de Email',

    // Configuración de cotizaciones
    'setting_default_tax_rate' => 'Tasa de Impuesto Predeterminada',
    'setting_quote_validity_days' => 'Días de Validez de Cotizaciones',
    'setting_quote_prefix' => 'Prefijo de Número de Cotización',
    'setting_quote_next_number' => 'Próximo Número de Cotización',
    'setting_quote_terms' => 'Términos y Condiciones',
    'setting_quote_notes' => 'Notas de Cotización',

    // Configuración de productos
    'setting_low_stock_threshold' => 'Umbral de Stock Bajo',
    'setting_default_currency' => 'Moneda Predeterminada',
    'setting_currency_symbol' => 'Símbolo de Moneda',
    'setting_product_code_prefix' => 'Prefijo de Código de Producto',

    // Configuración del sistema
    'setting_timezone' => 'Zona Horaria',
    'setting_date_format' => 'Formato de Fecha',
    'setting_time_format' => 'Formato de Hora',
    'setting_items_per_page' => 'Elementos por Página',
    'setting_available_languages' => 'Idiomas Disponibles',
    'setting_backup_time' => 'Hora de Copia de Seguridad',
    'setting_backup_retention_days' => 'Días de Retención de Copias',
    'setting_session_timeout' => 'Tiempo de Espera de Sesión (minutos)',

    // Textos de ayuda para configuraciones
    'setting_company_name_help' => 'Nombre legal de la empresa',
    'setting_company_display_name_help' => 'Nombre que se muestra a los clientes',
    'setting_default_tax_rate_help' => 'Tasa de impuesto predeterminada aplicada a las cotizaciones (%)',
    'setting_quote_validity_days_help' => 'Número de días que las cotizaciones son válidas por defecto',
    'setting_low_stock_threshold_help' => 'Cantidad mínima para alertas de stock bajo',
    'setting_items_per_page_help' => 'Número de elementos mostrados en listas',
    'setting_backup_retention_days_help' => 'Días que se conservan las copias de seguridad',
    'setting_session_timeout_help' => 'Minutos de inactividad antes de cerrar sesión',

    // Textos de ejemplo para formato JSON
    'json_language_example' => 'Ejemplo: ["es", "en", "fr"]',

    // Textos de prueba SMTP
    'test_smtp_connection' => 'Probar Conexión SMTP',
    'smtp_test_success' => 'Conexión SMTP exitosa',
    'smtp_test_failed' => 'Error en conexión SMTP',
    'smtp_test_in_progress' => 'Probando conexión SMTP...',
    'smtp_test_credentials' => 'Verificando credenciales...',
    'smtp_test_connection' => 'Conectando al servidor...',
    'smtp_test_authentication' => 'Autenticando...',

    // Textos de seguridad
    'security_settings' => 'Configuración de Seguridad',
    'password_policy' => 'Política de Contraseñas',
    'login_security' => 'Seguridad de Login',
    'api_security' => 'Seguridad de API',

    // Textos de backup
    'backup_settings' => 'Configuración de Copias de Seguridad',
    'auto_backup' => 'Copia Automática',
    'backup_frequency' => 'Frecuencia de Copias',
    'backup_location' => 'Ubicación de Copias',

    // Textos de notificaciones
    'notification_settings' => 'Configuración de Notificaciones',
    'email_notifications' => 'Notificaciones por Email',
    'alert_notifications' => 'Alertas del Sistema',
    'settings_updated_successfully' => 'Configuración actualizada exitosamente', 

    // Textos de integraciones
    'integration_settings' => 'Configuración de Integraciones',
    'api_settings' => 'Configuración de API',
    'third_party_integrations' => 'Integraciones de Terceros',

//=========================== Traducciones para configuraciones =======================

// Reportes
'reports' => 'Reportes',
'refresh' => 'Actualizar',
'no_data_available' => 'No hay datos disponibles',
'report_table' => 'Tabla de Reporte',

// Reportes de ventas
'sales_reports_description' => 'Analice el desempeño de ventas y tendencias',
'sales_performance' => 'Desempeño de Ventas',
'sales_trends' => 'Tendencias de Ventas',
'total_quotes' => 'Total Cotizaciones',
'conversion_rate' => 'Tasa de Conversión',
'month' => 'Mes',
'average_discount' => 'Descuento Promedio',

// Reportes de productos
'product_reports' => 'Reportes de Productos',
'total_products' => 'Total Productos',
'categories' => 'Categorías',
'low_stock_products' => 'Productos con Stock Bajo',
'product_performance' => 'Desempeño de Productos',
'category_summary' => 'Resumen de Categorías',
'product_performance_details' => 'Detalles de Desempeño de Productos',
'total_sold' => 'Total Vendido',
'stock_quantity' => 'Cantidad en Stock',
'low_stock' => 'Stock Bajo',
'medium_stock' => 'Stock Medio',
'high_stock' => 'Stock Alto',
'low_stock_alert' => 'Alerta de Stock Bajo',

// Reportes de cumplimiento
'compliance_reports' => 'Reportes de Cumplimiento',
'view_compliance_reports' => 'Reportes de Cumplimiento',
'security_posture' => 'Postura de Seguridad',
'audit_logs' => 'Logs de Auditoría',
'user_activities' => 'Actividades de Usuario',
'export_csv' => 'Exportar CSV',
'loading' => 'Cargando',

// Métricas de seguridad
'total_audit_logs' => 'Total Logs de Auditoría',
'failed_login_attempts' => 'Intentos de Login Fallidos',
'inactive_accounts' => 'Cuentas Inactivas',
'permission_changes' => 'Cambios de Permisos',

// Acciones de auditoría
'action_insert' => 'INSERTAR',
'action_update' => 'ACTUALIZAR',
'action_delete' => 'ELIMINAR',
'action_login' => 'LOGIN',
'action_logout' => 'LOGOUT',
'action_stock_update' => 'ACTUALIZAR STOCK',

// Reportes de clientes
'client_reports' => 'Reportes de Clientes',
'top_clients' => 'Clientes Principales',
'client_activity' => 'Actividad de Clientes',
'purchase_patterns' => 'Patrones de Compra',
'rank' => 'Posición',
'total_spend' => 'Gasto Total',
'purchase_count' => 'Cantidad de Compras',
'last_quote_date' => 'Última Fecha de Cotización',
'last_purchase_date' => 'Última Fecha de Compra',

// Textos generales de reportes
'username' => 'Usuario',
'product_name' => 'Nombre de Producto',
'category' => 'Categoría',
'status' => 'Estado',
'date_time' => 'Fecha/Hora',
'user' => 'Usuario',
'action' => 'Acción',
'entity_type' => 'Tipo de Entidad',
'entity_id' => 'ID Entidad',
'ip_address' => 'Dirección IP',
'page' => 'Página',
'previous' => 'Anterior',
'next' => 'Siguiente',

// Mensajes de error/éxito
'error' => 'Error',
'success' => 'Éxito',
'network_error' => 'Error de red',
'failed_to_refresh' => 'Error al actualizar reportes',

// Textos de navegación
'back_to_dashboard' => 'Volver al Panel de Control',
'back_to_reports' => 'Volver a Reportes',

// Estados y badges
'active' => 'Activo',
'inactive' => 'Inactivo',
'pending' => 'Pendiente',
'completed' => 'Completado',
'cancelled' => 'Cancelado',

// Textos de tablas
'showing_results' => 'Mostrando {start} a {end} de {total} resultados',
'no_records_found' => 'No se encontraron registros',
'search' => 'Buscar',
'filter' => 'Filtrar',
'clear' => 'Limpiar',

// Textos de gráficos
'chart' => 'Gráfico',
'data' => 'Datos',
'legend' => 'Leyenda',
'export' => 'Exportar',
'print' => 'Imprimir',

// Textos de tiempo
'today' => 'Hoy',
'yesterday' => 'Ayer',
'this_week' => 'Esta Semana',
'this_month' => 'Este Mes',
'this_year' => 'Este Año',
'last_week' => 'La Semana Pasada',
'last_month' => 'El Mes Pasado',
'last_year' => 'El Año Pasado',
'custom_range' => 'Rango Personalizado',

// Textos de formato
'currency' => 'Moneda',
'percentage' => 'Porcentaje',
'number' => 'Número',
'date' => 'Fecha',
'time' => 'Hora',
'datetime' => 'Fecha y Hora',

// Textos de permisos
'view_reports' => 'Ver Reportes',
'export_reports' => 'Exportar Reportes',
'manage_settings' => 'Gestionar Configuración',

// Textos de validación
'required_field' => 'Campo requerido',
'invalid_format' => 'Formato inválido',
'min_length' => 'Longitud mínima: {min}',
'max_length' => 'Longitud máxima: {max}',
'invalid_email' => 'Email inválido',
'invalid_number' => 'Número inválido',
'invalid_date' => 'Fecha inválida',

// Textos de confirmación
'confirm_action' => 'Confirmar acción',
'are_you_sure' => '¿Está seguro?',
'yes' => 'Sí',
'no' => 'No',
'cancel' => 'Cancelar',
'confirm' => 'Confirmar',

// Textos de carga
'loading_data' => 'Cargando datos...',
'processing' => 'Procesando...',
'please_wait' => 'Por favor espere...',

// Textos de éxito
'operation_completed' => 'Operación completada',
'changes_saved' => 'Cambios guardados',
'data_exported' => 'Datos exportados',

// Textos de error
'operation_failed' => 'Operación fallida',
'data_not_available' => 'Datos no disponibles',
'access_denied' => 'Acceso denegado',
'not_found' => 'No encontrado',

// Agregar estas traducciones al archivo es.php

// Navegación y menús
'dashboard' => 'Panel de Control',
'users' => 'Usuarios',
'roles_management' => 'Gestión de Roles',
'clients' => 'Clientes',
'products' => 'Productos',
'quotes' => 'Cotizaciones',
'reports' => 'Reportes',
'sales_reports' => 'Reportes de Ventas',
'client_reports' => 'Reportes de Clientes',
'product_reports' => 'Reportes de Productos',
'compliance_reports' => 'Reportes de Cumplimiento',
'administration' => 'Administración',
'settings' => 'Configuración',
'backups' => 'Copias de Seguridad',
'access_requests' => 'Solicitudes de Acceso',
'profile' => 'Perfil',
'logout' => 'Cerrar Sesión',
'toggle_menu' => 'Alternar Menú',

// Textos de usuario
'user_profile' => 'Perfil de Usuario',
'user_role' => 'Rol de Usuario',
'user_settings' => 'Configuración de Usuario',

// Estados de navegación
'active' => 'Activo',
'inactive' => 'Inactivo',
'expanded' => 'Expandido',
'collapsed' => 'Contraído',

// Textos de accesibilidad
'menu' => 'Menú',
'navigation' => 'Navegación',
'main_navigation' => 'Navegación Principal',
'reports_section' => 'Sección de Reportes',
'admin_section' => 'Sección de Administración',
'user_section' => 'Sección de Usuario',

// Textos de responsive
'mobile_menu' => 'Menú Móvil',
'desktop_menu' => 'Menú de Escritorio',

// Textos de botones
'close_menu' => 'Cerrar Menú',
'open_menu' => 'Abrir Menú',
'expand_menu' => 'Expandir Menú',
'collapse_menu' => 'Contraer Menú',

// Textos de overlay
'menu_overlay' => 'Superposición de Menú',

// Permisos y acceso
'access_denied' => 'Acceso Denegado',
'insufficient_permissions' => 'Permisos Insuficientes',
'admin_access_required' => 'Se Requiere Acceso de Administrador',

// Textos de marca
'app_name' => 'Athenea',

];