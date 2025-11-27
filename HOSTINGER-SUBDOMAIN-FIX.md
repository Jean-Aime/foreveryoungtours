# Hostinger Subdomain Fix Guide

## Problem
Subdomains (visit-rw.iforeveryoungtours.com, africa.iforeveryoungtours.com) are redirecting to the main domain (iforeveryoungtours.com)

## Root Cause
This is typically caused by:
1. DNS records pointing to wrong directory
2. Subdomain configuration in Hostinger panel redirecting to main domain
3. Missing or incorrect .htaccess rules

## Solution Steps

### Step 1: Check DNS/Subdomain Configuration in Hostinger

1. Log into your Hostinger control panel (hPanel)
2. Go to **Domains** → **Subdomains**
3. Check if your subdomains exist:
   - `visit-rw.iforeveryoungtours.com`
   - `africa.iforeveryoungtours.com`
   
4. **IMPORTANT**: Each subdomain should point to the **SAME directory** as your main domain
   - Main domain: `/public_html` or `/domains/iforeveryoungtours.com/public_html`
   - Subdomains: Should point to **THE SAME** directory (NOT separate folders)

5. If subdomains don't exist, create them:
   - Click "Create Subdomain"
   - Enter subdomain name (e.g., `visit-rw`)
   - Set document root to **same as main domain**
   - Click Create

### Step 2: Create All Required Subdomains

Create these subdomains in Hostinger panel (all pointing to same directory):

**Country Subdomains:**
- visit-rw.iforeveryoungtours.com (Rwanda)
- visit-ke.iforeveryoungtours.com (Kenya)
- visit-tz.iforeveryoungtours.com (Tanzania)
- visit-ug.iforeveryoungtours.com (Uganda)
- visit-eg.iforeveryoungtours.com (Egypt)
- visit-ma.iforeveryoungtours.com (Morocco)
- visit-za.iforeveryoungtours.com (South Africa)

**Continent Subdomains:**
- africa.iforeveryoungtours.com
- asia.iforeveryoungtours.com
- europe.iforeveryoungtours.com
- caribbean.iforeveryoungtours.com

### Step 3: Update .htaccess File

1. Rename `.htaccess-hostinger` to `.htaccess`
2. Upload to your public_html directory
3. Make sure it replaces the old .htaccess

### Step 4: Test Subdomain Detection

1. Upload `subdomain-test.php` to your public_html directory
2. Visit: https://visit-rw.iforeveryoungtours.com/subdomain-test.php
3. Check if it shows "Country Subdomain Detected: RW"
4. If it shows "Main Domain", go back to Step 1

### Step 5: Verify Database Country Codes

Make sure your countries table has correct country_code values:
- Rwanda: RW
- Kenya: KE
- Tanzania: TZ
- Uganda: UG
- Egypt: EG
- Morocco: MA
- South Africa: ZA

Run this SQL to check:
```sql
SELECT id, name, country_code FROM countries WHERE status = 'active';
```

If country_code is missing, update it:
```sql
UPDATE countries SET country_code = 'RW' WHERE name = 'Rwanda';
UPDATE countries SET country_code = 'KE' WHERE name = 'Kenya';
UPDATE countries SET country_code = 'TZ' WHERE name = 'Tanzania';
UPDATE countries SET country_code = 'UG' WHERE name = 'Uganda';
UPDATE countries SET country_code = 'EG' WHERE name = 'Egypt';
UPDATE countries SET country_code = 'MA' WHERE name = 'Morocco';
UPDATE countries SET country_code = 'ZA' WHERE name = 'South Africa';
```

### Step 6: Clear Cache

1. Clear browser cache
2. Clear Hostinger cache (if using caching)
3. Try accessing subdomain in incognito/private mode

## Common Issues

### Issue 1: Subdomain shows "This site can't be reached"
**Solution**: DNS not propagated yet. Wait 24-48 hours or check DNS records.

### Issue 2: Subdomain redirects to main domain
**Solution**: Check subdomain document root in Hostinger panel. Must point to same directory as main domain.

### Issue 3: 404 errors on subdomain
**Solution**: .htaccess not working. Check if mod_rewrite is enabled in Hostinger.

### Issue 4: Subdomain shows but no content filtering
**Solution**: Check index.php is properly handling $_GET['country'] or $_GET['continent'] parameters.

## Testing Checklist

- [ ] Subdomains created in Hostinger panel
- [ ] All subdomains point to same directory as main domain
- [ ] .htaccess file uploaded and renamed correctly
- [ ] subdomain-test.php shows correct subdomain detection
- [ ] Database has correct country_code values
- [ ] index.php handles country/continent parameters
- [ ] Browser cache cleared
- [ ] Tested in incognito mode

## Need Help?

If still not working after following all steps:
1. Check subdomain-test.php output
2. Check Hostinger error logs
3. Contact Hostinger support to verify subdomain configuration
4. Verify DNS propagation at: https://dnschecker.org

## Files Modified
- `.htaccess` - Subdomain routing rules
- `index.php` - Subdomain parameter handling
- `subdomain-test.php` - Diagnostic tool
