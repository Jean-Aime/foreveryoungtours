<?php
$page_title = "Sample Admin Page";
$page_subtitle = "Example Implementation";

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Sample data processing
$sample_data = [
    ['id' => 1, 'name' => 'Sample Item 1', 'status' => 'active'],
    ['id' => 2, 'name' => 'Sample Item 2', 'status' => 'inactive'],
    ['id' => 3, 'name' => 'Sample Item 3', 'status' => 'active'],
];
?>

<?php include 'includes/admin-header.php'; ?>
<?php include 'includes/admin-sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-8">
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gradient">Sample Admin Page</h1>
                <p class="text-slate-600">This demonstrates the new admin layout structure</p>
            </div>

            <!-- Sample Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Items</p>
                            <p class="text-2xl font-bold text-gradient">3</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-items text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Active Items</p>
                            <p class="text-2xl font-bold text-gradient">2</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Inactive Items</p>
                            <p class="text-2xl font-bold text-gradient">1</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Success Rate</p>
                            <p class="text-2xl font-bold text-gradient">67%</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-yellow-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sample Data Table -->
            <div class="nextcloud-card overflow-hidden">
                <div class="p-6 border-b">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <h2 class="text-xl font-bold mb-4 md:mb-0">Sample Data</h2>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="text" placeholder="Search..." class="border border-slate-300 rounded-lg px-4 py-2 text-sm">
                            <button class="btn-primary px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-plus mr-2"></i>Add New
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-4">ID</th>
                                <th class="text-left p-4">Name</th>
                                <th class="text-left p-4">Status</th>
                                <th class="text-left p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sample_data as $item): ?>
                            <tr class="border-b hover:bg-slate-50">
                                <td class="p-4 font-mono text-sm">#<?php echo str_pad($item['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td class="p-4 font-semibold"><?php echo htmlspecialchars($item['name']); ?></td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-medium <?php echo $item['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo ucfirst($item['status']); ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <button class="btn-secondary px-3 py-1 rounded text-sm" data-tooltip="Edit item">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm" data-tooltip="Delete item" onclick="return confirmDelete()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sample Form -->
            <div class="nextcloud-card mt-8">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold">Sample Form</h2>
                </div>
                <form class="p-6" id="sampleForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Item Name</label>
                            <input type="text" name="name" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Enter item name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                            <select name="status" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Enter description"></textarea>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="btn-primary px-6 py-2 rounded-lg" onclick="showLoading(this)">
                            Save Item
                        </button>
                    </div>
                </form>
            </div>
        </div>

<?php
$additional_scripts = '
    <script>
        // Initialize auto-save for the sample form
        autoSaveForm("sampleForm");
        
        // Sample chart
        document.addEventListener("DOMContentLoaded", function() {
            showToast("Sample page loaded successfully!", "success");
        });
    </script>
';
?>

<?php include 'includes/admin-footer.php'; ?>