<?php

require_once 'config.php';
$page_title = "Contact Us - iForYoungTours | Get in Touch";
$page_description = "Contact iForYoungTours for inquiries about African travel packages, bookings, and travel advice. We're here to help plan your perfect African adventure.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1577563908411-5077b6dc7624?auto=format&fit=crop&w=2072&q=80" alt="Contact Us" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-5xl font-bold text-white mb-6">
            Get in <span class="text-gradient bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">Touch</span>
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 max-w-3xl mx-auto">
            Our travel experts are ready to help plan your perfect African adventure
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-gradient-to-br from-slate-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-100 to-orange-100 rounded-full mb-4">
                <span class="text-sm font-semibold text-yellow-800">Contact Us</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Send us a <span class="text-gradient bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">Message</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Fill out the form below and we'll get back to you within 24 hours
            </p>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-container bg-white rounded-3xl shadow-2xl p-8 md:p-12 relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-200/30 to-orange-200/30 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-yellow-100/40 to-orange-100/40 rounded-full translate-y-12 -translate-x-12"></div>
            
            <form id="contactForm" class="space-y-8 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-800 mb-3">First Name *</label>
                        <input type="text" name="first_name" required class="contact-input w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition-all duration-300 bg-gray-50 focus:bg-white">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Last Name *</label>
                        <input type="text" name="last_name" required class="contact-input w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition-all duration-300 bg-gray-50 focus:bg-white">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Email *</label>
                        <input type="email" name="email" required class="contact-input w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition-all duration-300 bg-gray-50 focus:bg-white">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Phone</label>
                        <input type="tel" name="phone" class="contact-input w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition-all duration-300 bg-gray-50 focus:bg-white">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="block text-sm font-semibold text-gray-800 mb-3">Subject *</label>
                    <select name="subject" required class="contact-input w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition-all duration-300 bg-gray-50 focus:bg-white">
                        <option value="">Select a subject</option>
                        <option value="general">General Inquiry</option>
                        <option value="booking">Booking Question</option>
                        <option value="package">Package Information</option>
                        <option value="partnership">Partnership Opportunity</option>
                        <option value="feedback">Feedback</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="block text-sm font-semibold text-gray-800 mb-3">Message *</label>
                    <textarea name="message" rows="6" required class="contact-input w-full border-2 border-gray-200 rounded-xl px-4 py-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition-all duration-300 bg-gray-50 focus:bg-white resize-none" placeholder="Tell us about your travel plans or questions..."></textarea>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="contact-submit-btn w-full btn-primary text-black py-5 rounded-xl font-bold text-lg hover:transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Send Message
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Visit Our Office</h2>
            <p class="text-xl text-gray-600">Find us in the heart of Kigali</p>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-lg h-96">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3987.5084!2d30.0619!3d-1.9536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca425b2f47eb1%3A0x3f0e3c3e3e3e3e3e!2sKN%2078%20St%2C%20Kigali%2C%20Rwanda!5e0!3m2!1sen!2srw!4v1234567890" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('.contact-submit-btn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = `
        <span class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Sending...
        </span>
    `;
    submitBtn.disabled = true;
    
    // Simulate form submission
    setTimeout(() => {
        // Show success state
        submitBtn.innerHTML = `
            <span class="flex items-center justify-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Message Sent!
            </span>
        `;
        submitBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        
        // Reset form and button after 2 seconds
        setTimeout(() => {
            this.reset();
            submitBtn.innerHTML = originalText;
            submitBtn.style.background = '';
            submitBtn.disabled = false;
            
            // Show success message
            const successMsg = document.createElement('div');
            successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
            successMsg.innerHTML = '✓ Thank you! We\'ll get back to you within 24 hours.';
            document.body.appendChild(successMsg);
            
            setTimeout(() => successMsg.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                successMsg.classList.add('translate-x-full');
                setTimeout(() => document.body.removeChild(successMsg), 300);
            }, 4000);
        }, 2000);
    }, 1500);
});

// Add input animations
document.querySelectorAll('.contact-input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
        if (!this.value) {
            this.parentElement.classList.remove('focused');
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>