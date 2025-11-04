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

$page_title = "Booking Engine Orders";
$current_page = 'booking-engine-orders';
include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<style>
.content-wrapper { margin-left: 260px; padding: 2rem; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
.stat-card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.stat-value { font-size: 2rem; font-weight: 700; color: #DAA520; }
.stat-label { color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem; }
.filters { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; }
.filter-select { padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; }
.table-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
table { width: 100%; border-collapse: collapse; }
th { background: #f9fafb; padding: 1rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb; }
td { padding: 1rem; border-bottom: 1px solid #e5e7eb; }
.status-badge { padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-confirmed { background: #dbeafe; color: #1e40af; }
.status-paid { background: #d1fae5; color: #065f46; }
.status-completed { background: #e0e7ff; color: #3730a3; }
.status-cancelled { background: #fee2e2; color: #991b1b; }
.btn-action { padding: 0.5rem 1rem; border-radius: 6px; border: none; cursor: pointer; font-size: 0.875rem; margin-right: 0.5rem; }
.btn-view { background: #3b82f6; color: white; }
.btn-update { background: #10b981; color: white; }
</style>

<div class="content-wrapper">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem;">Booking Engine Orders</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value" id="total-orders">0</div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="pending-orders">0</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="confirmed-orders">0</div>
            <div class="stat-label">Confirmed</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="completed-orders">0</div>
            <div class="stat-label">Completed</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="total-revenue">$0</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>

    <div class="filters">
        <select class="filter-select" id="filter-type" onchange="filterOrders()">
            <option value="">All Types</option>
            <option value="flight">Flights</option>
            <option value="hotel">Hotels</option>
            <option value="car">Cars</option>
            <option value="cruise">Cruises</option>
            <option value="activity">Activities</option>
        </select>
        <select class="filter-select" id="filter-status" onchange="filterOrders()">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="paid">Paid</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <input type="text" class="filter-select" id="search-ref" placeholder="Search by reference..." onkeyup="filterOrders()">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Type</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Passengers</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="orders-tbody"></tbody>
        </table>
    </div>
</div>

<script>
let allOrders = [];

function loadOrders() {
    fetch('booking-engine-orders-api.php?action=get_all')
        .then(r => r.json())
        .then(data => {
            allOrders = data;
            displayOrders(data);
            updateStats(data);
        });
}

function displayOrders(orders) {
    const tbody = document.getElementById('orders-tbody');
    tbody.innerHTML = orders.map(order => `
        <tr>
            <td><strong>${order.booking_reference}</strong></td>
            <td>${getTypeIcon(order.booking_type)} ${order.booking_type.toUpperCase()}</td>
            <td>${order.customer_name}<br><small style="color:#6b7280;">${order.customer_email}</small></td>
            <td>${formatDate(order.booking_date)}</td>
            <td>${order.passengers}</td>
            <td><strong>$${parseFloat(order.total_price).toFixed(2)}</strong></td>
            <td><span class="status-badge status-${order.status}">${order.status.toUpperCase()}</span></td>
            <td><span class="status-badge status-${order.payment_status}">${order.payment_status.toUpperCase()}</span></td>
            <td>
                <button class="btn-action btn-view" onclick="viewOrder(${order.id})">View</button>
                <select onchange="updateStatus(${order.id}, this.value, 'status')" style="padding:0.5rem;border-radius:6px;border:1px solid #d1d5db;">
                    <option value="">Update Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="paid">Paid</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </td>
        </tr>
    `).join('');
}

function updateStats(orders) {
    document.getElementById('total-orders').textContent = orders.length;
    document.getElementById('pending-orders').textContent = orders.filter(o => o.status === 'pending').length;
    document.getElementById('confirmed-orders').textContent = orders.filter(o => o.status === 'confirmed').length;
    document.getElementById('completed-orders').textContent = orders.filter(o => o.status === 'completed').length;
    
    const revenue = orders.reduce((sum, o) => sum + parseFloat(o.total_price), 0);
    document.getElementById('total-revenue').textContent = '$' + revenue.toFixed(2);
}

function filterOrders() {
    const type = document.getElementById('filter-type').value;
    const status = document.getElementById('filter-status').value;
    const search = document.getElementById('search-ref').value.toLowerCase();
    
    let filtered = allOrders;
    if (type) filtered = filtered.filter(o => o.booking_type === type);
    if (status) filtered = filtered.filter(o => o.status === status);
    if (search) filtered = filtered.filter(o => o.booking_reference.toLowerCase().includes(search) || o.customer_name.toLowerCase().includes(search));
    
    displayOrders(filtered);
}

function updateStatus(id, status, field) {
    if (!status) return;
    
    fetch('booking-engine-orders-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'update_status', id, status, field})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            loadOrders();
            alert('Status updated successfully');
        }
    });
}

function viewOrder(id) {
    const order = allOrders.find(o => o.id === id);
    alert(`Order Details:\n\nReference: ${order.booking_reference}\nCustomer: ${order.customer_name}\nEmail: ${order.customer_email}\nPhone: ${order.customer_phone || 'N/A'}\nType: ${order.booking_type}\nDate: ${order.booking_date}\nPassengers: ${order.passengers}\nPrice: $${order.total_price}\nStatus: ${order.status}\nPayment: ${order.payment_status}\nNotes: ${order.notes || 'N/A'}`);
}

function getTypeIcon(type) {
    const icons = {flight: '✈️', hotel: '🏨', car: '🚗', cruise: '🚢', activity: '🎯'};
    return icons[type] || '📦';
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'});
}

loadOrders();
</script>

    </div>
</div>
<?php include 'includes/admin-footer.php'; ?>
