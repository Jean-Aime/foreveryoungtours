<?php 
$page_title = "Community - ForeverYoung Tours";
$page_description = "Join our travel community and connect with fellow African adventure enthusiasts. Access exclusive travel club membership benefits and share your experiences.";
$css_path = "../assets/css/modern-styles.css";
$base_path = "/foreveryoungtours/";
include './header.php'; 
?>

<main class="pt-16">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-green-50 to-emerald-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">Travel Community</h1>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">Connect with fellow travelers and share your African adventures</p>
        </div>
    </section>

    <!-- Community Features -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Travel Club Membership -->
                <div class="nextcloud-card p-8">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Travel Club Membership</h3>
                    <p class="text-slate-600 mb-6">Join our exclusive travel club for special discounts, early access to tours, and member-only events.</p>
                    <button class="btn-primary text-white px-6 py-2 rounded-lg font-semibold w-full">Join Club</button>
                </div>

                <!-- Community Forum -->
                <div class="nextcloud-card p-8">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Travel Forum</h3>
                    <p class="text-slate-600 mb-6">Share experiences, ask questions, and get advice from fellow African travel enthusiasts.</p>
                    <button class="btn-secondary px-6 py-2 rounded-lg font-semibold w-full">Visit Forum</button>
                </div>

                <!-- Photo Gallery -->
                <div class="nextcloud-card p-8">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Photo Gallery</h3>
                    <p class="text-slate-600 mb-6">Browse and share stunning photos from African adventures and inspire others.</p>
                    <button class="btn-secondary px-6 py-2 rounded-lg font-semibold w-full">View Gallery</button>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>