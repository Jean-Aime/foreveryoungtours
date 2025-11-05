<?php
$page_title = "Booking Engine - iForYoungTours";
$css_path = "../assets/css/modern-styles.css";
include './header.php';
?>

<style>
.booking-hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 5rem 0 8rem; position: relative; overflow: hidden; }
.booking-hero::before { content: ''; position: absolute; inset: 0; background: url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1920') center/cover; opacity: 0.15; }
.booking-container { max-width: 1400px; margin: -5rem auto 3rem; padding: 0 1.5rem; position: relative; z-index: 10; }
.booking-card { background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); overflow: hidden; }
.tabs-header { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; border-bottom: 1px solid #e5e7eb; gap: 1rem; }
.tabs-nav { display: flex; gap: 0.5rem; background: #f3f4f6; padding: 0.5rem; border-radius: 12px; }
.tab { display: flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.5rem; border-radius: 10px; border: none; background: transparent; color: #6b7280; font-weight: 600; cursor: pointer; transition: all 0.3s; }
.tab.active { background: linear-gradient(135deg, #DAA520, #B8860B); color: white; box-shadow: 0 4px 12px rgba(218,165,32,0.3); }
.tab:hover:not(.active) { background: white; }
.help-text { display: flex; align-items: center; gap: 0.5rem; color: #6b7280; font-size: 0.875rem; }
.tab-content { padding: 2rem; border-top: 1px solid #e5e7eb; display: none; }
.tab-content.active { display: block; }
.form-title { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end; }
.form-field label { display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; }
.form-field input, .form-field select { width: 100%; padding: 0.875rem 1rem; border: 1px solid #d1d5db; border-radius: 10px; font-size: 1rem; transition: all 0.3s; }
.form-field input:focus, .form-field select:focus { outline: none; border-color: #DAA520; box-shadow: 0 0 0 3px rgba(218,165,32,0.1); }
.btn-search { background: linear-gradient(135deg, #DAA520, #B8860B); color: white; padding: 0.875rem 2rem; border-radius: 10px; border: none; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(218,165,32,0.3); display: inline-flex; align-items: center; gap: 0.5rem; }
.btn-search:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(218,165,32,0.4); }
.results-section { max-width: 1400px; margin: 0 auto; padding: 0 1.5rem 3rem; }
.layout { display: grid; grid-template-columns: 280px 1fr; gap: 2rem; }
.sidebar { background: white; border-radius: 16px; padding: 1.5rem; height: fit-content; box-shadow: 0 2px 12px rgba(0,0,0,0.08); position: sticky; top: 100px; }
.sidebar h3 { font-size: 1.125rem; font-weight: 700; margin: 0 0 1.5rem; }
.filter-group { margin-bottom: 1.5rem; }
.filter-group h4 { font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem; color: #374151; }
.filter-group label { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.875rem; cursor: pointer; }
.sort-bar { display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.sort-btn { padding: 0.625rem 1.25rem; border: 1px solid #e5e7eb; background: white; border-radius: 50px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.3s; }
.sort-btn.active { background: #FFF8DC; border-color: #DAA520; color: #B8860B; font-weight: 600; }
.sort-btn:hover { border-color: #DAA520; }
.result-card { background: white; border-radius: 16px; padding: 1.5rem; display: flex; gap: 1.5rem; align-items: center; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s; margin-bottom: 1rem; }
.result-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.12); transform: translateY(-4px); }
.result-logo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.result-info { flex: 1; }
.result-name { font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0 0 0.25rem; }
.result-rating { font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem; }
.result-details { display: flex; gap: 1.5rem; font-size: 0.875rem; color: #6b7280; flex-wrap: wrap; }
.result-price { text-align: right; flex-shrink: 0; }
.price-label { font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
.price-value { font-size: 2rem; font-weight: 700; color: #dc3545; margin: 0.25rem 0; }
.btn-details { background: #DAA520; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
.btn-details:hover { background: #B8860B; transform: translateY(-2px); }
.modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
.modal.active { display: flex; }
.modal-content { background: white; padding: 2rem; border-radius: 16px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; }
.modal-header { font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; }
.form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; }
.btn-submit { background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; width: 100%; font-weight: 600; }
.btn-close { background: #6b7280; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; width: 100%; margin-top: 0.5rem; }
@media (max-width: 1024px) { .layout { grid-template-columns: 1fr; } .sidebar { position: static; } }
@media (max-width: 768px) { .tabs-nav { overflow-x: auto; } .result-card { flex-direction: column; text-align: center; } .result-price { text-align: center; } }
</style>

<div class="booking-hero">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; text-align: center;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 50px; color: white; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem;">
            <div style="width: 1.5rem; height: 1.5rem; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <div style="width: 0.75rem; height: 0.75rem; background: white; border-radius: 50%;"></div>
            </div>
            The Best Booking System
        </div>
        <h1 style="font-size: 3rem; font-weight: 800; color: white; margin-bottom: 1rem;">Discover Amazing Experiences</h1>
        <p style="font-size: 1.125rem; color: rgba(255,255,255,0.9); max-width: 600px; margin: 0 auto;">Find the best flights, hotels, cars, cruises & activities across Africa</p>
    </div>
</div>

<div class="booking-container">
    <div class="booking-card">
        <div class="tabs-header">
            <div class="tabs-nav">
                <button class="tab active" onclick="switchTab('flights')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
                    Flights
                </button>
                <button class="tab" onclick="switchTab('hotels')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Hotels
                </button>
                <button class="tab" onclick="switchTab('cars')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                    Cars
                </button>
                <button class="tab" onclick="switchTab('cruises')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>
                    Cruises
                </button>
                <button class="tab" onclick="switchTab('activities')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    Activities
                </button>
            </div>
            <div class="help-text">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Need help?
            </div>
        </div>

        <div class="tab-content active" id="content-flights">
            <h2 class="form-title">Where are you flying?</h2>
            <form class="form-grid">
                <div class="form-field" style="grid-column: span 2;"><label>From - To</label><input type="text" value="Lahore - Kigali"></div>
                <div class="form-field"><label>Trip</label><select><option>Round trip</option><option>One Way</option></select></div>
                <div class="form-field"><label>Depart</label><input type="date" value="2025-10-28"></div>
                <div class="form-field"><label>Return</label><input type="date" value="2025-11-04"></div>
                <div class="form-field" style="grid-column: span 2;"><label>Passenger - Class</label><select><option>1 Passenger, Business</option></select></div>
                <div class="form-field" style="display: flex; align-items: end;"><button type="button" class="btn-search"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>Show Flights</button></div>
            </form>
        </div>

        <div class="tab-content" id="content-hotels">
            <h2 class="form-title">Find your perfect stay</h2>
            <form class="form-grid">
                <div class="form-field" style="grid-column: span 2;"><label>Destination</label><input type="text" value="Kigali, Rwanda"></div>
                <div class="form-field"><label>Check-in</label><input type="date" value="2025-10-28"></div>
                <div class="form-field"><label>Check-out</label><input type="date" value="2025-11-04"></div>
                <div class="form-field"><label>Guests & Rooms</label><select><option>2 Adults, 1 Room</option></select></div>
                <div class="form-field" style="display: flex; align-items: end;"><button type="button" class="btn-search">Search Hotels</button></div>
            </form>
        </div>

        <div class="tab-content" id="content-cars">
            <h2 class="form-title">Rent a car</h2>
            <form class="form-grid">
                <div class="form-field"><label>Pick-up Location</label><input type="text" value="Kigali Airport"></div>
                <div class="form-field"><label>Pick-up Date</label><input type="date" value="2025-10-28"></div>
                <div class="form-field"><label>Drop-off Location</label><input type="text" value="Kigali Airport"></div>
                <div class="form-field"><label>Drop-off Date</label><input type="date" value="2025-11-04"></div>
                <div class="form-field" style="display: flex; align-items: end;"><button type="button" class="btn-search">Search Cars</button></div>
            </form>
        </div>

        <div class="tab-content" id="content-cruises">
            <h2 class="form-title">Book a cruise</h2>
            <form class="form-grid">
                <div class="form-field" style="grid-column: span 2;"><label>Destination</label><input type="text" value="East African Coast"></div>
                <div class="form-field"><label>Departure Date</label><input type="date" value="2025-10-28"></div>
                <div class="form-field"><label>Duration</label><select><option>7 Days</option></select></div>
                <div class="form-field" style="display: flex; align-items: end;"><button type="button" class="btn-search">Search Cruises</button></div>
            </form>
        </div>

        <div class="tab-content" id="content-activities">
            <h2 class="form-title">Find activities</h2>
            <form class="form-grid">
                <div class="form-field" style="grid-column: span 2;"><label>Destination</label><input type="text" value="Kigali, Rwanda"></div>
                <div class="form-field"><label>Date</label><input type="date" value="2025-10-28"></div>
                <div class="form-field"><label>Activity Type</label><select><option>Safari Tours</option></select></div>
                <div class="form-field" style="display: flex; align-items: end;"><button type="button" class="btn-search">Search Activities</button></div>
            </form>
        </div>
    </div>
</div>

<div class="results-section">
    <div class="layout">
        <aside class="sidebar">
            <h3>Filters</h3>
            <div class="filter-group">
                <h4>Price Range</h4>
                <input type="range" min="50" max="1200" value="600" style="width:100%;">
                <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#6b7280;margin-top:0.5rem;"><span>$50</span><span>$1200</span></div>
            </div>
            <div class="filter-group">
                <h4>Airlines</h4>
                <label><input type="checkbox"> RwandAir</label>
                <label><input type="checkbox"> Emirates</label>
                <label><input type="checkbox"> Qatar Airways</label>
            </div>
            <div class="filter-group">
                <h4>Rating</h4>
                <label><input type="checkbox"> 5★</label>
                <label><input type="checkbox"> 4★ & up</label>
            </div>
        </aside>

        <main>
            <div class="sort-bar">
                <button class="sort-btn active">Cheapest $99, 2h 18m</button>
                <button class="sort-btn">Best $99, 2h 18m</button>
                <button class="sort-btn">Quickest $99, 2h 18m</button>
            </div>

            <div class="result-card">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/RwandAir_Logo.svg/200px-RwandAir_Logo.svg.png" class="result-logo" alt="RwandAir">
                <div class="result-info">
                    <h3 class="result-name">RwandAir</h3>
                    <div class="result-rating">⭐ 4.5 Very Good • 89 reviews</div>
                    <div class="result-details">
                        <span>08:30 AM - 11:25 AM</span>
                        <span>Non-stop</span>
                        <span>2h 55m</span>
                        <span>LHE - KGL</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">starting from</div>
                    <div class="price-value">$245</div>
                    <button class="btn-details" onclick="openBookingModal('flight', 1, 'RwandAir', 245)">Book Now</button>
                </div>
            </div>

            <div class="result-card">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Emirates_logo.svg/200px-Emirates_logo.svg.png" class="result-logo" alt="Emirates">
                <div class="result-info">
                    <h3 class="result-name">Emirates</h3>
                    <div class="result-rating">⭐ 4.7 Excellent • 156 reviews</div>
                    <div class="result-details">
                        <span>10:15 AM - 02:45 PM</span>
                        <span>1 Stop</span>
                        <span>4h 30m</span>
                        <span>LHE - KGL</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">starting from</div>
                    <div class="price-value">$289</div>
                    <button class="btn-details" onclick="openBookingModal('flight', 2, 'Emirates', 289)">Book Now</button>
                </div>
            </div>

            <div class="result-card">
                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/9/9b/Qatar_Airways_Logo.svg/200px-Qatar_Airways_Logo.svg.png" class="result-logo" alt="Qatar">
                <div class="result-info">
                    <h3 class="result-name">Qatar Airways</h3>
                    <div class="result-rating">⭐ 4.6 Excellent • 203 reviews</div>
                    <div class="result-details">
                        <span>06:00 AM - 10:30 AM</span>
                        <span>1 Stop</span>
                        <span>4h 30m</span>
                        <span>LHE - KGL</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">starting from</div>
                    <div class="price-value">$312</div>
                    <button class="btn-details" onclick="openBookingModal('flight', 3, 'Qatar Airways', 312)">Book Now</button>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    event.target.closest('.tab').classList.add('active');
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById('content-' + tab).classList.add('active');
    
    // Update results based on selected tab
    const resultsSection = document.querySelector('.results-section');
    if (tab === 'flights') {
        resultsSection.style.display = 'block';
        updateResults('flights');
    } else if (tab === 'hotels') {
        resultsSection.style.display = 'block';
        updateResults('hotels');
    } else if (tab === 'cars') {
        resultsSection.style.display = 'block';
        updateResults('cars');
    } else if (tab === 'cruises') {
        resultsSection.style.display = 'block';
        updateResults('cruises');
    } else if (tab === 'activities') {
        resultsSection.style.display = 'block';
        updateResults('activities');
    }
}

function updateResults(type) {
    const mainContent = document.querySelector('.results-section main');
    
    if (type === 'hotels') {
        mainContent.innerHTML = `
            <div class="sort-bar">
                <button class="sort-btn active">Lowest Price</button>
                <button class="sort-btn">Highest Rated</button>
                <button class="sort-btn">Most Popular</button>
            </div>
            <div class="result-card">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200" class="result-logo" alt="Hotel">
                <div class="result-info">
                    <h3 class="result-name">Kigali Serena Hotel</h3>
                    <div class="result-rating">⭐ 4.8 Excellent • 342 reviews</div>
                    <div class="result-details">
                        <span>📍 City Center</span>
                        <span>🛏️ Deluxe Room</span>
                        <span>✓ Free WiFi</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">per night</div>
                    <div class="price-value">$180</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>
            <div class="result-card">
                <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=200" class="result-logo" alt="Hotel">
                <div class="result-info">
                    <h3 class="result-name">Radisson Blu Hotel</h3>
                    <div class="result-rating">⭐ 4.6 Very Good • 289 reviews</div>
                    <div class="result-details">
                        <span>📍 Convention Center</span>
                        <span>🛏️ Superior Room</span>
                        <span>✓ Pool & Spa</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">per night</div>
                    <div class="price-value">$165</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>`;
    } else if (type === 'cars') {
        mainContent.innerHTML = `
            <div class="sort-bar">
                <button class="sort-btn active">Lowest Price</button>
                <button class="sort-btn">Highest Rated</button>
                <button class="sort-btn">Vehicle Type</button>
            </div>
            <div class="result-card">
                <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=200" class="result-logo" alt="SUV">
                <div class="result-info">
                    <h3 class="result-name">Toyota Land Cruiser</h3>
                    <div class="result-rating">⭐ 4.7 Excellent • SUV</div>
                    <div class="result-details">
                        <span>👥 7 Seats</span>
                        <span>🧳 4 Bags</span>
                        <span>⚙️ Automatic</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">per day</div>
                    <div class="price-value">$95</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>
            <div class="result-card">
                <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=200" class="result-logo" alt="Sedan">
                <div class="result-info">
                    <h3 class="result-name">Toyota Camry</h3>
                    <div class="result-rating">⭐ 4.5 Very Good • Sedan</div>
                    <div class="result-details">
                        <span>👥 5 Seats</span>
                        <span>🧳 3 Bags</span>
                        <span>⚙️ Automatic</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">per day</div>
                    <div class="price-value">$65</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>`;
    } else if (type === 'cruises') {
        mainContent.innerHTML = `
            <div class="sort-bar">
                <button class="sort-btn active">Best Value</button>
                <button class="sort-btn">Duration</button>
                <button class="sort-btn">Departure Date</button>
            </div>
            <div class="result-card">
                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=200" class="result-logo" alt="Cruise">
                <div class="result-info">
                    <h3 class="result-name">East African Coast Explorer</h3>
                    <div class="result-rating">⭐ 4.9 Exceptional • 7 Days</div>
                    <div class="result-details">
                        <span>🚢 Luxury Cruise</span>
                        <span>📍 5 Ports</span>
                        <span>🍽️ All Inclusive</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">per person</div>
                    <div class="price-value">$1,450</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>`;
    } else if (type === 'activities') {
        mainContent.innerHTML = `
            <div class="sort-bar">
                <button class="sort-btn active">Most Popular</button>
                <button class="sort-btn">Lowest Price</button>
                <button class="sort-btn">Highest Rated</button>
            </div>
            <div class="result-card">
                <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=200" class="result-logo" alt="Safari">
                <div class="result-info">
                    <h3 class="result-name">Akagera Safari Tour</h3>
                    <div class="result-rating">⭐ 4.8 Excellent • 178 reviews</div>
                    <div class="result-details">
                        <span>⏱️ Full Day</span>
                        <span>🦁 Wildlife</span>
                        <span>✓ Guide Included</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">per person</div>
                    <div class="price-value">$120</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>
            <div class="result-card">
                <img src="https://images.unsplash.com/photo-1551918120-9739cb430c6d?w=200" class="result-logo" alt="Gorilla">
                <div class="result-info">
                    <h3 class="result-name">Gorilla Trekking Experience</h3>
                    <div class="result-rating">⭐ 5.0 Exceptional • 412 reviews</div>
                    <div class="result-details">
                        <span>⏱️ Full Day</span>
                        <span>🦍 Volcanoes NP</span>
                        <span>✓ Permit Included</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">per person</div>
                    <div class="price-value">$1,500</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>`;
    } else {
        // Default flights
        mainContent.innerHTML = `
            <div class="sort-bar">
                <button class="sort-btn active">Cheapest $99, 2h 18m</button>
                <button class="sort-btn">Best $99, 2h 18m</button>
                <button class="sort-btn">Quickest $99, 2h 18m</button>
            </div>
            <div class="result-card">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/RwandAir_Logo.svg/200px-RwandAir_Logo.svg.png" class="result-logo" alt="RwandAir">
                <div class="result-info">
                    <h3 class="result-name">RwandAir</h3>
                    <div class="result-rating">⭐ 4.5 Very Good • 89 reviews</div>
                    <div class="result-details">
                        <span>08:30 AM - 11:25 AM</span>
                        <span>Non-stop</span>
                        <span>2h 55m</span>
                        <span>LHE - KGL</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">starting from</div>
                    <div class="price-value">$245</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>
            <div class="result-card">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Emirates_logo.svg/200px-Emirates_logo.svg.png" class="result-logo" alt="Emirates">
                <div class="result-info">
                    <h3 class="result-name">Emirates</h3>
                    <div class="result-rating">⭐ 4.7 Excellent • 156 reviews</div>
                    <div class="result-details">
                        <span>10:15 AM - 02:45 PM</span>
                        <span>1 Stop</span>
                        <span>4h 30m</span>
                        <span>LHE - KGL</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">starting from</div>
                    <div class="price-value">$289</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>
            <div class="result-card">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Emirates_logo.svg/200px-Emirates_logo.svg.png" class="result-logo" alt="Qatar">
                <div class="result-info">
                    <h3 class="result-name">Qatar Airways</h3>
                    <div class="result-rating">⭐ 4.6 Excellent • 203 reviews</div>
                    <div class="result-details">
                        <span>06:00 AM - 10:30 AM</span>
                        <span>1 Stop</span>
                        <span>4h 30m</span>
                        <span>LHE - KGL</span>
                    </div>
                </div>
                <div class="result-price">
                    <div class="price-label">starting from</div>
                    <div class="price-value">$312</div>
                    <button class="btn-details">View Details</button>
                </div>
            </div>`;
    }
}
}

let currentBooking = {};

function openBookingModal(type, itemId, itemName, price) {
    currentBooking = {type, itemId, itemName, price};
    document.getElementById('booking-modal').classList.add('active');
    document.getElementById('modal-title').textContent = `Book ${itemName}`;
    document.getElementById('booking-price').textContent = `$${price}`;
}

function closeBookingModal() {
    document.getElementById('booking-modal').classList.remove('active');
    document.getElementById('booking-form').reset();
}

document.getElementById('booking-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        booking_type: currentBooking.type,
        item_id: currentBooking.itemId,
        customer_name: document.getElementById('customer_name').value,
        customer_email: document.getElementById('customer_email').value,
        customer_phone: document.getElementById('customer_phone').value,
        booking_date: document.getElementById('booking_date').value,
        return_date: document.getElementById('return_date').value,
        passengers: document.getElementById('passengers').value,
        total_price: currentBooking.price * document.getElementById('passengers').value,
        notes: document.getElementById('notes').value
    };
    
    fetch('booking-engine-submit.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(formData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(`Booking successful! Your reference: ${data.booking_reference}`);
            closeBookingModal();
        } else {
            alert('Booking failed: ' + data.message);
        }
    });
});
</script>

<div id="booking-modal" class="modal">
    <div class="modal-content">
        <h2 class="modal-header" id="modal-title">Book Now</h2>
        <form id="booking-form">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" id="customer_name" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" id="customer_email" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="tel" id="customer_phone">
            </div>
            <div class="form-group">
                <label>Booking Date *</label>
                <input type="date" id="booking_date" required>
            </div>
            <div class="form-group">
                <label>Return Date</label>
                <input type="date" id="return_date">
            </div>
            <div class="form-group">
                <label>Number of Passengers *</label>
                <input type="number" id="passengers" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label>Total Price</label>
                <div style="font-size:1.5rem;font-weight:700;color:#dc3545;" id="booking-price">$0</div>
            </div>
            <div class="form-group">
                <label>Special Requests</label>
                <textarea id="notes" rows="3"></textarea>
            </div>
            <button type="submit" class="btn-submit">Confirm Booking</button>
            <button type="button" class="btn-close" onclick="closeBookingModal()">Cancel</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
