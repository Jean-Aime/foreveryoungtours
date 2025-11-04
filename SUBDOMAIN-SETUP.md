# Country Subdomain Setup Guide

## Overview
Each country has its own subdomain in the format: `visit-{country_code}.iforeveryoungtours.com`

Examples:
- Rwanda: **visit-rwa.iforeveryoungtours.com**
- Uganda: **visit-uga.iforeveryoungtours.com**
- Kenya: **visit-ken.iforeveryoungtours.com**
- Tanzania: **visit-tza.iforeveryoungtours.com**
- South Africa: **visit-zaf.iforeveryoungtours.com**

## Local Development Setup (XAMPP)

### 1. Edit Windows Hosts File
Location: `C:\Windows\System32\drivers\etc\hosts`

Add these lines:
```
127.0.0.1 iforeveryoungtours.local
127.0.0.1 visit-rwa.iforeveryoungtours.local
127.0.0.1 visit-uga.iforeveryoungtours.local
127.0.0.1 visit-ken.iforeveryoungtours.local
127.0.0.1 visit-tza.iforeveryoungtours.local
127.0.0.1 visit-zaf.iforeveryoungtours.local
```

### 2. Configure Apache Virtual Hosts
Edit: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

Add:
```apache
<VirtualHost *:80>
    ServerName iforeveryoungtours.local
    ServerAlias *.iforeveryoungtours.local
    DocumentRoot "C:/xampp/htdocs/foreveryoungtours"
    <Directory "C:/xampp/htdocs/foreveryoungtours">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 3. Restart Apache
Restart XAMPP Apache service

### 4. Test Subdomains
- Main site: http://iforeveryoungtours.local
- Rwanda: http://visit-rwa.iforeveryoungtours.local
- Kenya: http://visit-ken.iforeveryoungtours.local

## Production Setup (cPanel/Live Server)

### 1. Create Wildcard Subdomain
In cPanel:
- Go to **Domains** → **Subdomains**
- Create subdomain: `*.iforeveryoungtours.com`
- Point to main website directory

### 2. DNS Configuration
Add DNS A Record:
```
*.iforeveryoungtours.com → Your Server IP
```

### 3. SSL Certificate
Install wildcard SSL certificate for `*.iforeveryoungtours.com`

## Country Codes Reference

| Country | Code | Subdomain |
|---------|------|-----------|
| Rwanda | RWA | visit-rwa.iforeveryoungtours.com |
| Uganda | UGA | visit-uga.iforeveryoungtours.com |
| Kenya | KEN | visit-ken.iforeveryoungtours.com |
| Tanzania | TZA | visit-tza.iforeveryoungtours.com |
| South Africa | ZAF | visit-zaf.iforeveryoungtours.com |
| Egypt | EGY | visit-egy.iforeveryoungtours.com |
| Morocco | MAR | visit-mar.iforeveryoungtours.com |
| Ghana | GHA | visit-gha.iforeveryoungtours.com |
| Nigeria | NGA | visit-nga.iforeveryoungtours.com |
| Ethiopia | ETH | visit-eth.iforeveryoungtours.com |

## How It Works

1. User visits `visit-rwa.iforeveryoungtours.com`
2. `.htaccess` detects the subdomain pattern
3. `subdomain-handler.php` extracts country code (RWA)
4. System loads Rwanda country page automatically
5. All tours, destinations, and content filtered for Rwanda

## Usage in Code

```php
// Include subdomain config
require_once 'config/subdomain-config.php';

// Generate country subdomain URL
$rwanda_url = getCountrySubdomainUrl('RWA');
// Returns: http://visit-rwa.iforeveryoungtours.com

// Check if on country subdomain
if (isCountrySubdomain()) {
    $country_code = getCurrentCountryCode();
    // Returns: RWA
}
```

## Benefits

✅ SEO-friendly country-specific URLs
✅ Better user experience
✅ Easy to share country pages
✅ Professional branding
✅ Scalable for all 47+ African countries
