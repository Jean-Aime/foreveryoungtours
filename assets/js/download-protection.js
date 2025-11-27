// Disable right-click
document.addEventListener('contextmenu', e => e.preventDefault());

// Disable keyboard shortcuts
document.addEventListener('keydown', e => {
    if (e.ctrlKey && (e.key === 's' || e.key === 'u' || e.key === 'a' || e.key === 'c' || e.key === 'p')) {
        e.preventDefault();
    }
    if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
        e.preventDefault();
    }
});

// Disable drag on images
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('img').forEach(img => {
        img.setAttribute('draggable', 'false');
        img.style.userSelect = 'none';
        img.style.pointerEvents = 'none';
    });
});

// Disable text selection
document.body.style.userSelect = 'none';
document.body.style.webkitUserSelect = 'none';
document.body.style.mozUserSelect = 'none';
document.body.style.msUserSelect = 'none';
