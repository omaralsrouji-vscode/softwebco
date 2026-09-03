# Contributing

Thank you for considering a contribution to Softwebco.

## Before opening a change

- Keep changes focused and preserve the existing design unless the issue requires a visual update.
- Do not commit credentials, `.env` files, database exports, personal data, logs, or generated uploads.
- Explain any configuration or schema change in the pull request.
- Do not add a project license or relicense third-party code without the copyright holder's approval.

## Local checks

Before submitting a change:

1. Run `php -l` on every changed PHP file.
2. Test the affected public and administration flows with PHP 8.0 or newer.
3. Confirm that the application works with a fresh import of `database/install.sql`.
4. Recheck the staged diff for secrets and private data.

There is currently no automated test suite, so include concise manual verification steps with the change.
