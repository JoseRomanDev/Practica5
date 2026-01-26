<?php
/**
 * Guía de configuración de Email
 * 
 * OPCIÓN 1: Usar Mailtrap (RECOMENDADO PARA TESTING)
 * 
 * 1. Regístrate gratis en https://mailtrap.io
 * 2. Ve a Settings > Sending Credentials
 * 3. Copia el TOKEN (contraseña)
 * 4. Edita src/Connections/Email.php y actualiza:
 *    - USE_MAILTRAP = true
 *    - MAILTRAP_PASS = 'tu_token_aqui'
 * 5. Todos los emails se verán en el dashboard de Mailtrap
 * 
 * OPCIÓN 2: Usar servidor SMTP real (Gmail, Hotmail, etc)
 * 
 * Para Gmail:
 * 1. Habilita autenticación de dos factores
 * 2. Genera una contraseña de aplicación: https://support.google.com/accounts/answer/185833
 * 3. Edita Email.php:
 *    - USE_MAILTRAP = false
 *    - Modifica sendViaMailtrap para usar tu servidor SMTP
 * 
 * OPCIÓN 3: Usar PHP Mail (requiere servidor SMTP configurado en php.ini)
 * 
 * 1. Edita src/Connections/Email.php:
 *    - USE_MAILTRAP = false
 * 2. El script usará la función mail() de PHP
 * 3. Necesitas configurar SENDMAIL_PATH o SMTP en php.ini
 * 
 * PARA TESTING SIN EMAILS REALES:
 * 
 * Puedes ver el registro de todos los intentos de envío en:
 * emails.log (archivo generado automáticamente)
 * 
 * DEBUGGING:
 * 
 * Si los emails no se envían:
 * 1. Abre emails.log para ver el estado de cada intento
 * 2. Verifica que las credenciales sean correctas
 * 3. Intenta con Mailtrap primero (es lo más fácil)
 */
?>
