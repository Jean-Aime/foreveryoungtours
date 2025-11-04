// Admin Dashboard JavaScript
class AdminDashboard {
    constructor() {
        this.apiBase = 'http://localhost:5000/api';
        this.token = localStorage.getItem('adminToken');
        this.currentSection = 'overview';
        this.init();
    }

    init() {
        this.setupNavigation();
        this.loadDashboardData();
        this.setupEventListeners();
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
            await Promise.all([
                this.loadKPIs(),
                this.loadCharts(),
                this.loadRecentActivity()
            ]);
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    async loadKPIs() {
        // Simulate API call - replace with actual API
        const kpis = {
            totalRevenue: 245800,
            totalBookings: 1247,
            totalUsers: 3456,
            totalPartners: 89
        };

        this.animateCounter('total-revenue', kpis.totalRevenue, '$');
        this.animateCounter('total-bookings', kpis.totalBookings);
        this.animateCounter('total-users', kpis.totalUsers);
        this.animateCounter('total-partners', kpis.totalPartners);
    }

    animateCounter(elementId, targetValue, prefix = '') {
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

    loadCharts() {
        this.renderRevenueChart();
        this.renderBookingsChart();
    }

    renderRevenueChart() {
        const chartElement = document.getElementById('revenue-chart');
        if (!chartElement) return;

        const chart = echarts.init(chartElement);
        
        const option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: { type: 'cross' }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                axisLine: { lineStyle: { color: '#e5e7eb' } },
                axisLabel: { color: '#6b7280' }
            },
            yAxis: {
                type: 'value',
                axisLine: { show: false },
                axisTick: { show: false },
                axisLabel: {
                    color: '#6b7280',
                    formatter: value => '$' + (value / 1000) + 'K'
                },
                splitLine: { lineStyle: { color: '#f3f4f6' } }
            },
            series: [{
                name: 'Revenue',
                type: 'line',
                smooth: true,
                data: [65000, 72000, 68000, 89000, 95000, 89420],
                lineStyle: { color: '#3b82f6', width: 3 },
                itemStyle: { color: '#3b82f6' },
                areaStyle: {
                    color: {
                        type: 'linear',
                        x: 0, y: 0, x2: 0, y2: 1,
                        colorStops: [
                            { offset: 0, color: 'rgba(59, 130, 246, 0.3)' },
                            { offset: 1, color: 'rgba(59, 130, 246, 0.05)' }
                        ]
                    }
                }
            }]
        };

        chart.setOption(option);
        window.addEventListener('resize', () => chart.resize());
    }

    renderBookingsChart() {
        const chartElement = document.getElementById('bookings-chart');
        if (!chartElement) return;

        const chart = echarts.init(chartElement);
        
        const option = {
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b}: {c} ({d}%)'
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                textStyle: { color: '#6b7280' }
            },
            series: [{
                name: 'Bookings',
                type: 'pie',
                radius: ['40%', '70%'],
                center: ['60%', '50%'],
                data: [
                    { value: 450, name: 'Confirmed', itemStyle: { color: '#10b981' } },
                    { value: 280, name: 'Pending', itemStyle: { color: '#f59e0b' } },
                    { value: 517, name: 'Completed', itemStyle: { color: '#3b82f6' } }
                ]
            }]
        };

        chart.setOption(option);
        window.addEventListener('resize', () => chart.resize());
    }

    async loadRecentActivity() {
        const activities = [
            {
                type: 'booking',
                title: 'New booking confirmed',
                description: 'Kenya Safari Adventure - Sarah Johnson',
                time: '2 hours ago',
                amount: '+$2,499',
                status: 'success'
            },
            {
                type: 'user',
                title: 'New user registered',
                description: 'Michael Chen joined the platform',
                time: '4 hours ago',
                status: 'info'
            },
            {
                type: 'partner',
                title: 'Partner application',
                description: 'Safari Adventures Ltd submitted application',
                time: '6 hours ago',
                status: 'warning'
            }
        ];

        this.renderRecentActivity(activities);
    }

    renderRecentActivity(activities) {
        const container = document.getElementById('recent-activity');
        if (!container) return;

        container.innerHTML = activities.map(activity => `
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 ${this.getActivityIconBg(activity.status)} rounded-full flex items-center justify-center">
                    ${this.getActivityIcon(activity.type, activity.status)}
                </div>
                <div class="flex-1">
                    <p class="text-gray-900 font-medium">${activity.title}</p>
                    <p class="text-gray-600 text-sm">${activity.description}</p>
                    <p class="text-gray-500 text-xs">${activity.time}</p>
                </div>
                ${activity.amount ? `<div class="text-green-600 font-semibold">${activity.amount}</div>` : ''}
            </div>
        `).join('');
    }

    getActivityIconBg(status) {
        const colors = {
            success: 'bg-green-100',
            warning: 'bg-yellow-100',
            info: 'bg-blue-100'
        };
        return colors[status] || 'bg-gray-100';
    }

    getActivityIcon(type, status) {
        const iconColor = status === 'success' ? 'text-green-600' : 
                         status === 'warning' ? 'text-yellow-600' : 'text-blue-600';
        
        const icons = {
            booking: `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
            user: `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>`,
            partner: `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>`
        };
        
        return icons[type] || icons.booking;
    }

    async loadSectionData(section) {
        switch(section) {
            case 'tours':
                await this.loadTours();
                break;
            case 'bookings':
                await this.loadBookings();
                break;
            case 'users':
                await this.loadUsers();
                break;
            case 'partners':
                await this.loadPartners();
                break;
            default:
                break;
        }
    }

    async loadTours() {
        // Simulate loading tours
        const tours = [
            {
                id: 1,
                title: 'Kenya Safari Adventure',
                category: 'Safari',
                price: 2499,
                duration: '7 days',
                status: 'active',
                image: 'https://via.placeholder.com/60'
            },
            {
                id: 2,
                title: 'Morocco Desert Experience',
                category: 'Adventure',
                price: 1899,
                duration: '5 days',
                status: 'active',
                image: 'https://via.placeholder.com/60'
            }
        ];

        this.renderToursTable(tours);
    }

    renderToursTable(tours) {
        const tbody = document.getElementById('tours-table-body');
        if (!tbody) return;

        tbody.innerHTML = tours.map(tour => `
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-4 px-6">
                    <div class="flex items-center">
                        <img src="${tour.image}" alt="${tour.title}" class="w-12 h-12 rounded-lg mr-4">
                        <div>
                            <div class="font-medium text-gray-900">${tour.title}</div>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">${tour.category}</span>
                </td>
                <td class="py-4 px-6 font-medium">$${tour.price.toLocaleString()}</td>
                <td class="py-4 px-6">${tour.duration}</td>
                <td class="py-4 px-6">
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">${tour.status}</span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center space-x-2">
                        <button onclick="editTour(${tour.id})" class="text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteTour(${tour.id})" class="text-red-600 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
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

// Global functions for tour management
function showCreateTourModal() {
    // Implementation for create tour modal
    console.log('Show create tour modal');
}

function editTour(id) {
    console.log('Edit tour:', id);
}

function deleteTour(id) {
    if (confirm('Are you sure you want to delete this tour?')) {
        console.log('Delete tour:', id);
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.adminDashboard = new AdminDashboard();
});