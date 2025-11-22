<!DOCTYPE html>
<html>
<head>
    <title>Continent Pages Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Continent Pages - Include Fix Test</h1>
    
    <?php
    $continents = ['africa', 'asia', 'europe', 'north-america', 'south-america', 'oceania', 'caribbean'];
    
    foreach ($continents as $continent) {
        $file = __DIR__ . '/continents/' . $continent . '/index.php';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            
            $has_enhanced_modal = strpos($content, 'enhanced-booking-modal.php') !== false;
            $has_inquiry_modal = strpos($content, 'inquiry-modal.php') !== false;
            $has_footer_include = strpos($content, "include '../../includes/footer.php'") !== false;
            $has_footer_tag = strpos($content, '<footer') !== false;
            
            $status = (!$has_enhanced_modal && !$has_inquiry_modal && !$has_footer_include && $has_footer_tag) ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500';
            $icon = (!$has_enhanced_modal && !$has_inquiry_modal && !$has_footer_include && $has_footer_tag) ? '✓' : '✗';
            
            echo "<div class='$status border-2 rounded-lg p-4 mb-3'>
                <div class='flex items-center justify-between'>
                    <div>
                        <span class='text-2xl mr-2'>$icon</span>
                        <strong class='text-lg'>$continent</strong>
                    </div>
                    <div class='text-sm text-gray-600'>
                        Enhanced Modal: " . ($has_enhanced_modal ? '❌' : '✓') . " | 
                        Inquiry Modal: " . ($has_inquiry_modal ? '❌' : '✓') . " | 
                        Footer Include: " . ($has_footer_include ? '❌' : '✓') . " | 
                        Footer Tag: " . ($has_footer_tag ? '✓' : '❌') . "
                    </div>
                </div>
                <div class='mt-2'>
                    <a href='continents/$continent/' target='_blank' class='text-blue-600 hover:underline text-sm'>Test Page →</a>
                </div>
            </div>";
        }
    }
    ?>
    
    <div class="mt-8 p-4 bg-blue-50 rounded-lg">
        <p class="font-semibold">All checks should show ✓ for a working page</p>
        <p class="text-sm text-gray-600 mt-2">Click "Test Page" links to verify pages load without errors</p>
    </div>
</div>
</body>
</html>
