<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'tour_id' => $_POST['tour_id'] ?? null,
            'tour_name' => $_POST['tour_name'] ?? '',
            'client_name' => $_POST['client_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'adults' => $_POST['adults'] ?? 0,
            'children' => $_POST['children'] ?? '',
            'travel_dates' => $_POST['travel_dates'] ?? '',
            'flexible' => $_POST['flexible'] ?? '',
            'budget' => $_POST['budget'] ?? '',
            'categories' => isset($_POST['categories']) ? implode(', ', $_POST['categories']) : '',
            'destinations' => isset($_POST['destinations']) ? implode(', ', $_POST['destinations']) : '',
            'activities' => isset($_POST['activities']) ? implode(', ', $_POST['activities']) : '',
            'group_type' => isset($_POST['group_type']) ? implode(', ', $_POST['group_type']) : '',
            'group_size' => $_POST['group_size'] ?? '',
            'group_leader' => $_POST['group_leader'] ?? '',
            'group_leader_contact' => $_POST['group_leader_contact'] ?? '',
            'departure_city' => $_POST['departure_city'] ?? '',
            'seat_preference' => $_POST['seat_preference'] ?? '',
            'airline' => $_POST['airline'] ?? '',
            'class' => $_POST['class'] ?? '',
            'hotel_prefs' => isset($_POST['hotel_prefs']) ? implode(', ', $_POST['hotel_prefs']) : '',
            'room_type' => isset($_POST['room_type']) ? implode(', ', $_POST['room_type']) : '',
            'services' => isset($_POST['services']) ? implode(', ', $_POST['services']) : '',
            'referral' => isset($_POST['referral']) ? implode(', ', $_POST['referral']) : '',
            'referral_name' => $_POST['referral_name'] ?? '',
            'notes' => $_POST['notes'] ?? ''
        ];

        $stmt = $pdo->prepare("INSERT INTO booking_inquiries (
            tour_id, tour_name, client_name, email, phone, address, adults, children, travel_dates, flexible, budget,
            categories, destinations, activities, group_type, group_size, group_leader, group_leader_contact,
            departure_city, seat_preference, airline, class, hotel_prefs, room_type, services,
            referral, referral_name, notes
        ) VALUES (
            :tour_id, :tour_name, :client_name, :email, :phone, :address, :adults, :children, :travel_dates, :flexible, :budget,
            :categories, :destinations, :activities, :group_type, :group_size, :group_leader, :group_leader_contact,
            :departure_city, :seat_preference, :airline, :class, :hotel_prefs, :room_type, :services,
            :referral, :referral_name, :notes
        )");

        $stmt->execute($data);

        echo json_encode(['success' => true, 'message' => 'Booking submitted successfully']);
    } catch (Exception $e) {
        error_log("Booking Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
