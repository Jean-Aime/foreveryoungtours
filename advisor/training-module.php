<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$module_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$module_id) {
    header('Location: training-center.php');
    exit();
}

if ($_POST && isset($_POST['action'])) {
    if ($_POST['action'] == 'start') {
        $stmt = $pdo->prepare("INSERT INTO training_progress (user_id, module_id, status, started_at) 
                              VALUES (?, ?, 'in_progress', NOW()) 
                              ON DUPLICATE KEY UPDATE status = 'in_progress', started_at = NOW()");
        $stmt->execute([$user_id, $module_id]);
    } elseif ($_POST['action'] == 'complete') {
        $stmt = $pdo->prepare("UPDATE training_progress SET status = 'completed', progress_percentage = 100, completed_at = NOW() 
                              WHERE user_id = ? AND module_id = ?");
        $stmt->execute([$user_id, $module_id]);
    }
    header("Location: training-module.php?id=$module_id");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM training_modules WHERE id = ?");
$stmt->execute([$module_id]);
$module = $stmt->fetch();

if (!$module) {
    header('Location: training-center.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM training_progress WHERE user_id = ? AND module_id = ?");
$stmt->execute([$user_id, $module_id]);
$progress = $stmt->fetch();

require_once 'includes/advisor-header.php';
?>

<main class="flex-1 min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="training-center.php" class="text-primary-gold hover:text-yellow-600 mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Training Center
                </a>
                <h1 class="text-3xl font-bold text-slate-900 mb-2"><?= htmlspecialchars($module['title']) ?></h1>
                <div class="flex items-center space-x-4 text-sm text-slate-600">
                    <span class="px-2 py-1 rounded-full 
                        <?= $module['difficulty'] == 'beginner' ? 'bg-green-100 text-green-800' : 
                           ($module['difficulty'] == 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                        <?= ucfirst($module['difficulty']) ?>
                    </span>
                    <?php if ($module['duration_minutes']): ?>
                    <span><i class="fas fa-clock mr-1"></i><?= $module['duration_minutes'] ?> minutes</span>
                    <?php endif; ?>
                    <span><i class="fas fa-folder mr-1"></i><?= ucwords($module['category']) ?></span>
                </div>
            </div>
            
            <?php if ($progress && $progress['status'] == 'completed'): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i>You completed this module on <?= date('M j, Y', strtotime($progress['completed_at'])) ?>
            </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Description</h2>
                <p class="text-slate-700"><?= nl2br(htmlspecialchars($module['description'])) ?></p>
            </div>
            
            <?php if ($module['video_url']): ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Video Content</h2>
                <div class="aspect-video bg-slate-100 rounded-lg flex items-center justify-center">
                    <iframe src="<?= htmlspecialchars($module['video_url']) ?>" 
                            class="w-full h-full rounded-lg" 
                            frameborder="0" 
                            allowfullscreen></iframe>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Content</h2>
                <div class="prose max-w-none text-slate-700">
                    <?= nl2br(htmlspecialchars($module['content'])) ?>
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <?php if (!$progress || $progress['status'] == 'not_started'): ?>
                <form method="POST" class="w-full">
                    <input type="hidden" name="action" value="start">
                    <button type="submit" class="w-full px-6 py-3 bg-primary-gold text-white rounded-lg hover:bg-yellow-600 text-lg font-semibold">
                        <i class="fas fa-play mr-2"></i>Start Module
                    </button>
                </form>
                <?php elseif ($progress['status'] == 'in_progress'): ?>
                <form method="POST" class="w-full">
                    <input type="hidden" name="action" value="complete">
                    <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 text-lg font-semibold">
                        <i class="fas fa-check mr-2"></i>Mark as Complete
                    </button>
                </form>
                <?php else: ?>
                <a href="training-center.php" class="w-full block text-center px-6 py-3 bg-primary-gold text-white rounded-lg hover:bg-yellow-600 text-lg font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Training Center
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/advisor-footer.php'; ?>
