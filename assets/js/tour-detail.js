// Gallery Images
let galleryImages = [];
let currentImageIndex = 0;

function initGallery(images) {
    galleryImages = images;
}

function openImageModal(index) {
    currentImageIndex = index;
    document.getElementById('imageModal').classList.remove('hidden');
    updateModalImage();
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
    updateModalImage();
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
    updateModalImage();
}

function updateModalImage() {
    let imageSrc = galleryImages[currentImageIndex];
    if (imageSrc.startsWith('uploads/')) {
        if (window.location.host.indexOf('foreveryoungtours.local') !== -1) {
            imageSrc = imageSrc;
        } else {
            imageSrc = '../' + imageSrc;
        }
    } else if (imageSrc.startsWith('../assets/')) {
        if (window.location.host.indexOf('foreveryoungtours.local') !== -1) {
            imageSrc = imageSrc.replace('../', '');
        }
    }
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageCounter').textContent = `${currentImageIndex + 1} / ${galleryImages.length}`;
}

// Login Modal
function openLoginModal() {
    const modal = document.getElementById('loginModal');
    modal.classList.remove('hidden');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    const modal = document.getElementById('loginModal');
    modal.classList.add('hidden');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('imageModal').classList.contains('hidden')) return;
    
    if (e.key === 'Escape') closeImageModal();
    if (e.key === 'ArrowLeft') prevImage();
    if (e.key === 'ArrowRight') nextImage();
});
