<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partners Management - Admin | iForYoungTours</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body>

<div class="min-h-screen bg-gray-50">
    <div class="flex">
        <!-- Admin Sidebar -->
        <div class="w-64 bg-white shadow-sm h-screen fixed left-0 top-0 overflow-y-auto">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gray-900">Admin Panel</h2>
                <p class="text-sm text-gray-600">Management Dashboard</p>
            </div>
            <nav class="p-6">
                <div class="space-y-2">
                    <a href="index.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Overview</a>
                    <a href="tours.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Tours & Packages</a>
                    <a href="bookings.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Bookings</a>
                    <a href="users.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Users</a>
                    <a href="partners.php" class="nav-item active flex items-center px-4 py-3 text-sm font-medium rounded-lg">Partners</a>
                    <a href="analytics.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Analytics</a>
                    <a href="../pages/dashboard.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">User Dashboard</a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64 p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Partners Management</h2>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Travel Partners</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-green-500 rounded-full mr-3"></div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Safari Adventures Ltd</h4>
                                <p class="text-sm text-gray-600">Kenya Partner</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Tours:</span>
                                <span class="font-medium">15</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Revenue:</span>
                                <span class="font-medium">$45,000</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Status:</span>
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>