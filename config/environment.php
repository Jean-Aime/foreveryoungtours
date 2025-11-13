<?php
/**
 * Environment Configuration for ForeverYoung Tours
 * Automatically detects local vs production environment
 */

class EnvironmentConfig {
    
    private static $instance = null;
    private $isLocal;
    private $baseUrl;
    private $domains;
    
    private function __construct() {
        $this->detectEnvironment();
        $this->setupDomains();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function detectEnvironment() {
        $this->isLocal = (
            strpos($_SERVER['HTTP_HOST'], '.local') !== false ||
            strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
            strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false
        );
        
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $this->baseUrl = $protocol . $_SERVER['HTTP_HOST'];
    }
    
    private function setupDomains() {
        if ($this->isLocal) {
            // Local development domains
            $this->domains = [
                'main' => 'http://foreveryoungtours.local',
                
                // Continents
                'africa' => 'http://africa.foreveryoungtours.local',
                'asia' => 'http://asia.foreveryoungtours.local',
                'europe' => 'http://europe.foreveryoungtours.local',
                'north-america' => 'http://north-america.foreveryoungtours.local',
                'south-america' => 'http://south-america.foreveryoungtours.local',
                'caribbean' => 'http://caribbean.foreveryoungtours.local',
                
                // Countries
                'visit-rw' => 'http://visit-rw.foreveryoungtours.local',
                'visit-ke' => 'http://visit-ke.foreveryoungtours.local',
                'visit-tz' => 'http://visit-tz.foreveryoungtours.local',
                'visit-ug' => 'http://visit-ug.foreveryoungtours.local',
                'visit-eg' => 'http://visit-eg.foreveryoungtours.local',
                'visit-ma' => 'http://visit-ma.foreveryoungtours.local',
                'visit-za' => 'http://visit-za.foreveryoungtours.local',
                'visit-bw' => 'http://visit-bw.foreveryoungtours.local',
                'visit-na' => 'http://visit-na.foreveryoungtours.local',
                'visit-zw' => 'http://visit-zw.foreveryoungtours.local',
                'visit-gh' => 'http://visit-gh.foreveryoungtours.local',
                'visit-ng' => 'http://visit-ng.foreveryoungtours.local',
                'visit-et' => 'http://visit-et.foreveryoungtours.local',
            ];
        } else {
            // Production domains
            $this->domains = [
                'main' => 'https://iforeveryoungtours.com',
                
                // Continents
                'africa' => 'https://africa.iforeveryoungtours.com',
                'asia' => 'https://asia.iforeveryoungtours.com',
                'europe' => 'https://europe.iforeveryoungtours.com',
                'north-america' => 'https://north-america.iforeveryoungtours.com',
                'south-america' => 'https://south-america.iforeveryoungtours.com',
                'caribbean' => 'https://caribbean.iforeveryoungtours.com',
                
                // Countries
                'visit-rw' => 'https://visit-rw.iforeveryoungtours.com',
                'visit-ke' => 'https://visit-ke.iforeveryoungtours.com',
                'visit-tz' => 'https://visit-tz.iforeveryoungtours.com',
                'visit-ug' => 'https://visit-ug.iforeveryoungtours.com',
                'visit-eg' => 'https://visit-eg.iforeveryoungtours.com',
                'visit-ma' => 'https://visit-ma.iforeveryoungtours.com',
                'visit-za' => 'https://visit-za.iforeveryoungtours.com',
                'visit-bw' => 'https://visit-bw.iforeveryoungtours.com',
                'visit-na' => 'https://visit-na.iforeveryoungtours.com',
                'visit-zw' => 'https://visit-zw.iforeveryoungtours.com',
                'visit-gh' => 'https://visit-gh.iforeveryoungtours.com',
                'visit-ng' => 'https://visit-ng.iforeveryoungtours.com',
                'visit-et' => 'https://visit-et.iforeveryoungtours.com',
            ];
        }
    }
    
    public function isLocal() {
        return $this->isLocal;
    }
    
    public function getBaseUrl() {
        return $this->baseUrl;
    }
    
    public function getDomain($key) {
        return isset($this->domains[$key]) ? $this->domains[$key] : $this->domains['main'];
    }
    
    public function getAllDomains() {
        return $this->domains;
    }
    
    public function getCurrentSubdomain() {
        $host = $_SERVER['HTTP_HOST'];
        
        // Extract subdomain
        if ($this->isLocal) {
            if (preg_match('/^([^.]+)\.foreveryoungtours\.local$/', $host, $matches)) {
                return $matches[1];
            }
        } else {
            if (preg_match('/^([^.]+)\.iforeveryoungtours\.com$/', $host, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
    
    public function getAssetPath($path = '') {
        if ($this->isLocal) {
            return '/' . ltrim($path, '/');
        } else {
            return '/' . ltrim($path, '/');
        }
    }
    
    public function getMainSiteUrl($path = '') {
        return $this->domains['main'] . '/' . ltrim($path, '/');
    }
    
    public function getContinentUrl($continent, $path = '') {
        $key = strtolower($continent);
        return $this->getDomain($key) . '/' . ltrim($path, '/');
    }
    
    public function getCountryUrl($countryCode, $path = '') {
        $key = 'visit-' . strtolower($countryCode);
        return $this->getDomain($key) . '/' . ltrim($path, '/');
    }
}

// Helper functions for easy access
function env() {
    return EnvironmentConfig::getInstance();
}

function isLocal() {
    return env()->isLocal();
}

function getDomain($key) {
    return env()->getDomain($key);
}

function getMainUrl($path = '') {
    return env()->getMainSiteUrl($path);
}

function getContinentUrl($continent, $path = '') {
    return env()->getContinentUrl($continent, $path);
}

function getCountryUrl($countryCode, $path = '') {
    return env()->getCountryUrl($countryCode, $path);
}

function getCurrentSubdomain() {
    return env()->getCurrentSubdomain();
}

function getAssetPath($path = '') {
    return env()->getAssetPath($path);
}

// Example usage in your templates:
/*
<?php
require_once 'config/environment.php';

// Get URLs based on environment
$main_url = getMainUrl();
$africa_url = getContinentUrl('africa');
$rwanda_url = getCountryUrl('rw');

// Check environment
if (isLocal()) {
    echo "Running in development mode";
} else {
    echo "Running in production mode";
}

// Get current subdomain
$subdomain = getCurrentSubdomain();
if ($subdomain) {
    echo "Current subdomain: " . $subdomain;
}
?>

<!-- Use in HTML -->
<a href="<?php echo $main_url; ?>">Home</a>
<a href="<?php echo $africa_url; ?>">Explore Africa</a>
<a href="<?php echo $rwanda_url; ?>">Visit Rwanda</a>
*/
?>
