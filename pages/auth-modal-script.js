document.querySelectorAll('.book-tour-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const tourId = this.getAttribute('data-tour-id');
        const tourName = this.getAttribute('data-tour-name');
        const tourImage = this.getAttribute('data-tour-image');
        const tourDesc = this.getAttribute('data-tour-desc');
        const isLoggedIn = document.body.getAttribute('data-logged-in') === 'true';
        
        if (isLoggedIn) {
            if (typeof openBookingModal === 'function') {
                openBookingModal(tourId, tourName, 0, '');
            }
        } else {
            showAuthModal(tourName, tourImage, tourDesc);
        }
    });
});

function showAuthModal(tourName, tourImage, tourDesc) {
    const modal = document.createElement('div');
    modal.id = 'authModal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    const currentUrl = window.location.href;
    modal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
            <button onclick="document.getElementById('authModal').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            <img src="${tourImage}" class="w-full h-40 object-cover rounded-lg mb-4" onerror="this.src='https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=500&q=60'">
            <h3 class="text-xl font-bold text-gray-900 mb-2">${tourName}</h3>
            <p class="text-sm text-gray-600 mb-6">${tourDesc}</p>
            <p class="text-gray-700 mb-6 font-semibold">Please login or create an account to book this tour.</p>
            <div class="space-y-3">
                <a href="auth/login.php?redirect=${encodeURIComponent(currentUrl)}" class="block w-full bg-yellow-500 hover:bg-yellow-600 text-black py-3 rounded-lg font-semibold text-center transition-colors">Login</a>
                <a href="auth/register.php?redirect=${encodeURIComponent(currentUrl)}" class="block w-full border-2 border-yellow-500 text-yellow-600 hover:bg-yellow-50 py-3 rounded-lg font-semibold text-center transition-colors">Create Account</a>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}
