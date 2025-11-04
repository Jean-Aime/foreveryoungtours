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

$page_title = "Booking Engine Management";
$current_page = 'booking-engine-management';
include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<style>
.content-wrapper { margin-left: 260px; padding: 2rem; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
.stat-card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.stat-value { font-size: 2rem; font-weight: 700; color: #DAA520; }
.stat-label { color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem; }
.tabs { display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid #e5e7eb; }
.tab-btn { padding: 1rem 1.5rem; background: none; border: none; font-weight: 600; color: #6b7280; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; }
.tab-btn.active { color: #DAA520; border-bottom-color: #DAA520; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.action-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.btn-add { background: #DAA520; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; }
.table-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
table { width: 100%; border-collapse: collapse; }
th { background: #f9fafb; padding: 1rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb; }
td { padding: 1rem; border-bottom: 1px solid #e5e7eb; }
.status-badge { padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
.status-active { background: #d1fae5; color: #065f46; }
.status-inactive { background: #fee2e2; color: #991b1b; }
.btn-edit, .btn-delete { padding: 0.5rem 1rem; border-radius: 6px; border: none; cursor: pointer; font-size: 0.875rem; margin-right: 0.5rem; }
.btn-edit { background: #3b82f6; color: white; }
.btn-delete { background: #ef4444; color: white; }
.modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
.modal.active { display: flex; }
.modal-content { background: white; padding: 2rem; border-radius: 12px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; }
.form-actions { display: flex; gap: 1rem; margin-top: 1.5rem; }
.btn-save { background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; flex: 1; }
.btn-cancel { background: #6b7280; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; flex: 1; }
</style>

<div class="content-wrapper">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem;">Booking Engine Management</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value" id="stat-flights">0</div>
            <div class="stat-label">Total Flights</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-hotels">0</div>
            <div class="stat-label">Total Hotels</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-cars">0</div>
            <div class="stat-label">Total Cars</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-cruises">0</div>
            <div class="stat-label">Total Cruises</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-activities">0</div>
            <div class="stat-label">Total Activities</div>
        </div>
    </div>

    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('flights')">✈️ Flights</button>
        <button class="tab-btn" onclick="switchTab('hotels')">🏨 Hotels</button>
        <button class="tab-btn" onclick="switchTab('cars')">🚗 Cars</button>
        <button class="tab-btn" onclick="switchTab('cruises')">🚢 Cruises</button>
        <button class="tab-btn" onclick="switchTab('activities')">🎯 Activities</button>
    </div>

    <div id="tab-flights" class="tab-content active">
        <div class="action-bar">
            <h2>Manage Flights</h2>
            <button class="btn-add" onclick="openModal('flights')">+ Add Flight</button>
        </div>
        <div class="table-container">
            <table id="flights-table">
                <thead>
                    <tr>
                        <th>Airline</th>
                        <th>Route</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Seats</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="tab-hotels" class="tab-content">
        <div class="action-bar">
            <h2>Manage Hotels</h2>
            <button class="btn-add" onclick="openModal('hotels')">+ Add Hotel</button>
        </div>
        <div class="table-container">
            <table id="hotels-table">
                <thead>
                    <tr>
                        <th>Hotel Name</th>
                        <th>Location</th>
                        <th>Room Type</th>
                        <th>Price/Night</th>
                        <th>Rooms</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="tab-cars" class="tab-content">
        <div class="action-bar">
            <h2>Manage Cars</h2>
            <button class="btn-add" onclick="openModal('cars')">+ Add Car</button>
        </div>
        <div class="table-container">
            <table id="cars-table">
                <thead>
                    <tr>
                        <th>Car Name</th>
                        <th>Type</th>
                        <th>Seats/Bags</th>
                        <th>Transmission</th>
                        <th>Price/Day</th>
                        <th>Units</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="tab-cruises" class="tab-content">
        <div class="action-bar">
            <h2>Manage Cruises</h2>
            <button class="btn-add" onclick="openModal('cruises')">+ Add Cruise</button>
        </div>
        <div class="table-container">
            <table id="cruises-table">
                <thead>
                    <tr>
                        <th>Cruise Name</th>
                        <th>Destination</th>
                        <th>Duration</th>
                        <th>Price/Person</th>
                        <th>Cabins</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="tab-activities" class="tab-content">
        <div class="action-bar">
            <h2>Manage Activities</h2>
            <button class="btn-add" onclick="openModal('activities')">+ Add Activity</button>
        </div>
        <div class="table-container">
            <table id="activities-table">
                <thead>
                    <tr>
                        <th>Activity Name</th>
                        <th>Location</th>
                        <th>Duration</th>
                        <th>Type</th>
                        <th>Price/Person</th>
                        <th>Slots</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal" class="modal">
    <div class="modal-content">
        <h2 id="modal-title">Add Item</h2>
        <form id="item-form">
            <input type="hidden" id="item-id">
            <input type="hidden" id="item-type">
            <div id="form-fields"></div>
            <div class="form-actions">
                <button type="submit" class="btn-save">Save</button>
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentType = 'flights';

function switchTab(type) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById('tab-' + type).classList.add('active');
    currentType = type;
    loadData(type);
}

function loadData(type) {
    fetch(`booking-engine-api.php?action=get&type=${type}`)
        .then(r => r.json())
        .then(data => {
            const tbody = document.querySelector(`#${type}-table tbody`);
            tbody.innerHTML = data.map(item => renderRow(type, item)).join('');
            updateStats();
        });
}

function renderRow(type, item) {
    const status = `<span class="status-badge status-${item.status}">${item.status}</span>`;
    const actions = `<button class="btn-edit" onclick='editItem(${JSON.stringify(item)}, "${type}")'>Edit</button><button class="btn-delete" onclick="deleteItem(${item.id}, '${type}')">Delete</button>`;
    
    if (type === 'flights') {
        return `<tr><td>${item.airline_name}</td><td>${item.from_city} → ${item.to_city}</td><td>${item.departure_time} - ${item.arrival_time}</td><td>${item.flight_type}</td><td>$${item.price}</td><td>${item.available_seats}</td><td>${status}</td><td>${actions}</td></tr>`;
    } else if (type === 'hotels') {
        return `<tr><td>${item.hotel_name}</td><td>${item.location}</td><td>${item.room_type}</td><td>$${item.price_per_night}</td><td>${item.available_rooms}</td><td>${status}</td><td>${actions}</td></tr>`;
    } else if (type === 'cars') {
        return `<tr><td>${item.car_name}</td><td>${item.car_type}</td><td>${item.seats}/${item.bags}</td><td>${item.transmission}</td><td>$${item.price_per_day}</td><td>${item.available_units}</td><td>${status}</td><td>${actions}</td></tr>`;
    } else if (type === 'cruises') {
        return `<tr><td>${item.cruise_name}</td><td>${item.destination}</td><td>${item.duration_days} days</td><td>$${item.price_per_person}</td><td>${item.available_cabins}</td><td>${status}</td><td>${actions}</td></tr>`;
    } else if (type === 'activities') {
        return `<tr><td>${item.activity_name}</td><td>${item.location}</td><td>${item.duration}</td><td>${item.activity_type}</td><td>$${item.price_per_person}</td><td>${item.available_slots}</td><td>${status}</td><td>${actions}</td></tr>`;
    }
}

function updateStats() {
    ['flights', 'hotels', 'cars', 'cruises', 'activities'].forEach(type => {
        fetch(`booking-engine-api.php?action=count&type=${type}`)
            .then(r => r.json())
            .then(data => document.getElementById(`stat-${type}`).textContent = data.count);
    });
}

function openModal(type, item = null) {
    document.getElementById('modal').classList.add('active');
    document.getElementById('item-type').value = type;
    document.getElementById('modal-title').textContent = item ? `Edit ${type.slice(0, -1)}` : `Add ${type.slice(0, -1)}`;
    
    const fields = getFormFields(type);
    document.getElementById('form-fields').innerHTML = fields;
    
    if (item) {
        document.getElementById('item-id').value = item.id;
        Object.keys(item).forEach(key => {
            const input = document.getElementById(key);
            if (input) input.value = item[key];
        });
    } else {
        document.getElementById('item-id').value = '';
        document.getElementById('item-form').reset();
    }
}

function getFormFields(type) {
    const common = `<div class="form-group"><label>Status</label><select id="status"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>`;
    
    if (type === 'flights') {
        return `<div class="form-group"><label>Airline Name</label><input type="text" id="airline_name" required></div>
                <div class="form-group"><label>From City</label><input type="text" id="from_city" required></div>
                <div class="form-group"><label>To City</label><input type="text" id="to_city" required></div>
                <div class="form-group"><label>Departure Time</label><input type="time" id="departure_time" required></div>
                <div class="form-group"><label>Arrival Time</label><input type="time" id="arrival_time" required></div>
                <div class="form-group"><label>Flight Type</label><select id="flight_type"><option>non-stop</option><option>1-stop</option><option>2-stop</option></select></div>
                <div class="form-group"><label>Duration</label><input type="text" id="duration" placeholder="2h 55m" required></div>
                <div class="form-group"><label>Price</label><input type="number" id="price" step="0.01" required></div>
                <div class="form-group"><label>Available Seats</label><input type="number" id="available_seats" required></div>${common}`;
    } else if (type === 'hotels') {
        return `<div class="form-group"><label>Hotel Name</label><input type="text" id="hotel_name" required></div>
                <div class="form-group"><label>Location</label><input type="text" id="location" required></div>
                <div class="form-group"><label>Room Type</label><input type="text" id="room_type" required></div>
                <div class="form-group"><label>Amenities</label><textarea id="amenities" rows="2"></textarea></div>
                <div class="form-group"><label>Price Per Night</label><input type="number" id="price_per_night" step="0.01" required></div>
                <div class="form-group"><label>Available Rooms</label><input type="number" id="available_rooms" required></div>${common}`;
    } else if (type === 'cars') {
        return `<div class="form-group"><label>Car Name</label><input type="text" id="car_name" required></div>
                <div class="form-group"><label>Car Type</label><input type="text" id="car_type" required></div>
                <div class="form-group"><label>Seats</label><input type="number" id="seats" required></div>
                <div class="form-group"><label>Bags</label><input type="number" id="bags" required></div>
                <div class="form-group"><label>Transmission</label><select id="transmission"><option>automatic</option><option>manual</option></select></div>
                <div class="form-group"><label>Price Per Day</label><input type="number" id="price_per_day" step="0.01" required></div>
                <div class="form-group"><label>Available Units</label><input type="number" id="available_units" required></div>${common}`;
    } else if (type === 'cruises') {
        return `<div class="form-group"><label>Cruise Name</label><input type="text" id="cruise_name" required></div>
                <div class="form-group"><label>Destination</label><input type="text" id="destination" required></div>
                <div class="form-group"><label>Duration (Days)</label><input type="number" id="duration_days" required></div>
                <div class="form-group"><label>Ports Count</label><input type="number" id="ports_count" required></div>
                <div class="form-group"><label>Price Per Person</label><input type="number" id="price_per_person" step="0.01" required></div>
                <div class="form-group"><label>Available Cabins</label><input type="number" id="available_cabins" required></div>${common}`;
    } else if (type === 'activities') {
        return `<div class="form-group"><label>Activity Name</label><input type="text" id="activity_name" required></div>
                <div class="form-group"><label>Location</label><input type="text" id="location" required></div>
                <div class="form-group"><label>Duration</label><input type="text" id="duration" placeholder="Full Day" required></div>
                <div class="form-group"><label>Activity Type</label><input type="text" id="activity_type" required></div>
                <div class="form-group"><label>Includes</label><textarea id="includes" rows="2"></textarea></div>
                <div class="form-group"><label>Price Per Person</label><input type="number" id="price_per_person" step="0.01" required></div>
                <div class="form-group"><label>Available Slots</label><input type="number" id="available_slots" required></div>${common}`;
    }
}

function closeModal() {
    document.getElementById('modal').classList.remove('active');
}

function editItem(item, type) {
    openModal(type, item);
}

function deleteItem(id, type) {
    if (!confirm('Are you sure you want to delete this item?')) return;
    
    fetch('booking-engine-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'delete', type, id})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            loadData(type);
            alert('Item deleted successfully');
        }
    });
}

document.getElementById('item-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const type = document.getElementById('item-type').value;
    const id = document.getElementById('item-id').value;
    const formData = {};
    
    document.querySelectorAll('#form-fields input, #form-fields select, #form-fields textarea').forEach(input => {
        formData[input.id] = input.value;
    });
    
    fetch('booking-engine-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: id ? 'update' : 'create', type, id, data: formData})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeModal();
            loadData(type);
            alert(id ? 'Item updated successfully' : 'Item created successfully');
        }
    });
});

loadData('flights');
updateStats();
</script>

    </div>
</div>
<?php include 'includes/admin-footer.php'; ?>
