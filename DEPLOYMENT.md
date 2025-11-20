# Deployment Guide - iForYoungTours

## Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- XAMPP/WAMP (for local) or cPanel/VPS (for production)

## Local Setup

### 1. Clone Repository
```bash
git clone <your-repo-url>
cd ForeverYoungTours
```

### 2. Database Setup
1. Create database: `forevveryoungtours`
2. Import SQL file (if provided) or create tables:
   - `regions` (continents)
   - `countries`
   - `tours`
   - `bookings`
   - `users`

### 3. Configuration
```bash
# Copy config template
cp config.example.php config.php

# Edit config.php and update:
# - BASE_URL for your local path
# - Database credentials (if separate db_config.php)
```

### 4. Apache VirtualHost Setup

Edit `httpd-vhosts.conf`:

```apache
# Main site (MUST BE FIRST)
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "C:/xampp1/htdocs"
    <Directory "C:/xampp1/htdocs">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

# Africa Continent
<VirtualHost *:80>
    ServerName africa.foreveryoungtours.local
    DocumentRoot "C:/xampp1/htdocs/ForeverYoungTours/continents/africa"
    <Directory "C:/xampp1/htdocs/ForeverYoungTours/continents/africa">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

# Rwanda Country
<VirtualHost *:80>
    ServerName visit-rw.foreveryoungtours.local
    DocumentRoot "C:/xampp1/htdocs/ForeverYoungTours/countries/rwanda"
    <Directory "C:/xampp1/htdocs/ForeverYoungTours/countries/rwanda">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 5. Hosts File
Add to `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 africa.foreveryoungtours.local
127.0.0.1 visit-rw.foreveryoungtours.local
```

### 6. Permissions
Ensure `uploads/` directory is writable:
```bash
chmod -R 755 uploads/
```

## Production Deployment

### 1. Upload Files
- Upload all files except: config.php, uploads/, .git/

### 2. Update config.php
```php
// For production, update detectBaseUrl() to use your domain
return 'https://yourdomain.com';
```

### 3. Subdomain Setup
Create subdomains in cPanel:
- africa.yourdomain.com → /public_html/continents/africa
- visit-rw.yourdomain.com → /public_html/countries/rwanda

### 4. Database
- Import database
- Update credentials in config.php or db_config.php

### 5. SSL Certificate
Install SSL for main domain and all subdomains

## Troubleshooting

### Images Not Loading
- Check BASE_URL in config.php
- Verify uploads/ directory exists and is readable
- Check image paths in database (should be: uploads/tours/filename.jpg)

### 404 Errors on Subdomains
- Verify VirtualHost configuration
- Check DocumentRoot paths
- Ensure localhost is FIRST VirtualHost

### Database Connection Issues
- Verify credentials
- Check MySQL service is running
- Ensure database exists

## Security Notes
- Never commit config.php with real credentials
- Keep uploads/ out of version control
- Use .htaccess to protect sensitive directories
- Enable HTTPS in production
- Sanitize all user inputs
- Use prepared statements for database queries

## Support
For issues, check Apache error logs:
- Windows: `C:\xampp\apache\logs\error.log`
- Linux: `/var/log/apache2/error.log`
