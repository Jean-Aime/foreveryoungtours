<?php
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Get advisor's assigned training modules
$stmt = $conn->prepare("
    SELECT tm.*, tp.status as progress_status, tp.progress_percentage, tp.score, tp.attempts, 
           tp.started_at, tp.completed_at, ta.due_date, ta.priority
    FROM training_modules tm
    LEFT JOIN training_assignments ta ON tm.id = ta.module_id AND ta.assigned_to = ?
    LEFT JOIN training_progress tp ON tm.id = tp.module_id AND tp.user_id = ?
    WHERE tm.status = 'active' AND (ta.assigned_to = ? OR tm.is_mandatory = 1)
    ORDER BY ta.priority DESC, tm.order_sequence
");
$stmt->execute([1, 1, 1]); // Demo advisor ID
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get advisor's KYC status
$stmt = $conn->prepare("SELECT * FROM kyc_status WHERE user_id = ?");
$stmt->execute([1]);
$kyc_status = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate overall progress
$total_modules = count($modules);
$completed_modules = count(array_filter($modules, fn($m) => $m['progress_status'] === 'completed'));
$overall_progress = $total_modules > 0 ? round(($completed_modules / $total_modules) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Portal - Advisor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gradient">Advisor Portal</h2>
                <p class="text-slate-600">Training Center</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">Dashboard</a>
                <a href="training-portal.php" class="nav-item active block px-6 py-3">Training Portal</a>
                <a href="kyc-upload.php" class="nav-item block px-6 py-3">KYC Documents</a>
                <a href="tours.php" class="nav-item block px-6 py-3">Browse Tours</a>
                <a href="team.php" class="nav-item block px-6 py-3">My Team</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gradient">Training Portal</h1>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-sm text-slate-600">Overall Progress</div>
                        <div class="text-2xl font-bold text-golden-600"><?php echo $overall_progress; ?>%</div>
                    </div>
                    <div class="w-16 h-16 relative">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-200" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="text-golden-600" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="<?php echo $overall_progress; ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Total Modules</h3>
                    <p class="text-3xl font-bold text-slate-600"><?php echo $total_modules; ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Completed</h3>
                    <p class="text-3xl font-bold text-green-600"><?php echo $completed_modules; ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">In Progress</h3>
                    <p class="text-3xl font-bold text-blue-600"><?php echo count(array_filter($modules, fn($m) => $m['progress_status'] === 'in_progress')); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">KYC Status</h3>
                    <p class="text-lg font-bold <?php echo ($kyc_status && $kyc_status['overall_status'] === 'approved') ? 'text-green-600' : 'text-orange-600'; ?>">
                        <?php echo $kyc_status ? ucfirst(str_replace('_', ' ', $kyc_status['overall_status'])) : 'Not Started'; ?>
                    </p>
                </div>
            </div>

            <!-- Training Requirements Notice -->
            <?php if ($overall_progress < 100 || !$kyc_status || $kyc_status['overall_status'] !== 'approved'): ?>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-orange-800">Training & KYC Required</h3>
                        <div class="mt-2 text-sm text-orange-700">
                            <p>To start selling tours and earning commissions, you must:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Complete all mandatory training modules (<?php echo $completed_modules; ?>/<?php echo $total_modules; ?> completed)</li>
                                <li>Upload and get KYC documents approved (Status: <?php echo $kyc_status ? ucfirst(str_replace('_', ' ', $kyc_status['overall_status'])) : 'Not Started'; ?>)</li>
                            </ul>
                        </div>
                        <div class="mt-4">
                            <a href="kyc-upload.php" class="btn-primary px-4 py-2 rounded-lg text-sm">Upload KYC Documents</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Training Modules -->
            <div class="nextcloud-card p-6">
                <h2 class="text-xl font-bold mb-6">Training Modules</h2>
                <div class="space-y-4">
                    <?php foreach ($modules as $module): ?>
                    <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-bold"><?php echo htmlspecialchars($module['title']); ?></h3>
                                    <?php if ($module['is_mandatory']): ?>
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Mandatory</span>
                                    <?php endif; ?>
                                    <?php if ($module['priority'] === 'high'): ?>
                                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">High Priority</span>
                                    <?php endif; ?>
                                </div>
                                
                                <p class="text-slate-600 mb-3"><?php echo htmlspecialchars($module['description']); ?></p>
                                
                                <div class="flex items-center space-x-6 text-sm text-slate-500 mb-4">
                                    <span><?php echo $module['duration_minutes']; ?> minutes</span>
                                    <span class="capitalize"><?php echo str_replace('_', ' ', $module['difficulty_level']); ?></span>
                                    <span class="capitalize"><?php echo str_replace('_', ' ', $module['category']); ?></span>
                                    <?php if ($module['due_date']): ?>
                                    <span class="text-orange-600">Due: <?php echo date('M j, Y', strtotime($module['due_date'])); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Progress Bar -->
                                <?php if ($module['progress_status']): ?>
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm text-slate-600">Progress</span>
                                        <span class="text-sm text-slate-600"><?php echo $module['progress_percentage'] ?: 0; ?>%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo $module['progress_percentage'] ?: 0; ?>%"></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="ml-6 flex flex-col items-end space-y-2">
                                <!-- Status Badge -->
                                <?php
                                $status = $module['progress_status'] ?: 'not_started';
                                $statusColors = [
                                    'not_started' => 'bg-gray-100 text-gray-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800'
                                ];
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $statusColors[$status]; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                </span>
                                
                                <?php if ($module['score'] > 0): ?>
                                <div class="text-sm text-slate-600">Score: <?php echo $module['score']; ?>%</div>
                                <?php endif; ?>
                                
                                <?php if ($module['attempts'] > 0): ?>
                                <div class="text-xs text-slate-500">Attempts: <?php echo $module['attempts']; ?></div>
                                <?php endif; ?>
                                
                                <!-- Action Button -->
                                <?php if ($status === 'completed'): ?>
                                <button onclick="reviewModule(<?php echo $module['id']; ?>)" class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600">Review</button>
                                <?php elseif ($status === 'in_progress'): ?>
                                <button onclick="continueModule(<?php echo $module['id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">Continue</button>
                                <?php else: ?>
                                <button onclick="startModule(<?php echo $module['id']; ?>)" class="btn-primary px-4 py-2 rounded text-sm">Start Module</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function startModule(moduleId) {
            window.location.href = 'training-module.php?id=' + moduleId + '&action=start';
        }
        
        function continueModule(moduleId) {
            window.location.href = 'training-module.php?id=' + moduleId + '&action=continue';
        }
        
        function reviewModule(moduleId) {
            window.location.href = 'training-module.php?id=' + moduleId + '&action=review';
        }
    </script>
</body>
</html>