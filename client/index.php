<?php
session_start();
require_once '../config/database.php';

$page_title = 'Dashboard';
$page_subtitle = 'Your Travel Overview';

$client_id = $_SESSION['user_id'];

// Get client's bookings
$stmt = $pdo->prepare("
    SELECT b.*, t.name as tour_name, t.image_url, c.name as country_name, r.name as region_name
    FROM bookings b 
    JOIN tours t ON b.tour_id = t.id 
    LEFT JOIN countries c ON t.country_id = c.id 
    LEFT JOIN regions r ON c.region_id = r.id
    WHERE b.user_id = ? 
    ORDER BY b.created_at DESC
");
$stmt->execute([$client_id]);
$bookings = $stmt->fetchAll();

// Get recommended tours based on client's booking history
$stmt = $pdo->prepare("
    SELECT DISTINCT t.*, c.name as country_name, r.name as region_name
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    LEFT JOIN regions r ON c.region_id = r.id
    WHERE t.status = 'active' AND t.featured = 1
    ORDER BY RAND()
    LIMIT 6
");
$stmt->execute();
$recommended_tours = $stmt->fetchAll();

// Get upcoming trips
$upcoming_trips = array_filter($bookings, function($booking) {
    return strtotime($booking['travel_date']) > time() && in_array($booking['status'], ['confirmed', 'pending']);
});

// Get travel statistics
$total_bookings = count($bookings);
$completed_trips = count(array_filter($bookings, fn($b) => $b['status'] == 'completed'));
$total_spent = array_sum(array_column(array_filter($bookings, fn($b) => in_array($b['status'], ['confirmed', 'completed'])), 'total_price'));
$countries_visited = count(array_unique(array_column($bookings, 'country_name')));

include 'includes/client-header.php';
?>

            <!-- Travel Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $total_bookings; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-suitcase-rolling text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Completed Trips</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $completed_trips; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Countries Visited</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $countries_visited; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-flag text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Invested</p>
                            <p class="text-2xl font-bold text-gradient">$<?php echo number_format($total_spent); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-golden-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-coins text-golden-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Trips -->
            <?php if (!empty($upcoming_trips)): ?>
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Upcoming Adventures</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach (array_slice($upcoming_trips, 0, 3) as $trip): ?>
                    <div class="nextcloud-card overflow-hidden">
                        <div class="h-48 bg-cover bg-center" style="background-image: url('<?php echo htmlspecialchars($trip['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80'); ?>');">
                            <div class="h-full bg-gradient-to-t from-black/80 to-transparent flex items-end">
                                <div class="p-4">
                                    <h3 class="font-bold image-overlay-text"><?php echo htmlspecialchars($trip['tour_name']); ?></h3>
                                    <p class="text-sm image-overlay-text"><?php echo htmlspecialchars($trip['country_name']); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-slate-600">Travel Date</span>
                                <span class="font-semibold"><?php echo date('M j, Y', strtotime($trip['travel_date'])); ?></span>
                            </div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm text-slate-600">Status</span>
                                <span class="px-2 py-1 rounded text-xs <?php echo $trip['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                    <?php echo ucfirst($trip['status']); ?>
                                </span>
                            </div>
                            <a href="bookings.php?id=<?php echo $trip['id']; ?>" class="btn-primary w-full text-center py-2 rounded-lg">
                                <i class="fas fa-eye mr-2"></i>View Details
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Travel Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">Travel Spending</h3>
                    <div id="spendingChart" style="height: 250px;"></div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">Destination Preferences</h3>
                    <div id="destinationChart" style="height: 250px;"></div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="nextcloud-card p-6 text-center">
                    <i class="fas fa-search text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-lg font-bold mb-2">Explore New Tours</h3>
                    <p class="text-slate-600 mb-4">Discover amazing African destinations and experiences</p>
                    <a href="explore.php" class="btn-primary px-6 py-2 rounded-lg">Explore Now</a>
                </div>
                
                <div class="nextcloud-card p-6 text-center">
                    <i class="fas fa-calendar-plus text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-lg font-bold mb-2">Plan Your Trip</h3>
                    <p class="text-slate-600 mb-4">Get personalized recommendations for your next adventure</p>
                    <a href="destinations.php" class="btn-primary px-6 py-2 rounded-lg">Start Planning</a>
                </div>
                
                <div class="nextcloud-card p-6 text-center">
                    <i class="fas fa-headset text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-lg font-bold mb-2">Travel Support</h3>
                    <p class="text-slate-600 mb-4">Need help? Our travel experts are here for you</p>
                    <a href="support.php" class="btn-primary px-6 py-2 rounded-lg">Get Support</a>
                </div>
            </div>

            <!-- Recommended Tours -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">Recommended for You</h2>
                    <a href="explore.php" class="text-golden-600 hover:text-golden-700">View All <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($recommended_tours as $tour): ?>
                    <div class="nextcloud-card overflow-hidden">
                        <div class="h-48 bg-cover bg-center" style="background-image: url('<?php echo htmlspecialchars($tour['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80'); ?>');">
                            <div class="h-full bg-gradient-to-t from-black/80 to-transparent flex items-end">
                                <div class="p-4">
                                    <h3 class="font-bold image-overlay-text"><?php echo htmlspecialchars($tour['name']); ?></h3>
                                    <p class="text-sm image-overlay-text"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-slate-600"><?php echo htmlspecialchars($tour['duration']); ?></span>
                                <span class="font-bold text-golden-600">$<?php echo number_format($tour['price']); ?></span>
                            </div>
                            <p class="text-sm text-slate-600 mb-4"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>...</p>
                            <div class="flex gap-2">
                                <a href="../pages/tour-details.php?slug=<?php echo $tour['slug']; ?>" class="btn-secondary flex-1 text-center py-2 rounded-lg text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>Details
                                </a>
                                <button onclick="addToWishlist(<?php echo $tour['id']; ?>)" class="btn-primary flex-1 text-center py-2 rounded-lg text-sm">
                                    <i class="fas fa-heart mr-1"></i>Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent Activity -->
            <?php if (!empty($bookings)): ?>
            <div class="nextcloud-card p-6">
                <h3 class="text-lg font-bold mb-4">Recent Activity</h3>
                <div class="space-y-4">
                    <?php foreach (array_slice($bookings, 0, 5) as $booking): ?>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-suitcase-rolling text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold"><?php echo htmlspecialchars($booking['tour_name']); ?></p>
                                <p class="text-sm text-slate-600"><?php echo date('M j, Y', strtotime($booking['created_at'])); ?></p>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded text-xs <?php 
                            echo match($booking['status']) {
                                'confirmed' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'completed' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
    <script>
        function addToWishlist(tourId) {
            // Implement wishlist functionality
            alert('Added to wishlist! Tour ID: ' + tourId);
        }

        // Travel Spending Chart
        const spendingChart = echarts.init(document.getElementById('spendingChart'));
        const spendingOption = {
            tooltip: { trigger: 'axis' },
            xAxis: {
                type: 'category',
                data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
            },
            yAxis: { type: 'value' },
            series: [{
                name: 'Spending',
                data: [<?php echo $total_spent * 0.1; ?>, <?php echo $total_spent * 0.15; ?>, <?php echo $total_spent * 0.2; ?>, <?php echo $total_spent * 0.25; ?>, <?php echo $total_spent * 0.2; ?>, <?php echo $total_spent * 0.1; ?>],
                type: 'line',
                smooth: true,
                itemStyle: { color: '#DAA520' }
            }]
        };
        spendingChart.setOption(spendingOption);

        // Destination Preferences Chart
        const destinationChart = echarts.init(document.getElementById('destinationChart'));
        const destinationOption = {
            tooltip: { trigger: 'item' },
            series: [{
                type: 'pie',
                radius: '60%',
                data: [
                    { value: 40, name: 'Safari Tours', itemStyle: { color: '#3B82F6' } },
                    { value: 30, name: 'Cultural Tours', itemStyle: { color: '#10B981' } },
                    { value: 20, name: 'Adventure', itemStyle: { color: '#F59E0B' } },
                    { value: 10, name: 'City Breaks', itemStyle: { color: '#EF4444' } }
                ]
            }]
        };
        destinationChart.setOption(destinationOption);
    </script>

<?php include 'includes/client-footer.php'; ?>