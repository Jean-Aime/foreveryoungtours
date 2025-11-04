// Booking System JavaScript
let currentTourId = null;
let currentTourPrice = 0;

// Open booking modal
function openBookingModal(tourId, tourName, tourPrice) {
    currentTourId = tourId;
    currentTourPrice = tourPrice;
    
    document.getElementById('tourId').value = tourId;
    document.getElementById('tourName').textContent = tourName;
    document.getElementById('tourPrice').textContent = '$' + new Intl.NumberFormat().format(tourPrice);
    
    updateTotalPrice();
    document.getElementById('bookingModal').classList.remove('hidden');
}

// Close booking modal
function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
    document.getElementById('bookingForm').reset();
}

// Update total price based on participants
function updateTotalPrice() {
    const participants = document.getElementById('participants').value || 1;
    const total = currentTourPrice * participants;
    document.getElementById('totalPrice').textContent = '$' + new Intl.NumberFormat().format(total);
}

// Handle booking form submission
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const participants = formData.get('participants');
            const totalPrice = currentTourPrice * participants;
            
            // Add calculated total price to form data
            formData.append('total_price', totalPrice);
            
            // Submit booking
            fetch('booking-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking submitted successfully! We will contact you soon.');
                    closeBookingModal();
                } else {
                    alert('Error: ' + (data.message || 'Failed to submit booking'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('bookingModal');
    if (modal && e.target === modal) {
        closeBookingModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBookingModal();
    }
});