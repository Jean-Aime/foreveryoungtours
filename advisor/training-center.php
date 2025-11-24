<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$user_id = $_SESSION['user_id'];

$stmt = $pdo->query("SELECT tm.*, 
                     COALESCE(tp.status, 'not_started') as user_status,
                     COALESCE(tp.progress_percentage, 0) as user_progress
                     FROM training_modules tm
                     LEFT JOIN training_progress tp ON tm.id = tp.module_id AND tp.user_id = $user_id
                     WHERE tm.is_published = 1
                     ORDER BY tm.category, tm.order_index");
$modules = $stmt->fetchAll();

$modules_by_category = [];
foreach ($modules as $module) {
    $modules_by_category[$module['category']][] = $module;
}

$stmt = $pdo->prepare("SELECT COUNT(*) as total, 
                       SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                       FROM training_progress WHERE user_id = ?");
$stmt->execute([$user_id]);
$progress_stats = $stmt->fetch();

require_once 'includes/advisor-header.php';
?>

<main class="flex-1 min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Training Center</h1>
                <p class="text-slate-600">Enhance your skills and knowledge</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Total Modules</p>
                            <p class="text-2xl font-bold text-slate-900"><?= count($modules) ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Completed</p>
                            <p class="text-2xl font-bold text-slate-900"><?= $progress_stats['completed'] ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Completion Rate</p>
                            <p class="text-2xl font-bold text-slate-900">
                                <?= count($modules) > 0 ? round(($progress_stats['completed'] / count($modules)) * 100) : 0 ?>%
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-yellow-600"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php foreach ($modules_by_category as $category => $category_modules): ?>
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-4"><?= ucwords($category) ?></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($category_modules as $module): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2"><?= htmlspecialchars($module['title']) ?></h3>
                                    <p class="text-sm text-slate-600 mb-4"><?= htmlspecialchars($module['description']) ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?= $module['difficulty'] == 'beginner' ? 'bg-green-100 text-green-800' : 
                                       ($module['difficulty'] == 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                    <?= ucfirst($module['difficulty']) ?>
                                </span>
                                <?php if ($module['duration_minutes']): ?>
                                <span class="text-sm text-slate-500">
                                    <i class="fas fa-clock mr-1"></i><?= $module['duration_minutes'] ?> min
                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-slate-600 mb-1">
                                    <span>Progress</span>
                                    <span><?= $module['user_progress'] ?>%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-primary-gold h-2 rounded-full" style="width: <?= $module['user_progress'] ?>%"></div>
                                </div>
                            </div>
                            
                            <a href="training-module.php?id=<?= $module['id'] ?>" 
                               class="block w-full text-center px-4 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600 transition-colors">
                                <?= $module['user_status'] == 'completed' ? 'Review' : ($module['user_status'] == 'in_progress' ? 'Continue' : 'Start') ?>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php require_once 'includes/advisor-footer.php'; ?>
