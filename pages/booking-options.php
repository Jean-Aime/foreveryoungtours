<?php
$page_title = "Booking Options - iForYoungTours";
$css_path = "../assets/css/modern-styles.css";
include './header.php';
?>

<section class="py-20 bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-slate-900 mb-4">How Would You Like to Book?</h1>
            <p class="text-xl text-slate-600">Choose the option that best fits your needs</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Quick Booking -->
            <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-shadow">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Quick Booking</h2>
                <p class="text-slate-600 mb-6">Perfect for booking a specific tour with fixed dates. Fast checkout process with instant confirmation.</p>
                
                <div class="space-y-3 mb-8">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700">Single-page checkout</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700">Specific tour & dates</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700">Real-time pricing</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700">Instant confirmation</span>
                    </div>
                </div>

                <a href="tour-booking.php" class="block w-full btn-primary text-white text-center px-8 py-4 rounded-lg font-semibold text-lg">
                    Book a Tour Now
                </a>
            </div>

            <!-- Custom Inquiry -->
            <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-shadow">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Custom Inquiry</h2>
                <p class="text-slate-600 mb-6">Need a personalized itinerary? Tell us your preferences and we'll create a custom tour just for you.</p>
                
                <div class="space-y-3 mb-8">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700">Flexible dates</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700">Custom itinerary</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700">Group bookings</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700">Personal consultation</span>
                    </div>
                </div>

                <a href="booking-form.php" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                    Request Custom Tour
                </a>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-12 bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-slate-900 mb-4 text-center">Not Sure Which to Choose?</h3>
            <div class="grid md:grid-cols-2 gap-6 text-center">
                <div>
                    <p class="text-slate-600 mb-2"><strong>Choose Quick Booking if:</strong></p>
                    <ul class="text-sm text-slate-600 space-y-1">
                        <li>✓ You know which tour you want</li>
                        <li>✓ You have specific travel dates</li>
                        <li>✓ You want to book immediately</li>
                    </ul>
                </div>
                <div>
                    <p class="text-slate-600 mb-2"><strong>Choose Custom Inquiry if:</strong></p>
                    <ul class="text-sm text-slate-600 space-y-1">
                        <li>✓ You need a personalized itinerary</li>
                        <li>✓ Your dates are flexible</li>
                        <li>✓ You're booking for a large group</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
