<?php
require_once '../config/database.php';
require_once '../includes/client-portal-functions.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'advisor') {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';
$existingClient = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'check_client') {
        $existingClient = checkExistingClient($pdo, $_POST['email'], $_POST['phone']);
        
        if ($existingClient) {
            $message = '<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mr-3"></i>
                    <div>
                        <p class="font-bold text-yellow-800">CLIENT ALREADY EXISTS!</p>
                        <p class="text-yellow-700 text-sm mt-1">This client belongs to: <strong>' . htmlspecialchars($existingClient['owned_by_name']) . '</strong></p>
                    </div>
                </div>
            </div>';
        } else {
            $message = '<div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <div class="flex">
                    <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                    <div>
                        <p class="font-bold text-green-800">Client Available!</p>
                        <p class="text-green-700 text-sm mt-1">You can create a new portal for this client.</p>
                    </div>
                </div>
            </div>';
        }
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'create_portal') {
        $data = [
            'name' => $_POST['client_name'],
            'email' => $_POST['client_email'],
            'phone' => $_POST['client_phone'],
            'country' => $_POST['client_country'] ?? null,
            'interest' => $_POST['client_interest'] ?? null,
            'owner_id' => $_SESSION['user_id'],
            'owner_name' => $_SESSION['first_name'] . ' ' . $_SESSION['last_name'],
            'owner_role' => 'advisor',
            'source' => $_POST['source'] ?? 'advisor_direct',
            'created_by' => $_SESSION['user_id']
        ];
        
        $result = createClientPortal($pdo, $data);
        
        if ($result['success']) {
            if (!empty($_POST['tour_ids'])) {
                addToursToPortal($pdo, $result['portal_code'], $_POST['tour_ids']);
            }
            
            sendPortalLink(
                $result['portal_code'],
                $data['email'],
                $data['phone'],
                $data['name'],
                $data['owner_name']
            );
            
            header('Location: my-clients.php?created=' . $result['portal_code']);
            exit;
        } elseif ($result['error'] === 'CLIENT_EXISTS') {
            $existingClient = $result['existing_client'];
            $message = '<div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                <div class="flex">
                    <i class="fas fa-times-circle text-red-400 text-xl mr-3"></i>
                    <div>
                        <p class="font-bold text-red-800">CANNOT CREATE PORTAL</p>
                        <p class="text-red-700 text-sm mt-1">This client already belongs to: <strong>' . htmlspecialchars($existingClient['owned_by_name']) . '</strong></p>
                    </div>
                </div>
            </div>';
        }
    }
}

$stmt = $pdo->query("SELECT id, name, destination, price FROM tours WHERE status = 'active' ORDER BY name");
$tours = $stmt->fetchAll();

$page_title = 'Create Client Portal';
$page_subtitle = 'Lock your client and protect your commission';
require_once 'includes/advisor-header.php';
?>

        <div class="max-w-4xl mx-auto">
            
            <?php if ($message): ?>
                <?= $message ?>
                <div class="mb-6"></div>
            <?php endif; ?>

            <?php if ($existingClient): ?>
            <div class="bg-white rounded-xl shadow-lg p-8 mb-6 border-l-4 border-red-500">
                <h2 class="text-2xl font-bold text-red-600 mb-6 flex items-center">
                    <i class="fas fa-shield-alt mr-3"></i>
                    CLIENT ALREADY REGISTERED
                </h2>
                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Client Name</p>
                        <p class="text-lg font-bold text-slate-900"><?= htmlspecialchars($existingClient['client_name']) ?></p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Email</p>
                        <p class="text-lg font-bold text-slate-900"><?= htmlspecialchars($existingClient['client_email']) ?></p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Phone</p>
                        <p class="text-lg font-bold text-slate-900"><?= htmlspecialchars($existingClient['client_phone']) ?></p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Portal Code</p>
                        <p class="text-lg font-bold text-slate-900"><?= htmlspecialchars($existingClient['portal_code']) ?></p>
                    </div>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                    <p class="font-bold text-red-900 mb-2 text-lg">🔒 Owned By:</p>
                    <p class="text-2xl font-bold text-red-800"><?= htmlspecialchars($existingClient['owned_by_name']) ?></p>
                    <p class="text-slate-600 mt-1"><?= htmlspecialchars($existingClient['owner_email']) ?></p>
                </div>

                <button onclick="location.reload()" 
                        class="bg-slate-600 text-white px-8 py-3 rounded-lg hover:bg-slate-700 font-semibold">
                    Check Another Client
                </button>
            </div>
            <?php else: ?>

            <div class="bg-white rounded-xl shadow-lg p-8">
                
                <form method="POST" class="mb-8 pb-8 border-b-2 border-slate-100">
                    <input type="hidden" name="action" value="check_client">
                    
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">1</span>
                        Check if Client Exists
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Client Email *</label>
                            <input type="email" name="email" required
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Client Phone *</label>
                            <input type="text" name="phone" required
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition"
                                   placeholder="+250788712679">
                        </div>
                    </div>
                    
                    <button type="submit" class="mt-6 bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold transition">
                        <i class="fas fa-search mr-2"></i>Check Client
                    </button>
                </form>

                <form method="POST">
                    <input type="hidden" name="action" value="create_portal">
                    
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <span class="bg-yellow-100 text-yellow-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">2</span>
                        Create Portal & Lock Client
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Client Name *</label>
                            <input type="text" name="client_name" required
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Client Email *</label>
                            <input type="email" name="client_email" required
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Client Phone *</label>
                            <input type="text" name="client_phone" required
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Country</label>
                            <input type="text" name="client_country"
                                   class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Client Interest</label>
                        <textarea name="client_interest" rows="2"
                                  class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition"
                                  placeholder="e.g., Rwanda Gorilla Trekking, 2 people, March 2024"></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Source</label>
                        <select name="source" class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition">
                            <option value="advisor_direct">Direct Contact</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="facebook">Facebook</option>
                            <option value="instagram">Instagram</option>
                            <option value="referral">Referral</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <button type="button" onclick="document.getElementById('tourSection').classList.toggle('hidden')" 
                                class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-4 rounded-lg hover:from-yellow-600 hover:to-orange-600 font-bold text-lg transition shadow-lg flex items-center justify-center">
                            <i class="fas fa-map-marked-alt mr-3 text-xl"></i>
                            Select Tours for Client
                            <i class="fas fa-chevron-down ml-3"></i>
                        </button>
                        
                        <div id="tourSection" class="hidden mt-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-xl p-6">
                            <input type="text" id="tourSearch" placeholder="🔍 Search tours by name or destination..."
                                   class="w-full border-2 border-yellow-400 rounded-lg px-4 py-3 mb-4 text-slate-900 font-semibold focus:border-yellow-600 focus:ring-2 focus:ring-yellow-300 transition bg-white shadow-sm">
                            <div class="border-2 border-yellow-300 rounded-lg p-4 max-h-80 overflow-y-auto bg-white shadow-inner" id="tourList">
                                <?php foreach ($tours as $tour): ?>
                                <label class="tour-item flex items-center mb-3 hover:bg-yellow-50 p-4 rounded-lg cursor-pointer transition border-2 border-transparent hover:border-yellow-300" 
                                       data-name="<?= strtolower(htmlspecialchars($tour['name'])) ?>" 
                                       data-destination="<?= strtolower(htmlspecialchars($tour['destination'])) ?>">
                                    <input type="checkbox" name="tour_ids[]" value="<?= $tour['id'] ?>"
                                           class="w-6 h-6 text-yellow-600 border-2 border-slate-400 rounded focus:ring-yellow-500 mr-4">
                                    <div class="flex-1">
                                        <p class="font-bold text-slate-900 text-base"><?= htmlspecialchars($tour['name']) ?></p>
                                        <p class="text-sm text-slate-700 mt-1">
                                            <i class="fas fa-map-marker-alt text-yellow-600 mr-1"></i>
                                            <?= htmlspecialchars($tour['destination']) ?> - 
                                            <span class="font-bold text-yellow-700 text-base">$<?= number_format($tour['price'], 2) ?></span>
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

                    <div class="flex gap-4">
                        <button type="submit" class="bg-yellow-600 text-white px-8 py-4 rounded-lg hover:bg-yellow-700 font-bold text-lg transition shadow-lg">
                            <i class="fas fa-lock mr-2"></i>Create Portal & Lock Client
                        </button>
                        <a href="index.php" class="border-2 border-slate-300 px-8 py-4 rounded-lg hover:bg-slate-50 font-semibold text-slate-700 transition">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once 'includes/advisor-footer.php'; ?>
