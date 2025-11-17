<?php
/**
 * Batch Theme Generator
 * Regenerates themes for all countries
 */

session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/theme-generator.php';
checkAuth('super_admin');

set_time_limit(300); // 5 minutes

$page_title = "Batch Theme Generator";

// Get all active countries
$stmt = $pdo->query("SELECT * FROM countries WHERE status = 'active' ORDER BY name");
$countries = $stmt->fetchAll();

$results = [];
$total_countries = count($countries);
$processed = 0;

if ($_POST && isset($_POST['generate_all'])) {
    foreach ($countries as $country) {
        try {
            $folder_name = generateFolderName($country['slug']);
            
            $result = generateCountryTheme([
                'id' => $country['id'],
                'name' => $country['name'],
                'slug' => $country['slug'],
                'country_code' => $country['country_code'],
                'folder' => $folder_name,
                'currency' => $country['currency'],
                'description' => $country['description']
            ]);
            
            $results[] = [
                'country' => $country['name'],
                'status' => 'success',
                'message' => $result['message']
            ];
            $processed++;
            
        } catch (Exception $e) {
            $results[] = [
                'country' => $country['name'],
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-white">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-slate-900 mb-6">Batch Theme Generator</h1>
            
            <?php if (!$results): ?>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-sync me-2"></i>Regenerate All Country Themes
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This will regenerate themes for all <?php echo $total_countries; ?> countries. 
                        Any custom modifications will be overwritten.
                    </div>
                    
                    <div class="mb-4">
                        <h5>Countries to Process:</h5>
                        <div class="row">
                            <?php foreach ($countries as $country): ?>
                            <div class="col-md-3 mb-2">
                                <span class="badge bg-primary"><?php echo htmlspecialchars($country['name']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <form method="POST">
                        <button type="submit" name="generate_all" class="btn btn-danger btn-lg" onclick="return confirm('Are you sure? This process may take several minutes.')">
                            <i class="fas fa-sync me-2"></i>Generate All Themes
                        </button>
                        <a href="enhanced-manage-countries.php" class="btn btn-secondary btn-lg ms-3">
                            <i class="fas fa-arrow-left me-2"></i>Back to Countries
                        </a>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-check-circle me-2"></i>Generation Results
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <strong>Processed:</strong> <?php echo $processed; ?> of <?php echo $total_countries; ?> countries
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $result): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($result['country']); ?></strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo $result['status'] === 'success' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($result['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($result['message']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="enhanced-manage-countries.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Countries Management
                        </a>
                        <a href="batch-theme-generator.php" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Run Again
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>
