<?php
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Get advisors with KYC status
$stmt = $conn->prepare("
    SELECT u.*, ks.overall_status, ks.identity_verified, ks.address_verified, 
           ks.business_verified, ks.approved_at, ks.rejection_reason, ks.notes,
           COUNT(kd.id) as document_count
    FROM users u 
    LEFT JOIN kyc_status ks ON u.id = ks.user_id 
    LEFT JOIN kyc_documents kd ON u.id = kd.user_id 
    WHERE u.role = 'advisor' AND u.mca_id = ? 
    GROUP BY u.id
    ORDER BY ks.last_updated DESC, u.created_at DESC
");
$stmt->execute([1]); // Demo MCA ID
$advisors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle KYC approval/rejection
if ($_POST && isset($_POST['action'])) {
    $advisorId = $_POST['advisor_id'];
    $action = $_POST['action'];
    
    if ($action === 'approve') {
        $stmt = $conn->prepare("
            INSERT INTO kyc_status (user_id, overall_status, identity_verified, address_verified, business_verified, approved_by, approved_at) 
            VALUES (?, 'approved', 1, 1, 1, ?, NOW()) 
            ON DUPLICATE KEY UPDATE 
            overall_status = 'approved', identity_verified = 1, address_verified = 1, business_verified = 1, 
            approved_by = ?, approved_at = NOW()
        ");
        $stmt->execute([$advisorId, 1, 1]); // Demo MCA ID
        
        // Update user can_sell status
        $stmt = $conn->prepare("UPDATE users SET can_sell = 1, kyc_status = 'approved' WHERE id = ?");
        $stmt->execute([$advisorId]);
        
    } elseif ($action === 'reject') {
        $reason = $_POST['rejection_reason'];
        $stmt = $conn->prepare("
            INSERT INTO kyc_status (user_id, overall_status, rejection_reason, approved_by) 
            VALUES (?, 'rejected', ?, ?) 
            ON DUPLICATE KEY UPDATE 
            overall_status = 'rejected', rejection_reason = ?, approved_by = ?
        ");
        $stmt->execute([$advisorId, $reason, 1, $reason, 1]);
        
        // Update user status
        $stmt = $conn->prepare("UPDATE users SET can_sell = 0, kyc_status = 'rejected' WHERE id = ?");
        $stmt->execute([$advisorId]);
    }
    
    header('Location: kyc-management.php?updated=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Management - MCA Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gradient">MCA Dashboard</h2>
                <p class="text-slate-600">KYC Center</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">Dashboard</a>
                <a href="advisors.php" class="nav-item block px-6 py-3">My Advisors</a>
                <a href="training.php" class="nav-item block px-6 py-3">Training Center</a>
                <a href="kyc-management.php" class="nav-item active block px-6 py-3">KYC Management</a>
                <a href="tours.php" class="nav-item block px-6 py-3">Tours</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gradient">KYC Management</h1>
                <button onclick="openKycGuidelinesModal()" class="btn-secondary px-6 py-3 rounded-lg">KYC Guidelines</button>
            </div>
            
            <?php if (isset($_GET['updated'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                KYC status updated successfully!
            </div>
            <?php endif; ?>
            
            <!-- KYC Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Total Advisors</h3>
                    <p class="text-3xl font-bold text-slate-600"><?php echo count($advisors); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Pending Review</h3>
                    <p class="text-3xl font-bold text-orange-600"><?php echo count(array_filter($advisors, fn($a) => $a['overall_status'] === 'pending_review')); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Approved</h3>
                    <p class="text-3xl font-bold text-green-600"><?php echo count(array_filter($advisors, fn($a) => $a['overall_status'] === 'approved')); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Rejected</h3>
                    <p class="text-3xl font-bold text-red-600"><?php echo count(array_filter($advisors, fn($a) => $a['overall_status'] === 'rejected')); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Can Sell</h3>
                    <p class="text-3xl font-bold text-emerald-600"><?php echo count(array_filter($advisors, fn($a) => $a['overall_status'] === 'approved')); ?></p>
                </div>
            </div>

            <!-- KYC Status Table -->
            <div class="nextcloud-card p-6">
                <h2 class="text-xl font-bold mb-6">Advisor KYC Status</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4">Advisor</th>
                                <th class="text-left py-3 px-4">Documents</th>
                                <th class="text-left py-3 px-4">Verification Status</th>
                                <th class="text-left py-3 px-4">Overall Status</th>
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
                                        <div class="text-xs text-slate-400"><?php echo htmlspecialchars($advisor['phone']); ?></div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm">
                                        <div class="font-medium"><?php echo $advisor['document_count']; ?> documents</div>
                                        <button onclick="viewDocuments(<?php echo $advisor['id']; ?>)" class="text-blue-600 hover:text-blue-800 text-xs">View Documents</button>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center text-xs">
                                            <span class="w-2 h-2 rounded-full mr-2 <?php echo $advisor['identity_verified'] ? 'bg-green-500' : 'bg-gray-300'; ?>"></span>
                                            Identity
                                        </div>
                                        <div class="flex items-center text-xs">
                                            <span class="w-2 h-2 rounded-full mr-2 <?php echo $advisor['address_verified'] ? 'bg-green-500' : 'bg-gray-300'; ?>"></span>
                                            Address
                                        </div>
                                        <div class="flex items-center text-xs">
                                            <span class="w-2 h-2 rounded-full mr-2 <?php echo $advisor['business_verified'] ? 'bg-green-500' : 'bg-gray-300'; ?>"></span>
                                            Business
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <?php
                                    $status = $advisor['overall_status'] ?: 'not_started';
                                    $statusColors = [
                                        'not_started' => 'bg-gray-100 text-gray-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'pending_review' => 'bg-orange-100 text-orange-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800'
                                    ];
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $statusColors[$status]; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                    </span>
                                    <?php if ($advisor['rejection_reason']): ?>
                                    <div class="text-xs text-red-600 mt-1" title="<?php echo htmlspecialchars($advisor['rejection_reason']); ?>">
                                        Reason: <?php echo htmlspecialchars(substr($advisor['rejection_reason'], 0, 30)) . '...'; ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $advisor['overall_status'] === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $advisor['overall_status'] === 'approved' ? 'Yes' : 'No'; ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <button onclick="reviewKyc(<?php echo $advisor['id']; ?>)" class="text-blue-600 hover:text-blue-800 text-sm">Review</button>
                                        <?php if ($advisor['overall_status'] === 'pending_review' || $advisor['overall_status'] === 'in_progress'): ?>
                                        <button onclick="approveKyc(<?php echo $advisor['id']; ?>)" class="text-green-600 hover:text-green-800 text-sm">Approve</button>
                                        <button onclick="rejectKyc(<?php echo $advisor['id']; ?>)" class="text-red-600 hover:text-red-800 text-sm">Reject</button>
                                        <?php endif; ?>
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

    <!-- KYC Review Modal -->
    <div id="kycReviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">KYC Document Review</h3>
            </div>
            <div id="kycReviewContent" class="p-6">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- KYC Approval Modal -->
    <div id="kycApprovalModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Approve KYC</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="approve">
                <input type="hidden" name="advisor_id" id="approveAdvisorId">
                <p class="text-slate-600 mb-4">Are you sure you want to approve this advisor's KYC? This will enable them to sell tours and earn commissions.</p>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeKycApprovalModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Approve KYC</button>
                </div>
            </form>
        </div>
    </div>

    <!-- KYC Rejection Modal -->
    <div id="kycRejectionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Reject KYC</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="reject">
                <input type="hidden" name="advisor_id" id="rejectAdvisorId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Rejection Reason</label>
                        <textarea name="rejection_reason" required rows="4" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Please provide a detailed reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeKycRejectionModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Reject KYC</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function reviewKyc(advisorId) {
            document.getElementById('kycReviewModal').classList.remove('hidden');
            // Load advisor documents via AJAX
            fetch('ajax/get-kyc-documents.php?advisor_id=' + advisorId)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('kycReviewContent').innerHTML = html;
                });
        }
        
        function approveKyc(advisorId) {
            document.getElementById('approveAdvisorId').value = advisorId;
            document.getElementById('kycApprovalModal').classList.remove('hidden');
        }
        
        function rejectKyc(advisorId) {
            document.getElementById('rejectAdvisorId').value = advisorId;
            document.getElementById('kycRejectionModal').classList.remove('hidden');
        }
        
        function closeKycReviewModal() { document.getElementById('kycReviewModal').classList.add('hidden'); }
        function closeKycApprovalModal() { document.getElementById('kycApprovalModal').classList.add('hidden'); }
        function closeKycRejectionModal() { document.getElementById('kycRejectionModal').classList.add('hidden'); }
        
        function viewDocuments(advisorId) {
            window.open('advisor-documents.php?id=' + advisorId, '_blank');
        }
        
        function openKycGuidelinesModal() {
            alert('KYC Guidelines:\n\n1. Identity verification required\n2. Address proof mandatory\n3. Business registration (if applicable)\n4. All documents must be clear and valid\n5. Training completion required before approval');
        }
    </script>
</body>
</html>