    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                <!-- Column 1: Logo & Description -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="<?php echo $base_path; ?>assets/images/logo.png" alt="Forever Young Tours Logo" class="w-12 h-12">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Forever Young Tours</h3>
                            <p class="text-sm text-gray-600">Travel Bold. Stay Forever Young.</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        Discover the world with our luxury group travel experiences. From adventure tours to cultural exchanges, we create unforgettable journeys that connect you with amazing destinations and fellow travelers.
                    </p>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-3">WE ACCEPT</p>
                        <div class="flex space-x-3 items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="h-6 w-auto object-contain">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple Pay" class="h-6 w-4 object-contain">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-6 w-16 object-contain">
                        </div>
                    </div>
                </div>
                
                <!-- Column 2: Tour Types & Resources -->
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Tour Categories</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/packages.php?category=adventure" class="text-gray-600 hover:text-yellow-600 transition-colors">Adventure Tours</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/packages.php?category=luxury" class="text-gray-600 hover:text-yellow-600 transition-colors">Luxury Travel</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/packages.php?category=cultural" class="text-gray-600 hover:text-yellow-600 transition-colors">Cultural Tours</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/packages.php?category=wildlife" class="text-gray-600 hover:text-yellow-600 transition-colors">Wildlife Safari</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/packages.php?category=agro" class="text-gray-600 hover:text-yellow-600 transition-colors">Agro-Tourism</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/store.php" class="text-gray-600 hover:text-yellow-600 transition-colors">Travel Store</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Resources</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/resources.php" class="text-gray-600 hover:text-yellow-600 transition-colors">Travel Resources</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/resources.php#visa" class="text-gray-600 hover:text-yellow-600 transition-colors">VISA Information</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/resources.php#safety" class="text-gray-600 hover:text-yellow-600 transition-colors">Travel Safety</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/faq.php" class="text-gray-600 hover:text-yellow-600 transition-colors">FAQ</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/terms.php" class="text-gray-600 hover:text-yellow-600 transition-colors">Terms & Conditions</a></li>
                            <li><a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/privacy.php" class="text-gray-600 hover:text-yellow-600 transition-colors">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Column 3: Contact & Newsletter -->
                <div>
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-0">
                            <input type="email" placeholder="Enter your Email" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg sm:rounded-l-lg sm:rounded-r-none text-sm focus:outline-none focus:border-yellow-500 min-w-0">
                            <button class="bg-yellow-500 text-white px-4 sm:px-6 py-2 rounded-lg sm:rounded-l-none sm:rounded-r-lg font-semibold hover:bg-yellow-600 transition-colors whitespace-nowrap">Subscribe</button>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-3">Follow us on</p>
                        <div class="flex space-x-3">
                            <a href="https://facebook.com/iforeveryoungtours" target="_blank" class="text-yellow-500 hover:text-yellow-600 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="https://instagram.com/iforeveryoungtours" target="_blank" class="text-yellow-500 hover:text-yellow-600 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <a href="https://linkedin.com/company/iforeveryoungtours" target="_blank" class="text-yellow-500 hover:text-yellow-600 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            <a href="https://twitter.com/iforeveryoung" target="_blank" class="text-yellow-500 hover:text-yellow-600 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <div class="border-t border-gray-200 mt-8 pt-6 text-center">
                <p class="text-sm text-gray-600">
                    © 2025 Forever Young Tours LTD. All rights reserved
                    <span class="mx-2">|</span>
                    <a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/terms.php" class="text-yellow-600 hover:underline">Terms & Conditions</a>
                    <span class="mx-2">|</span>
                    <a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/privacy.php" class="text-yellow-600 hover:underline">Privacy Policy</a>
                    <span class="mx-2">|</span>
                    <a href="<?php echo isset($base_path) ? $base_path : '/foreveryoungtours/'; ?>pages/faq.php" class="text-yellow-600 hover:underline">FAQ</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button with Dropup -->
    <div id="whatsapp-floating-button" class="whatsapp-fixed-button">
        <!-- Dropdown Card -->
        <div id="whatsappDropup" class="hidden mb-4 bg-white rounded-2xl shadow-2xl w-80 animate-slide-up">
            <div class="bg-green-500 text-white px-6 py-4 rounded-t-2xl">
                <h3 class="font-bold text-lg">WhatsApp Support</h3>
                <p class="text-sm text-white/90">We're here to help!</p>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4 text-sm">Get instant answers about tours, pricing, and availability.</p>
                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        <span>24/7 Support</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        <span>Expert Advisors</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        <span>Quick Response</span>
                    </div>
                </div>
                <a href="https://wa.me/17374439646?text=Hi!%20I%20need%20help" class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 rounded-xl font-bold transition text-sm">
                    <i class="fab fa-whatsapp mr-2"></i>Start Chat
                </a>
                <p class="text-center text-gray-500 text-xs mt-3">+1 (737) 443-9646</p>
            </div>
        </div>
        
        <!-- WhatsApp Button -->
        <button onclick="toggleWhatsAppDropup()" class="whatsapp-main-button bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:bg-green-600 transition transform hover:scale-110">
            <i class="fab fa-whatsapp text-3xl"></i>
        </button>
    </div>

    <style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
        animation: slideUp 0.3s ease-out;
    }
    
    /* WhatsApp Button - Ultra Strong Fixed Positioning */
    .whatsapp-fixed-button,
    #whatsapp-floating-button {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        z-index: 999999 !important;
        display: block !important;
        width: auto !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        transform: none !important;
        -webkit-transform: none !important;
        top: auto !important;
        left: auto !important;
        float: none !important;
        clear: none !important;
    }
    
    /* Button inside container */
    .whatsapp-fixed-button button,
    #whatsapp-floating-button button {
        position: relative !important;
        z-index: 1000000 !important;
        width: 64px !important;
        height: 64px !important;
        border-radius: 50% !important;
        background-color: #25d366 !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
    }
    
    .whatsapp-fixed-button button:hover,
    #whatsapp-floating-button button:hover {
        background-color: #128c7e !important;
        transform: scale(1.1) !important;
    }
    
    /* Mobile specific overrides */
    @media screen and (max-width: 768px) {
        .whatsapp-fixed-button,
        #whatsapp-floating-button {
            position: fixed !important;
            bottom: 15px !important;
            right: 15px !important;
            z-index: 999999 !important;
        }
        
        .whatsapp-fixed-button button,
        #whatsapp-floating-button button {
            width: 56px !important;
            height: 56px !important;
        }
        
        /* Dropup positioning for mobile */
        .whatsapp-fixed-button #whatsappDropup,
        #whatsapp-floating-button #whatsappDropup {
            position: fixed !important;
            bottom: 80px !important;
            right: 15px !important;
            z-index: 999998 !important;
            width: calc(100vw - 30px) !important;
            max-width: 300px !important;
        }
    }
    
    /* Tablet overrides */
    @media screen and (min-width: 769px) and (max-width: 1024px) {
        .whatsapp-fixed-button,
        #whatsapp-floating-button {
            bottom: 20px !important;
            right: 20px !important;
        }
    }
    
    /* Desktop overrides */
    @media screen and (min-width: 1025px) {
        .whatsapp-fixed-button,
        #whatsapp-floating-button {
            bottom: 25px !important;
            right: 25px !important;
        }
    }
    
    /* Nuclear option - override everything */
    html .whatsapp-fixed-button,
    body .whatsapp-fixed-button,
    div .whatsapp-fixed-button,
    section .whatsapp-fixed-button,
    main .whatsapp-fixed-button,
    html #whatsapp-floating-button,
    body #whatsapp-floating-button,
    div #whatsapp-floating-button,
    section #whatsapp-floating-button,
    main #whatsapp-floating-button {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        z-index: 999999 !important;
    }
    </style>

    <script>
    // Immediate WhatsApp button fix - runs as soon as script loads
    (function() {
        function immediateWhatsAppFix() {
            const whatsappButton = document.getElementById('whatsapp-floating-button');
            if (whatsappButton) {
                whatsappButton.style.cssText = `
                    position: fixed !important;
                    bottom: ${window.innerWidth <= 768 ? '15px' : '20px'} !important;
                    right: ${window.innerWidth <= 768 ? '15px' : '20px'} !important;
                    z-index: 999999 !important;
                    display: block !important;
                    transform: none !important;
                    -webkit-transform: none !important;
                `;
            }
        }
        
        // Run immediately
        immediateWhatsAppFix();
        
        // Run when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', immediateWhatsAppFix);
        } else {
            immediateWhatsAppFix();
        }
    })();
    
    function toggleWhatsAppDropup() {
        const dropup = document.getElementById('whatsappDropup');
        dropup.classList.toggle('hidden');
    }
    
    // Ensure WhatsApp button stays fixed on mobile
    function enforceWhatsAppPosition() {
        const whatsappContainer = document.getElementById('whatsapp-floating-button');
        if (whatsappContainer) {
            // Force fixed positioning with ultra-strong rules
            whatsappContainer.style.position = 'fixed';
            whatsappContainer.style.zIndex = '999999';
            whatsappContainer.style.display = 'block';
            whatsappContainer.style.transform = 'none';
            whatsappContainer.style.webkitTransform = 'none';
            
            // Adjust for different screen sizes
            if (window.innerWidth <= 768) {
                whatsappContainer.style.position = 'fixed';
                whatsappContainer.style.bottom = '15px';
                whatsappContainer.style.right = '15px';
            } else if (window.innerWidth <= 1280) {
                whatsappContainer.style.position = 'fixed';
                whatsappContainer.style.bottom = '20px';
                whatsappContainer.style.right = '20px';
            } else {
                whatsappContainer.style.position = 'fixed';
                whatsappContainer.style.bottom = '25px';
                whatsappContainer.style.right = '25px';
            }
            
            // Also ensure the button inside is properly styled
            const button = whatsappContainer.querySelector('button');
            if (button) {
                button.style.position = 'relative';
                button.style.zIndex = '1000000';
            }
        }
    }
    
    // Run on page load
    document.addEventListener('DOMContentLoaded', enforceWhatsAppPosition);
    
    // Run on window resize
    window.addEventListener('resize', enforceWhatsAppPosition);
    
    // Run on scroll (for extra safety)
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(enforceWhatsAppPosition, 100);
    });
    
    document.addEventListener('click', function(e) {
        const dropup = document.getElementById('whatsappDropup');
        const button = e.target.closest('button[onclick="toggleWhatsAppDropup()"]');
        if (!button && !dropup.contains(e.target)) {
            dropup.classList.add('hidden');
        }
    });
    </script>

    <!-- Scripts -->
    <script src="<?php echo isset($js_path) ? $js_path : 'assets/js/main.js'; ?>"></script>
    <script>
    // Prevent duplicate WhatsApp dropup script
    if (typeof toggleWhatsAppDropup === 'undefined') {
        function toggleWhatsAppDropup() {
            const dropup = document.getElementById('whatsappDropup');
            if (dropup) dropup.classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const dropup = document.getElementById('whatsappDropup');
            const button = e.target.closest('button[onclick="toggleWhatsAppDropup()"]');
            if (dropup && !button && !dropup.contains(e.target)) {
                dropup.classList.add('hidden');
            }
        });
    }
    </script>
</body>
</html>