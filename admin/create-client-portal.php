<?php
require_once '../config/database.php';
require_once '../includes/client-portal-functions.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';
$existingClient = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'check_client') {
        // Check if client exists
        $existingClient = checkExistingClient($pdo, $_POST['email'], $_POST['phone']);
        
        if ($existingClient) {
            $message = '<div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">
                <strong>⚠️ CLIENT ALREADY EXISTS!</strong><br>
                This client belongs to: <strong>' . htmlspecialchars($existingClient['owned_by_name']) . '</strong>
            </div>';
        } else {
            $message = '<div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
                ✅ Client not found. You can create a new portal.
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
            'owner_role' => 'admin',
            'source' => $_POST['source'] ?? 'direct',
            'created_by' => $_SESSION['user_id']
        ];
        
        $result = createClientPortal($pdo, $data);
        
        if ($result['success']) {
            // Add selected tours
            if (!empty($_POST['tour_ids'])) {
                addToursToPortal($pdo, $result['portal_code'], $_POST['tour_ids']);
            }
            
            // Send portal link
            sendPortalLink(
                $result['portal_code'],
                $data['email'],
                $data['phone'],
                $data['name'],
                $data['owner_name']
            );
            
            header('Location: manage-portals.php?created=' . $result['portal_code']);
            exit;
        } elseif ($result['error'] === 'CLIENT_EXISTS') {
            $existingClient = $result['existing_client'];
            $message = '<div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded">
                <strong>❌ CANNOT CREATE PORTAL</strong><br>
                This client already belongs to: <strong>' . htmlspecialchars($existingClient['owned_by_name']) . '</strong>
            </div>';
        }
    }
}

// Get all tours for selection
$stmt = $pdo->query("SELECT id, name, destination, price FROM tours WHERE status = 'active' ORDER BY name");
$tours = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Create Client Portal</h1>
                <p class="text-slate-600">Create personalized portal for social media leads</p>
            </div>

            <?php if ($message): ?>
                <?= $message ?>
                <div class="mb-6"></div>
            <?php endif; ?>

            <?php if ($existingClient): ?>
            <!-- Existing Client Warning -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-4 border-red-500">
                <h2 class="text-xl font-bold text-red-600 mb-4">⚠️ CLIENT OWNERSHIP PROTECTED</h2>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-slate-600">Client Name</p>
                        <p class="font-semibold"><?= htmlspecialchars($existingClient['client_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Email</p>
                        <p class="font-semibold"><?= htmlspecialchars($existingClient['client_email']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Phone</p>
                        <p class="font-semibold"><?= htmlspecialchars($existingClient['client_phone']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Portal Code</p>
                        <p class="font-semibold"><?= htmlspecialchars($existingClient['portal_code']) ?></p>
                    </div>
                </div>

                <div class="bg-red-50 p-4 rounded mb-4">
                    <p class="font-bold text-red-800 mb-2">Owned By:</p>
                    <p class="text-lg font-bold"><?= htmlspecialchars($existingClient['owned_by_name']) ?></p>
                    <p class="text-sm text-slate-600"><?= htmlspecialchars($existingClient['owner_email']) ?></p>
                    <p class="text-sm text-slate-600"><?= htmlspecialchars($existingClient['owner_phone']) ?></p>
                </div>

                <div class="flex gap-4">
                    <a href="manage-portals.php?view=<?= $existingClient['portal_code'] ?>" 
                       class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        View Portal
                    </a>
                    <a href="<?= $existingClient['portal_url'] ?>" target="_blank"
                       class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                        Open Portal Link
                    </a>
                    <button onclick="location.reload()" 
                            class="bg-slate-600 text-white px-6 py-2 rounded hover:bg-slate-700">
                        Check Another Client
                    </button>
                </div>
            </div>
            <?php else: ?>

            <!-- Create Portal Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                
                <!-- Step 1: Check Client -->
                <form method="POST" class="mb-8 pb-8 border-b">
                    <input type="hidden" name="action" value="check_client">
                    
                    <h3 class="text-lg font-bold mb-4">Step 1: Check if Client Exists</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Client Email *</label>
                            <input type="email" name="email" required
                                   class="w-full border rounded px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Client Phone *</label>
                            <input type="text" name="phone" required
                                   class="w-full border rounded px-4 py-2"
                                   placeholder="+250788712679">
                        </div>
                    </div>
                    
                    <button type="submit" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        🔍 Check Client
                    </button>
                </form>

                <!-- Step 2: Create Portal -->
                <form method="POST">
                    <input type="hidden" name="action" value="create_portal">
                    
                    <h3 class="text-lg font-bold mb-4">Step 2: Create New Portal</h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Client Name *</label>
                            <input type="text" name="client_name" required
                                   class="w-full border rounded px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Client Email *</label>
                            <input type="email" name="client_email" required
                                   class="w-full border rounded px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Client Phone *</label>
                            <input type="text" name="client_phone" required
                                   class="w-full border rounded px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Country</label>
                            <input type="text" name="client_country"
                                   class="w-full border rounded px-4 py-2">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Client Interest</label>
                        <textarea name="client_interest" rows="2"
                                  class="w-full border rounded px-4 py-2"
                                  placeholder="e.g., Rwanda Gorilla Trekking, 2 people, March 2024"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Source</label>
                        <select name="source" class="w-full border rounded px-4 py-2">
                            <option value="instagram">Instagram</option>
                            <option value="facebook">Facebook</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="website">Website</option>
                            <option value="email">Email</option>
                            <option value="phone">Phone Call</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Select Tours for Client</label>
                        <div class="border rounded p-4 max-h-64 overflow-y-auto">
                            <?php foreach ($tours as $tour): ?>
                            <label class="flex items-center mb-2 hover:bg-slate-50 p-2 rounded cursor-pointer">
                                <input type="checkbox" name="tour_ids[]" value="<?= $tour['id'] ?>"
                                       class="mr-3">
                                <div class="flex-1">
                                    <p class="font-medium"><?= htmlspecialchars($tour['name']) ?></p>
                                    <p class="text-sm text-slate-600">
                                        <?= htmlspecialchars($tour['destination']) ?> - 
                                        $<?= number_format($tour['price'], 2) ?>
                                    </p>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-primary-gold text-white px-8 py-3 rounded-lg hover:bg-yellow-600">
                            🚀 Create Portal & Send Link
                        </button>
                        <a href="manage-portals.php" class="border border-slate-300 px-8 py-3 rounded-lg hover:bg-slate-50">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>
