<!-- Inquiry Modal -->
<div id="inquiryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b sticky top-0 bg-white z-10">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">FOREVER YOUNG TOURS LTD.</h3>
                    <p class="text-sm text-slate-600 mt-1">Customer Information Form</p>
                </div>
                <button onclick="closeInquiryModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="inquiryForm" class="p-6">
            <input type="hidden" name="tour_id" id="inquiry_tour_id">
            <input type="hidden" name="tour_name" id="inquiry_tour_name">
            
            <!-- Traveler Details -->
            <div class="mb-8">
                <h4 class="font-bold text-lg text-slate-900 mb-4 border-b pb-2">Traveler Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Client Name *</label>
                        <input type="text" name="client_name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Phone / WhatsApp *</label>
                        <input type="tel" name="phone" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                        <input type="text" name="address" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Number of Adults *</label>
                        <input type="number" name="adults" min="1" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Number of Children and Ages</label>
                        <input type="text" name="children" placeholder="e.g., 2 (ages 5, 8)" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Dates of Travel *</label>
                        <input type="text" name="travel_dates" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Flexible</label>
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center text-sm"><input type="radio" name="flexible" value="yes" class="mr-2"> Yes</label>
                            <label class="flex items-center text-sm"><input type="radio" name="flexible" value="no" class="mr-2"> No</label>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Vacation Budget (USD) *</label>
                        <input type="text" name="budget" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <!-- Tour Categories -->
            <div class="mb-8">
                <h4 class="font-bold text-lg text-slate-900 mb-4 border-b pb-2">Tour Categories (Select All That Apply)</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="Adventure Tours" class="mr-2"> Adventure Tours</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="Agro-Tourism" class="mr-2"> Agro-Tourism</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="Sports Tourism" class="mr-2"> Sports Tourism</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="Cultural Exchanges" class="mr-2"> Cultural Exchanges</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="Conferences" class="mr-2"> Conferences</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="Expos" class="mr-2"> Expos</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="Motorcoach Tours" class="mr-2"> Motorcoach Tours</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="Rail Tours" class="mr-2"> Rail Tours</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="Cruises" class="mr-2"> Cruises</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="categories[]" value="City Breaks" class="mr-2"> City Breaks</label>
                </div>
            </div>

            <!-- Destinations -->
            <div class="mb-8">
                <h4 class="font-bold text-lg text-slate-900 mb-4 border-b pb-2">Destinations (Select All That Apply)</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <label class="flex items-center text-sm"><input type="checkbox" name="destinations[]" value="African Tours" class="mr-2"> African Tours</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="destinations[]" value="Caribbean Tours" class="mr-2"> Caribbean Tours</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="destinations[]" value="North American Tours" class="mr-2"> North American Tours</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="destinations[]" value="Asian Tours" class="mr-2"> Asian Tours</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="destinations[]" value="European Tours" class="mr-2"> European Tours</label>
                </div>
            </div>

            <!-- Group Travel Information -->
            <div class="mb-8">
                <h4 class="font-bold text-lg text-slate-900 mb-4 border-b pb-2">Group Travel Information</h4>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">If Traveling in a Group, describe your Group:</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Family" class="mr-2"> Family</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Friends" class="mr-2"> Friends</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Friends & Family" class="mr-2"> Friends & Family</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Religious Group" class="mr-2"> Religious Group</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Company" class="mr-2"> Company</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Conference" class="mr-2"> Conference</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Expo" class="mr-2"> Expo</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Sporting Event" class="mr-2"> Sporting Event</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Cultural Event" class="mr-2"> Cultural Event</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="group_type[]" value="Other" class="mr-2"> Other</label>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Number of Persons in your Group</label>
                        <select name="group_size" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
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
                        <label class="block text-sm font-medium text-slate-700 mb-1">Group Leader Name</label>
                        <input type="text" name="group_leader" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Group Leader Mobile / WhatsApp / Email</label>
                        <input type="text" name="group_leader_contact" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <!-- Travel Preferences -->
            <div class="mb-8">
                <h4 class="font-bold text-lg text-slate-900 mb-4 border-b pb-2">Travel Preferences</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Departure City</label>
                        <input type="text" name="departure_city" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Seat Preference</label>
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center text-sm"><input type="radio" name="seat_preference" value="Window" class="mr-2"> Window</label>
                            <label class="flex items-center text-sm"><input type="radio" name="seat_preference" value="Aisle" class="mr-2"> Aisle</label>
                            <label class="flex items-center text-sm"><input type="radio" name="seat_preference" value="Middle" class="mr-2"> Middle</label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Airline Preference</label>
                        <input type="text" name="airline" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Class</label>
                        <div class="flex gap-4 mt-2 flex-wrap">
                            <label class="flex items-center text-sm"><input type="radio" name="class" value="Economy" class="mr-2"> Economy</label>
                            <label class="flex items-center text-sm"><input type="radio" name="class" value="Premium" class="mr-2"> Premium</label>
                            <label class="flex items-center text-sm"><input type="radio" name="class" value="Business" class="mr-2"> Business</label>
                            <label class="flex items-center text-sm"><input type="radio" name="class" value="First" class="mr-2"> First</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Hotel Preferences</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <label class="flex items-center text-sm"><input type="checkbox" name="hotel_prefs[]" value="Luxury Resort" class="mr-2"> Luxury Resort</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="hotel_prefs[]" value="Beachfront" class="mr-2"> Beachfront</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="hotel_prefs[]" value="City Center" class="mr-2"> City Center</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="hotel_prefs[]" value="Family Friendly" class="mr-2"> Family Friendly</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="hotel_prefs[]" value="Adults Only" class="mr-2"> Adults Only</label>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Room Type</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <label class="flex items-center text-sm"><input type="checkbox" name="room_type[]" value="Standard" class="mr-2"> Standard</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="room_type[]" value="Suite" class="mr-2"> Suite</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="room_type[]" value="Ocean View" class="mr-2"> Ocean View</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="room_type[]" value="Garden View" class="mr-2"> Garden View</label>
                        <label class="flex items-center text-sm"><input type="checkbox" name="room_type[]" value="Concierge Level" class="mr-2"> Concierge Level</label>
                    </div>
                </div>
            </div>

            <!-- Activities of Interest -->
            <div class="mb-8">
                <h4 class="font-bold text-lg text-slate-900 mb-4 border-b pb-2">Activities of Interest</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Sightseeing / History" class="mr-2"> Sightseeing / History</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Wine / Culinary" class="mr-2"> Wine / Culinary</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Culture / Arts" class="mr-2"> Culture / Arts</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Shopping" class="mr-2"> Shopping</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Active / Sports" class="mr-2"> Active / Sports</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Beach / Sun" class="mr-2"> Beach / Sun</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Spa" class="mr-2"> Spa</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Golf" class="mr-2"> Golf</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Wildlife / Safari" class="mr-2"> Wildlife / Safari</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="activities[]" value="Wellness & Fitness" class="mr-2"> Wellness & Fitness</label>
                </div>
            </div>

            <!-- Additional Services -->
            <div class="mb-8">
                <h4 class="font-bold text-lg text-slate-900 mb-4 border-b pb-2">Additional Services</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="Air Travel" class="mr-2"> Air Travel</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="Cruise Vacation" class="mr-2"> Cruise Vacation</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="Hotel / Resort Stay" class="mr-2"> Hotel / Resort Stay</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="Car Rental" class="mr-2"> Car Rental</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="Escorted Tour" class="mr-2"> Escorted Tour</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="Independent Tour" class="mr-2"> Independent Tour</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="Travel Insurance" class="mr-2"> Travel Insurance</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="All-Inclusive Package" class="mr-2"> All-Inclusive Package</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="Transfers" class="mr-2"> Transfers</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="services[]" value="Visa Assistance" class="mr-2"> Visa Assistance</label>
                </div>
            </div>

            <!-- How Did You Hear About Us -->
            <div class="mb-8">
                <h4 class="font-bold text-lg text-slate-900 mb-4 border-b pb-2">How Did You Hear About Us (Select All That Apply)</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                    <label class="flex items-center text-sm"><input type="checkbox" name="referral[]" value="MCA" class="mr-2"> MCA</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="referral[]" value="Advisor" class="mr-2"> Advisor</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="referral[]" value="FYT Staff" class="mr-2"> FYT Staff</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="referral[]" value="Ads" class="mr-2"> Ads</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="referral[]" value="Facebook" class="mr-2"> Facebook</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="referral[]" value="LinkedIn" class="mr-2"> LinkedIn</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="referral[]" value="X" class="mr-2"> X</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="referral[]" value="Instagram" class="mr-2"> Instagram</label>
                    <label class="flex items-center text-sm"><input type="checkbox" name="referral[]" value="Email" class="mr-2"> Email</label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">If From a MCA / Advisor / Staff</label>
                    <input type="text" name="referral_name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
            </div>

            <!-- Notes & Special Requests -->
            <div class="mb-8">
                <h4 class="font-bold text-lg text-slate-900 mb-4 border-b pb-2">Notes & Special Requests</h4>
                <textarea name="notes" rows="4" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Any special requests, dietary requirements, or additional information..."></textarea>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t">
                <button type="button" onclick="closeInquiryModal()" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">
                    Submit Inquiry
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openInquiryModal(tourId = '', tourName = '') {
    document.getElementById('inquiryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    if (tourId) document.getElementById('inquiry_tour_id').value = tourId;
    if (tourName) document.getElementById('inquiry_tour_name').value = tourName;
}

function closeInquiryModal() {
    document.getElementById('inquiryModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('inquiryForm').reset();
}

document.getElementById('inquiryForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('submit-booking.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Thank you! Your inquiry has been submitted. We will contact you within 24 hours.');
            closeInquiryModal();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error submitting inquiry. Please try again.');
    }
});
</script>
