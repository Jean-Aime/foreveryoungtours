<?php
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Handle module operations
if ($_POST && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add_module':
            $stmt = $conn->prepare("INSERT INTO training_modules (title, description, content, video_url, duration_minutes, difficulty_level, category, is_mandatory, order_sequence, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['title'], $_POST['description'], $_POST['content'], $_POST['video_url'], $_POST['duration_minutes'], $_POST['difficulty_level'], $_POST['category'], isset($_POST['is_mandatory']) ? 1 : 0, $_POST['order_sequence'], 1]);
            break;
        case 'edit_module':
            $stmt = $conn->prepare("UPDATE training_modules SET title = ?, description = ?, content = ?, video_url = ?, duration_minutes = ?, difficulty_level = ?, category = ?, is_mandatory = ?, order_sequence = ? WHERE id = ?");
            $stmt->execute([$_POST['title'], $_POST['description'], $_POST['content'], $_POST['video_url'], $_POST['duration_minutes'], $_POST['difficulty_level'], $_POST['category'], isset($_POST['is_mandatory']) ? 1 : 0, $_POST['order_sequence'], $_POST['module_id']]);
            break;
        case 'delete_module':
            $stmt = $conn->prepare("UPDATE training_modules SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$_POST['module_id']]);
            break;
    }
    header('Location: training-modules.php?updated=1');
    exit;
}

// Get all training modules
$stmt = $conn->prepare("SELECT * FROM training_modules WHERE status != 'inactive' ORDER BY order_sequence, created_at DESC");
$stmt->execute();
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get module for editing
$edit_module = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM training_modules WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_module = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Modules - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">Super Admin</h2>
                <p class="text-sm text-slate-600">Training Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">Dashboard</a>
                <a href="destinations.php" class="nav-item block px-6 py-3">Destinations</a>
                <a href="tours-enhanced.php" class="nav-item block px-6 py-3">Tours & Packages</a>
                <a href="training-modules.php" class="nav-item active block px-6 py-3">Training Modules</a>
                <a href="mca-management.php" class="nav-item block px-6 py-3">MCA Management</a>
                <a href="advisor-management.php" class="nav-item block px-6 py-3">Advisor Management</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gradient">Training Modules Management</h1>
                <button onclick="openModuleModal()" class="btn-primary px-6 py-3 rounded-lg">Add New Module</button>
            </div>
            
            <?php if (isset($_GET['updated'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                Training module updated successfully!
            </div>
            <?php endif; ?>

            <!-- Modules Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($modules as $module): ?>
                <div class="nextcloud-card p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-bold"><?php echo htmlspecialchars($module['title']); ?></h3>
                        <div class="flex gap-2">
                            <?php if ($module['is_mandatory']): ?>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Mandatory</span>
                            <?php endif; ?>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs"><?php echo ucfirst($module['status']); ?></span>
                        </div>
                    </div>
                    
                    <p class="text-slate-600 text-sm mb-4"><?php echo htmlspecialchars(substr($module['description'], 0, 100)) . '...'; ?></p>
                    
                    <div class="space-y-2 text-sm text-slate-500 mb-4">
                        <div class="flex justify-between">
                            <span>Duration:</span>
                            <span><?php echo $module['duration_minutes']; ?> minutes</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Level:</span>
                            <span class="capitalize"><?php echo str_replace('_', ' ', $module['difficulty_level']); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Category:</span>
                            <span class="capitalize"><?php echo str_replace('_', ' ', $module['category']); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Order:</span>
                            <span>#<?php echo $module['order_sequence']; ?></span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="editModule(<?php echo $module['id']; ?>)" class="flex-1 bg-blue-500 text-white py-2 rounded text-sm hover:bg-blue-600">Edit</button>
                        <button onclick="previewModule(<?php echo $module['id']; ?>)" class="flex-1 bg-green-500 text-white py-2 rounded text-sm hover:bg-green-600">Preview</button>
                        <button onclick="deleteModule(<?php echo $module['id']; ?>)" class="flex-1 bg-red-500 text-white py-2 rounded text-sm hover:bg-red-600">Delete</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Add/Edit Module Modal -->
    <div id="moduleModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient"><?php echo $edit_module ? 'Edit Training Module' : 'Add New Training Module'; ?></h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="<?php echo $edit_module ? 'edit_module' : 'add_module'; ?>">
                <?php if ($edit_module): ?>
                <input type="hidden" name="module_id" value="<?php echo $edit_module['id']; ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Module Title</label>
                        <input type="text" name="title" required class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_module ? htmlspecialchars($edit_module['title']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" required class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_module ? $edit_module['duration_minutes'] : ''; ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Order Sequence</label>
                        <input type="number" name="order_sequence" required class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_module ? $edit_module['order_sequence'] : ''; ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Difficulty Level</label>
                        <select name="difficulty_level" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="beginner" <?php echo ($edit_module && $edit_module['difficulty_level'] === 'beginner') ? 'selected' : ''; ?>>Beginner</option>
                            <option value="intermediate" <?php echo ($edit_module && $edit_module['difficulty_level'] === 'intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                            <option value="advanced" <?php echo ($edit_module && $edit_module['difficulty_level'] === 'advanced') ? 'selected' : ''; ?>>Advanced</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Category</label>
                        <select name="category" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="product_knowledge" <?php echo ($edit_module && $edit_module['category'] === 'product_knowledge') ? 'selected' : ''; ?>>Product Knowledge</option>
                            <option value="sales_techniques" <?php echo ($edit_module && $edit_module['category'] === 'sales_techniques') ? 'selected' : ''; ?>>Sales Techniques</option>
                            <option value="customer_service" <?php echo ($edit_module && $edit_module['category'] === 'customer_service') ? 'selected' : ''; ?>>Customer Service</option>
                            <option value="compliance" <?php echo ($edit_module && $edit_module['category'] === 'compliance') ? 'selected' : ''; ?>>Compliance</option>
                            <option value="marketing" <?php echo ($edit_module && $edit_module['category'] === 'marketing') ? 'selected' : ''; ?>>Marketing</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Video URL (Optional)</label>
                        <input type="url" name="video_url" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="https://youtube.com/watch?v=..." value="<?php echo $edit_module ? htmlspecialchars($edit_module['video_url']) : ''; ?>">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="3" required class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php echo $edit_module ? htmlspecialchars($edit_module['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Module Content</label>
                        <textarea name="content" rows="8" required class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Detailed module content, learning objectives, key points..."><?php echo $edit_module ? htmlspecialchars($edit_module['content']) : ''; ?></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_mandatory" class="mr-2" <?php echo ($edit_module && $edit_module['is_mandatory']) ? 'checked' : ''; ?>>
                            <span class="text-sm">Mandatory Module (Required for all advisors)</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeModuleModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg"><?php echo $edit_module ? 'Update Module' : 'Create Module'; ?></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModuleModal() { document.getElementById('moduleModal').classList.remove('hidden'); }
        function closeModuleModal() { document.getElementById('moduleModal').classList.add('hidden'); }
        function editModule(id) { window.location.href = 'training-modules.php?edit=' + id; }
        function previewModule(id) { window.open('preview-module.php?id=' + id, '_blank'); }
        function deleteModule(id) {
            if (confirm('Are you sure you want to delete this training module?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="delete_module"><input type="hidden" name="module_id" value="' + id + '">';
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        <?php if ($edit_module): ?>
        document.addEventListener('DOMContentLoaded', function() {
            openModuleModal();
        });
        <?php endif; ?>
    </script>
</body>
</html>