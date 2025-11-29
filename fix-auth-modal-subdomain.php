<?php
$file = 'c:\\xampp1\\htdocs\\foreveryoungtours\\countries\\visit-rw\\pages\\tour-detail.php';
$content = file_get_contents($file);

$old_script = <<<'EOD'
document.querySelectorAll('.book-tour-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tourId = this.getAttribute('data-tour-id');
        const tourName = this.getAttribute('data-tour-name');
        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        
        if (isLoggedIn) {
            openBookingModal(tourId, tourName, 0, '');
        } else {
            openLoginModal();
        }
    });
});
EOD;

$new_script = <<<'EOD'
document.querySelectorAll('.book-tour-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const tourId = this.getAttribute('data-tour-id');
        const tourName = this.getAttribute('data-tour-name');
        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        
        if (isLoggedIn) {
            if (typeof openBookingModal === 'function') {
                openBookingModal(tourId, tourName, 0, '');
            }
        } else {
            openLoginModal();
        }
    });
});
EOD;

$content = str_replace($old_script, $new_script, $content);
file_put_contents($file, $content);
echo "Subdomain auth modal script updated successfully!";
?>
