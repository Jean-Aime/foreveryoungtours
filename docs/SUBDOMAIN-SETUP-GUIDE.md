# 🌐 Subdomain Setup Guide for Forever Young Tours

## Issue: DNS_PROBE_FINISHED_NXDOMAIN

When you try to access `http://visit-se.foreveryoungtours.local/`, you get a DNS error because:

1. **"SE" is not a valid country code** in your database
2. **The subdomain is not configured** in your local environment

---

## 📋 Valid Country Subdomains

Here are the **correct subdomains** for all 17 countries:

| Country | Subdomain | Folder |
|---------|-----------|--------|
| 🇷🇼 Rwanda | `visit-rw.foreveryoungtours.local` | rwanda |
| 🇿🇦 South Africa | `visit-za.foreveryoungtours.local` | south-africa |
| 🇰🇪 Kenya | `visit-ke.foreveryoungtours.local` | kenya |
| 🇹🇿 Tanzania | `visit-tz.foreveryoungtours.local` | tanzania |
| 🇺🇬 Uganda | `visit-ug.foreveryoungtours.local` | uganda |
| 🇪🇬 Egypt | `visit-eg.foreveryoungtours.local` | egypt |
| 🇲🇦 Morocco | `visit-ma.foreveryoungtours.local` | morocco |
| 🇬🇭 Ghana | `visit-gh.foreveryoungtours.local` | ghana |
| 🇳🇬 Nigeria | `visit-ng.foreveryoungtours.local` | nigeria |
| 🇪🇹 Ethiopia | `visit-et.foreveryoungtours.local` | ethiopia |
| 🇧🇼 Botswana | `visit-bw.foreveryoungtours.local` | botswana |
| 🇳🇦 Namibia | `visit-na.foreveryoungtours.local` | namibia |
| 🇿🇼 Zimbabwe | `visit-zw.foreveryoungtours.local` | zimbabwe |
| 🇸🇳 Senegal | `visit-sn.foreveryoungtours.local` | senegal |
| 🇹🇳 Tunisia | `visit-tn.foreveryoungtours.local` | tunisia |
| 🇨🇲 Cameroon | `visit-cm.foreveryoungtours.local` | cameroon |
| 🇨🇩 DR Congo | `visit-cd.foreveryoungtours.local` | democratic-republic-of-congo |

**Note:** There is **NO "visit-se"** country. If you meant Senegal, use **`visit-sn`**.

---

## 🔧 How to Fix: Configure Local Subdomains

### Option 1: Use Localhost Format (Easiest)

Instead of `.local`, use `.localhost` format which works automatically:

```
http://visit-rw.localhost/foreveryoungtours/
http://visit-ke.localhost/foreveryoungtours/
http://visit-sn.localhost/foreveryoungtours/
```

**This works immediately without any configuration!**

### Option 2: Configure Windows Hosts File

If you want to use `.foreveryoungtours.local` format:

1. **Open Notepad as Administrator**
   - Right-click Notepad → "Run as administrator"

2. **Open Hosts File**
   ```
   File → Open → C:\Windows\System32\drivers\etc\hosts
   ```
   (Change file type to "All Files" to see it)

3. **Add These Lines:**
   ```
   127.0.0.1 foreveryoungtours.local
   127.0.0.1 visit-rw.foreveryoungtours.local
   127.0.0.1 visit-za.foreveryoungtours.local
   127.0.0.1 visit-ke.foreveryoungtours.local
   127.0.0.1 visit-tz.foreveryoungtours.local
   127.0.0.1 visit-ug.foreveryoungtours.local
   127.0.0.1 visit-eg.foreveryoungtours.local
   127.0.0.1 visit-ma.foreveryoungtours.local
   127.0.0.1 visit-gh.foreveryoungtours.local
   127.0.0.1 visit-ng.foreveryoungtours.local
   127.0.0.1 visit-et.foreveryoungtours.local
   127.0.0.1 visit-bw.foreveryoungtours.local
   127.0.0.1 visit-na.foreveryoungtours.local
   127.0.0.1 visit-zw.foreveryoungtours.local
   127.0.0.1 visit-sn.foreveryoungtours.local
   127.0.0.1 visit-tn.foreveryoungtours.local
   127.0.0.1 visit-cm.foreveryoungtours.local
   127.0.0.1 visit-cd.foreveryoungtours.local
   ```

4. **Save and Close**

5. **Flush DNS Cache**
   ```
   ipconfig /flushdns
   ```

### Option 3: Configure Apache Virtual Hosts

1. **Open Apache Config**
   ```
   C:\xampp1\apache\conf\extra\httpd-vhosts.conf
   ```

2. **Add Virtual Host:**
   ```apache
   <VirtualHost *:80>
       ServerName foreveryoungtours.local
       ServerAlias *.foreveryoungtours.local
       DocumentRoot "C:/xampp1/htdocs/foreveryoungtours"
       <Directory "C:/xampp1/htdocs/foreveryoungtours">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. **Restart Apache**

---

## ✅ What Was Fixed

### Updated `subdomain-handler.php`

Added missing countries to the code mapping:

```php
$code_mapping = [
    // ... existing countries ...
    'SN' => 'SEN',  // Senegal (NEW)
    'TN' => 'TUN',  // Tunisia (NEW)
    'CM' => 'CMR',  // Cameroon (NEW)
    'CD' => 'COD'   // DR Congo (NEW)
];
```

Added missing countries to folder mapping:

```php
$folder_mapping = [
    // ... existing countries ...
    'visit-sn' => 'senegal',                      // NEW
    'visit-tn' => 'tunisia',                      // NEW
    'visit-cm' => 'cameroon',                     // NEW
    'visit-cd' => 'democratic-republic-of-congo'  // NEW
];
```

**Now all 17 countries are properly configured in the subdomain handler!**

---

## 🧪 Testing Subdomains

### Test with Localhost Format (No Setup Required)

```
http://visit-rw.localhost/foreveryoungtours/  ✅ Rwanda
http://visit-ke.localhost/foreveryoungtours/  ✅ Kenya
http://visit-sn.localhost/foreveryoungtours/  ✅ Senegal
http://visit-tn.localhost/foreveryoungtours/  ✅ Tunisia
```

### Test with .local Format (After Hosts File Setup)

```
http://visit-rw.foreveryoungtours.local/  ✅ Rwanda
http://visit-ke.foreveryoungtours.local/  ✅ Kenya
http://visit-sn.foreveryoungtours.local/  ✅ Senegal
http://visit-tn.foreveryoungtours.local/  ✅ Tunisia
```

---

## 🎯 Quick Solution

**If you want to access Senegal right now:**

Use this URL (works immediately):
```
http://visit-sn.localhost/foreveryoungtours/
```

**NOT:**
```
http://visit-se.foreveryoungtours.local/  ❌ Wrong code
```

---

## 📊 Country Code Reference

| Country | 2-Letter | 3-Letter | Slug |
|---------|----------|----------|------|
| Rwanda | RW | RWA | visit-rw |
| Kenya | KE | KEN | visit-ke |
| Tanzania | TZ | TZA | visit-tz |
| Uganda | UG | UGA | visit-ug |
| South Africa | ZA | ZAF | visit-za |
| Egypt | EG | EGY | visit-eg |
| Morocco | MA | MAR | visit-ma |
| Ghana | GH | GHA | visit-gh |
| Nigeria | NG | NGA | visit-ng |
| Ethiopia | ET | ETH | visit-et |
| Botswana | BW | BWA | visit-bw |
| Namibia | NA | NAM | visit-na |
| Zimbabwe | ZW | ZWE | visit-zw |
| **Senegal** | **SN** | **SEN** | **visit-sn** |
| Tunisia | TN | TUN | visit-tn |
| Cameroon | CM | CMR | visit-cm |
| DR Congo | CD | COD | visit-cd |

---

## 🚀 Production Setup

For production (iforeveryoungtours.com), you'll need to:

1. **Add DNS Records** (in your domain registrar):
   ```
   Type: A Record
   Name: visit-rw
   Value: Your server IP
   
   Type: A Record
   Name: visit-ke
   Value: Your server IP
   
   ... (repeat for all countries)
   ```

2. **Or use Wildcard DNS:**
   ```
   Type: A Record
   Name: *.
   Value: Your server IP
   ```

3. **Configure SSL** for all subdomains

---

## ✅ Summary

- ❌ **`visit-se`** does NOT exist
- ✅ **`visit-sn`** is Senegal (correct)
- ✅ All 17 countries now in subdomain handler
- ✅ Use `.localhost` format for immediate testing
- ✅ Configure hosts file for `.local` format
- ✅ Rwanda theme cloned to all countries

**Use the correct country codes and the subdomains will work!**

