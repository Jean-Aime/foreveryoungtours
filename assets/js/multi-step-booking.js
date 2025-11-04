// Multi-step booking form functionality
let currentStep = 1;
const totalSteps = 4;
let bookingData = {};

function openMultiStepBooking(tourId, tourName, tourPrice) {
    bookingData = { tourId, tourName, tourPrice };
    document.getElementById('multiStepBookingModal').classList.remove('hidden');
    document.getElementById('modalTourName').textContent = tourName;
    document.getElementById('modalTourPrice').textContent = `$${tourPrice}`;
    showStep(1);
}

function closeMultiStepBooking() {
    document.getElementById('multiStepBookingModal').classList.add('hidden');
    currentStep = 1;
    bookingData = {};
}

function showStep(step) {
    // Hide all steps
    for (let i = 1; i <= totalSteps; i++) {
        document.getElementById(`step${i}`).classList.add('hidden');
        document.getElementById(`stepIndicator${i}`).classList.remove('bg-golden-500', 'text-black');
        document.getElementById(`stepIndicator${i}`).classList.add('bg-gray-300', 'text-gray-600');
    }
    
    // Show current step
    document.getElementById(`step${step}`).classList.remove('hidden');
    document.getElementById(`stepIndicator${step}`).classList.remove('bg-gray-300', 'text-gray-600');
    document.getElementById(`stepIndicator${step}`).classList.add('bg-golden-500', 'text-black');
    
    // Update buttons
    document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'block';
    document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'block';
    document.getElementById('submitBtn').style.display = step === totalSteps ? 'block' : 'none';
    
    currentStep = step;
}

function nextStep() {
    if (validateStep(currentStep)) {
        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        }
    }
}

function prevStep() {
    if (currentStep > 1) {
        showStep(currentStep - 1);
    }
}

function validateStep(step) {
    const stepElement = document.getElementById(`step${step}`);
    const requiredFields = stepElement.querySelectorAll('[required]');
    
    for (let field of requiredFields) {
        if (!field.value.trim()) {
            field.focus();
            field.classList.add('border-red-500');
            return false;
        }
        field.classList.remove('border-red-500');
    }
    
    if (step === 2) {
        updatePricingSummary();
    }
    
    return true;
}

function updatePricingSummary() {
    const participants = parseInt(document.getElementById('participants').value) || 1;
    const accommodation = document.getElementById('accommodation').value;
    const transport = document.getElementById('transport').value;
    
    let basePrice = bookingData.tourPrice;
    let accommodationPrice = 0;
    let transportPrice = 0;
    
    // Accommodation pricing
    if (accommodation === 'luxury') accommodationPrice = 200;
    else if (accommodation === 'premium') accommodationPrice = 100;
    
    // Transport pricing
    if (transport === 'private') transportPrice = 150;
    else if (transport === 'premium') transportPrice = 75;
    
    const subtotal = (basePrice + accommodationPrice + transportPrice) * participants;
    const tax = subtotal * 0.1;
    const total = subtotal + tax;
    
    document.getElementById('summaryParticipants').textContent = participants;
    document.getElementById('summaryBasePrice').textContent = `$${basePrice}`;
    document.getElementById('summaryAccommodation').textContent = `$${accommodationPrice}`;
    document.getElementById('summaryTransport').textContent = `$${transportPrice}`;
    document.getElementById('summarySubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('summaryTax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('summaryTotal').textContent = `$${total.toFixed(2)}`;
}

function submitBooking() {
    if (validateStep(4)) {
        // Collect all form data
        const formData = new FormData();
        formData.append('tour_id', bookingData.tourId);
        
        // Personal info
        formData.append('customer_name', document.getElementById('customerName').value);
        formData.append('customer_email', document.getElementById('customerEmail').value);
        formData.append('customer_phone', document.getElementById('customerPhone').value);
        formData.append('emergency_contact', document.getElementById('emergencyContact').value);
        
        // Travel details
        formData.append('travel_date', document.getElementById('travelDate').value);
        formData.append('participants', document.getElementById('participants').value);
        formData.append('accommodation', document.getElementById('accommodation').value);
        formData.append('transport', document.getElementById('transport').value);
        formData.append('special_requests', document.getElementById('specialRequests').value);
        
        // Payment info
        formData.append('payment_method', document.querySelector('input[name="payment_method"]:checked').value);
        
        // Submit to backend
        fetch('../admin/process_booking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Booking confirmed! Your booking reference is: ${data.booking_reference}`);
                closeMultiStepBooking();
                // Redirect to client bookings page if logged in
                if (window.location.pathname.includes('/client/')) {
                    window.location.href = 'bookings.php';
                } else {
                    window.location.reload();
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for participant count change
    const participantsSelect = document.getElementById('participants');
    if (participantsSelect) {
        participantsSelect.addEventListener('change', updatePricingSummary);
    }
});