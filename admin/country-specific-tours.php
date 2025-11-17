<?php
/**
 * Country-Specific Tours Management
 * Enhanced tours management with country filtering and subdomain integration
 */

$page_title = "Country-Specific Tours Management";
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$success = '';
$error = '';
$country_id = $_GET['country_id'] ?? null;

// Get country information
$country = null;
if ($country_id) {
    $stmt = $pdo->prepare("SELECT * FROM countries WHERE id = ?");
    $stmt->execute([$country_id]);
    $country = $stmt->fetch();
}

// Handle tour operations
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_tour') {
        try {
            $stmt = $pdo->prepare("INSERT INTO tours (country_id, name, description, price, duration, difficulty, featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['country_id'],
                $_POST['name'],
                $_POST['description'],
                $_POST['price'],
                $_POST['duration'],
                $_POST['difficulty'],
                $_POST['featured'] ?? 0,
                $_POST['status']
            ]);
            $success = "Tour added successfully!";
        } catch (Exception $e) {
            $error = "Error adding tour: " . $e->getMessage();
        }
    }
}

// Get tours for specific country or all tours
if ($country_id) {
    $stmt = $pdo->prepare("SELECT t.*, c.name as country_name FROM tours t JOIN countries c ON t.country_id = c.id WHERE t.country_id = ? ORDER BY t.featured DESC, t.name");
    $stmt->execute([$country_id]);
} else {
    $stmt = $pdo->query("SELECT t.*, c.name as country_name FROM tours t JOIN countries c ON t.country_id = c.id ORDER BY c.name, t.featured DESC, t.name");
}
$tours = $stmt->fetchAll();

// Get all countries for dropdown
$stmt = $pdo->query("SELECT id, name, slug FROM countries WHERE status = 'active' ORDER BY name");
$countries = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-white">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">
                        <?php if ($country): ?>
                            Tours for <?php echo htmlspecialchars($country['name']); ?>
                        <?php else: ?>
                            All Tours by Country
                        <?php endif; ?>
                    </h1>
                    <?php if ($country): ?>
                    <p class="text-gray-600 mt-2">
                        Subdomain: <a href="http://<?php echo $country['slug']; ?>.localhost:8000" target="_blank" class="text-blue-600"><?php echo $country['slug']; ?>.iforeveryoungtours.com</a>
                    </p>
                    <?php endif; ?>
                </div>
                <div class="flex gap-3">
                    <a href="enhanced-manage-countries.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Countries
                    </a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTourModal">
                        <i class="fas fa-plus me-2"></i>Add Tour
                    </button>
                </div>
            </div>
            
            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <!-- Country Filter -->
            <?php if (!$country_id): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label class="form-label">Filter by Country:</label>
                            <select class="form-select" onchange="filterByCountry(this.value)">
                                <option value="">All Countries</option>
                                <?php foreach ($countries as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <strong><?php echo count($tours); ?></strong> tours total
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Tours Table -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-map-marked-alt me-2"></i>Tours Management
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <?php if (!$country_id): ?><th>Country</th><?php endif; ?>
                                    <th>Tour Name</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Featured</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tours as $tour): ?>
                                <tr>
                                    <td><?php echo $tour['id']; ?></td>
                                    <?php if (!$country_id): ?>
                                    <td>
                                        <strong><?php echo htmlspecialchars($tour['country_name']); ?></strong>
                                    </td>
                                    <?php endif; ?>
                                    <td>
                                        <strong><?php echo htmlspecialchars($tour['name']); ?></strong>
                                        <?php if ($tour['description']): ?>
                                        <br><small class="text-muted"><?php echo substr(htmlspecialchars($tour['description']), 0, 100); ?>...</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong>$<?php echo number_format($tour['price']); ?></strong></td>
                                    <td><?php echo $tour['duration']; ?> days</td>
                                    <td>
                                        <span class="badge bg-<?php echo $tour['featured'] ? 'warning' : 'secondary'; ?>">
                                            <?php echo $tour['featured'] ? 'Featured' : 'Standard'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $tour['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($tour['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="tours.php?edit=<?php echo $tour['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="../pages/tour-detail.php?id=<?php echo $tour['id']; ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
    </div>
</main>

<!-- Add Tour Modal -->
<div class="modal fade" id="addTourModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Tour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_tour">
                    
                    <div class="mb-3">
                        <label class="form-label">Country *</label>
                        <select name="country_id" class="form-select" required>
                            <?php if ($country): ?>
                            <option value="<?php echo $country['id']; ?>" selected><?php echo htmlspecialchars($country['name']); ?></option>
                            <?php else: ?>
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tour Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Price (USD) *</label>
                                <input type="number" name="price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Duration (days) *</label>
                                <input type="number" name="duration" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Difficulty</label>
                                <select name="difficulty" class="form-select">
                                    <option value="Easy">Easy</option>
                                    <option value="Moderate">Moderate</option>
                                    <option value="Challenging">Challenging</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Featured Tour</label>
                                <div class="form-check">
                                    <input type="checkbox" name="featured" value="1" class="form-check-input">
                                    <label class="form-check-label">Mark as featured</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Tour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterByCountry(countryId) {
    if (countryId) {
        window.location.href = 'country-specific-tours.php?country_id=' + countryId;
    } else {
        window.location.href = 'country-specific-tours.php';
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
