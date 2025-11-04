// Booking Engine for iForYoungTours
// Handles booking flow, payment processing, and reservation management

// Booking state management
let bookingState = {
    packageId: null,
    packageDetails: null,
    travelers: 1,
    selectedDate: null,
    customerInfo: {},
    paymentInfo: {},
    totalPrice: 0,
    bookingStep: 1
};

// Initialize booking engine
document.addEventListener('DOMContentLoaded', function() {
    initializeBookingEngine();
});

function initializeBookingEngine() {
    // Initialize date pickers and form validation
    setupDatePickers();
    setupFormValidation();
    setupPaymentMethods();
}

// Initiate booking process
function initiateBooking(packageId) {
    // Close any open modals
    const modal = document.getElementById('package-modal');
    if (modal && !modal.classList.contains('hidden')) {
        closePackageModal();
    }
    
    // Get package details from search-filters.js
    const pkg = samplePackages.find(p => p.id === packageId);
    if (!pkg) return;
    
    // Initialize booking state
    bookingState = {
        packageId: packageId,
        packageDetails: pkg,
        travelers: 1,
        selectedDate: null,
        customerInfo: {},
        paymentInfo: {},
        totalPrice: pkg.price,
        bookingStep: 1
    };
    
    // Create and show booking modal
    createBookingModal();
    showBookingStep(1);
}

// Create booking modal
function createBookingModal() {
    // Remove existing modal if present
    const existingModal = document.getElementById('booking-modal');
    if (existingModal) {
        existingModal.remove();
    }
    
    const modal = document.createElement('div');
    modal.id = 'booking-modal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Book Your Adventure</h2>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Progress Indicator -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div id="step-1" class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold">1</div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Details</span>
                        </div>
                        <div class="w-16 h-1 bg-gray-200"></div>
                        <div class="flex items-center">
                            <div id="step-2" class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold">2</div>
                            <span class="ml-2 text-sm font-medium text-gray-600">Information</span>
                        </div>
                        <div class="w-16 h-1 bg-gray-200"></div>
                        <div class="flex items-center">
                            <div id="step-3" class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold">3</div>
                            <span class="ml-2 text-sm font-medium text-gray-600">Payment</span>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Content -->
                <div id="booking-content">
                    <!-- Content will be dynamically loaded here -->
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Animate modal in
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    anime({
        targets: modal.querySelector('.bg-white'),
        scale: [0.8, 1],
        opacity: [0, 1],
        duration: 300,
        easing: 'easeOutCubic'
    });
}

// Show specific booking step
function showBookingStep(step) {
    bookingState.bookingStep = step;
    updateProgressIndicator(step);
    
    const content = document.getElementById('booking-content');
    
    switch(step) {
        case 1:
            content.innerHTML = createBookingStep1();
            break;
        case 2:
            content.innerHTML = createBookingStep2();
            break;
        case 3:
            content.innerHTML = createBookingStep3();
            break;
        case 4:
            content.innerHTML = createBookingConfirmation();
            break;
    }
    
    // Animate step transition
    anime({
        targets: '#booking-content',
        opacity: [0, 1],
        translateY: [20, 0],
        duration: 300,
        easing: 'easeOutCubic'
    });
}

// Update progress indicator
function updateProgressIndicator(currentStep) {
    for (let i = 1; i <= 3; i++) {
        const stepElement = document.getElementById(`step-${i}`);
        const stepText = stepElement.nextElementSibling;
        
        if (i <= currentStep) {
            stepElement.className = 'w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold';
            stepText.className = 'ml-2 text-sm font-medium text-gray-900';
        } else {
            stepElement.className = 'w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold';
            stepText.className = 'ml-2 text-sm font-medium text-gray-600';
        }
    }
}

// Create booking step 1 - Package details and date selection
function createBookingStep1() {
    const pkg = bookingState.packageDetails;
    
    return `
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Package Summary -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Package Summary</h3>
                <img src="${pkg.image}" alt="${pkg.title}" class="w-full h-32 object-cover rounded-lg mb-4">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">${pkg.title}</h4>
                <p class="text-gray-600 mb-4">${pkg.description}</p>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-medium">${pkg.duration} days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Group Size:</span>
                        <span class="font-medium">${pkg.groupSize}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Difficulty:</span>
                        <span class="font-medium">${pkg.difficulty}</span>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg text-gray-600">Base Price:</span>
                        <span class="text-2xl font-bold text-blue-600">$${pkg.price}</span>
                    </div>
                </div>
            </div>
            
            <!-- Booking Details Form -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-6">Booking Details</h3>
                
                <div class="space-y-6">
                    <!-- Travelers Count -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Travelers</label>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="updateTravelers(-1)" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span id="travelers-count" class="text-xl font-semibold">${bookingState.travelers}</span>
                            <button type="button" onclick="updateTravelers(1)" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Date Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Start Date</label>
                        <input type="date" id="start-date" onchange="updateSelectedDate(this.value)" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Best time: ${pkg.bestTime}</p>
                    </div>
                    
                    <!-- Special Requests -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests (Optional)</label>
                        <textarea id="special-requests" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Any dietary requirements, accessibility needs, or special requests..."></textarea>
                    </div>
                    
                    <!-- Price Calculation -->
                    <div class="bg-blue-50 rounded-xl p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Price Breakdown</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Base price per person:</span>
                                <span>$${pkg.price}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Number of travelers:</span>
                                <span id="travelers-display">${bookingState.travelers}</span>
                            </div>
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between font-semibold text-lg">
                                    <span>Total:</span>
                                    <span id="total-price" class="text-blue-600">$${bookingState.totalPrice}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-8">
                    <button onclick="nextBookingStep()" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                        Continue to Information
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Create booking step 2 - Customer information
function createBookingStep2() {
    return `
        <div class="max-w-2xl mx-auto">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Traveler Information</h3>
            
            <div class="space-y-6">
                <!-- Primary Traveler -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Primary Traveler</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" id="first-name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" id="last-name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" id="phone" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                        <select id="nationality" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select your nationality</option>
                            <option value="US">United States</option>
                            <option value="UK">United Kingdom</option>
                            <option value="CA">Canada</option>
                            <option value="AU">Australia</option>
                            <option value="DE">Germany</option>
                            <option value="FR">France</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                
                <!-- Emergency Contact -->
                <div class="bg-yellow-50 rounded-xl p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Emergency Contact</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Name</label>
                            <input type="text" id="emergency-name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                            <input type="tel" id="emergency-phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
                
                <!-- Travel Preferences -->
                <div class="bg-blue-50 rounded-xl p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Travel Preferences</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dietary Requirements</label>
                            <div class="flex flex-wrap gap-3">
                                <label class="flex items-center">
                                    <input type="checkbox" value="vegetarian" class="mr-2"> Vegetarian
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="vegan" class="mr-2"> Vegan
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="gluten-free" class="mr-2"> Gluten-free
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="halal" class="mr-2"> Halal
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="kosher" class="mr-2"> Kosher
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions or Allergies</label>
                            <textarea id="medical-info" rows="2" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Please specify any medical conditions, allergies, or accessibility requirements..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button onclick="previousBookingStep()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                    Back to Details
                </button>
                <button onclick="nextBookingStep()" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                    Continue to Payment
                </button>
            </div>
        </div>
    `;
}

// Create booking step 3 - Payment
function createBookingStep3() {
    return `
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Payment Form -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-6">Payment Information</h3>
                
                <div class="space-y-6">
                    <!-- Payment Method Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method</label>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment-method" value="card" checked class="mr-3">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 2v2H4V6h16zm0 4H4v8h16v-8z"/>
                                    </svg>
                                    Credit/Debit Card
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment-method" value="paypal" class="mr-3">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.26-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.75.75 0 0 0-.741.638l-1.481 9.401a.641.641 0 0 0 .633.74h4.868c.524 0 .968-.382 1.05-.9l.29-1.84a.75.75 0 0 1 .741-.638h1.481c3.852 0 6.848-1.56 7.72-6.1.34-1.76.166-3.22-.674-4.42z"/>
                                    </svg>
                                    PayPal
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment-method" value="momo" class="mr-3">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                    Mobile Money
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Card Details -->
                    <div id="card-details">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Number *</label>
                            <input type="text" id="card-number" placeholder="1234 5678 9012 3456" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date *</label>
                                <input type="text" id="expiry-date" placeholder="MM/YY" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CVV *</label>
                                <input type="text" id="cvv" placeholder="123" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name *</label>
                            <input type="text" id="cardholder-name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <!-- Billing Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Billing Address *</label>
                        <input type="text" id="billing-address" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" id="billing-city" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code *</label>
                            <input type="text" id="billing-zip" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h3>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span>${bookingState.packageDetails.title}</span>
                        <span>$${bookingState.packageDetails.price}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Travelers: ${bookingState.travelers}</span>
                        <span>$${bookingState.packageDetails.price * bookingState.travelers}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Processing Fee</span>
                        <span>$${Math.round(bookingState.packageDetails.price * bookingState.travelers * 0.03)}</span>
                    </div>
                    <div class="border-t pt-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span class="text-blue-600">$${bookingState.totalPrice}</span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3 mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" required class="mt-1 mr-3">
                        <span class="text-sm text-gray-600">I agree to the <a href="#" class="text-blue-600">Terms and Conditions</a> and <a href="#" class="text-blue-600">Privacy Policy</a></span>
                    </label>
                    <label class="flex items-start">
                        <input type="checkbox" class="mt-1 mr-3">
                        <span class="text-sm text-gray-600">I want to receive travel tips and special offers via email</span>
                    </label>
                    <label class="flex items-start">
                        <input type="checkbox" class="mt-1 mr-3">
                        <span class="text-sm text-gray-600">I need travel insurance (recommended)</span>
                    </label>
                </div>
                
                <div class="bg-yellow-50 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-yellow-800">Important Information</h4>
                            <p class="text-sm text-yellow-700 mt-1">Please ensure all information is accurate. You can modify your booking up to 30 days before departure.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-between mt-8">
            <button onclick="previousBookingStep()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                Back to Details
            </button>
            <button onclick="processPayment()" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                Complete Booking
            </button>
        </div>
    `;
}

// Update travelers count
function updateTravelers(change) {
    const newCount = Math.max(1, bookingState.travelers + change);
    bookingState.travelers = newCount;
    
    // Update total price
    const basePrice = bookingState.packageDetails.price;
    const processingFee = Math.round(basePrice * newCount * 0.03);
    bookingState.totalPrice = (basePrice * newCount) + processingFee;
    
    // Update display
    document.getElementById('travelers-count').textContent = newCount;
    document.getElementById('travelers-display').textContent = newCount;
    document.getElementById('total-price').textContent = `$${bookingState.totalPrice}`;
}

// Update selected date
function updateSelectedDate(date) {
    bookingState.selectedDate = date;
}

// Navigation functions
function nextBookingStep() {
    if (validateCurrentStep()) {
        showBookingStep(bookingState.bookingStep + 1);
    }
}

function previousBookingStep() {
    showBookingStep(bookingState.bookingStep - 1);
}

// Validate current step
function validateCurrentStep() {
    const step = bookingState.bookingStep;
    
    switch(step) {
        case 1:
            return document.getElementById('start-date').value !== '';
        case 2:
            return document.getElementById('first-name').value !== '' &&
                   document.getElementById('last-name').value !== '' &&
                   document.getElementById('email').value !== '' &&
                   document.getElementById('phone').value !== '';
        case 3:
            return true; // Payment validation handled separately
        default:
            return true;
    }
}

// Process payment
function processPayment() {
    // Simulate payment processing
    const button = event.target;
    
    // Show loading state
    button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Processing...
    `;
    button.disabled = true;
    
    // Simulate payment processing delay
    setTimeout(() => {
        // Generate booking confirmation
        const bookingReference = 'IFT' + Math.random().toString(36).substr(2, 9).toUpperCase();
        bookingState.bookingReference = bookingReference;
        
        showBookingStep(4);
    }, 2000);
}

// Create booking confirmation
function createBookingConfirmation() {
    return `
        <div class="text-center max-w-2xl mx-auto">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Booking Confirmed!</h2>
            <p class="text-xl text-gray-600 mb-8">Your African adventure has been successfully booked.</p>
            
            <div class="bg-blue-50 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Reference</h3>
                <div class="text-3xl font-bold text-blue-600 mb-2">${bookingState.bookingReference}</div>
                <p class="text-gray-600">Please save this reference number for future correspondence.</p>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Next Steps</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <span class="text-green-500 mr-3">✓</span>
                        <span>Confirmation email sent to your registered email address</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-500 mr-3">→</span>
                        <span>Our travel specialist will contact you within 24 hours</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-500 mr-3">→</span>
                        <span>Detailed itinerary and travel documents will be sent 2 weeks before departure</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-500 mr-3">→</span>
                        <span>24/7 support available for any questions or changes</span>
                    </li>
                </ul>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="downloadConfirmation()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                    Download Confirmation
                </button>
                <button onclick="closeBookingModal()" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                    Continue Exploring
                </button>
            </div>
        </div>
    `;
}

// Download confirmation
function downloadConfirmation() {
    // Simulate PDF download
    if (window.iForYoungTours) {
        window.iForYoungTours.showNotification('Confirmation PDF downloaded!', 'success');
    }
}

// Close booking modal
function closeBookingModal() {
    const modal = document.getElementById('booking-modal');
    if (modal) {
        anime({
            targets: modal.querySelector('.bg-white'),
            scale: [1, 0.8],
            opacity: [1, 0],
            duration: 200,
            easing: 'easeInCubic',
            complete: () => {
                modal.remove();
                document.body.style.overflow = 'auto';
            }
        });
    }
}

// Setup date pickers
function setupDatePickers() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.min = today;
    });
}

// Setup form validation
function setupFormValidation() {
    // Add real-time validation for form fields
    document.addEventListener('input', function(e) {
        if (e.target.type === 'email') {
            validateEmailInput(e.target);
        } else if (e.target.type === 'tel') {
            validatePhoneInput(e.target);
        } else if (e.target.id === 'card-number') {
            formatCardNumber(e.target);
        } else if (e.target.id === 'expiry-date') {
            formatExpiryDate(e.target);
        }
    });
}

// Setup payment methods
function setupPaymentMethods() {
    document.addEventListener('change', function(e) {
        if (e.target.name === 'payment-method') {
            togglePaymentDetails(e.target.value);
        }
    });
}

// Toggle payment details based on selected method
function togglePaymentDetails(method) {
    const cardDetails = document.getElementById('card-details');
    if (cardDetails) {
        cardDetails.style.display = method === 'card' ? 'block' : 'none';
    }
}

// Email validation
function validateEmailInput(input) {
    const email = input.value;
    const isValid = window.iForYoungTours && window.iForYoungTours.validateEmail(email);
    
    if (email && !isValid) {
        input.classList.add('border-red-500');
        input.classList.remove('border-gray-300');
    } else {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-300');
    }
}

// Phone validation
function validatePhoneInput(input) {
    const phone = input.value;
    const isValid = window.iForYoungTours && window.iForYoungTours.validatePhone(phone);
    
    if (phone && !isValid) {
        input.classList.add('border-red-500');
        input.classList.remove('border-gray-300');
    } else {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-300');
    }
}

// Format card number
function formatCardNumber(input) {
    let value = input.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    input.value = formattedValue;
}

// Format expiry date
function formatExpiryDate(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}