<!-- Enhanced Multi-Step Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="p-6 border-b sticky top-0 bg-white z-10">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gradient">Complete Your Booking</h3>
                    <p class="text-sm text-slate-600 mt-1">Tour: <span id="tourName" class="font-semibold"></span> - <span id="tourPrice" class="text-golden-600 font-bold"></span></p>
                </div>
                <button onclick="closeBookingModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <!-- Progress Steps -->
            <div class="flex items-center justify-between mt-6">
                <div class="step-item active" data-step="1">
                    <div class="step-circle">1</div>
                    <span class="step-label">Personal</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="2">
                    <div class="step-circle">2</div>
                    <span class="step-label">Travel</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="3">
                    <div class="step-circle">3</div>
                    <span class="step-label">Preferences</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="4">
                    <div class="step-circle">4</div>
                    <span class="step-label">Confirm</span>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form id="bookingForm" class="p-6">
            <input type="hidden" id="tourId" name="tour_id">
            <input type="hidden" id="sharedLink" name="shared_link_id">
            <input type="hidden" id="tourPriceValue" name="tour_price">

            <!-- Step 1: Personal Information -->
            <div class="form-step active" data-step="1">
                <h4 class="text-xl font-bold mb-4">Personal Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Full Name (as per ID/Passport) *</label>
                        <input type="text" name="customer_name" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Gender *</label>
                        <select name="gender" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date of Birth *</label>
                        <input type="date" name="date_of_birth" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nationality *</label>
                        <input type="text" name="nationality" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Passport/ID Number *</label>
                        <input type="text" name="passport_number" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email *</label>
                        <input type="email" name="customer_email" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number *</label>
                        <input type="tel" name="customer_phone" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Emergency Contact *</label>
                        <input type="text" name="emergency_contact" required placeholder="Name and Phone" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                </div>
            </div>

            <!-- Step 2: Travel Details -->
            <div class="form-step" data-step="2">
                <h4 class="text-xl font-bold mb-4">Travel Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Number of Adults *</label>
                        <input type="number" name="adults" min="1" value="1" required onchange="calculateTotal()" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Number of Children</label>
                        <input type="number" name="children" min="0" value="0" onchange="calculateTotal()" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Number of Infants</label>
                        <input type="number" name="infants" min="0" value="0" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Arrival Date *</label>
                        <input type="date" name="travel_date" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Departure Date *</label>
                        <input type="date" name="departure_date" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pickup Location *</label>
                        <select name="pickup_location" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Location</option>
                            <option value="airport">Airport</option>
                            <option value="hotel">Hotel</option>
                            <option value="residence">Residence</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pickup Address</label>
                        <input type="text" name="pickup_address" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Special Interests</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <label class="flex items-center"><input type="checkbox" name="interests[]" value="culture" class="mr-2"> Culture</label>
                            <label class="flex items-center"><input type="checkbox" name="interests[]" value="wildlife" class="mr-2"> Wildlife</label>
                            <label class="flex items-center"><input type="checkbox" name="interests[]" value="adventure" class="mr-2"> Adventure</label>
                            <label class="flex items-center"><input type="checkbox" name="interests[]" value="photography" class="mr-2"> Photography</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Accommodation & Preferences -->
            <div class="form-step" data-step="3">
                <h4 class="text-xl font-bold mb-4">Accommodation & Preferences</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Hotel Category *</label>
                        <select name="accommodation_type" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Category</option>
                            <option value="budget">Budget</option>
                            <option value="mid-range">Mid-Range</option>
                            <option value="luxury">Luxury</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Room Type *</label>
                        <select name="room_type" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Type</option>
                            <option value="single">Single</option>
                            <option value="double">Double</option>
                            <option value="twin">Twin</option>
                            <option value="family">Family</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Bed Preference</label>
                        <select name="bed_preference" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Preference</option>
                            <option value="king">King</option>
                            <option value="twin">Twin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Transport Type *</label>
                        <select name="transport_type" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Type</option>
                            <option value="private">Private Car</option>
                            <option value="group">Group Van</option>
                            <option value="safari">Safari Jeep</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Dietary Preferences</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <label class="flex items-center"><input type="checkbox" name="dietary[]" value="vegetarian" class="mr-2"> Vegetarian</label>
                            <label class="flex items-center"><input type="checkbox" name="dietary[]" value="vegan" class="mr-2"> Vegan</label>
                            <label class="flex items-center"><input type="checkbox" name="dietary[]" value="halal" class="mr-2"> Halal</label>
                            <label class="flex items-center"><input type="checkbox" name="dietary[]" value="allergies" class="mr-2"> Allergies</label>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Allergy Details (if any)</label>
                        <textarea name="allergy_details" rows="2" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Medical Conditions (for safety)</label>
                        <textarea name="medical_conditions" rows="2" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Physical Activity Level</label>
                        <select name="activity_level" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="low">Low</option>
                            <option value="moderate" selected>Moderate</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Guide Language *</label>
                        <select name="guide_language" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="english">English</option>
                            <option value="french">French</option>
                            <option value="kinyarwanda">Kinyarwanda</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 4: Additional Details & Confirmation -->
            <div class="form-step" data-step="4">
                <h4 class="text-xl font-bold mb-4">Additional Details & Confirmation</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Arrival Flight Details</label>
                        <input type="text" name="arrival_flight" placeholder="Flight Number & Time" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Departure Flight Details</label>
                        <input type="text" name="departure_flight" placeholder="Flight Number & Time" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Number of Bags</label>
                        <input type="number" name="luggage_count" min="0" value="1" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Special Occasion</label>
                        <select name="special_occasion" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">None</option>
                            <option value="honeymoon">Honeymoon</option>
                            <option value="birthday">Birthday</option>
                            <option value="anniversary">Anniversary</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Extra Services</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="airport_transfer" class="mr-2"> Airport Transfer</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="local_guide" class="mr-2"> Local Guide</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="sim_card" class="mr-2"> SIM Card</label>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Payment Method *</label>
                        <select name="payment_method" id="paymentMethod" required onchange="showPaymentDetails()" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Method</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    
                    <!-- Mobile Money Details -->
                    <div id="mobileMoneyDetails" class="md:col-span-2 hidden">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h5 class="font-bold mb-3">Mobile Money Details</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number *</label>
                                    <input type="tel" name="mm_phone" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="+250 XXX XXX XXX">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Provider *</label>
                                    <select name="mm_provider" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                        <option value="">Select Provider</option>
                                        <option value="mtn">MTN Mobile Money</option>
                                        <option value="airtel">Airtel Money</option>
                                        <option value="mpesa">M-Pesa</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bank Transfer Details -->
                    <div id="bankTransferDetails" class="md:col-span-2 hidden">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h5 class="font-bold mb-3">Bank Transfer Details</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Bank Name *</label>
                                    <input type="text" name="bank_name" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Your Bank Name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Account Number *</label>
                                    <input type="text" name="account_number" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Account Number">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Account Holder Name *</label>
                                    <input type="text" name="account_holder" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Full Name on Account">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Details -->
                    <div id="cardDetails" class="md:col-span-2 hidden">
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <h5 class="font-bold mb-3">Card Details</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Card Number *</label>
                                    <input type="text" name="card_number" maxlength="19" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="1234 5678 9012 3456">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Expiry Date *</label>
                                    <input type="text" name="card_expiry" maxlength="5" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="MM/YY">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">CVV *</label>
                                    <input type="text" name="card_cvv" maxlength="3" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="123">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Cardholder Name *</label>
                                    <input type="text" name="card_holder" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Name on Card">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cash Details -->
                    <div id="cashDetails" class="md:col-span-2 hidden">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h5 class="font-bold mb-3">Cash Payment</h5>
                            <p class="text-sm text-slate-600 mb-3">You have selected to pay in cash. Please note:</p>
                            <ul class="text-sm text-slate-600 space-y-1 list-disc list-inside">
                                <li>Cash payment must be made before the tour starts</li>
                                <li>You can pay at our office or upon arrival</li>
                                <li>A deposit may be required to confirm your booking</li>
                            </ul>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Preferred Payment Location *</label>
                                <select name="cash_location" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                    <option value="">Select Location</option>
                                    <option value="office">At Office</option>
                                    <option value="arrival">Upon Arrival</option>
                                    <option value="hotel">At Hotel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Additional Requests</label>
                        <textarea name="special_requests" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                    </div>
                    
                    <!-- Booking Summary -->
                    <div class="md:col-span-2 bg-gradient-to-r from-golden-50 to-golden-100 p-6 rounded-lg border-2 border-golden-300">
                        <h5 class="font-bold text-lg mb-3">Booking Summary</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span>Tour:</span><span id="summaryTour" class="font-semibold"></span></div>
                            <div class="flex justify-between"><span>Price per person:</span><span id="summaryPrice" class="font-semibold"></span></div>
                            <div class="flex justify-between"><span>Total travelers:</span><span id="summaryTravelers" class="font-semibold">1</span></div>
                            <div class="border-t border-golden-300 pt-2 mt-2"></div>
                            <div class="flex justify-between text-lg font-bold"><span>Total Amount:</span><span id="summaryTotal" class="text-golden-600"></span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8 pt-6 border-t">
                <button type="button" id="prevBtn" onclick="changeStep(-1)" class="btn-secondary px-6 py-3 rounded-lg hidden">
                    <i class="fas fa-arrow-left mr-2"></i>Previous
                </button>
                <button type="button" id="nextBtn" onclick="changeStep(1)" class="btn-primary px-6 py-3 rounded-lg ml-auto">
                    Next<i class="fas fa-arrow-right ml-2"></i>
                </button>
                <button type="submit" id="submitBtn" class="btn-primary px-8 py-3 rounded-lg ml-auto hidden">
                    <i class="fas fa-check mr-2"></i>Confirm Booking
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s;
}

.step-item.active .step-circle {
    background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
    color: white;
}

.step-item.completed .step-circle {
    background: #10b981;
    color: white;
}

.step-label {
    font-size: 0.75rem;
    margin-top: 0.5rem;
    color: #64748b;
}

.step-item.active .step-label {
    color: #d4af37;
    font-weight: 600;
}

.step-line {
    flex: 1;
    height: 2px;
    background: #e2e8f0;
    margin: 0 1rem;
    align-self: center;
    margin-bottom: 1.5rem;
}

.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
let currentStep = 1;
const totalSteps = 4;

function changeStep(direction) {
    const currentStepEl = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    
    // Validate current step before moving forward
    if (direction === 1) {
        const inputs = currentStepEl.querySelectorAll('input[required], select[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value) {
                input.classList.add('border-red-500');
                isValid = false;
            } else {
                input.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            showToast('Please fill in all required fields', 'error');
            return;
        }
    }
    
    // Update step
    currentStep += direction;
    
    if (currentStep < 1) currentStep = 1;
    if (currentStep > totalSteps) currentStep = totalSteps;
    
    // Show/hide steps
    document.querySelectorAll('.form-step').forEach(step => {
        step.classList.remove('active');
    });
    document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');
    
    // Update progress
    document.querySelectorAll('.step-item').forEach((item, index) => {
        if (index + 1 < currentStep) {
            item.classList.add('completed');
            item.classList.remove('active');
        } else if (index + 1 === currentStep) {
            item.classList.add('active');
            item.classList.remove('completed');
        } else {
            item.classList.remove('active', 'completed');
        }
    });
    
    // Update buttons
    document.getElementById('prevBtn').classList.toggle('hidden', currentStep === 1);
    document.getElementById('nextBtn').classList.toggle('hidden', currentStep === totalSteps);
    document.getElementById('submitBtn').classList.toggle('hidden', currentStep !== totalSteps);
    
    // Update summary on last step
    if (currentStep === totalSteps) {
        updateSummary();
    }
}

function calculateTotal() {
    const adults = parseInt(document.querySelector('input[name="adults"]').value) || 0;
    const children = parseInt(document.querySelector('input[name="children"]').value) || 0;
    const pricePerPerson = parseFloat(document.getElementById('tourPriceValue').value) || 0;
    
    const total = (adults + children) * pricePerPerson;
    document.getElementById('summaryTravelers').textContent = adults + children;
    document.getElementById('summaryTotal').textContent = '$' + new Intl.NumberFormat().format(total);
}

function updateSummary() {
    document.getElementById('summaryTour').textContent = document.getElementById('tourName').textContent;
    document.getElementById('summaryPrice').textContent = document.getElementById('tourPrice').textContent;
    calculateTotal();
}

function openBookingModal(tourId, tourName, tourPrice, sharedLink) {
    console.log('Opening booking modal for tour:', tourId, tourName, tourPrice);
    const modal = document.getElementById('bookingModal');
    if (!modal) {
        console.error('Booking modal not found!');
        alert('Error: Booking form not loaded. Please refresh the page.');
        return;
    }
    document.getElementById('tourId').value = tourId;
    document.getElementById('tourName').textContent = tourName;
    document.getElementById('tourPrice').textContent = tourPrice || '$0';
    document.getElementById('tourPriceValue').value = tourPrice || 0;
    document.getElementById('sharedLink').value = sharedLink || '';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    currentStep = 1;
    changeStep(0);
}

function closeBookingModal() {
    const modal = document.getElementById('bookingModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('bookingForm').reset();
    showPaymentDetails(); // Reset payment details visibility
}

function showPaymentDetails() {
    const paymentMethod = document.getElementById('paymentMethod').value;
    
    // Hide all payment detail sections
    document.getElementById('mobileMoneyDetails').classList.add('hidden');
    document.getElementById('bankTransferDetails').classList.add('hidden');
    document.getElementById('cardDetails').classList.add('hidden');
    document.getElementById('cashDetails').classList.add('hidden');
    
    // Show relevant section based on selection
    if (paymentMethod === 'mobile_money') {
        document.getElementById('mobileMoneyDetails').classList.remove('hidden');
    } else if (paymentMethod === 'bank_transfer') {
        document.getElementById('bankTransferDetails').classList.remove('hidden');
    } else if (paymentMethod === 'card') {
        document.getElementById('cardDetails').classList.remove('hidden');
    } else if (paymentMethod === 'cash') {
        document.getElementById('cashDetails').classList.remove('hidden');
    }
}

// Toast notification system
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-[80] transform transition-all duration-300 translate-x-full`;
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Form Submission
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'book_tour');
    
    // Calculate total participants
    const adults = parseInt(formData.get('adults')) || 0;
    const children = parseInt(formData.get('children')) || 0;
    const infants = parseInt(formData.get('infants')) || 0;
    formData.append('participants', adults + children);
    formData.append('total_amount', (adults + children) * parseFloat(document.getElementById('tourPriceValue').value));
    
    fetch('../booking-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Booking request submitted successfully! We will contact you soon.', 'success');
            closeBookingModal();
        } else {
            showToast('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred. Please try again.', 'error');
    });
});

function showLoginRequiredModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-[70] flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-md w-full p-8 transform transition-all">
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Login Required</h3>
                <p class="text-slate-600 mb-6">Please login or create an account to book this tour</p>
                <div class="flex gap-3">
                    <button onclick="window.open('/auth/login.php', '_blank'); this.closest('.fixed').remove();" class="flex-1 px-6 py-3 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-600 transition-all">
                        Login
                    </button>
                    <button onclick="window.open('/auth/register.php', '_blank'); this.closest('.fixed').remove();" class="flex-1 px-6 py-3 bg-slate-700 text-white font-semibold rounded-lg hover:bg-slate-800 transition-all">
                        Register
                    </button>
                </div>
                <button onclick="this.closest('.fixed').remove()" class="mt-4 text-slate-500 hover:text-slate-700 text-sm">Cancel</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}
</script>
