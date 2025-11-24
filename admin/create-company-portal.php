<?php
$current_page = 'create-company-portal';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$message = '';
$page_title = 'Create Company Portal';
$page_subtitle = 'Create portal for company social media leads';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_portal') {
    // Generate company portal code (CO prefix)
    $initials = 'CO';
    $year = date('Y');
    $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    $portalCode = $initials . '-' . $year . '-' . $random;
    
    // Ensure uniqueness
    $stmt = $pdo->prepare("SELECT id FROM client_registry WHERE portal_code = ?");
    $stmt->execute([$portalCode]);
    while ($stmt->fetch()) {
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $portalCode = $initials . '-' . $year . '-' . $random;
        $stmt->execute([$portalCode]);
    }
    
    // Create portal URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $baseDir = str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'])));
    $portalUrl = $protocol . '://' . $host . $baseDir . '/portal.php?code=' . $portalCode;
    
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    // Insert as company-owned portal
    $stmt = $pdo->prepare("
        INSERT INTO client_registry (
            client_name, client_email, client_phone, client_country, client_interest,
            portal_code, portal_url,
            owned_by_user_id, owned_by_name, owned_by_role,
            source, expires_at, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        $_POST['client_name'],
        $_POST['client_email'],
        $_POST['client_phone'],
        $_POST['client_country'] ?? null,
        $_POST['client_interest'] ?? null,
        $portalCode,
        $portalUrl,
        $_SESSION['user_id'],
        'Forever Young Tours (Company)',
        'admin',
        $_POST['source'] ?? 'instagram',
        $expiresAt,
        $_SESSION['user_id']
    ]);
    
    if ($result) {
        // Add selected tours
        if (!empty($_POST['tour_ids'])) {
            $stmt = $pdo->prepare("INSERT INTO portal_tours (portal_code, tour_id, display_order) VALUES (?, ?, ?)");
            $order = 1;
            foreach ($_POST['tour_ids'] as $tourId) {
                $stmt->execute([$portalCode, $tourId, $order++]);
            }
        }
        
        header('Location: company-portals.php?created=' . $portalCode);
        exit;
    }
}

// Get active tours
$stmt = $pdo->query("SELECT id, name, destination, price FROM tours WHERE status = 'active' ORDER BY name");
$tours = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-4xl mx-auto">
            
            <?php if ($message): ?>
                <?= $message ?>
                <div class="mb-6"></div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-lg p-8">
                
                <div class="mb-6 bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-purple-900 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Company Lead Portal
                    </h3>
                    <p class="text-purple-700">This portal is owned by the company. You can assign it to an advisor later.</p>
                </div>

                <form method="POST">
                    <input type="hidden" name="action" value="create_portal">
                    
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <span class="bg-purple-100 text-purple-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">1</span>
                        Client Information
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Client Name *</label>
                            <input type="text" name="client_name" required
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Client Email *</label>
                            <input type="email" name="client_email" required
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Client Phone *</label>
                            <input type="text" name="client_phone" required
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                                   placeholder="+250788712679">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Country</label>
                            <input type="text" name="client_country"
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Client Interest</label>
                        <textarea name="client_interest" rows="2"
                                  class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                                  placeholder="e.g., Rwanda Gorilla Trekking, 2 people, March 2024"></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Lead Source</label>
                        <select name="source" class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                            <option value="instagram">Instagram</option>
                            <option value="facebook">Facebook</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="website">Website</option>
                            <option value="email">Email</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <button type="button" onclick="document.getElementById('tourSection').classList.toggle('hidden')" 
                                class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-4 rounded-lg hover:from-purple-600 hover:to-pink-600 font-bold text-lg transition shadow-lg flex items-center justify-center">
                            <i class="fas fa-map-marked-alt mr-3 text-xl"></i>
                            Select Tours for Client
                            <i class="fas fa-chevron-down ml-3"></i>
                        </button>
                        
                        <div id="tourSection" class="hidden mt-4 bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-300 rounded-xl p-6">
                            <input type="text" id="tourSearch" placeholder="🔍 Search tours by name or destination..."
                                   class="w-full border-2 border-purple-400 rounded-lg px-4 py-3 mb-4 text-slate-900 font-semibold focus:border-purple-600 focus:ring-2 focus:ring-purple-300 transition bg-white shadow-sm">
                            <div class="border-2 border-purple-300 rounded-lg p-4 max-h-80 overflow-y-auto bg-white shadow-inner" id="tourList">
                                <?php foreach ($tours as $tour): ?>
                                <label class="tour-item flex items-center mb-3 hover:bg-purple-50 p-4 rounded-lg cursor-pointer transition border-2 border-transparent hover:border-purple-300" 
                                       data-name="<?= strtolower(htmlspecialchars($tour['name'])) ?>" 
                                       data-destination="<?= strtolower(htmlspecialchars($tour['destination'])) ?>">
                                    <input type="checkbox" name="tour_ids[]" value="<?= $tour['id'] ?>"
                                           class="w-6 h-6 text-purple-600 border-2 border-slate-400 rounded focus:ring-purple-500 mr-4">
                                    <div class="flex-1">
                                        <p class="font-bold text-slate-900 text-base"><?= htmlspecialchars($tour['name']) ?></p>
                                        <p class="text-sm text-slate-700 mt-1">
                                            <i class="fas fa-map-marker-alt text-purple-600 mr-1"></i>
                                            <?= htmlspecialchars($tour['destination']) ?> - 
                                            <span class="font-bold text-purple-700 text-base">$<?= number_format($tour['price'], 2) ?></span>
                                        </p>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                            <p class="text-sm text-slate-600 mt-3 italic">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                Select one or more tours to show in the client portal
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-lg hover:from-purple-700 hover:to-pink-700 font-bold text-lg transition shadow-lg">
                            <i class="fas fa-plus-circle mr-2"></i>Create Company Portal
                        </button>
                        <a href="company-portals.php" class="border-2 border-slate-300 px-8 py-4 rounded-lg hover:bg-slate-50 font-semibold text-slate-700 transition">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>

        </div>
    </div>
</main>

<script>
document.getElementById('tourSearch').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.tour-item');
    
    items.forEach(item => {
        const name = item.dataset.name;
        const destination = item.dataset.destination;
        
        if (name.includes(search) || destination.includes(search)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

<?php require_once 'includes/admin-footer.php'; ?>
