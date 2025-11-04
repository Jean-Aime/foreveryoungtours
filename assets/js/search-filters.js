// Search and Filters System for iForYoungTours
// Handles package search, filtering, and display logic

// Sample package data with comprehensive African destinations
const samplePackages = [
    {
        id: 1,
        title: "Kenya Safari Adventure",
        country: "kenya",
        destination: "Maasai Mara, Kenya",
        duration: 7,
        price: 2499,
        originalPrice: 2999,
        rating: 4.8,
        reviews: 1247,
        image: "https://kimi-web-img.moonshot.cn/img/cdn.pixabay.com/b48c23d2697572a428c82f4586d7d4d00cf1c896.jpg",
        experienceType: "safari",
        description: "Witness the Great Migration and encounter the Big Five in Kenya's most iconic national reserve.",
        highlights: ["Big Five Safari", "Great Migration", "Maasai Culture", "Luxury Lodges"],
        inclusions: ["Accommodation", "Meals", "Game Drives", "Park Fees", "Guide"],
        groupSize: "12 max",
        difficulty: "Easy",
        bestTime: "June - October",
        discount: 17
    },
    {
        id: 2,
        title: "Morocco Desert Expedition",
        country: "morocco",
        destination: "Sahara Desert, Morocco",
        duration: 8,
        price: 1899,
        originalPrice: 1899,
        rating: 4.6,
        reviews: 892,
        image: "https://kimi-web-img.moonshot.cn/img/images.squarespace-cdn.com/e64bfa69368dd0572e5cb9a25c221c42a846b95d.jpeg",
        experienceType: "cultural",
        description: "Explore ancient medinas, ride camels through Sahara dunes, and experience Berber hospitality.",
        highlights: ["Sahara Desert", "Camel Trekking", "Berber Camps", "Marrakech Medina"],
        inclusions: ["Accommodation", "Meals", "Transportation", "Camel Ride", "Guide"],
        groupSize: "16 max",
        difficulty: "Moderate",
        bestTime: "October - April",
        discount: 0
    },
    {
        id: 3,
        title: "Zanzibar Beach Paradise",
        country: "tanzania",
        destination: "Zanzibar, Tanzania",
        duration: 6,
        price: 1299,
        originalPrice: 1599,
        rating: 4.7,
        reviews: 654,
        image: "https://kimi-web-img.moonshot.cn/img/img.freepik.com/b51f725a0a54973046734b5c03d074d0048d2986.jpg",
        experienceType: "beach",
        description: "Relax on pristine white beaches, explore historic Stone Town, and enjoy turquoise waters.",
        highlights: ["White Sand Beaches", "Stone Town", "Snorkeling", "Spice Tours"],
        inclusions: ["Beach Resort", "Breakfast", "Airport Transfers", "Stone Town Tour"],
        groupSize: "20 max",
        difficulty: "Easy",
        bestTime: "June - March",
        discount: 19
    },
    {
        id: 4,
        title: "Namibia Dunes Adventure",
        country: "namibia",
        destination: "Sossusvlei, Namibia",
        duration: 10,
        price: 2199,
        originalPrice: 2199,
        rating: 4.5,
        reviews: 423,
        image: "https://kimi-web-img.moonshot.cn/img/static0.thetravelimages.com/329d1040e0785781c36009c9cb19e2ea34e7c976.jpg",
        experienceType: "adventure",
        description: "Climb the world's highest sand dunes and explore the dramatic landscapes of the Namib Desert.",
        highlights: ["Sossusvlei Dunes", "Deadvlei", "Namib Desert", "Wildlife Safari"],
        inclusions: ["Accommodation", "Meals", "Park Fees", "Guided Tours", "Transport"],
        groupSize: "14 max",
        difficulty: "Moderate",
        bestTime: "May - September",
        discount: 0
    },
    {
        id: 5,
        title: "South Africa Wildlife & Wine",
        country: "south-africa",
        destination: "Cape Town & Kruger, South Africa",
        duration: 12,
        price: 3299,
        originalPrice: 3799,
        rating: 4.9,
        reviews: 1156,
        image: "https://kimi-web-img.moonshot.cn/img/assets.website-files.com/ab55ad9adceb6705fb6b6301df0ed3a16c5ebf1c.jpg",
        experienceType: "luxury",
        description: "Combine Big Five safari with Cape Town's culture and world-class wine regions.",
        highlights: ["Kruger Safari", "Cape Town", "Wine Tasting", "Table Mountain"],
        inclusions: ["Luxury Lodges", "All Meals", "Wine Tours", "Game Drives", "Flights"],
        groupSize: "10 max",
        difficulty: "Easy",
        bestTime: "Year Round",
        discount: 13
    },
    {
        id: 6,
        title: "Egypt Ancient Wonders",
        country: "egypt",
        destination: "Cairo & Luxor, Egypt",
        duration: 8,
        price: 1799,
        originalPrice: 1799,
        rating: 4.4,
        reviews: 789,
        image: "https://kimi-web-img.moonshot.cn/img/www.artnews.com/a1198499d5f182400aad4570fb7eb0eff1f317f5.jpg",
        experienceType: "cultural",
        description: "Explore the mysteries of ancient Egypt, from the Pyramids of Giza to the temples of Luxor.",
        highlights: ["Great Pyramids", "Sphinx", "Valley of Kings", "Nile Cruise"],
        inclusions: ["Hotels", "Breakfast", "Guided Tours", "Nile Cruise", "Entrance Fees"],
        groupSize: "18 max",
        difficulty: "Easy",
        bestTime: "October - April",
        discount: 0
    },
    {
        id: 7,
        title: "Uganda Gorilla Trekking",
        country: "uganda",
        destination: "Bwindi Forest, Uganda",
        duration: 5,
        price: 2899,
        originalPrice: 2899,
        rating: 4.8,
        reviews: 567,
        image: "https://kimi-web-img.moonshot.cn/img/assets.website-files.com/dcda843a2d3e8172aa8604e745dd50321b6288e1.jpg",
        experienceType: "adventure",
        description: "Come face-to-face with mountain gorillas in their natural habitat in Uganda's ancient rainforest.",
        highlights: ["Gorilla Trekking", "Bwindi Forest", "Batwa Culture", "Wildlife Viewing"],
        inclusions: ["Permits", "Lodging", "Meals", "Guide", "Transport"],
        groupSize: "8 max",
        difficulty: "Challenging",
        bestTime: "June - September",
        discount: 0
    },
    {
        id: 8,
        title: "Mauritius Luxury Escape",
        country: "mauritius",
        destination: "Mauritius",
        duration: 7,
        price: 2499,
        originalPrice: 2999,
        rating: 4.7,
        reviews: 445,
        image: "https://kimi-web-img.moonshot.cn/img/kreolmagazine.com/fd583351bb188ce6deb799ac8fd78f9b62ca251e.jpg",
        experienceType: "luxury",
        description: "Indulge in tropical paradise with luxury resorts, pristine beaches, and crystal-clear waters.",
        highlights: ["Luxury Resorts", "Water Sports", "Spa Treatments", "Fine Dining"],
        inclusions: ["All-Inclusive Resort", "Airport Transfers", "Some Activities", "Spa Credit"],
        groupSize: "Couples",
        difficulty: "Easy",
        bestTime: "May - December",
        discount: 17
    }
];

let currentPackages = [...samplePackages];
let displayedPackages = [];
let currentView = 'grid';
let currentPage = 1;
const packagesPerPage = 9;

// Initialize the search and filter system
document.addEventListener('DOMContentLoaded', function() {
    displayedPackages = [...currentPackages];
    displayPackages();
    updateResultsCount();
});

// Search functionality
function performSearch() {
    const searchTerm = document.getElementById('package-search').value.toLowerCase();
    
    if (searchTerm.trim() === '') {
        currentPackages = [...samplePackages];
    } else {
        currentPackages = samplePackages.filter(pkg => 
            pkg.title.toLowerCase().includes(searchTerm) ||
            pkg.destination.toLowerCase().includes(searchTerm) ||
            pkg.description.toLowerCase().includes(searchTerm) ||
            pkg.highlights.some(highlight => highlight.toLowerCase().includes(searchTerm))
        );
    }
    
    applyFilters();
}

// Apply all active filters
function applyFilters() {
    let filteredPackages = [...currentPackages];
    
    // Apply price filters
    const priceFilters = document.querySelectorAll('.price-filter:checked');
    if (priceFilters.length > 0) {
        filteredPackages = filteredPackages.filter(pkg => {
            return Array.from(priceFilters).some(filter => {
                const minPrice = parseInt(filter.dataset.min);
                const maxPrice = parseInt(filter.dataset.max);
                return pkg.price >= minPrice && pkg.price <= maxPrice;
            });
        });
    }
    
    // Apply duration filters
    const durationFilters = document.querySelectorAll('.duration-filter:checked');
    if (durationFilters.length > 0) {
        filteredPackages = filteredPackages.filter(pkg => {
            return Array.from(durationFilters).some(filter => {
                const minDuration = parseInt(filter.dataset.min);
                const maxDuration = parseInt(filter.dataset.max);
                return pkg.duration >= minDuration && pkg.duration <= maxDuration;
            });
        });
    }
    
    // Apply experience type filters
    const experienceFilters = document.querySelectorAll('.experience-filter:checked');
    if (experienceFilters.length > 0) {
        const selectedExperiences = Array.from(experienceFilters).map(filter => filter.value);
        filteredPackages = filteredPackages.filter(pkg => 
            selectedExperiences.includes(pkg.experienceType)
        );
    }
    
    // Apply country filters
    const countryFilters = document.querySelectorAll('.country-filter:checked');
    if (countryFilters.length > 0) {
        const selectedCountries = Array.from(countryFilters).map(filter => filter.value);
        filteredPackages = filteredPackages.filter(pkg => 
            selectedCountries.includes(pkg.country)
        );
    }
    
    displayedPackages = filteredPackages;
    currentPage = 1;
    displayPackages();
    updateResultsCount();
}

// Apply sorting
function applySorting() {
    const sortBy = document.getElementById('sort-select').value;
    
    switch(sortBy) {
        case 'price-low':
            displayedPackages.sort((a, b) => a.price - b.price);
            break;
        case 'price-high':
            displayedPackages.sort((a, b) => b.price - a.price);
            break;
        case 'duration':
            displayedPackages.sort((a, b) => a.duration - b.duration);
            break;
        case 'rating':
            displayedPackages.sort((a, b) => b.rating - a.rating);
            break;
        case 'popularity':
        default:
            displayedPackages.sort((a, b) => b.reviews - a.reviews);
            break;
    }
    
    displayPackages();
}

// Clear all filters
function clearFilters() {
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    document.getElementById('package-search').value = '';
    currentPackages = [...samplePackages];
    displayedPackages = [...samplePackages];
    currentPage = 1;
    displayPackages();
    updateResultsCount();
}

// Toggle view between grid and list
function toggleView(view) {
    currentView = view;
    
    // Update button states
    document.getElementById('grid-view').className = view === 'grid' ? 
        'p-2 rounded-lg bg-blue-500 text-white' : 
        'p-2 rounded-lg bg-gray-200 text-gray-700';
    
    document.getElementById('list-view').className = view === 'list' ? 
        'p-2 rounded-lg bg-blue-500 text-white' : 
        'p-2 rounded-lg bg-gray-200 text-gray-700';
    
    displayPackages();
}

// Display packages based on current view and page
function displayPackages() {
    const grid = document.getElementById('packages-grid');
    const startIndex = (currentPage - 1) * packagesPerPage;
    const endIndex = startIndex + packagesPerPage;
    const packagesToShow = displayedPackages.slice(0, endIndex);
    
    if (currentView === 'grid') {
        grid.className = 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8';
        grid.innerHTML = packagesToShow.map(pkg => createPackageCard(pkg)).join('');
    } else {
        grid.className = 'space-y-6';
        grid.innerHTML = packagesToShow.map(pkg => createPackageListItem(pkg)).join('');
    }
    
    // Add fade-in animation
    anime({
        targets: '.package-card',
        opacity: [0, 1],
        translateY: [30, 0],
        duration: 600,
        delay: anime.stagger(100),
        easing: 'easeOutCubic'
    });
}

// Create package card for grid view
function createPackageCard(pkg) {
    const discountBadge = pkg.discount > 0 ? 
        `<span class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">-${pkg.discount}%</span>` : '';
    
    return `
        <div class="package-card rounded-2xl overflow-hidden shadow-lg cursor-pointer" onclick="showPackageDetail(${pkg.id})">
            <div class="relative">
                <img src="${pkg.image}" alt="${pkg.title}" class="w-full h-48 object-cover">
                ${discountBadge}
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-full text-sm font-semibold">
                    ${pkg.duration} days
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">${pkg.destination}</span>
                    <div class="flex items-center">
                        <span class="text-yellow-400">★</span>
                        <span class="text-sm text-gray-600 ml-1">${pkg.rating} (${pkg.reviews})</span>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">${pkg.title}</h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-2">${pkg.description}</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    ${pkg.highlights.slice(0, 2).map(highlight => 
                        `<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">${highlight}</span>`
                    ).join('')}
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        ${pkg.originalPrice > pkg.price ? 
                            `<span class="text-gray-400 line-through">$${pkg.originalPrice}</span>` : ''}
                        <span class="text-2xl font-bold text-blue-600">$${pkg.price}</span>
                    </div>
                    <button class="btn-primary text-white px-4 py-2 rounded-lg font-medium text-sm">
                        View Details
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Create package list item for list view
function createPackageListItem(pkg) {
    return `
        <div class="package-card rounded-2xl overflow-hidden shadow-lg cursor-pointer flex" onclick="showPackageDetail(${pkg.id})">
            <div class="w-1/3">
                <img src="${pkg.image}" alt="${pkg.title}" class="w-full h-48 object-cover">
            </div>
            <div class="w-2/3 p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">${pkg.destination}</span>
                    <div class="flex items-center">
                        <span class="text-yellow-400">★</span>
                        <span class="text-sm text-gray-600 ml-1">${pkg.rating} (${pkg.reviews})</span>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">${pkg.title}</h3>
                <p class="text-gray-600 mb-4">${pkg.description}</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    ${pkg.highlights.map(highlight => 
                        `<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">${highlight}</span>`
                    ).join('')}
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">${pkg.duration} days</span>
                        <div class="flex items-center space-x-2">
                            ${pkg.originalPrice > pkg.price ? 
                                `<span class="text-gray-400 line-through">$${pkg.originalPrice}</span>` : ''}
                            <span class="text-3xl font-bold text-blue-600">$${pkg.price}</span>
                        </div>
                    </div>
                    <button class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                        View Details
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Show package detail modal
function showPackageDetail(packageId) {
    const pkg = samplePackages.find(p => p.id === packageId);
    if (!pkg) return;
    
    const modal = document.getElementById('package-modal');
    const modalContent = document.getElementById('modal-content');
    
    modalContent.innerHTML = `
        <div class="relative">
            <button onclick="closePackageModal()" class="absolute top-4 right-4 z-10 bg-white rounded-full p-2 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <img src="${pkg.image}" alt="${pkg.title}" class="w-full h-64 object-cover">
            
            <div class="p-8">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-lg text-gray-600">${pkg.destination}</span>
                    <div class="flex items-center">
                        <span class="text-yellow-400 text-xl">★</span>
                        <span class="text-lg text-gray-600 ml-1">${pkg.rating} (${pkg.reviews} reviews)</span>
                    </div>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-900 mb-4">${pkg.title}</h2>
                <p class="text-gray-600 text-lg mb-6">${pkg.description}</p>
                
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Highlights</h3>
                        <ul class="space-y-2">
                            ${pkg.highlights.map(highlight => 
                                `<li class="flex items-center"><span class="text-green-500 mr-2">✓</span>${highlight}</li>`
                            ).join('')}
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">What's Included</h3>
                        <ul class="space-y-2">
                            ${pkg.inclusions.map(inclusion => 
                                `<li class="flex items-center"><span class="text-blue-500 mr-2">•</span>${inclusion}</li>`
                            ).join('')}
                        </ul>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">${pkg.duration}</div>
                            <div class="text-gray-600">Days</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">${pkg.groupSize}</div>
                            <div class="text-gray-600">Group Size</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">${pkg.difficulty}</div>
                            <div class="text-gray-600">Difficulty</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">${pkg.bestTime}</div>
                            <div class="text-gray-600">Best Time</div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        ${pkg.originalPrice > pkg.price ? 
                            `<span class="text-2xl text-gray-400 line-through">$${pkg.originalPrice}</span>` : ''}
                        <span class="text-4xl font-bold text-blue-600">$${pkg.price}</span>
                        <span class="text-gray-600">per person</span>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button onclick="addToWishlist(${pkg.id})" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                            Add to Wishlist
                        </button>
                        <button onclick="initiateBooking(${pkg.id})" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal in
    anime({
        targets: modal.querySelector('.bg-white'),
        scale: [0.8, 1],
        opacity: [0, 1],
        duration: 300,
        easing: 'easeOutCubic'
    });
}

// Close package detail modal
function closePackageModal() {
    const modal = document.getElementById('package-modal');
    
    anime({
        targets: modal.querySelector('.bg-white'),
        scale: [1, 0.8],
        opacity: [1, 0],
        duration: 200,
        easing: 'easeInCubic',
        complete: () => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
}

// Add to wishlist
function addToWishlist(packageId) {
    // Simulate adding to wishlist
    anime({
        targets: event.target,
        scale: [1, 0.95, 1],
        backgroundColor: ['#e5e7eb', '#059669', '#e5e7eb'],
        color: ['#374151', '#ffffff', '#374151'],
        duration: 600,
        easing: 'easeInOutCubic',
        complete: function() {
            if (window.iForYoungTours) {
                window.iForYoungTours.showNotification('Package added to wishlist!', 'success');
            }
        }
    });
}

// Load more packages
function loadMorePackages() {
    currentPage++;
    displayPackages();
}

// Update results count
function updateResultsCount() {
    document.getElementById('results-count').textContent = Math.min(currentPage * packagesPerPage, displayedPackages.length);
    document.getElementById('total-count').textContent = displayedPackages.length;
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('package-modal');
    if (e.target === modal) {
        closePackageModal();
    }
});

// Handle escape key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePackageModal();
    }
});