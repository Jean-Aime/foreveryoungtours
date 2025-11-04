<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Test - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-gray-900">🧪 Navigation Test Page</h1>
        
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Current Server Info</h2>
            <div class="space-y-2 text-sm">
                <p><strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?></p>
                <p><strong>Script Name:</strong> <?= $_SERVER['SCRIPT_NAME'] ?></p>
                <p><strong>Request URI:</strong> <?= $_SERVER['REQUEST_URI'] ?></p>
                <p><strong>Server Name:</strong> <?= $_SERVER['SERVER_NAME'] ?></p>
                <p><strong>Base Path:</strong> /foreveryoungtours/</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">🏠 Main Pages</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="/foreveryoungtours/index.php" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                    <div class="font-semibold">Homepage</div>
                    <div class="text-sm text-gray-600">/foreveryoungtours/index.php</div>
                </a>
                <a href="/foreveryoungtours/pages/destinations.php" class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                    <div class="font-semibold">Destinations</div>
                    <div class="text-sm text-gray-600">/foreveryoungtours/pages/destinations.php</div>
                </a>
                <a href="/foreveryoungtours/pages/packages.php" class="block p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                    <div class="font-semibold">Packages</div>
                    <div class="text-sm text-gray-600">/foreveryoungtours/pages/packages.php</div>
                </a>
                <a href="/foreveryoungtours/pages/blog.php" class="block p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition">
                    <div class="font-semibold">Blog</div>
                    <div class="text-sm text-gray-600">/foreveryoungtours/pages/blog.php</div>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">🔐 Authentication</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="/foreveryoungtours/auth/login.php" class="block p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                    <div class="font-semibold">Login</div>
                    <div class="text-sm text-gray-600">/foreveryoungtours/auth/login.php</div>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">👑 Admin Pages</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="/foreveryoungtours/admin/index.php" class="block p-4 bg-red-50 hover:bg-red-100 rounded-lg transition">
                    <div class="font-semibold">Admin Dashboard</div>
                    <div class="text-sm text-gray-600">/foreveryoungtours/admin/index.php</div>
                </a>
                <a href="/foreveryoungtours/admin/bookings.php" class="block p-4 bg-red-50 hover:bg-red-100 rounded-lg transition">
                    <div class="font-semibold">Bookings</div>
                    <div class="text-sm text-gray-600">/foreveryoungtours/admin/bookings.php</div>
                </a>
                <a href="/foreveryoungtours/admin/users.php" class="block p-4 bg-red-50 hover:bg-red-100 rounded-lg transition">
                    <div class="font-semibold">Users</div>
                    <div class="text-sm text-gray-600">/foreveryoungtours/admin/users.php</div>
                </a>
                <a href="/foreveryoungtours/admin/tours.php" class="block p-4 bg-red-50 hover:bg-red-100 rounded-lg transition">
                    <div class="font-semibold">Tours</div>
                    <div class="text-sm text-gray-600">/foreveryoungtours/admin/tours.php</div>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">📁 File Existence Check</h2>
            <div class="space-y-2 text-sm">
                <?php
                $files_to_check = [
                    'index.php' => 'Homepage',
                    'pages/destinations.php' => 'Destinations Page',
                    'pages/packages.php' => 'Packages Page',
                    'pages/blog.php' => 'Blog Page',
                    'auth/login.php' => 'Login Page',
                    'admin/index.php' => 'Admin Dashboard',
                    'admin/bookings.php' => 'Admin Bookings',
                    'admin/users.php' => 'Admin Users',
                    'admin/tours.php' => 'Admin Tours',
                ];

                foreach ($files_to_check as $file => $name) {
                    $exists = file_exists(__DIR__ . '/' . $file);
                    $color = $exists ? 'text-green-600' : 'text-red-600';
                    $icon = $exists ? '✅' : '❌';
                    echo "<p class='$color'>$icon <strong>$name:</strong> $file " . ($exists ? '(EXISTS)' : '(NOT FOUND)') . "</p>";
                }
                ?>
            </div>
        </div>

        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
            <h3 class="font-semibold mb-2">💡 Troubleshooting Tips:</h3>
            <ul class="list-disc list-inside space-y-1 text-sm">
                <li>Make sure Apache is running in XAMPP Control Panel</li>
                <li>Make sure MySQL is running in XAMPP Control Panel</li>
                <li>Access the site using: <code class="bg-white px-2 py-1 rounded">http://localhost/foreveryoungtours/</code></li>
                <li>Check that your htdocs folder is: <code class="bg-white px-2 py-1 rounded">c:\xampp1\htdocs\foreveryoungtours</code></li>
                <li>If you see 404 errors, check the Apache error log in XAMPP</li>
            </ul>
        </div>
    </div>
</body>
</html>

