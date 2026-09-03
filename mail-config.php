<?php
/**
 * SMTP / PHPMailer configuration for the public contact form.
 *
 * Keep credentials in the server environment or an uncommitted .env file.
 * See .env.example for every supported setting.
 */

require_once __DIR__ . '/includes/environment.php';

define('SMTP_HOST', (string)swc_env('SWC_SMTP_HOST', ''));
define('SMTP_PORT', (int)swc_env('SWC_SMTP_PORT', '587'));
define('SMTP_ENCRYPTION', strtolower((string)swc_env('SWC_SMTP_ENCRYPTION', 'tls')));
define('SMTP_AUTH', filter_var(swc_env('SWC_SMTP_AUTH', 'true'), FILTER_VALIDATE_BOOL));
define('SMTP_USERNAME', (string)swc_env('SWC_SMTP_USERNAME', ''));
define('SMTP_PASSWORD', (string)swc_env('SWC_SMTP_PASSWORD', ''));

define('MAIL_FROM_EMAIL', (string)swc_env('SWC_MAIL_FROM_EMAIL', SMTP_USERNAME));
define('MAIL_FROM_NAME', (string)swc_env('SWC_MAIL_FROM_NAME', 'Softwebco Team'));
define('MAIL_TO_ADDRESSES', swc_env_list('SWC_MAIL_TO_ADDRESSES'));
define('MAIL_BCC_ADDRESSES', swc_env_list('SWC_MAIL_BCC_ADDRESSES'));
