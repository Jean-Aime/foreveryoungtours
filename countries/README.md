# Country Subdomains Structure

## Current Active Countries
- **visit-rw** - Rwanda
- **visit-ke** - Kenya

## Directory Structure
Each country subdomain has:
```
visit-XX/
├── .htaccess           # URL rewriting for /tour/slug routes
├── config.php          # Includes main config
├── index.php           # Country homepage
├── assets/             # Country-specific assets
│   ├── css/
│   └── images/
├── includes/           # Country-specific includes
│   ├── header.php
│   └── footer.php
└── pages/              # Country pages
    ├── config.php      # Page-level config
    ├── packages.php    # Tour listings
    ├── tour-detail.php # Tour detail page
    └── inquiry-modal.php
```

## Adding New Country Subdomain

### Automatic Method (Recommended)
When admin adds a new country to the database, call the auto-clone function:

```php
require_once 'countries/auto-clone-country.php';
$result = autoCloneCountrySubdomain('tanzania', 'Tanzania', 'tz');
```

### Manual Method
1. Copy `visit-rw` folder to `visit-XX` (where XX is country code)
2. Update any country-specific content in index.php
3. Test the subdomain: `http://localhost/foreveryoungtours/countries/visit-XX/`

## Theme & Features
All country subdomains use:
- Continent subdomain theme (no navigation header)
- Poppins/Inter fonts
- Full-screen hero sections
- Yellow/orange gradients
- Relative paths to stay within subdomain
- Slug-based tour URLs (/tour/city-tour)
- Square gallery images
- Login modal for booking

## Important Notes
- All links use relative paths to keep users within subdomain
- Tour detail pages support both slug and ID parameters
- Footer links point to subdomain pages, not main domain
- Images use getImageUrl() helper for proper path resolution
