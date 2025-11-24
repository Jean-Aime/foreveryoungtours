<?php
$current_page = 'company-portals';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$page_title = 'Chat with Client';
$page_subtitle = 'Company Portal Chat';

$portal_code = $_GET['code'] ?? '';

if (empty($portal_code)) {
    header('Location: company-portals.php');
    exit;
}

// Get portal details
$stmt = $pdo->prepare("SELECT * FROM client_registry WHERE portal_code = ?");
$stmt->execute([$portal_code]);
$client = $stmt->fetch();

if (!$client) {
    header('Location: company-portals.php');
    exit;
}

// Get messages
$stmt = $pdo->prepare("SELECT * FROM portal_messages WHERE portal_code = ? ORDER BY created_at ASC");
$stmt->execute([$portal_code]);
$messages = $stmt->fetchAll();

// Mark messages as read
$pdo->prepare("UPDATE portal_messages SET is_read = 1 WHERE portal_code = ? AND sender_type = 'client'")
    ->execute([$portal_code]);

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-4xl mx-auto">

            <div class="mb-6">
                <a href="company-portals.php" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Company Portals
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-purple-50 to-pink-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900"><?= htmlspecialchars($client['client_name']) ?></h2>
                            <p class="text-slate-600"><?= htmlspecialchars($client['client_email']) ?> • <?= htmlspecialchars($client['client_phone']) ?></p>
                        </div>
                        <div class="text-right">
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                                🏢 Company Lead
                            </span>
                            <p class="text-sm text-slate-600 mt-2">Portal: <code class="bg-slate-100 px-2 py-1 rounded"><?= $portal_code ?></code></p>
                        </div>
                    </div>
                </div>

                <div id="chatMessages" class="p-6 h-96 overflow-y-auto bg-slate-50">
                    <?php if (empty($messages)): ?>
                    <p class="text-slate-500 text-center py-8">No messages yet. Start the conversation!</p>
                    <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                    <div class="mb-4 <?= $msg['sender_type'] === 'admin' ? 'text-right' : '' ?>">
                        <div class="inline-block max-w-[70%] <?= $msg['sender_type'] === 'admin' ? 'bg-purple-600 text-white' : 'bg-white border border-slate-200 text-slate-900' ?> rounded-lg px-4 py-3 shadow-sm">
                            <p class="text-sm font-semibold mb-1"><?= htmlspecialchars($msg['sender_name']) ?></p>
                            <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            <p class="text-xs opacity-75 mt-1"><?= date('M d, H:i', strtotime($msg['created_at'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="p-6 border-t border-slate-200 bg-white">
                    <div class="flex gap-3">
                        <input type="text" id="messageInput" 
                               placeholder="Type your message..."
                               class="flex-1 border-2 border-slate-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                               onkeypress="if(event.key==='Enter') sendMessage()">
                        <button onclick="sendMessage()" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all">
                            <i class="fas fa-paper-plane mr-2"></i>Send
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
const portalCode = '<?= $portal_code ?>';
const adminName = 'Forever Young Tours';

function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) {
        alert('Please enter a message');
        return;
    }
    
    fetch('../includes/portal-chat.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=send_message&portal_code=${portalCode}&message=${encodeURIComponent(message)}&sender_type=admin&sender_name=${encodeURIComponent(adminName)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadMessages();
        } else {
            alert('Failed to send message');
        }
    });
}

function loadMessages() {
    fetch(`../includes/portal-chat.php?action=get_messages&portal_code=${portalCode}`)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const container = document.getElementById('chatMessages');
            if (data.messages.length === 0) {
                container.innerHTML = '<p class="text-slate-500 text-center py-8">No messages yet. Start the conversation!</p>';
            } else {
                container.innerHTML = data.messages.map(msg => `
                    <div class="mb-4 ${msg.sender_type === 'admin' ? 'text-right' : ''}">
                        <div class="inline-block max-w-[70%] ${msg.sender_type === 'admin' ? 'bg-purple-600 text-white' : 'bg-white border border-slate-200 text-slate-900'} rounded-lg px-4 py-3 shadow-sm">
                            <p class="text-sm font-semibold mb-1">${msg.sender_name}</p>
                            <p>${msg.message.replace(/\n/g, '<br>')}</p>
                            <p class="text-xs opacity-75 mt-1">${new Date(msg.created_at).toLocaleString()}</p>
                        </div>
                    </div>
                `).join('');
                container.scrollTop = container.scrollHeight;
            }
        }
    });
}

// Auto-refresh messages every 3 seconds
setInterval(loadMessages, 3000);
</script>

<?php require_once 'includes/admin-footer.php'; ?>
