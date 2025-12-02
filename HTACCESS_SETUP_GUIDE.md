# .htaccess Configuration Guide - Offline & Online Setup

## Current Status

Your project has **3 different .htaccess configurations**:
- `.htaccess` - Local XAMPP setup
- `.htaccess-production` - Production Hostinger setup
- `.htaccess-hostinger` - Alternative Hostinger setup

## Issues with Current Setup

### ❌ Local (.htaccess)
```
RewriteBase /ForeverYoungTours/
```
- **Problem**: Hardcoded folder name - won't work if you rename the folder
- **Works on**: XAMPP only

### ❌ Production (.htaccess-production)
```
RewriteCond %{HTTP_HOST} ^visit-rw\.foreveryoungtours\.com$ [NC]
```
- **Problem**: Hardcoded domain names - won't work on different domains
- **Works on**: Hostinger with specific domain only

### ❌ Hostinger (.htaccess-hostinger)
```
RewriteCond %{HTTP_HOST} ^visit-([a-z]{2})\.iforeveryoungtours\.com$ [NC]
```
- **Problem**: Hardcoded domain - won't work on other hosting providers
- **Works on**: Hostinger with specific domain only

---

## ✅ Solution: Universal .htaccess

I've created `.htaccess-universal` that **automatically detects** your environment and applies the correct configuration.

### How It Works

```apache
# Detects localhost/XAMPP
RewriteCond %{HTTP_HOST} ^localhost [OR]
RewriteCond %{HTTP_HOST} ^127\.0\.0\.1 [OR]
RewriteCond %{HTTP_HOST} ^.*\.local$
RewriteRule ^(.*)$ - [E=REWRITE_BASE:/ForeverYoungTours/]

# If not localhost, use root base (production)
RewriteCond %{ENV:REWRITE_BASE} ^$
RewriteRule ^(.*)$ - [E=REWRITE_BASE:/]
```

### Key Features

✅ **Works on XAMPP** - Auto-detects `/ForeverYoungTours/` path  
✅ **Works on Production** - Auto-detects root `/` path  
✅ **Subdomain Support** - Uses wildcard matching for any domain  
✅ **Tour Routing** - `/tour/slug-name` works everywhere  
✅ **Security** - Blocks access to sensitive files  
✅ **Performance** - Gzip compression & caching headers  

---

## Setup Instructions

### For Local Development (XAMPP)

1. **Replace current .htaccess**:
   ```bash
   cp .htaccess-universal .htaccess
   ```

2. **Test subdomains locally** (add to Windows hosts file):
   ```
   C:\Windows\System32\drivers\etc\hosts
   
   127.0.0.1 localhost
   127.0.0.1 visit-rw.localhost
   127.0.0.1 visit-ke.localhost
   127.0.0.1 africa.localhost
   127.0.0.1 asia.localhost
   ```

3. **Access via**:
   - Main: `http://localhost/ForeverYoungTours/`
   - Rwanda: `http://visit-rw.localhost/ForeverYoungTours/`
   - Kenya: `http://visit-ke.localhost/ForeverYoungTours/`
   - Tours: `http://visit-rw.localhost/ForeverYoungTours/tour/tour-slug`

### For Production (Any Hosting)

1. **Upload .htaccess-universal as .htaccess** to your public_html root

2. **Configure DNS/Subdomains** in your hosting panel:
   - `visit-rw.yourdomain.com` → points to public_html
   - `visit-ke.yourdomain.com` → points to public_html
   - `africa.yourdomain.com` → points to public_html
   - etc.

3. **Access via**:
   - Main: `https://yourdomain.com/`
   - Rwanda: `https://visit-rw.yourdomain.com/`
   - Kenya: `https://visit-ke.yourdomain.com/`
   - Tours: `https://visit-rw.yourdomain.com/tour/tour-slug`

---

## Subdomain Routing Rules

### Country Subdomains
```
visit-rw.domain.com → /countries/visit-rw/
visit-ke.domain.com → /countries/visit-ke/
visit-tz.domain.com → /countries/visit-tz/
```

### Continent Subdomains
```
africa.domain.com → /continents/africa/
asia.domain.com → /continents/asia/
europe.domain.com → /continents/europe/
```

### Tour Detail Pages
```
/tour/gorilla-trekking → pages/tour-detail.php?slug=gorilla-trekking
/tour/safari-adventure → pages/tour-detail.php?slug=safari-adventure
```

---

## Testing Checklist

### Local (XAMPP)
- [ ] Main site loads: `http://localhost/ForeverYoungTours/`
- [ ] Country subdomain works: `http://visit-rw.localhost/ForeverYoungTours/`
- [ ] Tour detail loads: `http://visit-rw.localhost/ForeverYoungTours/tour/gorilla-trekking`
- [ ] Continent subdomain works: `http://africa.localhost/ForeverYoungTours/`

### Production
- [ ] Main site loads: `https://yourdomain.com/`
- [ ] Country subdomain works: `https://visit-rw.yourdomain.com/`
- [ ] Tour detail loads: `https://visit-rw.yourdomain.com/tour/gorilla-trekking`
- [ ] HTTPS redirect works
- [ ] Sensitive files blocked (try accessing .sql, .md files)

---

## Troubleshooting

### Subdomains Not Working

**Issue**: Getting 404 on subdomains

**Solutions**:
1. Check DNS records point to correct IP
2. Verify mod_rewrite is enabled: `a2enmod rewrite`
3. Check .htaccess permissions: `chmod 644 .htaccess`
4. Verify RewriteBase is correct for your setup

### Tours Not Loading

**Issue**: `/tour/slug` returns 404

**Solutions**:
1. Check tour exists in database
2. Verify `pages/tour-detail.php` exists
3. Check query parameter: `?slug=` is being passed
4. Review error logs for rewrite errors

### Folder Rename Issues

**Issue**: After renaming folder, site breaks

**Solution**: Universal .htaccess auto-detects folder name - no changes needed!

---

## File Structure

```
foreveryoungtours/
├── .htaccess (use .htaccess-universal)
├── .htaccess-universal (NEW - recommended)
├── .htaccess-production (old - for reference)
├── .htaccess-hostinger (old - for reference)
├── pages/
│   └── tour-detail.php
├── countries/
│   ├── visit-rw/
│   │   ├── .htaccess
│   │   └── pages/tour-detail.php
│   ├── visit-ke/
│   └── ...
└── continents/
    ├── africa/
    ├── asia/
    └── ...
```

---

## Next Steps

1. **Backup current .htaccess**:
   ```bash
   cp .htaccess .htaccess.backup
   ```

2. **Use universal version**:
   ```bash
   cp .htaccess-universal .htaccess
   ```

3. **Test locally** with subdomain hosts entries

4. **Deploy to production** - same .htaccess works!

5. **Monitor** error logs for any rewrite issues

---

## Additional Notes

- **Wildcard Subdomains**: The universal .htaccess uses `^visit-([a-z]{2,3})\.(.+)$` which matches ANY domain
- **No Hardcoding**: Domain names are not hardcoded - works on any domain
- **Folder Agnostic**: Works regardless of folder name
- **Environment Detection**: Automatically detects localhost vs production
- **Security**: Blocks access to .sql, .md, and .htaccess files
- **Performance**: Includes gzip compression and cache headers

