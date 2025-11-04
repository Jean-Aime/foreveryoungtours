<?php
require_once '../config/database.php';

$page_title = 'Client Stories';
$current_page = 'client-stories';

// Fetch all client stories (blog posts with user reviews)
$stories = $pdo->query("
    SELECT bp.*, bc.name as category_name
    FROM blog_posts bp 
    LEFT JOIN blog_categories bc ON bp.category_id = bc.id 
    ORDER BY bp.created_at DESC
")->fetchAll();

include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Client Stories</h1>
            <button onclick="openAddModal()" class="bg-primary-gold text-black px-6 py-2 rounded-lg font-semibold hover:bg-golden-600">
                <i class="fas fa-plus mr-2"></i>Add Story
            </button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($stories as $story): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900"><?= htmlspecialchars($story['title']) ?></div>
                            <div class="text-sm text-gray-500"><?= htmlspecialchars(substr($story['excerpt'], 0, 60)) ?>...</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($story['author_name'] ?: 'Anonymous') ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="text-yellow-400">★</span>
                                <span class="ml-1 text-sm"><?= $story['rating'] ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?= $story['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                <?= ucfirst($story['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?= number_format($story['views']) ?></td>
                        <td class="px-6 py-4 text-sm">
                            <button onclick="editStory(<?= $story['id'] ?>)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button onclick="deleteStory(<?= $story['id'] ?>)" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
function openAddModal() {
    window.location.href = 'blog-management.php?action=add';
}

function editStory(id) {
    window.location.href = 'blog-management.php?action=edit&id=' + id;
}

function deleteStory(id) {
    if (confirm('Are you sure you want to delete this story?')) {
        fetch('blog-submit.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=delete&id=' + id
        }).then(() => location.reload());
    }
}
</script>

<?php include 'includes/admin-footer.php'; ?>
