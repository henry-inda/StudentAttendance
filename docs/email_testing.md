# Email testing notes

This short document explains how to manually and automatically test the email sending behavior for the StudentAttendance app.

Manual test (quick)
- Ensure `app/config/config.php` contains valid SMTP settings.
- Edit `TEST_EMAIL` in your environment or the config if you want an address different from `SMTP_USER`.
-- For ad-hoc manual testing, run a small one-off PHP script that requires `app/helpers/email_helper.php` and calls the send helper, or use an interactive PHP REPL.
  (Note: legacy CLI runner `run_debug_email_test.php` has been removed from this repository.)

File-based logging
- Email sends and failures are now logged to `logs/email.log` with a JSON payload and timestamp.
- Example line:

```
2025-11-03T12:34:56+00:00 | SENT | {"to":"you@example.com","subject":"...","message_id":null,"status":"sent"}
```

Database logging
- If the `app/helpers/logger.php` and `SystemLog` model are available and the DB is reachable, the helper will attempt to write `email_sent` and `email_failed` entries to `system_logs`.
- Entries include `user_id` (if session is set), action, and JSON details.

Automated test (basic)
- For CI, write a small PHPUnit test that calls the email helper with PHPMailer mocked or use a mail catcher like MailHog. Wrap `send_email()` so SMTP interactions can be stubbed in tests.

Troubleshooting
- If sending fails, check:
  - `logs/email.log` for the file-based record
  - PHP error log for PHPMailer messages
  - SMTP credentials in `app/config/config.php`

Next steps
- Add a PHPUnit test that uses a local mail catcher (Mailhog) or a mocked PHPMailer to assert the helper returns true/false as expected.
