<?php
$page_title = "Tour Inquiry - iForYoungTours";
$page_description = "Submit your tour inquiry - we'll create a custom itinerary for you";
$css_path = "../assets/css/modern-styles.css";

// Get tour info if coming from tour page
$tour_id = $_GET['tour_id'] ?? '';
$tour_name = $_GET['tour_name'] ?? '';
$tour_price = $_GET['price'] ?? '';

include './header.php';
?>

<section class="py-20 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-4">Tour Inquiry Form</h1>
            <p class="text-slate-600">Tell us about your dream trip and we'll create a personalized itinerary</p>
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">💡 <strong>Looking to book a specific tour?</strong> Use our <a href="tour-booking.php" class="font-semibold underline">Quick Booking Form</a> instead.</p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div class="step-indicator active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Traveler Details</div>
                </div>
                <div class="step-line"></div>
                <div class="step-indicator" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Tour Preferences</div>
                </div>
                <div class="step-line"></div>
                <div class="step-indicator" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Group Info</div>
                </div>
                <div class="step-line"></div>
                <div class="step-indicator" data-step="4">
                    <div class="step-circle">4</div>
                    <div class="step-label">Travel Details</div>
                </div>
                <div class="step-line"></div>
                <div class="step-indicator" data-step="5">
                    <div class="step-circle">5</div>
                    <div class="step-label">Review</div>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form id="bookingForm">
                
                <!-- Step 1: Traveler Details -->
                <div class="form-step active" data-step="1">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Traveler Details</h2>
                    
                    <input type="hidden" name="tour_id" value="<?php echo htmlspecialchars($tour_id); ?>">
                    <input type="hidden" name="tour_name" value="<?php echo htmlspecialchars($tour_name); ?>">
                    
                    <?php if ($tour_name): ?>
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-semibold text-slate-900">Selected Tour:</p>
                        <p class="text-lg font-bold text-primary-gold"><?php echo htmlspecialchars($tour_name); ?></p>
                        <?php if ($tour_price): ?>
                        <p class="text-sm text-slate-600">Price: $<?php echo htmlspecialchars($tour_price); ?> per person</p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Client Name *</label>
                            <input type="text" name="client_name" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Email *</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Phone / WhatsApp *</label>
                            <input type="tel" name="phone" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Address</label>
                            <input type="text" name="address" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Number of Adults *</label>
                            <input type="number" name="adults" min="1" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Number of Children & Ages</label>
                            <input type="text" name="children" placeholder="e.g., 2 (ages 5, 8)" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Dates of Travel *</label>
                            <input type="text" name="travel_dates" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Flexible Dates?</label>
                            <div class="flex gap-4 mt-3">
                                <label class="flex items-center"><input type="radio" name="flexible" value="yes" class="mr-2"> Yes</label>
                                <label class="flex items-center"><input type="radio" name="flexible" value="no" class="mr-2"> No</label>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Vacation Budget (USD) *</label>
                            <input type="text" name="budget" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Step 2: Tour Preferences -->
                <div class="form-step" data-step="2">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Tour Preferences</h2>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-900 mb-3">Tour Categories (Select All That Apply)</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="Adventure Tours" class="mr-2"> Adventure Tours</label>
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="Agro-Tourism" class="mr-2"> Agro-Tourism</label>
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="Sports Tourism" class="mr-2"> Sports Tourism</label>
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="Cultural Exchanges" class="mr-2"> Cultural Exchanges</label>
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="Conferences" class="mr-2"> Conferences</label>
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="Expos" class="mr-2"> Expos</label>
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="Motorcoach Tours" class="mr-2"> Motorcoach Tours</label>
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="Rail Tours" class="mr-2"> Rail Tours</label>
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="Cruises" class="mr-2"> Cruises</label>
                            <label class="flex items-center"><input type="checkbox" name="categories[]" value="City Breaks" class="mr-2"> City Breaks</label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-900 mb-3">Destinations (Select All That Apply)</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center"><input type="checkbox" name="destinations[]" value="African Tours" class="mr-2"> African Tours</label>
                            <label class="flex items-center"><input type="checkbox" name="destinations[]" value="Caribbean Tours" class="mr-2"> Caribbean Tours</label>
                            <label class="flex items-center"><input type="checkbox" name="destinations[]" value="North American Tours" class="mr-2"> North American Tours</label>
                            <label class="flex items-center"><input type="checkbox" name="destinations[]" value="Asian Tours" class="mr-2"> Asian Tours</label>
                            <label class="flex items-center"><input type="checkbox" name="destinations[]" value="European Tours" class="mr-2"> European Tours</label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-3">Activities of Interest</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Sightseeing / History" class="mr-2"> Sightseeing / History</label>
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Wine / Culinary" class="mr-2"> Wine / Culinary</label>
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Culture / Arts" class="mr-2"> Culture / Arts</label>
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Shopping" class="mr-2"> Shopping</label>
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Active / Sports" class="mr-2"> Active / Sports</label>
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Beach / Sun" class="mr-2"> Beach / Sun</label>
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Spa" class="mr-2"> Spa</label>
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Golf" class="mr-2"> Golf</label>
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Wildlife / Safari" class="mr-2"> Wildlife / Safari</label>
                            <label class="flex items-center"><input type="checkbox" name="activities[]" value="Wellness & Fitness" class="mr-2"> Wellness & Fitness</label>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Group Information -->
                <div class="form-step" data-step="3">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Group Travel Information</h2>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-900 mb-3">Group Type</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center"><input type="checkbox" name="group_type[]" value="Family" class="mr-2"> Family</label>
                            <label class="flex items-center"><input type="checkbox" name="group_type[]" value="Friends" class="mr-2"> Friends</label>
                            <label class="flex items-center"><input type="checkbox" name="group_type[]" value="Friends & Family" class="mr-2"> Friends & Family</label>
                            <label class="flex items-center"><input type="checkbox" name="group_type[]" value="Religious Group" class="mr-2"> Religious Group</label>
                            <label class="flex items-center"><input type="checkbox" name="group_type[]" value="Company" class="mr-2"> Company</label>
                            <label class="flex items-center"><input type="checkbox" name="group_type[]" value="Conference" class="mr-2"> Conference</label>
                            <label class="flex items-center"><input type="checkbox" name="group_type[]" value="Expo" class="mr-2"> Expo</label>
                            <label class="flex items-center"><input type="checkbox" name="group_type[]" value="Sporting Event" class="mr-2"> Sporting Event</label>
                            <label class="flex items-center"><input type="checkbox" name="group_type[]" value="Cultural Event" class="mr-2"> Cultural Event</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Number of Persons in Group</label>
                            <select name="group_size" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select group size</option>
                                <option value="Less than 10">Less than 10</option>
                                <option value="11-15">11-15</option>
                                <option value="16-25">16-25</option>
                                <option value="26-36">26-36</option>
                                <option value="37-50">37-50</option>
                                <option value="50+">50+</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Group Leader Name</label>
                            <input type="text" name="group_leader" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Group Leader Contact</label>
                            <input type="text" name="group_leader_contact" placeholder="Mobile / WhatsApp / Email" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Step 4: Travel Details -->
                <div class="form-step" data-step="4">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Travel Details & Preferences</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Departure City</label>
                            <input type="text" name="departure_city" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Seat Preference</label>
                            <select name="seat_preference" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select preference</option>
                                <option value="Window">Window</option>
                                <option value="Aisle">Aisle</option>
                                <option value="Middle">Middle</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Airline Preference</label>
                            <input type="text" name="airline" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Class</label>
                            <select name="class" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select class</option>
                                <option value="Economy">Economy</option>
                                <option value="Premium">Premium</option>
                                <option value="Business">Business</option>
                                <option value="First">First</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-900 mb-3">Hotel Preferences</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center"><input type="checkbox" name="hotel_prefs[]" value="Luxury Resort" class="mr-2"> Luxury Resort</label>
                            <label class="flex items-center"><input type="checkbox" name="hotel_prefs[]" value="Beachfront" class="mr-2"> Beachfront</label>
                            <label class="flex items-center"><input type="checkbox" name="hotel_prefs[]" value="City Center" class="mr-2"> City Center</label>
                            <label class="flex items-center"><input type="checkbox" name="hotel_prefs[]" value="Family Friendly" class="mr-2"> Family Friendly</label>
                            <label class="flex items-center"><input type="checkbox" name="hotel_prefs[]" value="Adults Only" class="mr-2"> Adults Only</label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-900 mb-3">Room Type</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center"><input type="checkbox" name="room_type[]" value="Standard" class="mr-2"> Standard</label>
                            <label class="flex items-center"><input type="checkbox" name="room_type[]" value="Suite" class="mr-2"> Suite</label>
                            <label class="flex items-center"><input type="checkbox" name="room_type[]" value="Ocean View" class="mr-2"> Ocean View</label>
                            <label class="flex items-center"><input type="checkbox" name="room_type[]" value="Garden View" class="mr-2"> Garden View</label>
                            <label class="flex items-center"><input type="checkbox" name="room_type[]" value="Concierge Level" class="mr-2"> Concierge Level</label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-3">Additional Services</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="Air Travel" class="mr-2"> Air Travel</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="Cruise Vacation" class="mr-2"> Cruise Vacation</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="Hotel / Resort Stay" class="mr-2"> Hotel / Resort Stay</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="Car Rental" class="mr-2"> Car Rental</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="Escorted Tour" class="mr-2"> Escorted Tour</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="Independent Tour" class="mr-2"> Independent Tour</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="Travel Insurance" class="mr-2"> Travel Insurance</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="All-Inclusive Package" class="mr-2"> All-Inclusive Package</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="Transfers" class="mr-2"> Transfers</label>
                            <label class="flex items-center"><input type="checkbox" name="services[]" value="Visa Assistance" class="mr-2"> Visa Assistance</label>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Review & Submit -->
                <div class="form-step" data-step="5">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Review & Submit</h2>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-900 mb-3">How Did You Hear About Us?</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center"><input type="checkbox" name="referral[]" value="MCA" class="mr-2"> MCA</label>
                            <label class="flex items-center"><input type="checkbox" name="referral[]" value="Advisor" class="mr-2"> Advisor</label>
                            <label class="flex items-center"><input type="checkbox" name="referral[]" value="FYT Staff" class="mr-2"> FYT Staff</label>
                            <label class="flex items-center"><input type="checkbox" name="referral[]" value="Ads" class="mr-2"> Ads</label>
                            <label class="flex items-center"><input type="checkbox" name="referral[]" value="Facebook" class="mr-2"> Facebook</label>
                            <label class="flex items-center"><input type="checkbox" name="referral[]" value="LinkedIn" class="mr-2"> LinkedIn</label>
                            <label class="flex items-center"><input type="checkbox" name="referral[]" value="X" class="mr-2"> X</label>
                            <label class="flex items-center"><input type="checkbox" name="referral[]" value="Instagram" class="mr-2"> Instagram</label>
                            <label class="flex items-center"><input type="checkbox" name="referral[]" value="Email" class="mr-2"> Email</label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-900 mb-2">If From MCA / Advisor / Staff</label>
                        <input type="text" name="referral_name" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Notes & Special Requests</label>
                        <textarea name="notes" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-slate-700">By submitting this form, you agree to our terms and conditions. Our team will review your information and contact you within 24 hours.</p>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8">
                    <button type="button" id="prevBtn" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-lg font-semibold hover:bg-slate-300 transition-colors">Previous</button>
                    <button type="button" id="nextBtn" class="px-6 py-3 btn-primary text-white rounded-lg font-semibold">Next</button>
                    <button type="submit" id="submitBtn" class="px-6 py-3 btn-primary text-white rounded-lg font-semibold hidden">Submit</button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
.step-indicator {
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
    margin-bottom: 8px;
}
.step-indicator.active .step-circle {
    background: #3b82f6;
    color: white;
}
.step-label {
    font-size: 12px;
    color: #64748b;
    text-align: center;
}
.step-line {
    flex: 1;
    height: 2px;
    background: #e2e8f0;
    margin: 0 10px;
    align-self: flex-start;
    margin-top: 20px;
}
.form-step {
    display: none;
}
.form-step.active {
    display: block;
}
</style>

<script>
let currentStep = 1;
const totalSteps = 5;

function showStep(step) {
    document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.step-indicator').forEach(el => el.classList.remove('active'));
    
    document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
    document.querySelector(`.step-indicator[data-step="${step}"]`).classList.add('active');
    
    document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'block';
    document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'block';
    document.getElementById('submitBtn').style.display = step === totalSteps ? 'block' : 'none';
}

document.getElementById('nextBtn').addEventListener('click', () => {
    if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
    }
});

document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
});

document.getElementById('bookingForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    fetch('submit-booking.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Thank you! Your booking request has been submitted. We will contact you within 24 hours.');
            window.location.href = '../index.php';
        } else {
            alert('Error submitting form. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting form. Please try again.');
    });
});

showStep(1);
</script>

<?php include '../includes/footer.php'; ?>
