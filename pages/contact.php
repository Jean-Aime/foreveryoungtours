<?php
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
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Send us a Message</h3>
                <p class="text-gray-600 mb-6">Fill out the form below and we'll get back to you within 24 hours</p>
                
                <form id="contactForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" name="first_name" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" name="last_name" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <select name="subject" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="booking">Booking Question</option>
                            <option value="package">Package Information</option>
                            <option value="partnership">Partnership Opportunity</option>
                            <option value="feedback">Feedback</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea name="message" rows="6" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full btn-primary text-white py-4 rounded-lg font-semibold text-lg hover:opacity-90 transition-all">
                        Send Message
                    </button>
                </form>
            </div>
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
    alert('Thank you for your message! We will get back to you within 24 hours.');
    this.reset();
});
</script>

<?php include '../includes/footer.php'; ?>