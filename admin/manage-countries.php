<?php
$page_title = 'Manage Countries';
$current_page = 'manage-countries';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

// Include theme generation functions
require_once '../includes/theme-generator.php';

// Handle Add/Edit/Delete with automatic theme generation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        try {
            // Insert country into database
            $stmt = $pdo->prepare("INSERT INTO countries (region_id, name, slug, country_code, description, image_url, currency, language, best_time_to_visit, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['region_id'], $_POST['name'], $_POST['slug'], $_POST['country_code'], $_POST['description'], $_POST['image_url'], $_POST['currency'], $_POST['language'], $_POST['best_time_to_visit'], $_POST['status']]);

            $country_id = $pdo->lastInsertId();

            // Generate folder name from slug
            $folder_name = generateFolderName($_POST['slug']);

            // Automatically generate Rwanda/South Africa theme for new country
            $theme_result = generateCountryTheme([
                'id' => $country_id,
                'name' => $_POST['name'],
                'slug' => $_POST['slug'],
                'country_code' => $_POST['country_code'],
                'folder' => $folder_name,
                'currency' => $_POST['currency'],
                'description' => $_POST['description']
            ]);

            // Update subdomain handler
            updateSubdomainHandler($_POST['country_code'], $_POST['slug'], $folder_name);

            $success = "Country added successfully! Rwanda theme automatically cloned and subdomain configured.";

        } catch (Exception $e) {
            $error = "Error adding country: " . $e->getMessage();
        }

    } elseif ($action === 'edit') {
        try {
            // Get old status before update
            $stmt = $pdo->prepare("SELECT status FROM countries WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $old_country = $stmt->fetch();
            $old_status = $old_country['status'];

            // Update country
            $stmt = $pdo->prepare("UPDATE countries SET region_id = ?, name = ?, slug = ?, country_code = ?, description = ?, image_url = ?, currency = ?, language = ?, best_time_to_visit = ?, status = ? WHERE id = ?");
            $stmt->execute([$_POST['region_id'], $_POST['name'], $_POST['slug'], $_POST['country_code'], $_POST['description'], $_POST['image_url'], $_POST['currency'], $_POST['language'], $_POST['best_time_to_visit'], $_POST['status'], $_POST['id']]);

            // If country is being activated (inactive -> active), generate theme
            if ($old_status === 'inactive' && $_POST['status'] === 'active') {
                $folder_name = generateFolderName($_POST['slug']);

                // Check if theme already exists
                $theme_dir = __DIR__ . '/../countries/' . $folder_name;
                if (!file_exists($theme_dir . '/index.php')) {
                    // Generate theme if it doesn't exist
                    $theme_result = generateCountryTheme([
                        'id' => $_POST['id'],
                        'name' => $_POST['name'],
                        'slug' => $_POST['slug'],
                        'country_code' => $_POST['country_code'],
                        'folder' => $folder_name,
                        'currency' => $_POST['currency'],
                        'description' => $_POST['description']
                    ]);

                    // Update subdomain handler
                    updateSubdomainHandler($_POST['country_code'], $_POST['slug'], $folder_name);

                    $success = "Country activated successfully! Rwanda theme automatically cloned.";
                } else {
                    $success = "Country activated successfully! (Theme already exists)";
                }
            } else {
                $success = "Country updated successfully!";
            }

        } catch (Exception $e) {
            $error = "Error updating country: " . $e->getMessage();
        }

    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("UPDATE countries SET status = 'inactive' WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $success = "Country deactivated successfully!";

    } elseif ($action === 'activate') {
        try {
            // Get country data
            $stmt = $pdo->prepare("SELECT * FROM countries WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $country = $stmt->fetch();

            if ($country) {
                // Activate country
                $stmt = $pdo->prepare("UPDATE countries SET status = 'active' WHERE id = ?");
                $stmt->execute([$_POST['id']]);

                // Generate folder name
                $folder_name = generateFolderName($country['slug']);

                // Check if theme exists
                $theme_dir = __DIR__ . '/../countries/' . $folder_name;
                if (!file_exists($theme_dir . '/index.php')) {
                    // Generate Rwanda theme
                    $theme_result = generateCountryTheme([
                        'id' => $country['id'],
                        'name' => $country['name'],
                        'slug' => $country['slug'],
                        'country_code' => $country['country_code'],
                        'folder' => $folder_name,
                        'currency' => $country['currency'],
                        'description' => $country['description']
                    ]);

                    // Update subdomain handler
                    updateSubdomainHandler($country['country_code'], $country['slug'], $folder_name);

                    $success = "Country activated successfully! Rwanda theme automatically cloned.";
                } else {
                    $success = "Country activated successfully! (Theme already exists)";
                }
            }

        } catch (Exception $e) {
            $error = "Error activating country: " . $e->getMessage();
        }
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
    <div class="alert alert-success mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
    </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
    <div class="alert alert-danger mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
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
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <?php if ($country['status'] === 'active'): ?>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Deactivate this country?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $country['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-ban"></i> Deactivate
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Activate this country? Rwanda theme will be automatically cloned.');">
                                    <input type="hidden" name="action" value="activate">
                                    <input type="hidden" name="id" value="<?php echo $country['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check-circle"></i> Activate
                                    </button>
                                </form>
                            <?php endif; ?>
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
