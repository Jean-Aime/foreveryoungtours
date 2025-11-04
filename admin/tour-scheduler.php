<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_role = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';
if ($user_role !== 'super_admin' && $user_role !== 'admin') {
    header('Location: login.php');
    exit();
}

$page_title = "Tour Scheduler";
$current_page = 'tour-scheduler';
include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';

try {
    $stmt = $pdo->prepare("SELECT id, name as title, price FROM tours WHERE status = 'active' ORDER BY name");
    $stmt->execute();
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Tours query error: " . $e->getMessage());
    $tours = [];
}
?>

<style>
.content-wrapper { margin-left: 260px; padding: 2rem; }
.calendar-container { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #e5e7eb; border: 1px solid #e5e7eb; }
.calendar-day { background: white; padding: 1rem; min-height: 100px; position: relative; }
.calendar-day.other-month { background: #f9fafb; color: #9ca3af; }
.day-number { font-weight: 600; margin-bottom: 0.5rem; }
.scheduled-tour { background: #dbeafe; border-left: 3px solid #3b82f6; padding: 0.25rem 0.5rem; margin-bottom: 0.25rem; font-size: 0.75rem; border-radius: 4px; cursor: pointer; }
.scheduled-tour:hover { background: #bfdbfe; }
.btn-schedule { background: #DAA520; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; }
.modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
.modal.active { display: flex; }
.modal-content { background: white; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; }
.btn-save { background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; width: 100%; }
.btn-close { background: #6b7280; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; width: 100%; margin-top: 0.5rem; }
</style>

<div class="content-wrapper">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem;">Tour Scheduler</h1>

    <div class="calendar-container">
        <div class="calendar-header">
            <button onclick="changeMonth(-1)" style="padding: 0.5rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; background: white;">← Previous</button>
            <h2 id="current-month" style="font-size: 1.5rem; font-weight: 700;"></h2>
            <button onclick="changeMonth(1)" style="padding: 0.5rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; background: white;">Next →</button>
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

<div id="schedule-modal" class="modal">
    <div class="modal-content">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">Schedule Tour</h2>
        <form id="schedule-form">
            <input type="hidden" id="schedule-date">
            <div class="form-group">
                <label>Select Tour *</label>
                <select id="tour-id" required>
                    <option value="">Choose a tour...</option>
                    <?php if (!empty($tours)): ?>
                        <?php foreach ($tours as $tour): ?>
                            <option value="<?= $tour['id'] ?>" data-price="<?= $tour['price'] ?>"><?= htmlspecialchars($tour['title']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No active tours available</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>End Date (Optional)</label>
                <input type="date" id="end-date">
            </div>
            <div class="form-group">
                <label>Available Slots *</label>
                <input type="number" id="available-slots" value="20" min="1" required>
            </div>
            <div class="form-group">
                <label>Price *</label>
                <input type="number" id="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Notes</label>
                <textarea id="notes" rows="3"></textarea>
            </div>
            <button type="submit" class="btn-save">Schedule Tour</button>
            <button type="button" class="btn-close" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
let currentDate = new Date();
let schedules = [];

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
    
    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = daysInPrevMonth - i;
        container.innerHTML += `<div class="calendar-day other-month"><div class="day-number">${day}</div></div>`;
    }
    
    // Current month days
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const daySchedules = schedules.filter(s => s.scheduled_date === dateStr);
        
        let schedulesHtml = '';
        daySchedules.forEach(s => {
            schedulesHtml += `<div class="scheduled-tour" onclick="viewSchedule(${s.id})">${s.tour_title} (${s.booked_slots}/${s.available_slots})</div>`;
        });
        
        container.innerHTML += `
            <div class="calendar-day" onclick="openScheduleModal('${dateStr}')">
                <div class="day-number">${day}</div>
                ${schedulesHtml}
            </div>`;
    }
    
    // Next month days
    const totalCells = container.children.length;
    const remainingCells = 42 - totalCells;
    for (let day = 1; day <= remainingCells; day++) {
        container.innerHTML += `<div class="calendar-day other-month"><div class="day-number">${day}</div></div>`;
    }
}

function changeMonth(delta) {
    currentDate.setMonth(currentDate.getMonth() + delta);
    renderCalendar();
}

function loadSchedules() {
    fetch('tour-scheduler-api.php?action=get_schedules')
        .then(r => r.json())
        .then(data => {
            schedules = data;
            renderCalendar();
        });
}

function openScheduleModal(date) {
    document.getElementById('schedule-date').value = date;
    document.getElementById('schedule-modal').classList.add('active');
}

function closeModal() {
    document.getElementById('schedule-modal').classList.remove('active');
    document.getElementById('schedule-form').reset();
}

document.getElementById('tour-id').addEventListener('change', function() {
    const price = this.options[this.selectedIndex].dataset.price;
    if (price) document.getElementById('price').value = price;
});

document.getElementById('schedule-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        tour_id: document.getElementById('tour-id').value,
        scheduled_date: document.getElementById('schedule-date').value,
        end_date: document.getElementById('end-date').value,
        available_slots: document.getElementById('available-slots').value,
        price: document.getElementById('price').value,
        notes: document.getElementById('notes').value
    };
    
    fetch('tour-scheduler-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'create', data: formData})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeModal();
            loadSchedules();
            alert('Tour scheduled successfully!');
        }
    });
});

function viewSchedule(id) {
    const schedule = schedules.find(s => s.id === id);
    if (confirm(`Tour: ${schedule.tour_title}\nDate: ${schedule.scheduled_date}\nSlots: ${schedule.booked_slots}/${schedule.available_slots}\nPrice: $${schedule.price}\nStatus: ${schedule.status}\n\nDelete this schedule?`)) {
        fetch('tour-scheduler-api.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'delete', id: id})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                loadSchedules();
                alert('Schedule deleted successfully!');
            }
        });
    }
}

loadSchedules();
</script>

    </div>
</div>
<?php include 'includes/admin-footer.php'; ?>
