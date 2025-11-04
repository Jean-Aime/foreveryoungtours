// Dashboard Modules for iForYoungTours - PHP/LocalStorage Version
// Handles all dashboard-specific functionality using local storage

class DashboardManager {
    constructor() {
        this.currentUser = this.getCurrentUser();
        this.selectedPeriod = '6months';
        this.activeModule = 'overview';
        this.init();
    }

    init() {
        this.initializeLocalData();
        this.setupModuleNavigation();
        this.loadDashboardData();
        this.setupRealTimeUpdates();
        this.animateDashboardElements();
    }

    getCurrentUser() {
        const user = localStorage.getItem('currentUser');
        if (user) {
            return JSON.parse(user);
        }
        // Default user for demo
        const defaultUser = {
            id: 1,
            name: 'Sarah Johnson',
            role: 'MCA Advisor',
            email: 'sarah@iforeveryoungtours.com',
            avatar: '../assets/images/logo.png',
            commissionTier: 'Gold'
        };
        localStorage.setItem('currentUser', JSON.stringify(defaultUser));
        return defaultUser;
    }

    initializeLocalData() {
        // Initialize sample data if not exists
        if (!localStorage.getItem('dashboardData')) {
            const sampleData = {
                kpis: {
                    totalCommission: 24580,
                    activeBookings: 47,
                    teamMembers: 128,
                    monthlyRevenue: 89420
                },
                recentActivity: [
                    {
                        id: 1,
                        type: 'booking',
                        title: 'New booking confirmed',
                        description: 'Kenya Safari Adventure - Sarah Johnson',
                        time: '2 hours ago',
                        amount: '+$2,499',
                        status: 'success'
                    },
                    {
                        id: 2,
                        type: 'team',
                        title: 'New team member joined',
                        description: 'Michael Chen joined your network',
                        time: '4 hours ago',
                        status: 'info'
                    },
                    {
                        id: 3,
                        type: 'departure',
                        title: 'Upcoming departure',
                        description: 'Morocco Desert Expedition - Departs in 3 days',
                        time: '6 hours ago',
                        status: 'warning'
                    }
                ],
                bookings: [
                    {
                        id: 1,
                        clientName: 'Sarah Johnson',
                        package: 'Kenya Safari Adventure',
                        bookingDate: '2024-06-10',
                        departureDate: '2024-08-15',
                        status: 'confirmed',
                        total: 2499,
                        commission: 375
                    },
                    {
                        id: 2,
                        clientName: 'Robert Smith',
                        package: 'Morocco Desert Expedition',
                        bookingDate: '2024-06-08',
                        departureDate: '2024-07-20',
                        status: 'confirmed',
                        total: 1899,
                        commission: 285
                    }
                ],
                tours: [
                    {
                        id: 1,
                        title: 'Kenya Safari Adventure',
                        category: 'Safari',
                        price: 2499,
                        duration: '7 days',
                        status: 'active',
                        image: '../assets/images/africa.png'
                    },
                    {
                        id: 2,
                        title: 'Morocco Desert Experience',
                        category: 'Adventure',
                        price: 1899,
                        duration: '5 days',
                        status: 'active',
                        image: '../assets/images/africa.png'
                    }
                ]
            };
            localStorage.setItem('dashboardData', JSON.stringify(sampleData));
        }
    }

    getLocalData() {
        return JSON.parse(localStorage.getItem('dashboardData'));
    }

    updateLocalData(data) {
        localStorage.setItem('dashboardData', JSON.stringify(data));
    }

// Sample data for charts and tables
const dashboardData = {
    revenueData: {
        '6months': {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [65000, 72000, 68000, 89000, 95000, 89420]
        },
        '1year': {
            labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [58000, 62000, 71000, 85000, 92000, 88000, 65000, 72000, 68000, 89000, 95000, 89420]
        }
    },
    
    bookingsData: {
        confirmed: 35,
        pending: 25,
        completed: 40
    },
    
    recentActivity: [
        {
            id: 1,
            type: 'booking',
            title: 'New booking confirmed',
            description: 'Kenya Safari Adventure - Sarah Johnson',
            time: '2 hours ago',
            amount: '+$2,499',
            status: 'success'
        },
        {
            id: 2,
            type: 'team',
            title: 'New team member joined',
            description: 'Michael Chen joined your network',
            time: '4 hours ago',
            status: 'info'
        },
        {
            id: 3,
            type: 'departure',
            title: 'Upcoming departure',
            description: 'Morocco Desert Expedition - Departs in 3 days',
            time: '6 hours ago',
            status: 'warning'
        },
        {
            id: 4,
            type: 'commission',
            title: 'Commission paid',
            description: 'Monthly commission transferred to your account',
            time: '1 day ago',
            amount: '+$8,420',
            status: 'success'
        }
    ],
    
    networkMembers: [
        {
            id: 1,
            name: 'Michael Chen',
            role: 'Junior Advisor',
            joinDate: '2024-01-15',
            bookings: 12,
            commission: 5800,
            status: 'active'
        },
        {
            id: 2,
            name: 'Emma Rodriguez',
            role: 'Senior Advisor',
            joinDate: '2023-08-22',
            bookings: 28,
            commission: 12400,
            status: 'active'
        },
        {
            id: 3,
            name: 'David Kim',
            role: 'Advisor',
            joinDate: '2024-03-10',
            bookings: 8,
            commission: 3200,
            status: 'pending'
        }
    ],
    
    clientBookings: [
        {
            id: 1,
            clientName: 'Sarah Johnson',
            package: 'Kenya Safari Adventure',
            bookingDate: '2024-06-10',
            departureDate: '2024-08-15',
            status: 'confirmed',
            total: 2499,
            commission: 375
        },
        {
            id: 2,
            clientName: 'Robert Smith',
            package: 'Morocco Desert Expedition',
            bookingDate: '2024-06-08',
            departureDate: '2024-07-20',
            status: 'confirmed',
            total: 1899,
            commission: 285
        },
        {
            id: 3,
            clientName: 'Lisa Wang',
            package: 'Zanzibar Beach Paradise',
            bookingDate: '2024-06-05',
            departureDate: '2024-09-01',
            status: 'pending',
            total: 1299,
            commission: 195
        }
    ]
};

    loadModule(moduleName) {
        // Handle different dashboard modules
        console.log('Loading module:', moduleName);
        // Add module-specific functionality here
    }

    setupRealTimeUpdates() {
        // Simulate real-time updates every 30 seconds
        setInterval(() => {
            this.updateDashboardData();
        }, 30000);
    }

    updateDashboardData() {
        // Simulate data updates
        const data = this.getLocalData();
        data.kpis.totalCommission += Math.floor(Math.random() * 100);
        data.kpis.monthlyRevenue += Math.floor(Math.random() * 500);
        this.updateLocalData(data);
        this.updateKPIs(data.kpis);
    }

    animateDashboardElements() {
        // Animate dashboard elements on load
        if (typeof anime !== 'undefined') {
            anime({
                targets: '.kpi-card',
                opacity: [0, 1],
                translateY: [30, 0],
                duration: 800,
                delay: anime.stagger(100),
                easing: 'easeOutCubic'
            });
        }
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.dashboardManager = new DashboardManager();
});

// Global functions for backward compatibility
function handleQuickAction(action) {
    switch(action) {
        case 'createPackage':
            window.location.href = 'packages.php';
            break;
        case 'inviteMember':
            showInviteMemberModal();
            break;
        case 'viewReports':
            showReportsModal();
            break;
        case 'supportChat':
            showSupportChat();
            break;
        default:
            alert('Feature coming soon!');
    }
}

function showInviteMemberModal() {
    alert('Invite Member feature - Coming soon!');
}

function showReportsModal() {
    alert('Reports feature - Coming soon!');
}

function showSupportChat() {
    alert('Support Chat feature - Coming soon!');
}

    loadDashboardData() {
        const data = this.getLocalData();
        this.updateKPIs(data.kpis);
        this.renderRecentActivity(data.recentActivity);
        this.renderRevenueChart();
        this.renderBookingsChart();
    }

// MCA Advisor Dashboard
function loadMCAAdvisorDashboard() {
    updateKPIs();
    renderRevenueChart();
    renderBookingsChart();
    renderRecentActivity();
    renderQuickActions();
}

// Admin Dashboard
function loadAdminDashboard() {
    // Admin sees all system metrics
    updateKPIs();
    renderRevenueChart();
    renderBookingsChart();
    renderSystemMetrics();
    renderUserManagement();
}

// Client Dashboard
function loadClientDashboard() {
    // Client sees their bookings and travel info
    updateClientKPIs();
    renderClientBookings();
    renderTravelDocuments();
    renderSupportTickets();
}

// Partner Dashboard
function loadPartnerDashboard() {
    // Partner sees inventory and bookings
    updatePartnerKPIs();
    renderInventoryManagement();
    renderPartnerBookings();
    renderRevenueShare();
}

    updateKPIs(kpis) {
        // Update KPI cards with animation
        this.animateKPICounter('.kpi-card:nth-child(1) .text-2xl', kpis.totalCommission, '$');
        this.animateKPICounter('.kpi-card:nth-child(2) .text-2xl', kpis.activeBookings);
        this.animateKPICounter('.kpi-card:nth-child(3) .text-2xl', kpis.teamMembers);
        this.animateKPICounter('.kpi-card:nth-child(4) .text-2xl', kpis.monthlyRevenue, '$');
    }

    animateKPICounter(selector, targetValue, prefix = '') {
        const element = document.querySelector(selector);
        if (!element) return;
        
        if (typeof anime !== 'undefined') {
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
        } else {
            // Fallback without animation
            element.textContent = prefix + targetValue.toLocaleString();
        }
    }

    renderRecentActivity(activities) {
        // This function is already implemented in the existing code
        // Just ensure it works with local data
    }

    renderRevenueChart() {
        const chartElement = document.getElementById('revenue-chart');
        if (!chartElement || typeof echarts === 'undefined') return;
        
        const chart = echarts.init(chartElement);
        
        const option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: { type: 'cross' },
                formatter: function(params) {
                    return `${params[0].name}<br/>Revenue: $${params[0].value.toLocaleString()}`;
                }
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
                lineStyle: { color: '#DAA520', width: 3 },
                itemStyle: { color: '#DAA520' },
                areaStyle: {
                    color: {
                        type: 'linear',
                        x: 0, y: 0, x2: 0, y2: 1,
                        colorStops: [
                            { offset: 0, color: 'rgba(218, 165, 32, 0.3)' },
                            { offset: 1, color: 'rgba(218, 165, 32, 0.05)' }
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
        if (!chartElement || typeof echarts === 'undefined') return;
        
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
                    { value: 35, name: 'Confirmed', itemStyle: { color: '#228B22' } },
                    { value: 25, name: 'Pending', itemStyle: { color: '#DAA520' } },
                    { value: 40, name: 'Completed', itemStyle: { color: '#1e3a8a' } }
                ]
            }]
        };
        
        chart.setOption(option);
        window.addEventListener('resize', () => chart.resize());
    }

// Render recent activity
function renderRecentActivity() {
    const activityContainer = document.getElementById('recent-activity');
    if (!activityContainer) return;
    
    const activities = dashboardData.recentActivity;
    
    activityContainer.innerHTML = activities.map(activity => `
        <div class="flex items-start space-x-4 fade-in-up">
            <div class="w-10 h-10 ${getActivityIconBg(activity.status)} rounded-full flex items-center justify-center">
                ${getActivityIcon(activity.type, activity.status)}
            </div>
            <div class="flex-1">
                <p class="text-gray-900 font-medium">${activity.title}</p>
                <p class="text-gray-600 text-sm">${activity.description}</p>
                <p class="text-gray-500 text-xs">${activity.time}</p>
            </div>
            ${activity.amount ? `<div class="${getActivityAmountColor(activity.status)} font-semibold">${activity.amount}</div>` : ''}
        </div>
    `).join('');
    
    // Animate activity items
    anime({
        targets: '.fade-in-up',
        opacity: [0, 1],
        translateY: [20, 0],
        duration: 600,
        delay: anime.stagger(100),
        easing: 'easeOutCubic'
    });
}

// Get activity icon background color
function getActivityIconBg(status) {
    switch(status) {
        case 'success': return 'bg-green-100';
        case 'warning': return 'bg-yellow-100';
        case 'info': return 'bg-blue-100';
        default: return 'bg-gray-100';
    }
}

// Get activity icon
function getActivityIcon(type, status) {
    const iconColor = status === 'success' ? 'text-green-600' : 
                     status === 'warning' ? 'text-yellow-600' : 'text-blue-600';
    
    switch(type) {
        case 'booking':
            return `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`;
        case 'team':
            return `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>`;
        case 'departure':
            return `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`;
        default:
            return `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`;
    }
}

// Get activity amount color
function getActivityAmountColor(status) {
    switch(status) {
        case 'success': return 'text-green-600';
        case 'warning': return 'text-yellow-600';
        case 'info': return 'text-blue-600';
        default: return 'text-gray-600';
    }
}

// Render quick actions
function renderQuickActions() {
    const actionsContainer = document.getElementById('quick-actions');
    if (!actionsContainer) return;
    
    const actions = [
        {
            title: 'Create New Package',
            icon: 'M12 6v6m0 0v6m0-6h6m-6 0H6',
            color: 'blue',
            action: 'createPackage'
        },
        {
            title: 'Invite Team Member',
            icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            color: 'green',
            action: 'inviteMember'
        },
        {
            title: 'View Reports',
            icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            color: 'yellow',
            action: 'viewReports'
        },
        {
            title: 'Support Chat',
            icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            color: 'purple',
            action: 'supportChat'
        }
    ];
    
    actionsContainer.innerHTML = actions.map(action => `
        <button onclick="handleQuickAction('${action.action}')" 
                class="w-full bg-${action.color}-50 hover:bg-${action.color}-100 text-${action.color}-700 px-4 py-3 rounded-lg font-medium transition-colors flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${action.icon}"/>
            </svg>
            ${action.title}
        </button>
    `).join('');
}

// Handle quick action
function handleQuickAction(action) {
    switch(action) {
        case 'createPackage':
            window.location.href = 'packages.html';
            break;
        case 'inviteMember':
            showInviteMemberModal();
            break;
        case 'viewReports':
            showReportsModal();
            break;
        case 'supportChat':
            showSupportChat();
            break;
        default:
            if (window.iForYoungTours) {
                window.iForYoungTours.showNotification('Feature coming soon!', 'info');
            }
    }
}

// Show invite member modal
function showInviteMemberModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-md w-full p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Invite Team Member</h3>
            <form onsubmit="sendInvitation(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="advisor">Travel Advisor</option>
                            <option value="junior">Junior Advisor</option>
                            <option value="senior">Senior Advisor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Personal Message (Optional)</label>
                        <textarea rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Welcome to iForYoungTours!"></textarea>
                    </div>
                </div>
                <div class="flex space-x-4 mt-6">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 text-gray-700 px-4 py-3 rounded-lg font-medium">Cancel</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-lg font-medium">Send Invitation</button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Animate modal in
    anime({
        targets: modal.querySelector('.bg-white'),
        scale: [0.8, 1],
        opacity: [0, 1],
        duration: 300,
        easing: 'easeOutCubic'
    });
}

// Send invitation
function sendInvitation(event) {
    event.preventDefault();
    
    // Simulate sending invitation
    const button = event.target.querySelector('button[type="submit"]');
    button.innerHTML = 'Sending...';
    button.disabled = true;
    
    setTimeout(() => {
        closeModal();
        if (window.iForYoungTours) {
            window.iForYoungTours.showNotification('Invitation sent successfully!', 'success');
        }
    }, 1500);
}

// Close modal
function closeModal() {
    const modal = document.querySelector('.fixed.inset-0');
    if (modal) {
        modal.remove();
    }
}

    setupModuleNavigation() {
        const navItems = document.querySelectorAll('.nav-item');
        
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active class from all items
                navItems.forEach(nav => nav.classList.remove('active'));
                
                // Add active class to clicked item
                item.classList.add('active');
                
                // Update active module
                const moduleName = item.textContent.toLowerCase().replace(/\s+/g, '');
                this.activeModule = moduleName;
                
                // Load corresponding module
                this.loadModule(moduleName);
            });
        });
    }

// Load specific module
function loadModule(moduleName) {
    switch(moduleName) {
        case 'overview':
            loadMCAAdvisorDashboard();
            break;
        case 'mcanetwork':
            loadMCANetworkModule();
            break;
        case 'clients':
            loadClientsModule();
            break;
        case 'packages':
            loadPackagesModule();
            break;
        case 'bookings':
            loadBookingsModule();
            break;
        case 'commissions':
            loadCommissionsModule();
            break;
        case 'affiliates':
            loadAffiliatesModule();
            break;
        case 'settings':
            loadSettingsModule();
            break;
        default:
            loadMCAAdvisorDashboard();
    }
}

// Load MCA Network module
function loadMCANetworkModule() {
    const content = `
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 mb-6">MCA Network Management</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                ${dashboardData.networkMembers.map(member => `
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-red-500 rounded-full mr-3"></div>
                            <div>
                                <h4 class="font-semibold text-gray-900">${member.name}</h4>
                                <p class="text-sm text-gray-600">${member.role}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Bookings:</span>
                                <span class="font-medium">${member.bookings}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Commission:</span>
                                <span class="font-medium">$${member.commission.toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Status:</span>
                                <span class="px-2 py-1 rounded-full text-xs ${member.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">${member.status}</span>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
    
    updateMainContent(content);
}

// Load Clients module
function loadClientsModule() {
    const content = `
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Client Management</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Client</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Package</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Booking Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Departure</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Total</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Commission</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${dashboardData.clientBookings.map(booking => `
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">${booking.clientName}</td>
                                <td class="py-3 px-4">${booking.package}</td>
                                <td class="py-3 px-4">${booking.bookingDate}</td>
                                <td class="py-3 px-4">${booking.departureDate}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs ${booking.status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">${booking.status}</span>
                                </td>
                                <td class="py-3 px-4">$${booking.total.toLocaleString()}</td>
                                <td class="py-3 px-4 text-green-600 font-semibold">$${booking.commission}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `;
    
    updateMainContent(content);
}

// Update main content area
function updateMainContent(content) {
    const mainContent = document.querySelector('.main-content .p-8');
    if (mainContent) {
        // Keep header, replace content
        const header = mainContent.querySelector('.mb-8');
        const newContent = document.createElement('div');
        newContent.innerHTML = content;
        
        // Clear existing content except header
        const existingContent = mainContent.querySelectorAll('.grid, .bg-white');
        existingContent.forEach(el => el.remove());
        
        // Add new content
        mainContent.appendChild(newContent.firstElementChild);
        
        // Animate new content
        anime({
            targets: newContent.firstElementChild,
            opacity: [0, 1],
            translateY: [20, 0],
            duration: 500,
            easing: 'easeOutCubic'
        });
    }
}

// Setup real-time updates
function setupRealTimeUpdates() {
    // Simulate real-time data updates every 30 seconds
    setInterval(() => {
        updateDashboardData();
    }, 30000);
}

// Update dashboard data
function updateDashboardData() {
    // Simulate data updates
    const user = dashboardState.currentUser;
    
    // Add small random variations to simulate real-time data
    user.totalCommission += Math.floor(Math.random() * 100);
    user.monthlyRevenue += Math.floor(Math.random() * 500);
    
    // Update KPIs if on overview
    if (dashboardState.activeModule === 'overview') {
        updateKPIs();
    }
}

// Animate dashboard elements
function animateDashboardElements() {
    // Animate KPI cards
    anime({
        targets: '.kpi-card',
        opacity: [0, 1],
        translateY: [30, 0],
        duration: 800,
        delay: anime.stagger(100),
        easing: 'easeOutCubic'
    });
    
    // Animate charts
    anime({
        targets: '.bg-white',
        opacity: [0, 1],
        translateY: [20, 0],
        duration: 1000,
        delay: 400,
        easing: 'easeOutCubic'
    });
}

// Export functions for global access
window.dashboardModules = {
    loadModule,
    handleQuickAction,
    showInviteMemberModal,
    sendInvitation,
    closeModal
};