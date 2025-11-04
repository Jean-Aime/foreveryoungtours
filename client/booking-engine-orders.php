<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "My Booking Engine Orders";
include 'includes/client-header.php';
?>

<style>
.container { max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; }
.page-header { margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; }
.page-subtitle { color: #6b7280; }
.stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.stat-box { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.stat-value { font-size: 2rem; font-weight: 700; color: #DAA520; }
.stat-label { color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem; }
.filters { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; }
.filter-select { padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; }
.orders-list { display: grid; gap: 1.5rem; }
.order-card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; transition: all 0.3s; }
.order-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.15); transform: translateY(-4px); }
.order-header { background: linear-gradient(135deg, #DAA520, #B8860B); color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
.order-ref { font-size: 1.25rem; font-weight: 700; }
.order-type { background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.875rem; }
.order-body { padding: 1.5rem; }
.order-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
.detail-item { }
.detail-label { font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
.detail-value { font-weight: 600; color: #1f2937; }
.order-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #e5e7eb; }
.status-badge { padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.875rem; font-weight: 600; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-confirmed { background: #dbeafe; color: #1e40af; }
.status-paid { background: #d1fae5; color: #065f46; }
.status-completed { background: #e0e7ff; color: #3730a3; }
.status-cancelled { background: #fee2e2; color: #991b1b; }
.price-tag { font-size: 1.5rem; font-weight: 700; color: #dc3545; }
.btn-track { background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; }
.empty-state { text-align: center; padding: 4rem 2rem; }
.empty-icon { font-size: 4rem; margin-bottom: 1rem; }
.empty-text { color: #6b7280; font-size: 1.125rem; }
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">My Booking Engine Orders</h1>
        <p class="page-subtitle">Track all your flights, hotels, cars, cruises, and activities bookings</p>
    </div>

    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-value" id="total-orders">0</div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="pending-orders">0</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="confirmed-orders">0</div>
            <div class="stat-label">Confirmed</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" id="completed-orders">0</div>
            <div class="stat-label">Completed</div>
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
    </div>

    <div class="orders-list" id="orders-container"></div>
</div>

<script>
let allOrders = [];

function loadOrders() {
    fetch('booking-engine-orders-api.php?action=get')
        .then(r => r.json())
        .then(data => {
            allOrders = data;
            displayOrders(data);
            updateStats(data);
        });
}

function displayOrders(orders) {
    const container = document.getElementById('orders-container');
    
    if (orders.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <div class="empty-text">No orders found</div>
            </div>`;
        return;
    }
    
    container.innerHTML = orders.map(order => `
        <div class="order-card">
            <div class="order-header">
                <div class="order-ref">📋 ${order.booking_reference}</div>
                <div class="order-type">${getTypeIcon(order.booking_type)} ${order.booking_type.toUpperCase()}</div>
            </div>
            <div class="order-body">
                <div class="order-details">
                    <div class="detail-item">
                        <div class="detail-label">Customer Name</div>
                        <div class="detail-value">${order.customer_name}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">${order.customer_email}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Booking Date</div>
                        <div class="detail-value">${formatDate(order.booking_date)}</div>
                    </div>
                    ${order.return_date ? `<div class="detail-item">
                        <div class="detail-label">Return Date</div>
                        <div class="detail-value">${formatDate(order.return_date)}</div>
                    </div>` : ''}
                    <div class="detail-item">
                        <div class="detail-label">Passengers</div>
                        <div class="detail-value">${order.passengers} ${order.passengers > 1 ? 'People' : 'Person'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Created</div>
                        <div class="detail-value">${formatDateTime(order.created_at)}</div>
                    </div>
                </div>
                ${order.notes ? `<div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                    <div class="detail-label">Notes</div>
                    <div style="color: #374151; margin-top: 0.5rem;">${order.notes}</div>
                </div>` : ''}
                <div class="order-footer">
                    <div>
                        <span class="status-badge status-${order.status}">${order.status.toUpperCase()}</span>
                        <span class="status-badge status-${order.payment_status}" style="margin-left: 0.5rem;">${order.payment_status.toUpperCase()}</span>
                    </div>
                    <div class="price-tag">$${parseFloat(order.total_price).toFixed(2)}</div>
                </div>
            </div>
        </div>
    `).join('');
}

function updateStats(orders) {
    document.getElementById('total-orders').textContent = orders.length;
    document.getElementById('pending-orders').textContent = orders.filter(o => o.status === 'pending').length;
    document.getElementById('confirmed-orders').textContent = orders.filter(o => o.status === 'confirmed').length;
    document.getElementById('completed-orders').textContent = orders.filter(o => o.status === 'completed').length;
}

function filterOrders() {
    const type = document.getElementById('filter-type').value;
    const status = document.getElementById('filter-status').value;
    
    let filtered = allOrders;
    if (type) filtered = filtered.filter(o => o.booking_type === type);
    if (status) filtered = filtered.filter(o => o.status === status);
    
    displayOrders(filtered);
}

function getTypeIcon(type) {
    const icons = {
        flight: '✈️',
        hotel: '🏨',
        car: '🚗',
        cruise: '🚢',
        activity: '🎯'
    };
    return icons[type] || '📦';
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'});
}

function formatDateTime(datetime) {
    return new Date(datetime).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'});
}

loadOrders();
</script>

            </div>
        </div>
    </div>
<?php include 'includes/client-footer.php'; ?>
