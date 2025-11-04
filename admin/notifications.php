<?php
$page_title = 'Notifications';
$page_subtitle = 'System Alerts & Activities';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 p-6 md:p-8 overflow-y-auto">
    <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Notifications</h2>
                <p class="text-gray-600">Stay updated with system alerts and activities</p>
            </div>

            <!-- Notification Filters -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Filter Notifications</h3>
                    <button onclick="markAllAsRead()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Mark All as Read</button>
                </div>
                <div class="flex space-x-4">
                    <button onclick="filterNotifications('all')" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium">All</button>
                    <button onclick="filterNotifications('booking')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">Bookings</button>
                    <button onclick="filterNotifications('system')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">System</button>
                    <button onclick="filterNotifications('payment')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">Payments</button>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Notifications</h3>
                </div>
                <div id="notificationsList" class="divide-y divide-gray-200">
                    <!-- Notifications will be loaded here -->
                </div>
            </div>
    </div>
</main>

<script>
// Notification Management
class NotificationManager {
    constructor() {
        this.notifications = [
            {
                id: 1,
                type: 'booking',
                title: 'New Booking Received',
                message: 'Safari Adventure tour booked by John Smith for March 15, 2024',
                time: '5 minutes ago',
                read: false,
                icon: 'booking'
            },
            {
                id: 2,
                type: 'payment',
                title: 'Payment Confirmed',
                message: 'Payment of $2,500 confirmed for Victoria Falls Tour',
                time: '1 hour ago',
                read: false,
                icon: 'payment'
            },
            {
                id: 3,
                type: 'system',
                title: 'System Backup Completed',
                message: 'Daily backup completed successfully at 2:00 AM',
                time: '3 hours ago',
                read: true,
                icon: 'system'
            },
            {
                id: 4,
                type: 'booking',
                title: 'Booking Cancelled',
                message: 'Cape Town Explorer booking cancelled by Sarah Johnson',
                time: '5 hours ago',
                read: true,
                icon: 'cancel'
            }
        ];
        this.loadNotifications();
    }

    loadNotifications(filter = 'all') {
        const container = document.getElementById('notificationsList');
        let filteredNotifications = this.notifications;
        
        if (filter !== 'all') {
            filteredNotifications = this.notifications.filter(n => n.type === filter);
        }

        container.innerHTML = filteredNotifications.map(notification => `
            <div class="p-6 hover:bg-gray-50 transition-colors ${!notification.read ? 'bg-blue-50' : ''}">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 ${this.getIconColor(notification.type)} rounded-full flex items-center justify-center flex-shrink-0">
                        ${this.getIcon(notification.icon)}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900 ${!notification.read ? 'font-semibold' : ''}">${notification.title}</h4>
                            <span class="text-xs text-gray-500">${notification.time}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                        <div class="flex items-center space-x-3 mt-3">
                            ${!notification.read ? '<button onclick="markAsRead(' + notification.id + ')" class="text-xs text-blue-600 hover:text-blue-800">Mark as Read</button>' : ''}
                            <button onclick="deleteNotification(${notification.id})" class="text-xs text-red-600 hover:text-red-800">Delete</button>
                        </div>
                    </div>
                    ${!notification.read ? '<div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>' : ''}
                </div>
            </div>
        `).join('');
    }

    getIconColor(type) {
        const colors = {
            booking: 'bg-green-100',
            payment: 'bg-blue-100',
            system: 'bg-gray-100',
            cancel: 'bg-red-100'
        };
        return colors[type] || 'bg-gray-100';
    }

    getIcon(type) {
        const icons = {
            booking: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            payment: '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>',
            system: '<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            cancel: '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        };
        return icons[type] || icons.system;
    }

    markAsRead(id) {
        const notification = this.notifications.find(n => n.id === id);
        if (notification) {
            notification.read = true;
            this.loadNotifications();
        }
    }

    markAllAsRead() {
        this.notifications.forEach(n => n.read = true);
        this.loadNotifications();
    }

    deleteNotification(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
        this.loadNotifications();
    }

    filterNotifications(type) {
        // Update active filter button
        document.querySelectorAll('button[onclick^="filterNotifications"]').forEach(btn => {
            btn.className = 'px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200';
        });
        event.target.className = 'px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium';
        
        this.loadNotifications(type);
    }
}

// Global functions
function markAsRead(id) {
    notificationManager.markAsRead(id);
}

function markAllAsRead() {
    notificationManager.markAllAsRead();
}

function deleteNotification(id) {
    notificationManager.deleteNotification(id);
}

function filterNotifications(type) {
    notificationManager.filterNotifications(type);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.notificationManager = new NotificationManager();
});
</script>

<?php require_once 'includes/admin-footer.php'; ?>