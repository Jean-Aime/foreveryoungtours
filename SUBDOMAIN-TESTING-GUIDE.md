# 🧪 SUBDOMAIN TESTING GUIDE

## Quick Verification

### Run Automated Test
```
http://localhost/foreveryoungtours/verify-subdomains.php
```

This will show you:
- ✅ All continent pages status
- ✅ All country pages status  
- ✅ Database connectivity
- ✅ Configuration settings
- ✅ Quick test links

## Manual Testing Checklist

### 1. Test Continent Pages

#### Africa
```
http://localhost/foreveryoungtours/continents/africa/
```
**Check:**
- [ ] Hero image loads
- [ ] Continent name displays
- [ ] Countries grid shows (should show Rwanda, Kenya, etc.)
- [ ] Featured tours display (top 6)
- [ ] All images load correctly
- [ ] Navigation links work
- [ ] "Explore Countries" button works
- [ ] Country cards are clickable

#### Asia
```
http://localhost/foreveryoungtours/continents/asia/
```
**Check:**
- [ ] Page loads without errors
- [ ] Shows Asian countries (if any in database)
- [ ] Tours display correctly

#### Europe
```
http://localhost/foreveryoungtours/continents/europe/
```
**Check:**
- [ ] Page loads without errors
- [ ] Shows European countries (if any in database)

#### North America
```
http://localhost/foreveryoungtours/continents/north-america/
```

#### South America
```
http://localhost/foreveryoungtours/continents/south-america/
```

#### Oceania
```
http://localhost/foreveryoungtours/continents/oceania/
```

#### Caribbean
```
http://localhost/foreveryoungtours/continents/caribbean/
```

### 2. Test Country Pages

#### Rwanda (Primary Test)
```
http://localhost/foreveryoungtours/countries/rwanda/
```
**Check:**
- [ ] Hero section with country name
- [ ] Country description displays
- [ ] Tours grid shows all Rwanda tours
- [ ] Featured badge shows on featured tours
- [ ] Tour images load correctly
- [ ] Price displays correctly
- [ ] Duration shows
- [ ] "View Details" button works
- [ ] Country info section (currency, language, etc.)
- [ ] Navigation back to main site works

#### Kenya
```
http://localhost/foreveryoungtours/countries/kenya/
```
**Check:**
- [ ] All tours for Kenya display
- [ ] Images load
- [ ] Links work

#### Tanzania
```
http://localhost/foreveryoungtours/countries/tanzania/
```

#### South Africa
```
http://localhost/foreveryoungtours/countries/south-africa/
```

#### Egypt
```
http://localhost/foreveryoungtours/countries/egypt/
```

#### Morocco
```
http://localhost/foreveryoungtours/countries/morocco/
```

#### Nigeria
```
http://localhost/foreveryoungtours/countries/nigeria/
```

#### Ghana
```
http://localhost/foreveryoungtours/countries/ghana/
```

### 3. Test Navigation Flow

#### From Main Site to Continent
1. Go to: `http://localhost/foreveryoungtours/pages/destinations.php`
2. Click on a continent
3. Should navigate to continent page
4. Check all links work

#### From Continent to Country
1. Go to any continent page
2. Click on a country card
3. Should navigate to country page
4. Check all content loads

#### From Country to Tour Detail
1. Go to any country page
2. Click "View Details" on a tour
3. Should navigate to tour detail page
4. Check tour information displays

### 4. Test Image Loading

#### Check These Image Types:
- [ ] Continent hero images
- [ ] Country hero images
- [ ] Tour cover images
- [ ] Tour gallery images
- [ ] Default fallback images (when no image exists)

#### Image URL Format Should Be:
```
http://localhost/foreveryoungtours/uploads/tours/[filename]
http://localhost/foreveryoungtours/assets/images/[filename]
```

### 5. Test Responsive Design

#### Desktop (1920x1080)
- [ ] All grids display properly
- [ ] Images scale correctly
- [ ] Navigation is accessible
- [ ] Text is readable

#### Tablet (768x1024)
- [ ] Grids adapt to 2 columns
- [ ] Touch targets are large enough
- [ ] Images don't overflow

#### Mobile (375x667)
- [ ] Single column layout
- [ ] All content is accessible
- [ ] Buttons are touch-friendly
- [ ] Images scale properly

### 6. Test Database Integration

#### Verify Data Shows:
- [ ] Active continents only
- [ ] Active countries only
- [ ] Active tours only
- [ ] Featured tours have badge
- [ ] Correct country-continent relationships
- [ ] Correct tour-country relationships

### 7. Test Error Handling

#### Test Invalid URLs:
```
http://localhost/foreveryoungtours/continents/invalid/
http://localhost/foreveryoungtours/countries/invalid/
```
**Should:**
- [ ] Redirect to destinations page
- [ ] Not show PHP errors
- [ ] Handle gracefully

#### Test Empty Data:
- [ ] Continent with no countries shows message
- [ ] Country with no tours shows message
- [ ] Missing images show fallback

## Common Issues & Solutions

### Issue: Images Not Loading
**Solution:**
1. Check BASE_URL in config.php
2. Verify uploads folder exists
3. Check file permissions
4. Verify image paths in database

### Issue: No Tours Showing
**Solution:**
1. Check tour status = 'active' in database
2. Verify country_id matches
3. Check database connection
4. Look for PHP errors in browser console

### Issue: Navigation Broken
**Solution:**
1. Verify BASE_URL is correct
2. Check all links use BASE_URL constant
3. Verify .htaccess is configured
4. Check file paths are correct

### Issue: Country Page Blank
**Solution:**
1. Check country slug matches database
2. Verify config.php is included
3. Check database connection
4. Look for PHP errors

### Issue: Styling Not Applied
**Solution:**
1. Check Tailwind CDN is loading
2. Verify internet connection
3. Check browser console for errors
4. Clear browser cache

## Performance Testing

### Page Load Times
- [ ] Continent pages load < 2 seconds
- [ ] Country pages load < 2 seconds
- [ ] Images load progressively
- [ ] No blocking resources

### Database Queries
- [ ] Queries are optimized
- [ ] Using proper indexes
- [ ] No N+1 query problems
- [ ] Efficient JOINs

## Browser Compatibility

### Test In:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Safari (iOS)
- [ ] Chrome Mobile (Android)

## Security Checks

### Verify:
- [ ] No SQL injection vulnerabilities
- [ ] Proper input sanitization
- [ ] XSS protection (htmlspecialchars)
- [ ] No sensitive data exposed
- [ ] Proper error handling

## Final Checklist

### Before Going Live:
- [ ] All continent pages tested
- [ ] All country pages tested
- [ ] All images loading correctly
- [ ] All navigation working
- [ ] Database queries optimized
- [ ] No PHP errors or warnings
- [ ] Responsive design verified
- [ ] Browser compatibility confirmed
- [ ] Performance acceptable
- [ ] Security measures in place

## Test Results Template

```
Date: _______________
Tester: _______________

Continent Pages: ___/7 Working
Country Pages: ___/20 Working
Image Loading: Pass/Fail
Navigation: Pass/Fail
Responsive Design: Pass/Fail
Database Integration: Pass/Fail
Error Handling: Pass/Fail

Issues Found:
1. _______________
2. _______________
3. _______________

Overall Status: Ready/Needs Work
```

## Support Resources

### Documentation:
- `SUBDOMAIN-FIX-COMPLETE.md` - Complete fix documentation
- `config.php` - Configuration reference
- `verify-subdomains.php` - Automated testing

### Key Files:
- `/continents/[slug]/index.php` - Continent pages
- `/countries/[slug]/index.php` - Country pages
- `/countries/[slug]/config.php` - Country configs
- `/config.php` - Main configuration

### Database Tables:
- `regions` - Continents
- `countries` - Countries
- `tours` - Tour listings

---

**Remember:** Test thoroughly before deploying to production!

*Last Updated: January 2025*
