<?php
session_start();
require_once '../config/database.php';

$page_title = 'Settings';
$page_subtitle = 'Manage Your Preferences';

include 'includes/client-header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="nextcloud-card p-6">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Notification Preferences</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-slate-900">Email Notifications</p>
                    <p class="text-sm text-slate-600">Receive booking updates via email</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-gold"></div>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-slate-900">SMS Notifications</p>
                    <p class="text-sm text-slate-600">Get text alerts for bookings</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-gold"></div>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-slate-900">Marketing Emails</p>
                    <p class="text-sm text-slate-600">Receive special offers and deals</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-gold"></div>
                </label>
            </div>
        </div>
    </div>

    <div class="nextcloud-card p-6">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Privacy Settings</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-slate-900">Profile Visibility</p>
                    <p class="text-sm text-slate-600">Show profile to other travelers</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-gold"></div>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-slate-900">Share Travel History</p>
                    <p class="text-sm text-slate-600">Allow others to see your trips</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-gold"></div>
                </label>
            </div>
        </div>
    </div>

    <div class="nextcloud-card p-6">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Language & Currency</h2>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Language</label>
                <select class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option>English</option>
                    <option>French</option>
                    <option>Spanish</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Currency</label>
                <select class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option>USD ($)</option>
                    <option>EUR (€)</option>
                    <option>GBP (£)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="nextcloud-card p-6">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Account Actions</h2>
        <div class="space-y-3">
            <button class="w-full btn-secondary py-3 rounded-lg">Download My Data</button>
            <button class="w-full bg-red-100 text-red-700 py-3 rounded-lg font-semibold hover:bg-red-200">Delete Account</button>
        </div>
    </div>
</div>

<?php include 'includes/client-footer.php'; ?>
