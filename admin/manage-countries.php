<?php
$page_title = 'Manage Countries';
$current_page = 'manage-countries';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO countries (region_id, name, slug, country_code, description, image_url, currency, language, best_time_to_visit, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['region_id'], $_POST['name'], $_POST['slug'], $_POST['country_code'], $_POST['description'], $_POST['image_url'], $_POST['currency'], $_POST['language'], $_POST['best_time_to_visit'], $_POST['status']]);
        $success = "Country added successfully!";
    } elseif ($action === 'edit') {
        $stmt = $pdo->prepare("UPDATE countries SET region_id = ?, name = ?, slug = ?, country_code = ?, description = ?, image_url = ?, currency = ?, language = ?, best_time_to_visit = ?, status = ? WHERE id = ?");
        $stmt->execute([$_POST['region_id'], $_POST['name'], $_POST['slug'], $_POST['country_code'], $_POST['description'], $_POST['image_url'], $_POST['currency'], $_POST['language'], $_POST['best_time_to_visit'], $_POST['status'], $_POST['id']]);
        $success = "Country updated successfully!";
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("UPDATE countries SET status = 'inactive' WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $success = "Country deactivated successfully!";
    }
}

// Get all countries with continent names
$stmt = $pdo->query("SELECT c.*, r.name as continent_name FROM countries c LEFT JOIN regions r ON c.region_id = r.id ORDER BY r.name, c.name");
$countries = $stmt->fetchAll();

// Get continents for dropdown
$stmt = $pdo->query("SELECT id, name FROM regions WHERE status = 'active' ORDER BY name");
$continents = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-white">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-slate-900 mb-6">Manage Countries</h1>
    
    <?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-flag me-1"></i>
            All Countries
            <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> Add Country
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Continent</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($countries as $country): ?>
                    <tr>
                        <td><?php echo $country['id']; ?></td>
                        <td><?php echo htmlspecialchars($country['continent_name']); ?></td>
                        <td><?php echo htmlspecialchars($country['name']); ?></td>
                        <td><?php echo htmlspecialchars($country['country_code']); ?></td>
                        <td><?php echo htmlspecialchars($country['currency']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $country['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                <?php echo ucfirst($country['status']); ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick='editCountry(<?php echo json_encode($country); ?>)'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Deactivate this country?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $country['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">
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
                    <h5 class="modal-title">Add Country</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label>Continent</label>
                        <select name="region_id" class="form-control" required>
                            <?php foreach ($continents as $cont): ?>
                            <option value="<?php echo $cont['id']; ?>"><?php echo htmlspecialchars($cont['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Country Code</label>
                        <input type="text" name="country_code" class="form-control" maxlength="3" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Image URL</label>
                        <input type="text" name="image_url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Currency</label>
                        <input type="text" name="currency" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Language</label>
                        <input type="text" name="language" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Best Time to Visit</label>
                        <input type="text" name="best_time_to_visit" class="form-control">
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
                    <button type="submit" class="btn btn-primary">Add Country</button>
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
                    <h5 class="modal-title">Edit Country</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label>Continent</label>
                        <select name="region_id" id="edit_region_id" class="form-control" required>
                            <?php foreach ($continents as $cont): ?>
                            <option value="<?php echo $cont['id']; ?>"><?php echo htmlspecialchars($cont['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Country Code</label>
                        <input type="text" name="country_code" id="edit_country_code" class="form-control" maxlength="3" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Image URL</label>
                        <input type="text" name="image_url" id="edit_image_url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Currency</label>
                        <input type="text" name="currency" id="edit_currency" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Language</label>
                        <input type="text" name="language" id="edit_language" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Best Time to Visit</label>
                        <input type="text" name="best_time_to_visit" id="edit_best_time_to_visit" class="form-control">
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
                    <button type="submit" class="btn btn-primary">Update Country</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCountry(country) {
    document.getElementById('edit_id').value = country.id;
    document.getElementById('edit_region_id').value = country.region_id;
    document.getElementById('edit_name').value = country.name;
    document.getElementById('edit_slug').value = country.slug;
    document.getElementById('edit_country_code').value = country.country_code;
    document.getElementById('edit_description').value = country.description || '';
    document.getElementById('edit_image_url').value = country.image_url || '';
    document.getElementById('edit_currency').value = country.currency || '';
    document.getElementById('edit_language').value = country.language || '';
    document.getElementById('edit_best_time_to_visit').value = country.best_time_to_visit || '';
    document.getElementById('edit_status').value = country.status;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>

    </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>
