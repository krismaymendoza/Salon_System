# TODO

- [ ] Configure SMTP credentials for PHPMailer (Gmail App Password)
  - Set: `GMAIL_FROM_EMAIL`, `GMAIL_SMTP_USER`, `GMAIL_SMTP_PASS`
  - Optional: `APP_DEBUG=1` for verbose SMTP debug output
- [x] Add SMTP debug + missing env var validation in `includes/mailer_register.php`
- [ ] Fix/verify `mail_test.php` for detailed SMTP debug output
- [ ] Restart Apache after env changes
- [ ] Test: `http://localhost/salon_system/mail_test.php?email=you@example.com`
- [ ] Retest registration flow

