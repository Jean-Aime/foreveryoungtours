<?php
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Get MCA's assigned advisors and their training status
$stmt = $conn->prepare("
    SELECT u.*, ts.overall_status as training_status, ts.progress_percentage, ts.last_updated as training_last_updated,
           ks.overall_status as kyc_status, ks.last_updated as kyc_last_updated
    FROM users u 
    LEFT JOIN training_progress ts ON u.id = ts.user_id 
    LEFT JOIN kyc_status ks ON u.id = ks.user_id 
    WHERE u.role = 'advisor' AND u.mca_id = ? 
    ORDER BY u.created_at DESC
");
$stmt->execute([1]); // Demo MCA ID
$advisors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get training modules
$stmt = $conn->prepare("SELECT * FROM training_modules WHERE status = 'active' ORDER BY order_sequence");
$stmt->execute();
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor Training - MCA Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gradient">MCA Dashboard</h2>
                <p class="text-slate-600">Training Center</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">Dashboard</a>
                <a href="advisors.php" class="nav-item block px-6 py-3">My Advisors</a>
                <a href="training.php" class="nav-item active block px-6 py-3">Training Center</a>
                <a href="kyc-management.php" class="nav-item block px-6 py-3">KYC Management</a>
                <a href="tours.php" class="nav-item block px-6 py-3">Tours</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gradient">Advisor Training Center</h1>
                <button onclick="openAssignTrainingModal()" class="btn-primary px-6 py-3 rounded-lg">Assign Training</button>
            </div>
            
            <!-- Training Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Total Advisors</h3>
                    <p class="text-3xl font-bold text-golden-600"><?php echo count($advisors); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Training Complete</h3>
                    <p class="text-3xl font-bold text-green-600"><?php echo count(array_filter($advisors, fn($a) => $a['training_status'] === 'completed')); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">In Progress</h3>
                    <p class="text-3xl font-bold text-blue-600"><?php echo count(array_filter($advisors, fn($a) => $a['training_status'] === 'in_progress')); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">KYC Approved</h3>
                    <p class="text-3xl font-bold text-emerald-600"><?php echo count(array_filter($advisors, fn($a) => $a['kyc_status'] === 'approved')); ?></p>
                </div>
            </div>

            <!-- Training Modules -->
            <div class="nextcloud-card p-6 mb-8">
                <h2 class="text-xl font-bold mb-6">Available Training Modules</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($modules as $module): ?>
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-lg"><?php echo htmlspecialchars($module['title']); ?></h3>
                            <?php if ($module['is_mandatory']): ?>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Mandatory</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-slate-600 text-sm mb-3"><?php echo htmlspecialchars($module['description']); ?></p>
                        <div class="flex justify-between items-center text-sm text-slate-500 mb-4">
                            <span><?php echo $module['duration_minutes']; ?> minutes</span>
                            <span class="capitalize"><?php echo str_replace('_', ' ', $module['difficulty_level']); ?></span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="previewModule(<?php echo $module['id']; ?>)" class="flex-1 bg-slate-200 text-slate-700 py-2 rounded text-sm hover:bg-slate-300">Preview</button>
                            <button onclick="assignToAdvisors(<?php echo $module['id']; ?>)" class="flex-1 btn-primary py-2 rounded text-sm">Assign</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Advisor Training Progress -->
            <div class="nextcloud-card p-6">
                <h2 class="text-xl font-bold mb-6">Advisor Training Progress</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4">Advisor</th>
                                <th class="text-left py-3 px-4">Training Status</th>
                                <th class="text-left py-3 px-4">Progress</th>
                                <th class="text-left py-3 px-4">KYC Status</th>
                                <th class="text-left py-3 px-4">Can Sell</th>
                                <th class="text-left py-3 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($advisors as $advisor): ?>
                            <tr class="border-b hover:bg-slate-50">
                                <td class="py-3 px-4">
                                    <div>
                                        <div class="font-medium"><?php echo htmlspecialchars($advisor['name']); ?></div>
                                        <div class="text-sm text-slate-500"><?php echo htmlspecialchars($advisor['email']); ?></div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <?php
                                    $status = $advisor['training_status'] ?: 'not_started';
                                    $statusColors = [
                                        'not_started' => 'bg-gray-100 text-gray-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'expired' => 'bg-red-100 text-red-800'
                                    ];
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $statusColors[$status]; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo $advisor['progress_percentage'] ?: 0; ?>%"></div>
                                    </div>
                                    <span class="text-xs text-slate-500"><?php echo $advisor['progress_percentage'] ?: 0; ?>%</span>
                                </td>
                                <td class="py-3 px-4">
                                    <?php
                                    $kycStatus = $advisor['kyc_status'] ?: 'not_started';
                                    $kycColors = [
                                        'not_started' => 'bg-gray-100 text-gray-800',
                                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                                        'pending_review' => 'bg-orange-100 text-orange-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800'
                                    ];
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $kycColors[$kycStatus]; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $kycStatus)); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?php $canSell = ($advisor['training_status'] === 'completed' && $advisor['kyc_status'] === 'approved'); ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $canSell ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $canSell ? 'Yes' : 'No'; ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <button onclick="viewProgress(<?php echo $advisor['id']; ?>)" class="text-blue-600 hover:text-blue-800 text-sm">View</button>
                                        <button onclick="assignTraining(<?php echo $advisor['id']; ?>)" class="text-golden-600 hover:text-golden-800 text-sm">Assign</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Training Modal -->
    <div id="assignTrainingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Assign Training Module</h3>
            </div>
            <form class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Select Advisors</label>
                        <div class="max-h-32 overflow-y-auto border rounded-lg p-2">
                            <?php foreach ($advisors as $advisor): ?>
                            <label class="flex items-center mb-2">
                                <input type="checkbox" name="advisors[]" value="<?php echo $advisor['id']; ?>" class="mr-2">
                                <span class="text-sm"><?php echo htmlspecialchars($advisor['name']); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Training Module</label>
                        <select required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Module</option>
                            <?php foreach ($modules as $module): ?>
                            <option value="<?php echo $module['id']; ?>"><?php echo htmlspecialchars($module['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Due Date</label>
                        <input type="date" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Priority</label>
                        <select class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeAssignTrainingModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Assign Training</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignTrainingModal() { document.getElementById('assignTrainingModal').classList.remove('hidden'); }
        function closeAssignTrainingModal() { document.getElementById('assignTrainingModal').classList.add('hidden'); }
        function previewModule(id) { window.open('training-module.php?id=' + id, '_blank'); }
        function assignToAdvisors(moduleId) { 
            openAssignTrainingModal();
            document.querySelector('select[name="module"]').value = moduleId;
        }
        function viewProgress(advisorId) { window.location.href = 'advisor-progress.php?id=' + advisorId; }
        function assignTraining(advisorId) { 
            openAssignTrainingModal();
            document.querySelector('input[value="' + advisorId + '"]').checked = true;
        }
    </script>
</body>
</html>