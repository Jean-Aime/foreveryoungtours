<?php
session_start();
require_once '../config/database.php';

$page_title = 'Travel Guide';
$page_subtitle = 'Essential Travel Information';

include 'includes/client-header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="nextcloud-card p-6">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-passport text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Visa Requirements</h3>
        </div>
        <p class="text-slate-600 mb-4">Check visa requirements for your destination and apply in advance.</p>
        <a href="../pages/resources.php" class="text-primary-gold font-semibold">Learn More →</a>
    </div>

    <div class="nextcloud-card p-6">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-suitcase text-green-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Packing Guide</h3>
        </div>
        <p class="text-slate-600 mb-4">Essential items to pack for your African adventure.</p>
        <a href="../pages/resources.php" class="text-primary-gold font-semibold">View Guide →</a>
    </div>

    <div class="nextcloud-card p-6">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-shield-alt text-yellow-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Travel Insurance</h3>
        </div>
        <p class="text-slate-600 mb-4">Protect your trip with comprehensive travel insurance.</p>
        <a href="../pages/resources.php" class="text-primary-gold font-semibold">Get Insured →</a>
    </div>

    <div class="nextcloud-card p-6">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-heartbeat text-red-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Health & Safety</h3>
        </div>
        <p class="text-slate-600 mb-4">Vaccinations, health tips, and safety guidelines.</p>
        <a href="../pages/resources.php" class="text-primary-gold font-semibold">Read More →</a>
    </div>
</div>

<?php include 'includes/client-footer.php'; ?>
