<?php
session_start();
require_once '../config/database.php';

$page_title = 'Support';
$page_subtitle = 'We\'re Here to Help';

include 'includes/client-header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="nextcloud-card p-6 text-center">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-phone text-blue-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Call Us</h3>
        <p class="text-slate-600 mb-4">+254 700 000 000</p>
        <p class="text-sm text-slate-500">Mon-Fri: 8AM-6PM</p>
    </div>

    <div class="nextcloud-card p-6 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-envelope text-green-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Email Us</h3>
        <p class="text-slate-600 mb-4">support@foreveryoungtours.com</p>
        <p class="text-sm text-slate-500">24-48 hour response</p>
    </div>

    <div class="nextcloud-card p-6 text-center">
        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fab fa-whatsapp text-purple-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">WhatsApp</h3>
        <p class="text-slate-600 mb-4">+254 720 000 000</p>
        <p class="text-sm text-slate-500">Instant messaging</p>
    </div>
</div>

<div class="nextcloud-card p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-6">Send us a Message</h2>
    <form class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Subject</label>
                <select class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option>Booking Question</option>
                    <option>Payment Issue</option>
                    <option>Tour Information</option>
                    <option>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Priority</label>
                <select class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option>Normal</option>
                    <option>Urgent</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Message</label>
            <textarea rows="6" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <button type="submit" class="btn-primary px-6 py-3 rounded-lg">Send Message</button>
    </form>
</div>

<?php include 'includes/client-footer.php'; ?>
