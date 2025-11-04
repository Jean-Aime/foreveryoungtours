<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$page_title = "Tour Calendar - iForYoungTours";
$css_path = "../assets/css/modern-styles.css";
include '../includes/header.php';
?>

<style>
.calendar-hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 5rem 0; text-align: center; color: white; }
.calendar-container { max-width: 1400px; margin: -3rem auto 3rem; padding: 0 1.5rem; position: relative; z-index: 10; }
.calendar-card { background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); padding: 2rem; }
.calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #e5e7eb; border: 1px solid #e5e7eb; }
.calendar-day { background: white; padding: 1rem; min-height: 120px; position: relative; }
.calendar-day.other-month { background: #f9fafb; color: #9ca3af; }
.day-number { font-weight: 600; margin-bottom: 0.5rem; }
.tour-item { background: linear-gradient(135deg, #DAA520, #B8860B); color: white; padding: 0.5rem; margin-bottom: 0.5rem; border-radius: 8px; cursor: pointer; font-size: 0.875rem; }
.tour-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(218,165,32,0.3); }
.btn-nav { background: #DAA520; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; }
.modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
.modal.active { display: flex; }
.modal-content { background: white; padding: 2rem; border-radius: 16px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; }
.tour-detail { margin-bottom: 1rem; }
.tour-detail label { font-weight: 600; color: #6b7280; font-size: 0.875rem; }
.tour-detail value { display: block; font-size: 1.125rem; color: #1f2937; margin-top: 0.25rem; }
.btn-book { background: #10b981; color: white; padding: 1rem 2rem; border-radius: 8px; border: none; cursor: pointer; width: 100%; font-weight: 700; font-size: 1.125rem; }
.btn-close { background: #6b7280; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; width: 100%; margin-top: 0.5rem; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; }
.form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; }
</style>

<div class="calendar-hero">
    <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 1rem;">Tour Calendar</h1>
    <p style="font-size: 1.125rem; opacity: 0.9;">Browse scheduled tours and book your adventure</p>
</div>

<div class="calendar-container">
    <div class="calendar-card">
        <div class="calendar-header">
            <button class="btn-nav" onclick="changeMonth(-1)">← Previous</button>
            <h2 id="current-month" style="font-size: 1.75rem; font-weight: 700;"></h2>
            <button class="btn-nav" onclick="changeMonth(1)">Next →</button>
        </div>

        <div class="calendar-grid">
            <div style="background: #f9fafb; padding: 0.75rem; font-weight: 600; text-align: center;">Sun</div>
            <div style="background: #f9fafb; padding: 0.75rem; font-weight: 600; text-align: center;">Mon</div>
            <div style="background: #f9fafb; padding: 0.75rem; font-weight: 600; text-align: center;">Tue</div>
            <div style="background: #f9fafb; padding: 0.75rem; font-weight: 600; text-align: center;">Wed</div>
            <div style="background: #f9fafb; padding: 0.75rem; font-weight: 600; text-align: center;">Thu</div>
            <div style="background: #f9fafb; padding: 0.75rem; font-weight: 600; text-align: center;">Fri</div>
            <div style="background: #f9fafb; padding: 0.75rem; font-weight: 600; text-align: center;">Sat</div>
        </div>
        <div class="calendar-grid" id="calendar-days"></div>
    </div>
</div>

<div id="tour-modal" class="modal">
    <div class="modal-content">
        <h2 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem;" id="modal-title"></h2>
        <div id="tour-details"></div>
        <button class="btn-book" onclick="showBookingForm()">Book This Tour</button>
        <button class="btn-close" onclick="closeModal()">Close</button>
    </div>
</div>

<div id="booking-modal" class="modal">
    <div class="modal-content">
        <h2 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem;">Complete Booking</h2>
        <form id="booking-form">
            <input type="hidden" id="schedule-id">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" id="customer-name" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" id="customer-email" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="tel" id="customer-phone">
            </div>
            <div class="form-group">
                <label>Number of People *</label>
                <input type="number" id="num-people" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label>Total Amount</label>
                <div style="font-size: 1.5rem; font-weight: 700; color: #dc3545;" id="total-amount">$0</div>
            </div>
            <div class="form-group">
                <label>Special Requests</label>
                <textarea id="special-requests" rows="3"></textarea>
            </div>
            <button type="submit" class="btn-book">Confirm Booking</button>
            <button type="button" class="btn-close" onclick="closeBookingModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
let currentDate = new Date();
let schedules = [];
let selectedSchedule = null;

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    document.getElementById('current-month').textContent = 
        new Date(year, month).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    const container = document.getElementById('calendar-days');
    container.innerHTML = '';
    
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = daysInPrevMonth - i;
        container.innerHTML += `<div class="calendar-day other-month"><div class="day-number">${day}</div></div>`;
    }
    
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const daySchedules = schedules.filter(s => s.scheduled_date === dateStr && s.status === 'active');
        
        let toursHtml = '';
        daySchedules.forEach(s => {
            const available = s.available_slots - s.booked_slots;
            if (available > 0) {
                toursHtml += `<div class="tour-item" onclick="viewTour(${s.id})">${s.tour_title}<br><small>${available} slots left</small></div>`;
            }
        });
        
        container.innerHTML += `<div class="calendar-day"><div class="day-number">${day}</div>${toursHtml}</div>`;
    }
    
    const totalCells = container.children.length;
    const remainingCells = 42 - totalCells;
    for (let day = 1; day <= remainingCells; day++) {
        container.innerHTML += `<div class="calendar-day other-month"><div class="day-number">${day}</div></div>`;
    }
}

function changeMonth(delta) {
    currentDate.setMonth(currentDate.getMonth() + delta);
    loadSchedules();
}

function loadSchedules() {
    fetch('tour-calendar-api.php?action=get_schedules')
        .then(r => r.json())
        .then(data => {
            schedules = data;
            renderCalendar();
        });
}

function viewTour(id) {
    selectedSchedule = schedules.find(s => s.id === id);
    const available = selectedSchedule.available_slots - selectedSchedule.booked_slots;
    
    document.getElementById('modal-title').textContent = selectedSchedule.tour_title;
    document.getElementById('tour-details').innerHTML = `
        <div class="tour-detail"><label>Date</label><value>${new Date(selectedSchedule.scheduled_date).toLocaleDateString('en-US', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}</value></div>
        <div class="tour-detail"><label>Price per Person</label><value>$${parseFloat(selectedSchedule.price).toFixed(2)}</value></div>
        <div class="tour-detail"><label>Available Slots</label><value>${available} / ${selectedSchedule.available_slots}</value></div>
        ${selectedSchedule.notes ? `<div class="tour-detail"><label>Notes</label><value>${selectedSchedule.notes}</value></div>` : ''}
    `;
    document.getElementById('tour-modal').classList.add('active');
}

function closeModal() {
    document.getElementById('tour-modal').classList.remove('active');
}

function showBookingForm() {
    closeModal();
    document.getElementById('schedule-id').value = selectedSchedule.id;
    document.getElementById('booking-modal').classList.add('active');
    updateTotal();
}

function closeBookingModal() {
    document.getElementById('booking-modal').classList.remove('active');
    document.getElementById('booking-form').reset();
}

document.getElementById('num-people').addEventListener('input', updateTotal);

function updateTotal() {
    const numPeople = document.getElementById('num-people').value || 1;
    const total = selectedSchedule.price * numPeople;
    document.getElementById('total-amount').textContent = `$${total.toFixed(2)}`;
}

document.getElementById('booking-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        schedule_id: document.getElementById('schedule-id').value,
        customer_name: document.getElementById('customer-name').value,
        customer_email: document.getElementById('customer-email').value,
        customer_phone: document.getElementById('customer-phone').value,
        number_of_people: document.getElementById('num-people').value,
        total_amount: selectedSchedule.price * document.getElementById('num-people').value,
        special_requests: document.getElementById('special-requests').value
    };
    
    fetch('tour-calendar-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'book', data: formData})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(`Booking successful! Your reference: ${data.booking_reference}`);
            closeBookingModal();
            loadSchedules();
        } else {
            alert('Booking failed: ' + data.message);
        }
    });
});

loadSchedules();
</script>

<?php include '../includes/footer.php'; ?>
