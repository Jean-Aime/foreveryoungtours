<?php

require_once 'config.php';
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Get advisor's KYC documents
$stmt = $conn->prepare("SELECT * FROM kyc_documents WHERE user_id = ? ORDER BY uploaded_at DESC");
$stmt->execute([1]); // Demo advisor ID
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get KYC status
$stmt = $conn->prepare("SELECT * FROM kyc_status WHERE user_id = ?");
$stmt->execute([1]);
$kyc_status = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle document upload
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'upload_document') {
    // In a real implementation, handle file upload here
    $document_url = 'uploads/kyc/' . uniqid() . '_' . $_POST['document_type'] . '.pdf';
    
    $stmt = $conn->prepare("INSERT INTO kyc_documents (user_id, document_type, document_number, document_url, expiry_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([1, $_POST['document_type'], $_POST['document_number'], $document_url, $_POST['expiry_date'] ?: null]);
    
    // Update KYC status to in_progress
    $stmt = $conn->prepare("
        INSERT INTO kyc_status (user_id, overall_status) 
        VALUES (?, 'in_progress') 
        ON DUPLICATE KEY UPDATE overall_status = 'in_progress'
    ");
    $stmt->execute([1]);
    
    header('Location: kyc-upload.php?uploaded=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Documents - Advisor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gradient">Advisor Portal</h2>
                <p class="text-slate-600">KYC Center</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">Dashboard</a>
                <a href="training-portal.php" class="nav-item block px-6 py-3">Training Portal</a>
                <a href="kyc-upload.php" class="nav-item active block px-6 py-3">KYC Documents</a>
                <a href="tours.php" class="nav-item block px-6 py-3">Browse Tours</a>
                <a href="team.php" class="nav-item block px-6 py-3">My Team</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gradient">KYC Documents</h1>
                <button onclick="openUploadModal()" class="btn-primary px-6 py-3 rounded-lg">Upload Document</button>
            </div>
            
            <?php if (isset($_GET['uploaded'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                Document uploaded successfully! It will be reviewed by your MCA.
            </div>
            <?php endif; ?>
            
            <!-- KYC Status Overview -->
            <div class="nextcloud-card p-6 mb-8">
                <h2 class="text-xl font-bold mb-4">KYC Verification Status</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center <?php echo ($kyc_status && $kyc_status['identity_verified']) ? 'bg-green-100' : 'bg-gray-100'; ?>">
                            <svg class="w-8 h-8 <?php echo ($kyc_status && $kyc_status['identity_verified']) ? 'text-green-600' : 'text-gray-400'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold">Identity Verification</h3>
                        <p class="text-sm text-slate-600">National ID or Passport</p>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium <?php echo ($kyc_status && $kyc_status['identity_verified']) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo ($kyc_status && $kyc_status['identity_verified']) ? 'Verified' : 'Pending'; ?>
                        </span>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center <?php echo ($kyc_status && $kyc_status['address_verified']) ? 'bg-green-100' : 'bg-gray-100'; ?>">
                            <svg class="w-8 h-8 <?php echo ($kyc_status && $kyc_status['address_verified']) ? 'text-green-600' : 'text-gray-400'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold">Address Verification</h3>
                        <p class="text-sm text-slate-600">Utility Bill or Bank Statement</p>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium <?php echo ($kyc_status && $kyc_status['address_verified']) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo ($kyc_status && $kyc_status['address_verified']) ? 'Verified' : 'Pending'; ?>
                        </span>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center <?php echo ($kyc_status && $kyc_status['business_verified']) ? 'bg-green-100' : 'bg-gray-100'; ?>">
                            <svg class="w-8 h-8 <?php echo ($kyc_status && $kyc_status['business_verified']) ? 'text-green-600' : 'text-gray-400'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold">Business Verification</h3>
                        <p class="text-sm text-slate-600">Business License (Optional)</p>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium <?php echo ($kyc_status && $kyc_status['business_verified']) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo ($kyc_status && $kyc_status['business_verified']) ? 'Verified' : 'Pending'; ?>
                        </span>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center <?php echo ($kyc_status && $kyc_status['overall_status'] === 'approved') ? 'bg-green-100' : 'bg-gray-100'; ?>">
                            <svg class="w-8 h-8 <?php echo ($kyc_status && $kyc_status['overall_status'] === 'approved') ? 'text-green-600' : 'text-gray-400'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold">Overall Status</h3>
                        <p class="text-sm text-slate-600">Final Approval</p>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium <?php 
                        $status = $kyc_status ? $kyc_status['overall_status'] : 'not_started';
                        $statusColors = [
                            'not_started' => 'bg-gray-100 text-gray-800',
                            'in_progress' => 'bg-blue-100 text-blue-800',
                            'pending_review' => 'bg-orange-100 text-orange-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800'
                        ];
                        echo $statusColors[$status];
                        ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Required Documents -->
            <div class="nextcloud-card p-6 mb-8">
                <h2 class="text-xl font-bold mb-4">Required Documents</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold text-lg mb-2">Identity Documents</h3>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li>• National ID (Front & Back)</li>
                            <li>• Passport (Bio-data page)</li>
                            <li>• Driver's License (if applicable)</li>
                        </ul>
                        <p class="text-xs text-slate-500 mt-3">At least one identity document is required</p>
                    </div>
                    
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold text-lg mb-2">Address Proof</h3>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li>• Utility Bill (Water, Electricity)</li>
                            <li>• Bank Statement (Last 3 months)</li>
                            <li>• Rental Agreement</li>
                        </ul>
                        <p class="text-xs text-slate-500 mt-3">Document must be dated within last 3 months</p>
                    </div>
                </div>
            </div>

            <!-- Uploaded Documents -->
            <div class="nextcloud-card p-6">
                <h2 class="text-xl font-bold mb-6">Uploaded Documents</h2>
                <?php if (empty($documents)): ?>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-slate-600 mb-2">No documents uploaded yet</h3>
                    <p class="text-slate-500 mb-4">Upload your KYC documents to get verified and start selling</p>
                    <button onclick="openUploadModal()" class="btn-primary px-6 py-3 rounded-lg">Upload First Document</button>
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4">Document Type</th>
                                <th class="text-left py-3 px-4">Document Number</th>
                                <th class="text-left py-3 px-4">Upload Date</th>
                                <th class="text-left py-3 px-4">Status</th>
                                <th class="text-left py-3 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                            <tr class="border-b hover:bg-slate-50">
                                <td class="py-3 px-4">
                                    <div class="font-medium"><?php echo ucfirst(str_replace('_', ' ', $doc['document_type'])); ?></div>
                                    <?php if ($doc['expiry_date']): ?>
                                    <div class="text-sm text-slate-500">Expires: <?php echo date('M j, Y', strtotime($doc['expiry_date'])); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($doc['document_number'] ?: 'N/A'); ?></td>
                                <td class="py-3 px-4"><?php echo date('M j, Y', strtotime($doc['uploaded_at'])); ?></td>
                                <td class="py-3 px-4">
                                    <?php
                                    $statusColors = [
                                        'pending' => 'bg-orange-100 text-orange-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'expired' => 'bg-gray-100 text-gray-800'
                                    ];
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $statusColors[$doc['verification_status']]; ?>">
                                        <?php echo ucfirst($doc['verification_status']); ?>
                                    </span>
                                    <?php if ($doc['rejection_reason']): ?>
                                    <div class="text-xs text-red-600 mt-1" title="<?php echo htmlspecialchars($doc['rejection_reason']); ?>">
                                        <?php echo htmlspecialchars(substr($doc['rejection_reason'], 0, 30)) . '...'; ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <button onclick="viewDocument('<?php echo $doc['document_url']; ?>')" class="text-blue-600 hover:text-blue-800 text-sm">View</button>
                                        <?php if ($doc['verification_status'] === 'rejected'): ?>
                                        <button onclick="replaceDocument(<?php echo $doc['id']; ?>)" class="text-golden-600 hover:text-golden-800 text-sm">Replace</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Upload Document Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Upload KYC Document</h3>
            </div>
            <form method="POST" enctype="multipart/form-data" class="p-6">
                <input type="hidden" name="action" value="upload_document">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Document Type</label>
                        <select name="document_type" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Document Type</option>
                            <option value="national_id">National ID</option>
                            <option value="passport">Passport</option>
                            <option value="driving_license">Driving License</option>
                            <option value="utility_bill">Utility Bill</option>
                            <option value="bank_statement">Bank Statement</option>
                            <option value="business_license">Business License</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Document Number (Optional)</label>
                        <input type="text" name="document_number" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="ID/Passport number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Expiry Date (Optional)</label>
                        <input type="date" name="expiry_date" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Upload File</label>
                        <input type="file" name="document_file" required accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        <p class="text-xs text-slate-500 mt-1">Accepted formats: PDF, JPG, PNG (Max 5MB)</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeUploadModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Upload Document</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openUploadModal() { document.getElementById('uploadModal').classList.remove('hidden'); }
        function closeUploadModal() { document.getElementById('uploadModal').classList.add('hidden'); }
        function viewDocument(url) { window.open(url, '_blank'); }
        function replaceDocument(docId) { 
            openUploadModal();
            // Add logic to pre-fill form for replacement
        }
    </script>
</body>
</html>