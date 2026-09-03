-- Replace every REPLACE_* placeholder before running this statement.
-- Generate a password hash with PHP's password_hash(..., PASSWORD_DEFAULT).

USE `softwebco`;

INSERT INTO `users` (
  `username`,
  `email`,
  `display_name`,
  `profile_image`,
  `bio`,
  `password`,
  `account_status`,
  `is_locked`
) VALUES (
  'REPLACE_WITH_USERNAME',
  'REPLACE_WITH_EMAIL',
  'REPLACE_WITH_DISPLAY_NAME',
  'default-avatar.png',
  '',
  'REPLACE_WITH_PASSWORD_HASH',
  'active',
  0
);
