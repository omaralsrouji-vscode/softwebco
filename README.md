# Softwebco

Softwebco is a PHP and MySQL website for presenting a software studio's services, program catalog, portfolio, and articles. It includes a small authenticated administration area for managing users, blog posts, categories, and uploaded images, plus an SMTP-backed contact form.

## Features

- Responsive public pages for the home, about, programs, portfolio, blog, and contact sections
- Data-driven program and portfolio catalogs
- MySQL-backed blog posts and categories
- Authenticated administration dashboard
- Administrator, blog, category, and profile-image management
- Validated JPG, PNG, GIF, and WebP uploads with a 5 MB application limit
- SMTP contact-form delivery and sender confirmation through PHPMailer
- Clean Apache routes and basic response-security headers through `.htaccess`

## Technology stack

- PHP 8.0 or newer
- MySQL or MariaDB
- HTML5, CSS, and vanilla JavaScript
- Apache with `mod_rewrite` for the extensionless routes
- PHPMailer 7.1.1, bundled under `vendor/PHPMailer/src`
- Remote Google Fonts and Bootstrap JavaScript on selected pages

There is no framework build step and no Composer installation is required for the supplied tree.

## Architecture

Public pages are individual PHP entry points at the repository root. `WebDesign.php` renders shared public and administration chrome, while `programs/catalog.php` and `portfolio/catalog.php` provide the catalog data. `config.php` initializes the database connections, session, and shared helpers. The `backend/` directory contains the authenticated administration screens and mutation handlers. Blog and user media are stored under `uploads/` and `storage/` respectively.

## Requirements

- PHP 8.0+
- PHP extensions: `mysqli`, `pdo_mysql`, `fileinfo`, and `openssl` for encrypted SMTP
- The `mbstring` extension is recommended but optional
- MySQL 5.7+/MariaDB 10.3+ or a compatible version
- Apache 2.4+ with `mod_rewrite` and `AllowOverride All` for clean URLs
- A writable `uploads/blogs/` directory when blog-image uploads are enabled
- A writable `storage/profile-images/` directory when profile-image uploads are enabled
- An SMTP account if the contact form should send email

## Installation

1. Clone or copy the repository into the web server document root. With XAMPP, a typical location is `htdocs/softwebco`.
2. Duplicate `.env.example` as `.env` and replace the example values. The real `.env` is intentionally ignored by Git.
3. Import `database/install.sql` into MySQL. This script is intended for a fresh installation and drops the existing Softwebco tables before recreating them.
4. Create the first administrator as described below.
5. Ensure Apache can read the project and write to the two runtime media directories.
6. Open the project URL, such as `http://localhost/softwebco/`. The admin login is under `/backend/login.php`.

If Apache overrides are unavailable, direct `.php` URLs can still be used, but the extensionless routes and redirects in `.htaccess` will not apply.

## Database setup

`database/install.sql` creates the `softwebco` database and these tables:

- `users`: administrator accounts and profile metadata
- `categories`: blog categories
- `blog_posts`: articles and their media references

No administrator, customer, or editorial data is included in the public schema.

To create the first administrator:

1. Generate a PHP password hash locally:

   ```sh
   php -r "echo password_hash('replace-with-a-strong-password', PASSWORD_DEFAULT), PHP_EOL;"
   ```

2. Open `database/create-admin.example.sql` and replace every `REPLACE_*` value, including `REPLACE_WITH_PASSWORD_HASH`.
3. Run the edited statement against the `softwebco` database. Keep the edited file outside the repository because it contains account information.

Do not use the literal example password in a deployed installation. Avoid placing a real production password in shared shell history.

## Configuration

The application reads settings from the server environment first and falls back to an uncommitted `.env` file in the project root.

### Database

- `SWC_DB_HOST`
- `SWC_DB_PORT`
- `SWC_DB_NAME`
- `SWC_DB_USER`
- `SWC_DB_PASSWORD`

### Mail delivery

- `SWC_SMTP_HOST`
- `SWC_SMTP_PORT`
- `SWC_SMTP_ENCRYPTION`: `tls`, `ssl`, or empty for a trusted local relay
- `SWC_SMTP_AUTH`: `true` or `false`
- `SWC_SMTP_USERNAME`
- `SWC_SMTP_PASSWORD`
- `SWC_MAIL_FROM_EMAIL`
- `SWC_MAIL_FROM_NAME`
- `SWC_MAIL_TO_ADDRESSES`: comma-separated recipient list
- `SWC_MAIL_BCC_ADDRESSES`: optional comma-separated BCC list

### Public contact details

- `SWC_PUBLIC_EMAIL`
- `SWC_PUBLIC_PHONE`
- `SWC_PUBLIC_PHONE_LINK`: the dialable version used by `tel:` links

Quote `.env` values that contain spaces or comment characters. Set `SWC_DEBUG=1` only during local development; production should leave it at `0`.

## Project structure

```text
softwebco/
├── assets/                 # Site styles, scripts, logos, and product media
├── backend/                # Authenticated administration pages and handlers
├── database/               # Clean schema and administrator SQL template
├── includes/               # Environment and media helpers
├── portfolio/              # Portfolio catalog and self-contained preview
├── programs/               # Program catalog and shared detail template
├── storage/profile-images/ # Runtime administrator images
├── uploads/blogs/          # Curated blog media and runtime uploads
├── vendor/PHPMailer/       # Bundled mail library source
├── config.php              # Session, database, and shared application helpers
├── mail-config.php         # Environment-backed mail configuration
└── *.php                   # Public website entry points
```

## Security notes

- Never commit `.env`, database exports, real administrator-creation scripts, logs, or generated uploads.
- The repository does not ship a default administrator or reusable password hash.
- Rotate credentials immediately if they have ever been copied into source code, archives, messages, or logs.
- The upload directories deny execution of common server-side script extensions through Apache configuration. Retain equivalent protections when using another web server.
- Review session-cookie settings, CSRF protection for administration actions, login rate limiting, and HTTP security headers before exposing the administration area directly to the internet.
- `database/install.sql` is destructive and must only be used for a fresh installation or with a verified backup.

See [SECURITY.md](SECURITY.md) for responsible reporting and deployment guidance.

## Known limitations

- The project has no automated test suite or migration framework.
- Clean routes depend on Apache `.htaccess`; other web servers need equivalent routing rules.
- The contact form depends on an independently configured SMTP service.
- Some visual resources and frontend libraries are loaded from third-party CDNs, so offline rendering is not complete.
- The administration area should receive an application-specific production security review before deployment.

## Contributing

Small, focused changes are welcome. Read [CONTRIBUTING.md](CONTRIBUTING.md), avoid committing private data, and document any database or configuration change in the pull request.

## License

No license has been selected for the Softwebco application code. Until the copyright holder adds one, no permission to copy, modify, or redistribute that code is granted beyond what applicable law allows. Bundled third-party components retain their own licenses; see [THIRD_PARTY_NOTICES.md](THIRD_PARTY_NOTICES.md).
