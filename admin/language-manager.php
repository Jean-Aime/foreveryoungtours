<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/csrf.php';
checkAuth('super_admin');

$success = $error = '';

if ($_POST && isset($_POST['action'])) {
    verifyCSRF();
    try {
        if ($_POST['action'] == 'add_translation') {
            $stmt = $pdo->prepare("INSERT INTO translations (language_code, translation_key, translation_value, category) 
                                   VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE translation_value = ?");
            $stmt->execute([
                $_POST['language_code'],
                $_POST['translation_key'],
                $_POST['translation_value'],
                $_POST['category'],
                $_POST['translation_value']
            ]);
            $success = 'Translation added!';
        }
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$languages = $pdo->query("SELECT * FROM languages WHERE is_active = 1")->fetchAll();
$translations = $pdo->query("SELECT * FROM translations ORDER BY category, translation_key")->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-slate-900">Language Manager</h1>
                <button onclick="showModal()" class="bg-primary-gold text-white px-6 py-3 rounded-lg hover:bg-yellow-600">
                    <i class="fas fa-plus mr-2"></i>Add Translation
                </button>
            </div>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <?php foreach ($languages as $lang): ?>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 text-center">
                    <div class="text-2xl mb-2"><?= htmlspecialchars($lang['native_name']) ?></div>
                    <div class="text-sm text-slate-600"><?= htmlspecialchars($lang['code']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Key</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Language</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Translation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Category</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php foreach ($translations as $trans): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900"><?= htmlspecialchars($trans['translation_key']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-900"><?= htmlspecialchars($trans['language_code']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-900"><?= htmlspecialchars($trans['translation_value']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-600"><?= htmlspecialchars($trans['category']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-900">Add Translation</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="action" value="add_translation">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Language *</label>
                <select name="language_code" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    <?php foreach ($languages as $lang): ?>
                    <option value="<?= $lang['code'] ?>"><?= htmlspecialchars($lang['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Translation Key *</label>
                <input type="text" name="translation_key" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Translation Value *</label>
                <input type="text" name="translation_value" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Category</label>
                <input type="text" name="category" class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600">
                    Add Translation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
