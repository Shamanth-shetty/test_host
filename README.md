# Nammcare Prototype (PHP + MySQL)

## Deploy
1. Push repository to GitHub (public).
2. On Hostinger: hPanel → Websites → Git → Create New Repository.
   - Repository URL:[ https://github.com/YOUR/repo.git](https://github.com/Shamanth-shetty/test_host)
   - Branch: main
   - Install path: leave empty to deploy to public_html
3. After deployment, visit your domain.

## DB credentials
Update `db.php` if you want to change DB credentials. Current values set for Hostinger test DB.

## Notes
- OTP / reset tokens / payments are mocked in this prototype for offline testing.
- To enable email/SMS/payment integrations, replace the mock sections with real API calls (places are commented).
- The UI uses CSS in `style.css` (teal gradient + gold accent).
