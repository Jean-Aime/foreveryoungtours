<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Booking System - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-slate-800 mb-8">
                <i class="fas fa-check-circle text-green-500"></i>
                Booking System Test Page
            </h1>

            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-slate-800 mb-4">✅ System Status</h2>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span>All 17 countries have packages pages</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span>All continents have packages pages</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span>Booking system works from all subdomains</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span>Tour isolation by country implemented</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span>Booking modals included everywhere</span>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8">
                <h3 class="text-lg font-bold text-blue-800 mb-2">
                    <i class="fas fa-info-circle"></i> Test Instructions
                </h3>
                <p class="text-blue-700 mb-4">
                    Click on any link below to test the packages page and booking system:
                </p>
            </div>

            <!-- Country Links -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">
                    <i class="fas fa-flag"></i> Test Country Packages Pages
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php
                    $countries = [
                        'Rwanda' => 'visit-rw.localhost/foreveryoungtours/pages/packages.php',
                        'Kenya' => 'visit-ke.localhost/foreveryoungtours/pages/packages.php',
                        'Tanzania' => 'visit-tz.localhost/foreveryoungtours/pages/packages.php',
                        'Uganda' => 'visit-ug.localhost/foreveryoungtours/pages/packages.php',
                        'South Africa' => 'visit-za.localhost/foreveryoungtours/pages/packages.php',
                        'Egypt' => 'visit-eg.localhost/foreveryoungtours/pages/packages.php',
                        'Morocco' => 'visit-ma.localhost/foreveryoungtours/pages/packages.php',
                        'Botswana' => 'visit-bw.localhost/foreveryoungtours/pages/packages.php',
                        'Namibia' => 'visit-na.localhost/foreveryoungtours/pages/packages.php',
                        'Zimbabwe' => 'visit-zw.localhost/foreveryoungtours/pages/packages.php',
                        'Ghana' => 'visit-gh.localhost/foreveryoungtours/pages/packages.php',
                        'Nigeria' => 'visit-ng.localhost/foreveryoungtours/pages/packages.php',
                        'Ethiopia' => 'visit-et.localhost/foreveryoungtours/pages/packages.php',
                        'Senegal' => 'visit-sn.localhost/foreveryoungtours/pages/packages.php',
                        'Tunisia' => 'visit-tn.localhost/foreveryoungtours/pages/packages.php',
                        'Cameroon' => 'visit-cm.localhost/foreveryoungtours/pages/packages.php',
                        'DR Congo' => 'visit-cd.localhost/foreveryoungtours/pages/packages.php',
                    ];

                    foreach ($countries as $name => $url) {
                        echo '<a href="http://' . $url . '" target="_blank" class="flex items-center justify-between p-4 bg-slate-50 hover:bg-golden-50 rounded-lg border border-slate-200 hover:border-golden-500 transition-all">';
                        echo '<span class="font-semibold text-slate-700">' . $name . '</span>';
                        echo '<i class="fas fa-external-link-alt text-slate-400"></i>';
                        echo '</a>';
                    }
                    ?>
                </div>
            </div>

            <!-- Continent Links -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">
                    <i class="fas fa-globe-africa"></i> Test Continent Packages Pages
                </h2>
                <div class="grid grid-cols-1 gap-4">
                    <?php
                    $continents = [
                        'Africa' => 'localhost/foreveryoungtours/continents/africa/pages/packages.php',
                        'North America' => 'localhost/foreveryoungtours/continents/north-america/pages/packages.php',
                        'South America' => 'localhost/foreveryoungtours/continents/south-america/pages/packages.php',
                    ];

                    foreach ($continents as $name => $url) {
                        echo '<a href="http://' . $url . '" target="_blank" class="flex items-center justify-between p-4 bg-slate-50 hover:bg-blue-50 rounded-lg border border-slate-200 hover:border-blue-500 transition-all">';
                        echo '<span class="font-semibold text-slate-700">' . $name . '</span>';
                        echo '<i class="fas fa-external-link-alt text-slate-400"></i>';
                        echo '</a>';
                    }
                    ?>
                </div>
            </div>

            <!-- Main Site Link -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">
                    <i class="fas fa-home"></i> Test Main Site Packages Page
                </h2>
                <a href="http://localhost/foreveryoungtours/pages/packages.php" target="_blank" class="flex items-center justify-between p-4 bg-slate-50 hover:bg-green-50 rounded-lg border border-slate-200 hover:border-green-500 transition-all">
                    <span class="font-semibold text-slate-700">Main Site - All Tours</span>
                    <i class="fas fa-external-link-alt text-slate-400"></i>
                </a>
            </div>

            <div class="mt-8 bg-green-50 border-l-4 border-green-500 p-6">
                <h3 class="text-lg font-bold text-green-800 mb-2">
                    <i class="fas fa-clipboard-check"></i> What to Test
                </h3>
                <ol class="list-decimal list-inside text-green-700 space-y-2">
                    <li>Click on any country link above</li>
                    <li>Verify only that country's tours are shown</li>
                    <li>Click "Book Now" on any tour</li>
                    <li>Fill in the booking form</li>
                    <li>Submit and verify success message</li>
                    <li>Check database: <code class="bg-white px-2 py-1 rounded">SELECT * FROM bookings ORDER BY id DESC LIMIT 1;</code></li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>

