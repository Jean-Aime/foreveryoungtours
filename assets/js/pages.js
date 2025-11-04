// Pages JavaScript - All page-specific functionality
// Gold, White, Green Theme Implementation

// Global utilities
window.iForYoungTours = {
    // Show notification function
    showNotification: function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform translate-x-full transition-transform duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(full)';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
};

// PACKAGES PAGE FUNCTIONALITY
if (window.location.pathname.includes('packages')) {
    // Package data
    const packages = [
        {
            id: 1,
            title: "Kenya Safari Adventure",
            price: 2499,
            duration: "7-14 days",
            rating: 4.5,
            reviews: 128,
            image: "https://kimi-web-img.moonshot.cn/img/cdn.pixabay.com/b48c23d2697572a428c82f4586d7d4d00cf1c896.jpg",
            description: "Experience the Great Migration in Maasai Mara with luxury accommodations.",
            fullDescription: "This comprehensive Kenya safari takes you through the most spectacular wildlife reserves in East Africa.",
            includes: ["7 nights luxury accommodation", "All meals and drinks", "Professional safari guide"],
            highlights: ["Great Migration viewing", "Big Five spotting", "Luxury tented camps"]
        }
    ];

    // Filter and search functionality
    function performSearch() {
        const searchTerm = document.getElementById('package-search').value.toLowerCase();
        applyFilters(searchTerm);
    }

    function applyFilters(searchTerm = '') {
        const priceFilters = document.querySelectorAll('.price-filter:checked');
        const durationFilters = document.querySelectorAll('.duration-filter:checked');
        const experienceFilters = document.querySelectorAll('.experience-filter:checked');
        const countryFilters = document.querySelectorAll('.country-filter:checked');
        
        console.log('Filters applied:', {
            searchTerm,
            priceFilters: Array.from(priceFilters).map(f => f.dataset),
            durationFilters: Array.from(durationFilters).map(f => f.dataset),
            experienceFilters: Array.from(experienceFilters).map(f => f.value),
            countryFilters: Array.from(countryFilters).map(f => f.value)
        });
    }

    function clearFilters() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('package-search').value = '';
        applyFilters();
    }

    function applySorting() {
        const sortValue = document.getElementById('sort-select').value;
        console.log('Sorting by:', sortValue);
    }

    function toggleView(viewType) {
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const packagesGrid = document.getElementById('packages-grid');
        
        if (viewType === 'grid') {
            gridView.classList.add('bg-blue-500', 'text-white');
            gridView.classList.remove('bg-gray-200', 'text-gray-700');
            listView.classList.add('bg-gray-200', 'text-gray-700');
            listView.classList.remove('bg-blue-500', 'text-white');
            packagesGrid.className = 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8';
        } else {
            listView.classList.add('bg-blue-500', 'text-white');
            listView.classList.remove('bg-gray-200', 'text-gray-700');
            gridView.classList.add('bg-gray-200', 'text-gray-700');
            gridView.classList.remove('bg-blue-500', 'text-white');
            packagesGrid.className = 'grid grid-cols-1 gap-8';
        }
    }

    function loadMorePackages() {
        console.log('Loading more packages...');
        window.iForYoungTours.showNotification('Loading more amazing packages for you!', 'info');
    }

    function openPackageModal(packageId) {
        const package = packages.find(p => p.id === packageId);
        if (!package) return;
        
        const modalContent = document.getElementById('modal-content');
        modalContent.innerHTML = `
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">${package.title}</h2>
                    <button onclick="closePackageModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <img src="${package.image}" alt="${package.title}" class="w-full h-64 object-cover rounded-lg mb-6">
                <div class="border-t pt-6">
                    <button onclick="startBooking(${package.id})" class="w-full bg-blue-600 text-white py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors">
                        Book Now - From $${package.price}
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('package-modal').classList.remove('hidden');
    }

    function closePackageModal() {
        document.getElementById('package-modal').classList.add('hidden');
    }

    function startBooking(packageId) {
        console.log('Starting booking for package:', packageId);
        window.iForYoungTours.showNotification('Redirecting to booking page...', 'info');
        closePackageModal();
    }

    // Make functions global for packages page
    window.performSearch = performSearch;
    window.applyFilters = applyFilters;
    window.clearFilters = clearFilters;
    window.applySorting = applySorting;
    window.toggleView = toggleView;
    window.loadMorePackages = loadMorePackages;
    window.openPackageModal = openPackageModal;
    window.closePackageModal = closePackageModal;
    window.startBooking = startBooking;
}

// DESTINATIONS PAGE FUNCTIONALITY
if (window.location.pathname.includes('destinations')) {
    // African countries data
    const africanCountries = [
        {
            name: "Kenya",
            region: "east",
            capital: "Nairobi",
            population: "53.8M",
            currency: "Kenyan Shilling",
            language: "English, Swahili",
            image: "https://kimi-web-img.moonshot.cn/img/cdn.pixabay.com/b48c23d2697572a428c82f4586d7d4d00cf1c896.jpg",
            highlights: ["Great Migration", "Big Five Safari", "Maasai Culture", "Mount Kenya"],
            description: "Home to the iconic Maasai Mara and the Great Migration, Kenya offers unparalleled safari experiences.",
            bestTime: "June - October",
            packages: 24
        }
    ];

    let currentRegion = 'all';
    let filteredCountries = [...africanCountries];

    function displayDestinations() {
        const grid = document.getElementById('destinations-grid');
        
        grid.innerHTML = filteredCountries.map(country => `
            <div class="destination-card rounded-2xl overflow-hidden shadow-lg cursor-pointer" onclick="showCountryDetail('${country.name}')">
                <div class="relative">
                    <img src="${country.image}" alt="${country.name}" class="w-full h-48 object-cover">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold">
                        ${country.packages} packages
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900">${country.name}</h3>
                    <p class="text-gray-600 text-sm mb-4">${country.description}</p>
                    <button class="btn-primary text-white px-4 py-2 rounded-lg font-medium text-sm">
                        Explore
                    </button>
                </div>
            </div>
        `).join('');
    }

    function filterByRegion(region) {
        currentRegion = region;
        
        document.querySelectorAll('.region-filter').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        
        event.target.classList.add('active');
        event.target.classList.remove('bg-gray-100', 'text-gray-700');
        
        if (region === 'all') {
            filteredCountries = [...africanCountries];
        } else {
            filteredCountries = africanCountries.filter(country => country.region === region);
        }
        
        displayDestinations();
    }

    function filterDestinations() {
        const searchTerm = document.getElementById('destination-search').value.toLowerCase();
        
        if (searchTerm.trim() === '') {
            filteredCountries = currentRegion === 'all' ? 
                [...africanCountries] : 
                africanCountries.filter(country => country.region === currentRegion);
        } else {
            const baseCountries = currentRegion === 'all' ? 
                africanCountries : 
                africanCountries.filter(country => country.region === currentRegion);
            
            filteredCountries = baseCountries.filter(country => 
                country.name.toLowerCase().includes(searchTerm) ||
                country.description.toLowerCase().includes(searchTerm)
            );
        }
        
        displayDestinations();
    }

    function showCountryDetail(countryName) {
        const country = africanCountries.find(c => c.name === countryName);
        if (!country) return;
        
        window.iForYoungTours.showNotification(`Showing details for ${countryName}`, 'info');
    }

    // Make functions global for destinations page
    window.displayDestinations = displayDestinations;
    window.filterByRegion = filterByRegion;
    window.filterDestinations = filterDestinations;
    window.showCountryDetail = showCountryDetail;

    // Initialize destinations page
    document.addEventListener('DOMContentLoaded', function() {
        displayDestinations();
    });
}

// CALENDAR PAGE FUNCTIONALITY
if (window.location.pathname.includes('calendar')) {
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    const events = {
        '2024-12-15': [
            { type: 'departure', title: 'Kenya Safari Adventure', description: '7-day safari in Maasai Mara' }
        ]
    };

    function generateCalendar() {
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        document.getElementById('current-month').textContent = `${monthNames[currentMonth]} ${currentYear}`;
        
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        const calendarDays = document.getElementById('calendar-days');
        calendarDays.innerHTML = '';
        
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.innerHTML = `<div class="font-semibold text-sm mb-1">${day}</div>`;
            calendarDays.appendChild(dayElement);
        }
    }

    function previousMonth() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar();
    }

    function nextMonth() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar();
    }

    // Make functions global for calendar page
    window.generateCalendar = generateCalendar;
    window.previousMonth = previousMonth;
    window.nextMonth = nextMonth;

    // Initialize calendar page
    document.addEventListener('DOMContentLoaded', function() {
        generateCalendar();
    });
}

// PARTNERS PAGE FUNCTIONALITY
if (window.location.pathname.includes('partners')) {
    function showPartnerForm(partnershipType) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Partnership Application</h3>
                    <form onsubmit="submitPartnerApplication(event, '${partnershipType}')">
                        <div class="space-y-6">
                            <input type="text" placeholder="Company Name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            <input type="email" placeholder="Email Address" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        </div>
                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="closePartnerModal()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium mr-4">
                                Cancel
                            </button>
                            <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }

    function submitPartnerApplication(event, partnershipType) {
        event.preventDefault();
        
        setTimeout(() => {
            closePartnerModal();
            window.iForYoungTours.showNotification('Application submitted successfully!', 'success');
        }, 1000);
    }

    function closePartnerModal() {
        const modal = document.querySelector('.fixed.inset-0');
        if (modal) {
            modal.remove();
        }
    }

    // Make functions global for partners page
    window.showPartnerForm = showPartnerForm;
    window.submitPartnerApplication = submitPartnerApplication;
    window.closePartnerModal = closePartnerModal;
}

// RESOURCES PAGE FUNCTIONALITY
if (window.location.pathname.includes('resources')) {
    const resourceContent = {
        planning: {
            title: "Trip Planning Resources",
            content: `<div class="bg-white rounded-2xl p-8 shadow-sm"><h3 class="text-2xl font-bold text-gray-900 mb-6">Planning Your African Adventure</h3></div>`
        }
    };

    let currentCategory = 'planning';

    function showResourceCategory(category) {
        currentCategory = category;
        
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        
        event.target.classList.add('active');
        event.target.classList.remove('bg-gray-100', 'text-gray-700');
        
        const content = resourceContent[category];
        document.getElementById('resource-content').innerHTML = `
            <div class="fade-in-up">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">${content.title}</h2>
                ${content.content}
            </div>
        `;
    }

    function toggleAccordion(index) {
        const content = document.querySelector(`.accordion-content-${index}`);
        const icon = document.querySelector(`.accordion-icon-${index}`);
        
        if (content.classList.contains('active')) {
            content.classList.remove('active');
            icon.style.transform = 'rotate(0deg)';
        } else {
            document.querySelectorAll('.accordion-content').forEach((item, i) => {
                item.classList.remove('active');
                document.querySelector(`.accordion-icon-${i}`).style.transform = 'rotate(0deg)';
            });
            
            content.classList.add('active');
            icon.style.transform = 'rotate(180deg)';
        }
    }

    // Make functions global for resources page
    window.showResourceCategory = showResourceCategory;
    window.toggleAccordion = toggleAccordion;

    // Initialize resources page
    document.addEventListener('DOMContentLoaded', function() {
        showResourceCategory('planning');
    });
}

// EXPERIENCES PAGE FUNCTIONALITY
if (window.location.pathname.includes('experiences')) {
    const experiences = {
        safari: {
            title: "Safari & Wildlife Experiences",
            description: "Witness Africa's incredible wildlife in their natural habitats.",
            packages: 156,
            experiences: []
        }
    };

    let currentExperience = 'all';

    function filterByExperience(type) {
        currentExperience = type;
        
        document.querySelectorAll('.experience-filter').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        
        event.target.classList.add('active');
        event.target.classList.remove('bg-gray-100', 'text-gray-700');
        
        window.iForYoungTours.showNotification(`Showing ${type} experiences`, 'info');
    }

    // Make functions global for experiences page
    window.filterByExperience = filterByExperience;
}

// DASHBOARD PAGE FUNCTIONALITY
if (window.location.pathname.includes('dashboard')) {
    function initializeDashboardCharts() {
        if (typeof echarts !== 'undefined') {
            const revenueChart = echarts.init(document.getElementById('revenue-chart'));
            const revenueOption = {
                tooltip: { trigger: 'axis' },
                xAxis: { type: 'category', data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
                yAxis: { type: 'value' },
                series: [{
                    name: 'Revenue',
                    type: 'line',
                    data: [65000, 72000, 68000, 89000, 95000, 89420],
                    itemStyle: { color: '#DAA520' }
                }]
            };
            revenueChart.setOption(revenueOption);
        }
    }

    // Make functions global for dashboard page
    window.initializeDashboardCharts = initializeDashboardCharts;

    // Initialize dashboard page
    document.addEventListener('DOMContentLoaded', function() {
        initializeDashboardCharts();
    });
}

// COMMON FUNCTIONALITY FOR ALL PAGES
document.addEventListener('DOMContentLoaded', function() {
    // Initialize common animations
    const fadeElements = document.querySelectorAll('.fade-in-up');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    fadeElements.forEach(el => observer.observe(el));

    // Initialize carousels if Splide is available
    if (typeof Splide !== 'undefined') {
        const carousels = document.querySelectorAll('.splide');
        carousels.forEach(carousel => {
            new Splide(carousel, {
                type: 'loop',
                perPage: 3,
                perMove: 1,
                gap: '2rem',
                pagination: false,
                breakpoints: {
                    1024: { perPage: 2 },
                    768: { perPage: 1 }
                }
            }).mount();
        });
    }
});