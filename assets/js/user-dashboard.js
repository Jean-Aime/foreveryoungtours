// User Dashboard JavaScript
class UserDashboard {
    constructor() {
        this.apiBase = 'http://localhost:5000/api';
        this.token = localStorage.getItem('userToken');
        this.currentUser = null;
        this.currentSection = 'overview';
        this.init();
    }

    async init() {
        await this.loadUserData();
        this.setupNavigation();
        this.loadDashboardData();
        this.setupEventListeners();
    }

    async loadUserData() {
        try {
            const response = await this.apiCall('/auth/me');
            this.currentUser = response.data;
            this.updateUserInfo();
        } catch (error) {
            console.error('Error loading user data:', error);
            // Fallback to demo data
            this.currentUser = {
                id: 1,
                name: 'Sarah Johnson',
                role: 'traveler',
                email: 'sarah@example.com',
                avatar: 'https://via.placeholder.com/40'
            };
            this.updateUserInfo();
        }
    }

    updateUserInfo() {
        const nameElement = document.getElementById('user-name');
        const avatarElement = document.getElementById('user-avatar');
        
        if (nameElement) nameElement.textContent = this.currentUser.name;
        if (avatarElement) avatarElement.src = this.currentUser.avatar || 'https://via.placeholder.com/40';
    }

    setupNavigation() {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const section = item.getAttribute('href').substring(1);
                this.switchSection(section);
                
                // Update active state
                navItems.forEach(nav => nav.classList.remove('active'));
                item.classList.add('active');
            });
        });
    }

    switchSection(section) {
        // Hide all sections
        document.querySelectorAll('.dashboard-section').forEach(sec => {
            sec.classList.add('hidden');
        });
        
        // Show selected section
        const targetSection = document.getElementById(`${section}-section`);
        if (targetSection) {
            targetSection.classList.remove('hidden');
            this.currentSection = section;
            
            // Load section-specific data
            this.loadSectionData(section);
        }
    }

    async loadDashboardData() {
        try {
            const dashboardData = await this.apiCall('/users/dashboard');
            this.updateKPIs(dashboardData.data.statistics);
            this.renderRecentBookings(dashboardData.data.recentBookings);
            this.renderUpcomingBookings(dashboardData.data.upcomingBookings);
        } catch (error) {
            console.error('Error loading dashboard data:', error);
            // Load demo data as fallback
            this.loadDemoData();
        }
    }

    loadDemoData() {
        const demoStats = {
            totalBookings: 12,
            confirmedBookings: 8,
            totalSpent: 15600,
            upcomingTrips: 3
        };
        
        const demoBookings = [
            {
                _id: '1',
                tour: {
                    title: 'Kenya Safari Adventure',
                    destination: { country: 'Kenya', city: 'Nairobi' },
                    images: [{ url: 'https://via.placeholder.com/60' }]
                },
                status: 'confirmed',
                travelDates: { startDate: '2024-08-15' },
                pricing: { totalAmount: 2499 }
            },
            {
                _id: '2',
                tour: {
                    title: 'Morocco Desert Experience',
                    destination: { country: 'Morocco', city: 'Marrakech' },
                    images: [{ url: 'https://via.placeholder.com/60' }]
                },
                status: 'pending',
                travelDates: { startDate: '2024-09-20' },
                pricing: { totalAmount: 1899 }
            }
        ];

        this.updateKPIs(demoStats);
        this.renderRecentBookings(demoBookings);
        this.renderUpcomingBookings(demoBookings.filter(b => b.status === 'confirmed'));
    }

    updateKPIs(stats) {
        this.animateKPICounter('total-bookings', stats.totalBookings);
        this.animateKPICounter('confirmed-bookings', stats.confirmedBookings);
        this.animateKPICounter('total-spent', stats.totalSpent, '$');
        this.animateKPICounter('upcoming-trips', stats.upcomingTrips);
    }

    animateKPICounter(elementId, targetValue, prefix = '') {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        anime({
            targets: { value: 0 },
            value: targetValue,
            duration: 2000,
            easing: 'easeOutCubic',
            update: function(anim) {
                const value = Math.floor(anim.animatables[0].target.value);
                element.textContent = prefix + value.toLocaleString();
            }
        });
    }

    renderRecentBookings(bookings) {
        const container = document.getElementById('recent-bookings');
        if (!container || !bookings) return;

        if (bookings.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-600">No bookings yet</p>
                    <a href="../pages/packages.html" class="text-blue-600 hover:text-blue-700 font-medium">Browse Tours</a>
                </div>
            `;
            return;
        }

        container.innerHTML = bookings.slice(0, 3).map(booking => `
            <div class="flex items-center space-x-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50">
                <img src="${booking.tour.images?.[0]?.url || 'https://via.placeholder.com/60'}" 
                     alt="${booking.tour.title}" 
                     class="w-12 h-12 rounded-lg object-cover">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${booking.tour.title}</h4>
                    <p class="text-sm text-gray-600">${booking.tour.destination.country}</p>
                    <p class="text-xs text-gray-500">${new Date(booking.travelDates.startDate).toLocaleDateString()}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-900">$${booking.pricing.totalAmount.toLocaleString()}</div>
                    <span class="px-2 py-1 text-xs rounded-full ${this.getStatusColor(booking.status)}">${booking.status}</span>
                </div>
            </div>
        `).join('');
    }

    renderUpcomingBookings(bookings) {
        const container = document.getElementById('upcoming-trips');
        if (!container || !bookings) return;

        const upcomingBookings = bookings.filter(booking => {
            const travelDate = new Date(booking.travelDates.startDate);
            return travelDate > new Date() && booking.status === 'confirmed';
        });

        if (upcomingBookings.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-600">No upcoming trips</p>
                    <a href="../pages/packages.html" class="text-blue-600 hover:text-blue-700 font-medium">Plan Your Next Adventure</a>
                </div>
            `;
            return;
        }

        container.innerHTML = upcomingBookings.slice(0, 3).map(booking => {
            const daysUntil = Math.ceil((new Date(booking.travelDates.startDate) - new Date()) / (1000 * 60 * 60 * 24));
            return `
                <div class="flex items-center space-x-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50">
                    <img src="${booking.tour.images?.[0]?.url || 'https://via.placeholder.com/60'}" 
                         alt="${booking.tour.title}" 
                         class="w-12 h-12 rounded-lg object-cover">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${booking.tour.title}</h4>
                        <p class="text-sm text-gray-600">${booking.tour.destination.country}</p>
                        <p class="text-xs text-blue-600 font-medium">${daysUntil} days to go</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">${new Date(booking.travelDates.startDate).toLocaleDateString()}</div>
                        <button class="text-xs text-blue-600 hover:text-blue-700">View Details</button>
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

    async loadSectionData(section) {
        switch(section) {
            case 'bookings':
                await this.loadAllBookings();
                break;
            case 'payments':
                await this.loadPaymentHistory();
                break;
            case 'profile':
                await this.loadProfileData();
                break;
            default:
                break;
        }
    }

    async loadAllBookings() {
        try {
            const response = await this.apiCall('/bookings');
            this.renderAllBookings(response.data);
        } catch (error) {
            console.error('Error loading bookings:', error);
            // Load demo data
            this.renderAllBookings([]);
        }
    }

    renderAllBookings(bookings) {
        const container = document.getElementById('bookings-list');
        if (!container) return;

        if (bookings.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings yet</h3>
                    <p class="text-gray-600 mb-4">Start your adventure by booking your first trip</p>
                    <a href="../pages/packages.html" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                        Browse Tours
                    </a>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="space-y-4">
                ${bookings.map(booking => `
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <img src="${booking.tour.images?.[0]?.url || 'https://via.placeholder.com/80'}" 
                                     alt="${booking.tour.title}" 
                                     class="w-20 h-20 rounded-lg object-cover">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">${booking.tour.title}</h3>
                                    <p class="text-gray-600">${booking.tour.destination.country}</p>
                                    <p class="text-sm text-gray-500">Booking ID: ${booking.bookingId || booking._id}</p>
                                    <p class="text-sm text-gray-500">Travel Date: ${new Date(booking.travelDates.startDate).toLocaleDateString()}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold text-gray-900">$${booking.pricing.totalAmount.toLocaleString()}</div>
                                <span class="px-3 py-1 text-sm rounded-full ${this.getStatusColor(booking.status)}">${booking.status}</span>
                                <div class="mt-2 space-x-2">
                                    <button class="text-blue-600 hover:text-blue-700 text-sm">View Details</button>
                                    ${booking.status === 'pending' ? '<button class="text-red-600 hover:text-red-700 text-sm">Cancel</button>' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    setupEventListeners() {
        // Add any additional event listeners here
    }

    // API Methods
    async apiCall(endpoint, options = {}) {
        const url = `${this.apiBase}${endpoint}`;
        const config = {
            headers: {
                'Content-Type': 'application/json',
                ...(this.token && { 'Authorization': `Bearer ${this.token}` })
            },
            ...options
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'API request failed');
            }
            
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.userDashboard = new UserDashboard();
});