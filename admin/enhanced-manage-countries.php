<?php
/**
 * Enhanced Country Management with Automatic Theme Generation
 * Integrates with subdomain system and auto-creates country themes
 */

$page_title = 'Manage Countries & Subdomains';
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
            $stmt->execute([
                $_POST['region_id'], 
                $_POST['name'], 
                $_POST['slug'], 
                $_POST['country_code'], 
                $_POST['description'], 
                $_POST['image_url'], 
                $_POST['currency'], 
                $_POST['language'], 
                $_POST['best_time_to_visit'], 
                $_POST['status']
            ]);
            
            $country_id = $pdo->lastInsertId();
            
            // Generate folder name from slug
            $folder_name = generateFolderName($_POST['slug']);
            
            // Automatically generate theme for new country
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
            
            $success = "Country added successfully! Theme generated and subdomain configured.";
            
        } catch (Exception $e) {
            $error = "Error adding country: " . $e->getMessage();
        }
        
    } elseif ($action === 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE countries SET region_id = ?, name = ?, slug = ?, country_code = ?, description = ?, image_url = ?, currency = ?, language = ?, best_time_to_visit = ?, status = ? WHERE id = ?");
            $stmt->execute([
                $_POST['region_id'], 
                $_POST['name'], 
                $_POST['slug'], 
                $_POST['country_code'], 
                $_POST['description'], 
                $_POST['image_url'], 
                $_POST['currency'], 
                $_POST['language'], 
                $_POST['best_time_to_visit'], 
                $_POST['status'], 
                $_POST['id']
            ]);
            
            // Regenerate theme with updated data
            $folder_name = generateFolderName($_POST['slug']);
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
            
            $success = "Country updated successfully! Theme regenerated.";
            
        } catch (Exception $e) {
            $error = "Error updating country: " . $e->getMessage();
        }
        
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("UPDATE countries SET status = 'inactive' WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $success = "Country deactivated successfully!";
        
    } elseif ($action === 'regenerate_theme') {
        try {
            // Get country data
            $stmt = $pdo->prepare("SELECT * FROM countries WHERE id = ?");
            $stmt->execute([$_POST['country_id']]);
            $country = $stmt->fetch();
            
            if ($country) {
                $folder_name = generateFolderName($country['slug']);
                $theme_result = generateCountryTheme([
                    'id' => $country['id'],
                    'name' => $country['name'],
                    'slug' => $country['slug'],
                    'country_code' => $country['country_code'],
                    'folder' => $folder_name,
                    'currency' => $country['currency'],
                    'description' => $country['description']
                ]);
                
                $success = "Theme regenerated successfully for " . $country['name'] . "!";
            }
        } catch (Exception $e) {
            $error = "Error regenerating theme: " . $e->getMessage();
        }
    }
}

// Get all countries with continent names
$stmt = $pdo->query("
    SELECT c.*, r.name as continent_name
    FROM countries c
    LEFT JOIN regions r ON c.region_id = r.id
    ORDER BY r.name, c.name
");
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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-slate-900">Manage Countries & Subdomains</h1>
                <div class="flex gap-3">
                    <button class="btn btn-info btn-sm" onclick="regenerateAllThemes()">
                        <i class="fas fa-sync"></i> Regenerate All Themes
                    </button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Add Country
                    </button>
                </div>
            </div>
            
            <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <!-- Countries Table -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-flag me-2"></i>Countries & Subdomain Status
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Continent</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Subdomain</th>
                                    <th>Theme Status</th>
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
                                    <td>
                                        <strong><?php echo htmlspecialchars($country['name']); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($country['slug']); ?></small>
                                    </td>
                                    <td><code><?php echo htmlspecialchars($country['country_code']); ?></code></td>
                                    <td>
                                        <a href="http://<?php echo $country['slug']; ?>.localhost:8000" target="_blank" class="text-decoration-none">
                                            <?php echo $country['slug']; ?>.iforeveryoungtours.com
                                        </a>
                                    </td>
                                    <td>
                                        <?php 
                                        $folder_name = generateFolderName($country['slug']);
                                        $theme_exists = file_exists("../countries/{$folder_name}/index.php");
                                        ?>
                                        <span class="badge bg-<?php echo $theme_exists ? 'success' : 'warning'; ?>">
                                            <?php echo $theme_exists ? 'Theme Ready' : 'No Theme'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($country['currency']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $country['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($country['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick='editCountry(<?php echo json_encode($country); ?>)' title="Edit Country">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="regenerateTheme(<?php echo $country['id']; ?>)" title="Regenerate Theme">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                            <a href="tours.php?country_id=<?php echo $country['id']; ?>" class="btn btn-sm btn-outline-success" title="Manage Tours">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </a>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Deactivate this country?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $country['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Deactivate">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Subdomain Configuration Panel -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cogs me-2"></i>Subdomain Configuration
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Active Subdomains</h5>
                            <div class="list-group">
                                <?php foreach ($countries as $country): ?>
                                <?php if ($country['status'] === 'active'): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo $country['name']; ?></strong>
                                        <br><small class="text-muted"><?php echo $country['slug']; ?>.iforeveryoungtours.com</small>
                                    </div>
                                    <span class="badge bg-success">Active</span>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Theme Generation Status</h5>
                            <div class="progress mb-3">
                                <?php 
                                $total_countries = count($countries);
                                $themed_countries = 0;
                                foreach ($countries as $country) {
                                    $folder_name = generateFolderName($country['slug']);
                                    if (file_exists("../countries/{$folder_name}/index.php")) {
                                        $themed_countries++;
                                    }
                                }
                                $percentage = $total_countries > 0 ? ($themed_countries / $total_countries) * 100 : 0;
                                ?>
                                <div class="progress-bar bg-success" style="width: <?php echo $percentage; ?>%">
                                    <?php echo round($percentage); ?>% Complete
                                </div>
                            </div>
                            <p class="text-muted">
                                <?php echo $themed_countries; ?> of <?php echo $total_countries; ?> countries have themes generated
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Add Country Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Continent *</label>
                                <select name="region_id" class="form-select" required>
                                    <option value="">Select Continent</option>
                                    <?php foreach ($continents as $continent): ?>
                                    <option value="<?php echo $continent['id']; ?>"><?php echo htmlspecialchars($continent['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Country Name *</label>
                                <input type="text" name="name" class="form-control" required onchange="generateSlug(this.value)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Subdomain Slug *</label>
                                <input type="text" name="slug" id="slug_field" class="form-control" required placeholder="visit-xx">
                                <small class="form-text text-muted">Format: visit-xx (e.g., visit-ke)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Country Code *</label>
                                <input type="text" name="country_code" class="form-control" required maxlength="3" placeholder="KEN">
                                <small class="form-text text-muted">3-letter ISO code</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Currency</label>
                                <input type="text" name="currency" class="form-control" placeholder="USD">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Language</label>
                                <input type="text" name="language" class="form-control" placeholder="English">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Premium travel experiences in..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Image URL</label>
                                <input type="url" name="image_url" class="form-control" placeholder="https://...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Best Time to Visit</label>
                                <input type="text" name="best_time_to_visit" class="form-control" placeholder="Year-round">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Auto-Generation:</strong> When you add this country, the system will automatically:
                        <ul class="mb-0 mt-2">
                            <li>Generate a complete theme based on Rwanda master template</li>
                            <li>Configure subdomain routing</li>
                            <li>Set up Africa continent inheritance (if applicable)</li>
                            <li>Create country-specific assets directory</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Country & Generate Theme
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Country Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <!-- Same form fields as add modal but with edit_ prefixes -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Continent *</label>
                                <select name="region_id" id="edit_region_id" class="form-select" required>
                                    <?php foreach ($continents as $continent): ?>
                                    <option value="<?php echo $continent['id']; ?>"><?php echo htmlspecialchars($continent['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Country Name *</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Subdomain Slug *</label>
                                <input type="text" name="slug" id="edit_slug" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Country Code *</label>
                                <input type="text" name="country_code" id="edit_country_code" class="form-control" required maxlength="3">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Currency</label>
                                <input type="text" name="currency" id="edit_currency" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Language</label>
                                <input type="text" name="language" id="edit_language" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Image URL</label>
                                <input type="url" name="image_url" id="edit_image_url" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Best Time to Visit</label>
                                <input type="text" name="best_time_to_visit" id="edit_best_time_to_visit" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Update & Regenerate Theme
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generateSlug(name) {
    const slug = 'visit-' + name.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-')
        .substring(0, 10);
    document.getElementById('slug_field').value = slug;
}

function editCountry(country) {
    document.getElementById('edit_id').value = country.id;
    document.getElementById('edit_region_id').value = country.region_id;
    document.getElementById('edit_name').value = country.name;
    document.getElementById('edit_slug').value = country.slug;
    document.getElementById('edit_country_code').value = country.country_code;
    document.getElementById('edit_currency').value = country.currency || '';
    document.getElementById('edit_language').value = country.language || '';
    document.getElementById('edit_description').value = country.description || '';
    document.getElementById('edit_image_url').value = country.image_url || '';
    document.getElementById('edit_best_time_to_visit').value = country.best_time_to_visit || '';
    document.getElementById('edit_status').value = country.status;
    
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

function regenerateTheme(countryId) {
    if (confirm('Regenerate theme for this country? This will overwrite existing customizations.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="regenerate_theme">
            <input type="hidden" name="country_id" value="${countryId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function regenerateAllThemes() {
    if (confirm('Regenerate themes for ALL countries? This may take a few minutes.')) {
        window.location.href = 'batch-theme-generator.php';
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
