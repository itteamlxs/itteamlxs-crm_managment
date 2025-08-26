<?php
/**
 * Spanish translations - Updated with User Management and Role Management
 */
return [
    // Login page
    'app_name' => 'Sistema CRM',
    'please_sign_in' => 'Por favor inicie sesión',
    'username_or_email' => 'Usuario o Email',
    'password' => 'Contraseña',
    'sign_in' => 'Iniciar Sesión',
    'forgot_password' => '¿Olvidó su contraseña?',
    'debug_mode_credentials' => 'Modo Debug: Use leon/temporal2024#',
    
    // Messages
    'invalid_security_token' => 'Token de seguridad inválido',
    'username_password_required' => 'Usuario y contraseña son requeridos',
    'too_many_attempts' => 'Demasiados intentos de login. Intente más tarde.',
    'invalid_credentials' => 'Usuario o contraseña incorrectos',
    'logout_success' => 'Ha cerrado sesión exitosamente.',
    
    // Dashboard
    'dashboard' => 'Panel de Control',
    'welcome' => 'Bienvenido',
    'role' => 'Rol',
    'login_date' => 'Fecha de Ingreso',
    'logout' => 'Cerrar Sesión',
    
    // Password reset
    'password_reset' => 'Restablecer Contraseña',
    'password_reset_not_available' => 'Restablecimiento de Contraseña No Disponible',
    'contact_admin_password' => 'Si necesita restablecer su contraseña, por favor contacte a su administrador del sistema.',
    'back_to_login' => 'Volver al Login',
    
    // User Management
    'users_management' => 'Gestión de Usuarios',
    'users' => 'Usuarios',
    'add_user' => 'Agregar Usuario',
    'edit_user' => 'Editar Usuario',
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
    
    // User Edit/Create
    'user_details' => 'Detalles del Usuario',
    'new_user_details' => 'Detalles del Nuevo Usuario',
    'back_to_list' => 'Volver a la Lista',
    'add' => 'Agregar',
    'profile_picture_help' => 'JPG, JPEG, PNG. Máximo 2MB.',
    'username_format_help' => '3-50 caracteres. Solo letras, números y guiones bajos.',
    'password_settings' => 'Configuración de Contraseña',
    'confirm_password' => 'Confirmar Contraseña',
    'password_requirements' => 'Mínimo 8 caracteres con mayúscula, minúscula, número y símbolo.',
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
    
    // Form validation messages
    'username_required' => 'El nombre de usuario es requerido',
    'invalid_username_format' => 'Formato de usuario inválido',
    'username_already_exists' => 'El nombre de usuario ya existe',
    'email_required' => 'El email es requerido',
    'invalid_email_format' => 'Formato de email inválido',
    'email_already_exists' => 'El email ya está registrado',
    'display_name_required' => 'El nombre completo es requerido',
    'password_required' => 'La contraseña es requerida',
    'passwords_do_not_match' => 'Las contraseñas no coinciden',
    'file_upload_failed' => 'Error al subir archivo',
    
    // Success/Error messages
    'user_deactivated_successfully' => 'Usuario desactivado exitosamente',
    'error_deactivating_user' => 'Error al desactivar usuario',
    'cannot_deactivate_own_account' => 'No puede desactivar su propia cuenta',
    'password_reset_successfully' => 'Contraseña restablecida exitosamente',
    'error_resetting_password' => 'Error al restablecer contraseña',
    'user_updated_successfully' => 'Usuario actualizado exitosamente',
    'error_updating_user' => 'Error al actualizar usuario',
    'user_created_successfully' => 'Usuario creado exitosamente',
    'error_creating_user' => 'Error al crear usuario',
    
    // Common
    'change_language' => 'Cambiar Idioma',

    // Roles Management
    'roles_management' => 'Gestión de Roles',
    'manage_roles_description' => 'Gestionar roles y permisos',
    'add_role' => 'Agregar Rol',
    'edit_role' => 'Editar Rol',
    'create_role' => 'Crear Rol',
    'update_role' => 'Actualizar Rol',
    'role_name' => 'Nombre del Rol',
    'description' => 'Descripción',
    'created_at' => 'Creado',
    'roles_list' => 'Lista de Roles',
    'no_roles_available' => 'No hay roles disponibles',
    'back_to_roles' => 'Volver a Roles',
    'back_to_dashboard' => 'Panel de Control',
    'delete' => 'Eliminar',
    'confirm_delete' => 'Confirmar Eliminación',
    
    // Role form validations
    'role_name_required' => 'El nombre del rol es requerido',
    'invalid_role_name_format' => 'Formato de nombre de rol inválido (3-50 caracteres, solo letras, números, espacios y guiones bajos)',
    'role_name_already_exists' => 'El nombre del rol ya existe',
    'description_required' => 'La descripción es requerida',
    
    // Role operations
    'error_creating_role' => 'Error al crear el rol',
    'role_created_successfully' => 'Rol creado exitosamente',
    'error_updating_role' => 'Error al actualizar el rol',
    'role_updated_successfully' => 'Rol actualizado exitosamente',
    'role_deleted_successfully' => 'Rol eliminado exitosamente',
    'error_deleting_role' => 'Error al eliminar el rol',
    'cannot_delete_role_with_users' => 'No se puede eliminar el rol porque tiene usuarios asignados. Primero reasigne los usuarios a otro rol.',
    'confirm_delete_role' => '¿Está seguro que desea eliminar el rol',
    'this_action_cannot_be_undone' => 'Esta acción no se puede deshacer.',
    
    // Permissions
    'assign_permissions' => 'Asignar Permisos',
    'permissions' => 'Permisos',
    'save_permissions' => 'Guardar Permisos',
    'select_all' => 'Seleccionar Todo',
    'select_none' => 'Seleccionar Nada',
    'no_permissions_available' => 'No hay permisos disponibles',
    'role_not_found' => 'Rol no encontrado',
    'permissions_updated_successfully' => 'Permisos actualizados exitosamente',
    'error_updating_permissions' => 'Error al actualizar los permisos',
    
    // General
    'access' => 'Acceder',
    'navigation' => 'Navegación'
];