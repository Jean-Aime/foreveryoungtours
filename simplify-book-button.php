<?php
$file = 'c:\\xampp1\\htdocs\\foreveryoungtours\\pages\\tour-detail.php';
$content = file_get_contents($file);

// Replace the book-tour-btn button with a simple link
$old_button = <<<'EOD'
<button class="book-tour-btn bg-golden-500 hover:bg-golden-600 text-black px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center" data-tour-id="<?php echo $tour['id']; ?>" data-tour-name="<?php echo htmlspecialchars($tour['name']); ?>" data-tour-image="<?php echo htmlspecialchars(getImageUrl($tour['cover_image'] ?: $tour['image_url'])); ?>" data-tour-desc="<?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>">
                            Book This Tour
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </button>
EOD;

$new_button = <<<'EOD'
<a href="<?php echo isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'client' ? '#' : 'auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']); ?>" class="book-tour-btn bg-golden-500 hover:bg-golden-600 text-black px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center" onclick="<?php echo isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'client' ? 'openBookingModal(' . $tour['id'] . ', \'' . addslashes($tour['name']) . '\', 0, \'\'); return false;' : ''; ?>">
                            Book This Tour
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
EOD;

$content = str_replace($old_button, $new_button, $content);

// Also replace the sidebar button
$old_sidebar = <<<'EOD'
<button class="book-tour-btn btn-primary w-full py-4 rounded-lg font-bold text-lg mb-3" data-tour-id="<?php echo $tour['id']; ?>" data-tour-name="<?php echo htmlspecialchars($tour['name']); ?>" data-tour-image="<?php echo htmlspecialchars(getImageUrl($tour['cover_image'] ?: $tour['image_url'])); ?>" data-tour-desc="<?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>">
                        Book This Tour
                    </button>
EOD;

$new_sidebar = <<<'EOD'
<a href="<?php echo isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'client' ? '#' : 'auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']); ?>" class="book-tour-btn btn-primary w-full py-4 rounded-lg font-bold text-lg mb-3 block text-center" onclick="<?php echo isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'client' ? 'openBookingModal(' . $tour['id'] . ', \'' . addslashes($tour['name']) . '\', 0, \'\'); return false;' : ''; ?>">
                        Book This Tour
                    </a>
EOD;

$content = str_replace($old_sidebar, $new_sidebar, $content);

file_put_contents($file, $content);
echo "Book button simplified to direct login link!";
?>
