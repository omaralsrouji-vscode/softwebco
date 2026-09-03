# Security policy

## Supported versions

This repository currently maintains only the latest revision on its default branch. No separate long-term-support release line is defined.

## Reporting a vulnerability

Do not include credentials, personal information, exploit payloads, or sensitive production details in a public issue. Contact the repository owner privately through the contact method shown on the owner's GitHub profile and include only the information needed to reproduce and assess the problem.

## Deployment guidance

- Supply secrets through the server environment or an uncommitted `.env` file.
- Create unique administrator credentials and use a modern password hash.
- Keep PHP, MySQL/MariaDB, Apache, PHPMailer, and browser-side dependencies current.
- Restrict direct access to configuration, database, vendor, and runtime storage paths.
- Add production-appropriate TLS, session-cookie, CSRF, login throttling, backup, and monitoring controls.
- Keep error display disabled in production and protect server logs from public access.

The repository is an application source release, not a hardened hosting image. Review the deployed server and application configuration before exposing it to the internet.
