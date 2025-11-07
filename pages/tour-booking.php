<?php
$page_title = "Book Tour - iForYoungTours";
$page_description = "Complete your tour booking";
$css_path = "../assets/css/modern-styles.css";

$tour_id = $_GET['tour_id'] ?? '';
$tour_name = $_GET['tour_name'] ?? '';
$tour_price = $_GET['price'] ?? '';

include './header.php';
?>

<section class="py-20 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-4">Complete Your Booking</h1>
            <p class="text-slate-600">Secure your spot on this amazing African adventure</p>
        </div>

        <?php if ($tour_name): ?>
        <div class="mb-6 p-6 bg-white border-l-4 border-blue-600 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 mb-2"><?= htmlspecialchars($tour_name) ?></h3>
            <?php if ($tour_price): ?>
            <p class="text-2xl font-bold text-blue-600">$<?= htmlspecialchars($tour_price) ?> per person</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form id="tourBookingForm">
                <input type="hidden" name="tour_id" value="<?= htmlspecialchars($tour_id) ?>">
                
                <!-- Customer Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Customer Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Full Name *</label>
                            <input type="text" name="customer_name" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Email *</label>
                            <input type="email" name="customer_email" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Phone *</label>
                            <input type="tel" name="customer_phone" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Emergency Contact</label>
                            <input type="text" name="emergency_contact" placeholder="Name & Phone" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Travel Details -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Travel Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Travel Date *</label>
                            <input type="date" name="travel_date" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Number of Participants *</label>
                            <input type="number" name="participants" min="1" value="1" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Accommodation Type</label>
                            <select name="accommodation_type" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="standard">Standard</option>
                                <option value="premium">Premium (+$100/person)</option>
                                <option value="luxury">Luxury (+$200/person)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Transport Type</label>
                            <select name="transport_type" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="shared">Shared Transport</option>
                                <option value="premium">Premium (+$75/person)</option>
                                <option value="private">Private (+$150/person)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Payment Method</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center p-4 border-2 border-slate-300 rounded-lg cursor-pointer hover:border-blue-500">
                            <input type="radio" name="payment_method" value="credit_card" required class="mr-3">
                            <span class="font-medium">Credit Card</span>
                        </label>
                        <label class="flex items-center p-4 border-2 border-slate-300 rounded-lg cursor-pointer hover:border-blue-500">
                            <input type="radio" name="payment_method" value="paypal" class="mr-3">
                            <span class="font-medium">PayPal</span>
                        </label>
                        <label class="flex items-center p-4 border-2 border-slate-300 rounded-lg cursor-pointer hover:border-blue-500">
                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                            <span class="font-medium">Bank Transfer</span>
                        </label>
                    </div>
                </div>

                <!-- Special Requests -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Special Requests</label>
                    <textarea name="special_requests" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Dietary requirements, accessibility needs, etc."></textarea>
                </div>

                <!-- Price Summary -->
                <div class="bg-blue-50 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Price Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Base Price:</span>
                            <span id="basePrice">$<?= $tour_price ?: '0' ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Participants:</span>
                            <span id="participantsCount">1</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Accommodation:</span>
                            <span id="accommodationPrice">$0</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Transport:</span>
                            <span id="transportPrice">$0</span>
                        </div>
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span id="totalPrice" class="text-blue-600">$<?= $tour_price ?: '0' ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" required class="mt-1 mr-3">
                        <span class="text-sm text-slate-600">I agree to the <a href="#" class="text-blue-600">Terms and Conditions</a> and <a href="#" class="text-blue-600">Cancellation Policy</a></span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                    Complete Booking
                </button>
            </form>
        </div>
    </div>
</section>

<script>
const basePrice = <?= $tour_price ?: 0 ?>;

function updatePrice() {
    const participants = parseInt(document.querySelector('[name="participants"]').value) || 1;
    const accommodation = document.querySelector('[name="accommodation_type"]').value;
    const transport = document.querySelector('[name="transport_type"]').value;
    
    let accommodationPrice = 0;
    if (accommodation === 'premium') accommodationPrice = 100;
    if (accommodation === 'luxury') accommodationPrice = 200;
    
    let transportPrice = 0;
    if (transport === 'premium') transportPrice = 75;
    if (transport === 'private') transportPrice = 150;
    
    const subtotal = (basePrice + accommodationPrice + transportPrice) * participants;
    
    document.getElementById('participantsCount').textContent = participants;
    document.getElementById('accommodationPrice').textContent = '$' + (accommodationPrice * participants);
    document.getElementById('transportPrice').textContent = '$' + (transportPrice * participants);
    document.getElementById('totalPrice').textContent = '$' + subtotal;
}

document.querySelector('[name="participants"]').addEventListener('input', updatePrice);
document.querySelector('[name="accommodation_type"]').addEventListener('change', updatePrice);
document.querySelector('[name="transport_type"]').addEventListener('change', updatePrice);

document.getElementById('tourBookingForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    // Add calculated total
    const totalPrice = document.getElementById('totalPrice').textContent.replace('$', '');
    formData.append('total_price', totalPrice);
    
    try {
        const response = await fetch('process-booking.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Booking successful! Reference: ' + data.booking_reference);
            window.location.href = '../index.php';
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error submitting booking. Please try again.');
    }
});
</script>

<?php include '../includes/footer.php'; ?>
