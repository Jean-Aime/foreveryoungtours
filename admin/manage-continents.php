<?php
$page_title = 'Manage Continents';
$current_page = 'manage-continents';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO regions (name, slug, description, image_url, featured, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['slug'], $_POST['description'], $_POST['image_url'], $_POST['featured'] ?? 0, $_POST['status']]);
        $success = "Continent added successfully!";
    } elseif ($action === 'edit') {
        $stmt = $pdo->prepare("UPDATE regions SET name = ?, slug = ?, description = ?, image_url = ?, featured = ?, status = ? WHERE id = ?");
        $stmt->execute([$_POST['name'], $_POST['slug'], $_POST['description'], $_POST['image_url'], $_POST['featured'] ?? 0, $_POST['status'], $_POST['id']]);
        $success = "Continent updated successfully!";
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("UPDATE regions SET status = 'inactive' WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $success = "Continent deactivated successfully!";
    }
}

// Get all continents
$stmt = $pdo->query("SELECT * FROM regions ORDER BY name");
$continents = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-white">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-slate-900 mb-6">Manage Continents</h1>
    
    <?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
        <div class="p-4 border-b border-slate-200 flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-globe text-yellow-600 mr-2"></i>
                <span class="font-semibold text-slate-900">All Continents</span>
            </div>
            <button class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> Add Continent
            </button>
        </div>
        <div class="p-4">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Featured</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($continents as $continent): ?>
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm"><?php echo $continent['id']; ?></td>
                        <td class="px-4 py-3 text-sm font-medium"><?php echo htmlspecialchars($continent['name']); ?></td>
                        <td class="px-4 py-3 text-sm text-slate-600"><?php echo htmlspecialchars($continent['slug']); ?></td>
                        <td class="px-4 py-3 text-sm text-slate-600"><?php echo htmlspecialchars(substr($continent['description'], 0, 50)) . '...'; ?></td>
                        <td class="px-4 py-3 text-sm"><?php echo $continent['featured'] ? '<span class="text-yellow-600">★ Yes</span>' : 'No'; ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full <?php echo $continent['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                <?php echo ucfirst($continent['status']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm mr-2" onclick="editContinent(<?php echo htmlspecialchars(json_encode($continent)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Deactivate this continent?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $continent['id']; ?>">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add Continent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Image URL</label>
                        <input type="text" name="image_url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Featured</label>
                        <select name="featured" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Continent</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Continent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Image URL</label>
                        <input type="text" name="image_url" id="edit_image_url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Featured</label>
                        <select name="featured" id="edit_featured" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Continent</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editContinent(continent) {
    document.getElementById('edit_id').value = continent.id;
    document.getElementById('edit_name').value = continent.name;
    document.getElementById('edit_slug').value = continent.slug;
    document.getElementById('edit_description').value = continent.description;
    document.getElementById('edit_image_url').value = continent.image_url;
    document.getElementById('edit_featured').value = continent.featured;
    document.getElementById('edit_status').value = continent.status;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>

    </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>
