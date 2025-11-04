// Admin Dashboard Functions
class AdminManager {
    constructor() {
        this.initializeData();
        this.bindEvents();
        this.loadDashboard();
    }

    initializeData() {
        // Initialize sample data if not exists
        if (!localStorage.getItem('admin_tours')) {
            const sampleTours = [
                { id: 1, name: 'Safari Adventure', destination: 'Kenya', price: 2500, duration: 7, status: 'active', bookings: 45 },
                { id: 2, name: 'Victoria Falls Tour', destination: 'Zimbabwe', price: 1800, duration: 5, status: 'active', bookings: 32 },
                { id: 3, name: 'Cape Town Explorer', destination: 'South Africa', price: 2200, duration: 6, status: 'active', bookings: 28 }
            ];
            localStorage.setItem('admin_tours', JSON.stringify(sampleTours));
        }

        if (!localStorage.getItem('admin_users')) {
            const sampleUsers = [
                { id: 1, name: 'John Smith', email: 'john@email.com', role: 'user', status: 'active', joinDate: '2024-01-15' },
                { id: 2, name: 'Sarah Johnson', email: 'sarah@email.com', role: 'user', status: 'active', joinDate: '2024-02-20' },
                { id: 3, name: 'Mike Admin', email: 'admin@email.com', role: 'admin', status: 'active', joinDate: '2024-01-01' }
            ];
            localStorage.setItem('admin_users', JSON.stringify(sampleUsers));
        }

        if (!localStorage.getItem('admin_bookings')) {
            const sampleBookings = [
                { id: 1, tourId: 1, userId: 1, status: 'confirmed', date: '2024-03-15', amount: 2500 },
                { id: 2, tourId: 2, userId: 2, status: 'pending', date: '2024-03-20', amount: 1800 },
                { id: 3, tourId: 1, userId: 1, status: 'completed', date: '2024-02-10', amount: 2500 }
            ];
            localStorage.setItem('admin_bookings', JSON.stringify(sampleBookings));
        }

        if (!localStorage.getItem('admin_partners')) {
            const samplePartners = [
                { id: 1, name: 'Safari Lodge Kenya', type: 'Accommodation', status: 'active', commission: 15 },
                { id: 2, name: 'African Airways', type: 'Transport', status: 'active', commission: 10 },
                { id: 3, name: 'Local Guide Services', type: 'Guide', status: 'active', commission: 20 }
            ];
            localStorage.setItem('admin_partners', JSON.stringify(samplePartners));
        }
    }

    bindEvents() {
        // Tour management
        const addTourForm = document.getElementById('addTourForm');
        if (addTourForm) {
            addTourForm.addEventListener('submit', (e) => this.addTour(e));
        }

        // User management
        const addUserForm = document.getElementById('addUserForm');
        if (addUserForm) {
            addUserForm.addEventListener('submit', (e) => this.addUser(e));
        }

        // Partner management
        const addPartnerForm = document.getElementById('addPartnerForm');
        if (addPartnerForm) {
            addPartnerForm.addEventListener('submit', (e) => this.addPartner(e));
        }
    }

    loadDashboard() {
        this.updateKPIs();
        this.loadCharts();
        this.loadRecentActivity();
    }

    updateKPIs() {
        const tours = JSON.parse(localStorage.getItem('admin_tours') || '[]');
        const users = JSON.parse(localStorage.getItem('admin_users') || '[]');
        const bookings = JSON.parse(localStorage.getItem('admin_bookings') || '[]');
        
        const totalRevenue = bookings.reduce((sum, booking) => sum + booking.amount, 0);
        const activeBookings = bookings.filter(b => b.status === 'confirmed' || b.status === 'pending').length;

        // Animate counters
        this.animateCounter('totalTours', tours.length);
        this.animateCounter('totalUsers', users.length);
        this.animateCounter('activeBookings', activeBookings);
        this.animateCounter('totalRevenue', totalRevenue, '$');
    }

    animateCounter(elementId, target, prefix = '') {
        const element = document.getElementById(elementId);
        if (!element) return;

        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = prefix + Math.floor(current).toLocaleString();
        }, 20);
    }

    loadCharts() {
        this.loadRevenueChart();
        this.loadBookingsChart();
    }

    loadRevenueChart() {
        const chartElement = document.getElementById('revenue-chart');
        if (!chartElement) return;

        const chart = echarts.init(chartElement);
        const option = {
            tooltip: { trigger: 'axis' },
            xAxis: {
                type: 'category',
                data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
            },
            yAxis: { type: 'value' },
            series: [{
                data: [12000, 19000, 15000, 25000, 22000, 30000],
                type: 'line',
                smooth: true,
                itemStyle: { color: '#3B82F6' }
            }]
        };
        chart.setOption(option);
    }

    loadBookingsChart() {
        const chartElement = document.getElementById('bookings-chart');
        if (!chartElement) return;

        const chart = echarts.init(chartElement);
        const option = {
            tooltip: { trigger: 'item' },
            series: [{
                type: 'pie',
                radius: '70%',
                data: [
                    { value: 45, name: 'Confirmed' },
                    { value: 25, name: 'Pending' },
                    { value: 15, name: 'Cancelled' },
                    { value: 35, name: 'Completed' }
                ]
            }]
        };
        chart.setOption(option);
    }

    loadRecentActivity() {
        const container = document.getElementById('recentBookings');
        if (!container) return;

        const bookings = JSON.parse(localStorage.getItem('admin_bookings') || '[]');
        const tours = JSON.parse(localStorage.getItem('admin_tours') || '[]');
        const users = JSON.parse(localStorage.getItem('admin_users') || '[]');

        const recentBookings = bookings.slice(-5).reverse();
        
        container.innerHTML = recentBookings.map(booking => {
            const tour = tours.find(t => t.id === booking.tourId);
            const user = users.find(u => u.id === booking.userId);
            return `
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">${user?.name || 'Unknown User'}</p>
                            <p class="text-sm text-gray-600">${tour?.name || 'Unknown Tour'}</p>
                            <p class="text-xs text-gray-500">${booking.date}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">$${booking.amount.toLocaleString()}</p>
                        <span class="px-2 py-1 text-xs rounded-full ${this.getStatusColor(booking.status)}">${booking.status}</span>
                    </div>
                </div>
            `;
        }).join('');
        
        this.loadTopTours();
    }
    
    loadTopTours() {
        const container = document.getElementById('topTours');
        if (!container) return;

        const tours = JSON.parse(localStorage.getItem('admin_tours') || '[]');
        const topTours = tours.sort((a, b) => (b.bookings || 0) - (a.bookings || 0)).slice(0, 3);
        
        container.innerHTML = topTours.map((tour, index) => {
            const colors = ['bg-yellow-100 text-yellow-800', 'bg-gray-100 text-gray-800', 'bg-orange-100 text-orange-800'];
            const medals = ['🥇', '🥈', '🥉'];
            return `
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-2xl">${medals[index]}</span>
                        <span class="px-2 py-1 text-xs rounded-full ${colors[index]}">#${index + 1}</span>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">${tour.name}</h4>
                    <p class="text-sm text-gray-600 mb-3">${tour.destination}</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-gray-500">Bookings</p>
                            <p class="font-semibold">${tour.bookings || 0}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Revenue</p>
                            <p class="font-semibold">$${((tour.bookings || 0) * tour.price).toLocaleString()}</p>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    getStatusColor(status) {
        const colors = {
            confirmed: 'bg-green-100 text-green-800',
            pending: 'bg-yellow-100 text-yellow-800',
            cancelled: 'bg-red-100 text-red-800',
            completed: 'bg-blue-100 text-blue-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }

    // Tour Management
    async addTour(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        const tourData = {
            name: formData.get('name'),
            description: formData.get('description') || '',
            destination: formData.get('destination'),
            price: parseFloat(formData.get('price')),
            duration: parseInt(formData.get('duration')),
            max_participants: parseInt(formData.get('max_participants')) || 20
        };

        try {
            const response = await fetch('../api/admin_sync.php?action=tour', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(tourData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Also update localStorage for immediate UI update
                const tours = JSON.parse(localStorage.getItem('admin_tours') || '[]');
                tours.push({...tourData, id: result.id, status: 'active', bookings: 0});
                localStorage.setItem('admin_tours', JSON.stringify(tours));
                
                this.closeModal('addTourModal');
                this.showNotification('Tour added successfully and synced to website!', 'success');
                this.loadTours();
            } else {
                throw new Error('Failed to add tour');
            }
        } catch (error) {
            this.showNotification('Error adding tour: ' + error.message, 'error');
        }
    }

    loadTours() {
        const container = document.getElementById('toursContainer');
        if (!container) return;

        const tours = JSON.parse(localStorage.getItem('admin_tours') || '[]');
        
        container.innerHTML = tours.map(tour => `
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">${tour.name}</h3>
                        <p class="text-gray-600">${tour.destination}</p>
                    </div>
                    <span class="px-3 py-1 text-sm rounded-full ${tour.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${tour.status}</span>
                </div>
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Price</p>
                        <p class="font-semibold">$${tour.price}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Duration</p>
                        <p class="font-semibold">${tour.duration} days</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Bookings</p>
                        <p class="font-semibold">${tour.bookings}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="adminManager.editTour(${tour.id})" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Edit</button>
                    <button onclick="adminManager.deleteTour(${tour.id})" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">Delete</button>
                </div>
            </div>
        `).join('');
    }

    async deleteTour(id) {
        if (confirm('Are you sure you want to delete this tour? It will be removed from the website.')) {
            try {
                const response = await fetch(`../api/admin_sync.php?action=tour&id=${id}`, {
                    method: 'DELETE'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Update localStorage
                    const tours = JSON.parse(localStorage.getItem('admin_tours') || '[]');
                    const updatedTours = tours.filter(tour => tour.id !== id);
                    localStorage.setItem('admin_tours', JSON.stringify(updatedTours));
                    
                    this.loadTours();
                    this.showNotification('Tour deleted successfully and removed from website!', 'success');
                } else {
                    throw new Error('Failed to delete tour');
                }
            } catch (error) {
                this.showNotification('Error deleting tour: ' + error.message, 'error');
            }
        }
    }

    // User Management
    addUser(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const users = JSON.parse(localStorage.getItem('admin_users') || '[]');
        
        const newUser = {
            id: Date.now(),
            name: formData.get('name'),
            email: formData.get('email'),
            role: formData.get('role'),
            status: 'active',
            joinDate: new Date().toISOString().split('T')[0]
        };

        users.push(newUser);
        localStorage.setItem('admin_users', JSON.stringify(users));
        
        this.closeModal('addUserModal');
        this.showNotification('User added successfully!', 'success');
        this.loadUsers();
    }

    loadUsers() {
        const container = document.getElementById('usersContainer');
        if (!container) return;

        const users = JSON.parse(localStorage.getItem('admin_users') || '[]');
        
        container.innerHTML = users.map(user => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium">${user.name.charAt(0)}</span>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${user.name}</div>
                            <div class="text-sm text-gray-500">${user.email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full ${user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'}">${user.role}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full ${user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${user.status}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.joinDate}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="adminManager.editUser(${user.id})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                    <button onclick="adminManager.deleteUser(${user.id})" class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            const users = JSON.parse(localStorage.getItem('admin_users') || '[]');
            const updatedUsers = users.filter(user => user.id !== id);
            localStorage.setItem('admin_users', JSON.stringify(updatedUsers));
            this.loadUsers();
            this.showNotification('User deleted successfully!', 'success');
        }
    }

    // Booking Management
    loadBookings() {
        const container = document.getElementById('bookingsContainer');
        if (!container) return;

        const bookings = JSON.parse(localStorage.getItem('admin_bookings') || '[]');
        const tours = JSON.parse(localStorage.getItem('admin_tours') || '[]');
        const users = JSON.parse(localStorage.getItem('admin_users') || '[]');
        
        container.innerHTML = bookings.map(booking => {
            const tour = tours.find(t => t.id === booking.tourId);
            const user = users.find(u => u.id === booking.userId);
            return `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${booking.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user?.name || 'Unknown'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tour?.name || 'Unknown'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${booking.date}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$${booking.amount}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full ${this.getStatusColor(booking.status)}">${booking.status}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <select onchange="adminManager.updateBookingStatus(${booking.id}, this.value)" class="text-sm border rounded px-2 py-1">
                            <option value="pending" ${booking.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="confirmed" ${booking.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                            <option value="completed" ${booking.status === 'completed' ? 'selected' : ''}>Completed</option>
                            <option value="cancelled" ${booking.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>
                    </td>
                </tr>
            `;
        }).join('');
    }

    async updateBookingStatus(id, status) {
        try {
            const response = await fetch('../api/admin_sync.php?action=booking_status', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id, status: status })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Update localStorage
                const bookings = JSON.parse(localStorage.getItem('admin_bookings') || '[]');
                const booking = bookings.find(b => b.id === id);
                if (booking) {
                    booking.status = status;
                    localStorage.setItem('admin_bookings', JSON.stringify(bookings));
                }
                
                this.showNotification('Booking status updated in database!', 'success');
            } else {
                throw new Error('Failed to update booking status');
            }
        } catch (error) {
            this.showNotification('Error updating booking: ' + error.message, 'error');
        }
    }

    // Partner Management
    addPartner(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const partners = JSON.parse(localStorage.getItem('admin_partners') || '[]');
        
        const newPartner = {
            id: Date.now(),
            name: formData.get('name'),
            type: formData.get('type'),
            commission: parseInt(formData.get('commission')),
            status: 'active'
        };

        partners.push(newPartner);
        localStorage.setItem('admin_partners', JSON.stringify(partners));
        
        this.closeModal('addPartnerModal');
        this.showNotification('Partner added successfully!', 'success');
        this.loadPartners();
    }

    loadPartners() {
        const container = document.getElementById('partnersContainer');
        if (!container) return;

        const partners = JSON.parse(localStorage.getItem('admin_partners') || '[]');
        
        container.innerHTML = partners.map(partner => `
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">${partner.name}</h3>
                        <p class="text-gray-600">${partner.type}</p>
                    </div>
                    <span class="px-3 py-1 text-sm rounded-full ${partner.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${partner.status}</span>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Commission Rate</p>
                    <p class="font-semibold">${partner.commission}%</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="adminManager.editPartner(${partner.id})" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Edit</button>
                    <button onclick="adminManager.deletePartner(${partner.id})" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">Delete</button>
                </div>
            </div>
        `).join('');
    }

    deletePartner(id) {
        if (confirm('Are you sure you want to delete this partner?')) {
            const partners = JSON.parse(localStorage.getItem('admin_partners') || '[]');
            const updatedPartners = partners.filter(partner => partner.id !== id);
            localStorage.setItem('admin_partners', JSON.stringify(updatedPartners));
            this.loadPartners();
            this.showNotification('Partner deleted successfully!', 'success');
        }
    }

    // Utility Functions
    showModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    exportData() {
        const data = {
            tours: JSON.parse(localStorage.getItem('admin_tours') || '[]'),
            users: JSON.parse(localStorage.getItem('admin_users') || '[]'),
            bookings: JSON.parse(localStorage.getItem('admin_bookings') || '[]'),
            partners: JSON.parse(localStorage.getItem('admin_partners') || '[]')
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'admin-data-export.json';
        a.click();
        URL.revokeObjectURL(url);
        
        this.showNotification('Data exported successfully!', 'success');
    }

    generateReport() {
        const tours = JSON.parse(localStorage.getItem('admin_tours') || '[]');
        const bookings = JSON.parse(localStorage.getItem('admin_bookings') || '[]');
        const users = JSON.parse(localStorage.getItem('admin_users') || '[]');
        
        const report = {
            summary: {
                totalTours: tours.length,
                totalUsers: users.length,
                totalBookings: bookings.length,
                totalRevenue: bookings.reduce((sum, b) => sum + b.amount, 0)
            },
            topTours: tours.sort((a, b) => b.bookings - a.bookings).slice(0, 5),
            recentBookings: bookings.slice(-10)
        };
        
        console.log('Generated Report:', report);
        this.showNotification('Report generated! Check console for details.', 'success');
    }
}

// Initialize admin manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.adminManager = new AdminManager();
});

// Global functions for modal management
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function showAddTourModal() {
    showModal('addTourModal');
}

function showAddUserModal() {
    showModal('addUserModal');
}

function showAddPartnerModal() {
    showModal('addPartnerModal');
}

function exportData() {
    window.adminManager.exportData();
}

function generateReport() {
    window.adminManager.generateReport();
}