<?php
/**
 * Database Adapter for Legacy Compatibility
 * This class provides backward compatibility between the old simple schema
 * and the new comprehensive MLM schema
 */

class DatabaseAdapter {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Get tours with legacy field mapping
     */
    public function getTours($status = 'active') {
        $sql = "SELECT 
                    id,
                    name,
                    description,
                    destination as destination,
                    destination_country,
                    price as price,
                    base_price,
                    duration as duration,
                    duration_days,
                    max_participants,
                    status,
                    image_url,
                    cover_image,
                    created_at
                FROM tours 
                WHERE status = :status 
                ORDER BY id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Add tour with automatic field mapping
     */
    public function addTour($data) {
        // Generate slug from name
        $slug = $this->generateSlug($data['name']);
        
        $sql = "INSERT INTO tours (
                    name, slug, description, destination, destination_country, 
                    price, duration, max_participants, image_url, status, created_by
                ) VALUES (
                    :name, :slug, :description, :destination, :destination_country,
                    :price, :duration, :max_participants, :image_url, 'active', 1
                )";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':destination', $data['destination']);
        $stmt->bindParam(':destination_country', $data['destination']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':duration', $data['duration']);
        $stmt->bindParam(':max_participants', $data['max_participants']);
        $stmt->bindParam(':image_url', $data['image_url']);
        
        return $stmt->execute();
    }
    
    /**
     * Get bookings with legacy field mapping
     */
    public function getBookings() {
        $sql = "SELECT 
                    b.id,
                    b.user_id,
                    b.tour_id,
                    b.customer_name,
                    b.customer_email,
                    b.customer_phone,
                    b.travel_date,
                    b.participants,
                    b.total_amount,
                    b.status,
                    b.booking_date,
                    b.notes,
                    t.name as tour_name,
                    COALESCE(u.name, CONCAT(u.first_name, ' ', u.last_name)) as user_name
                FROM bookings b
                LEFT JOIN tours t ON b.tour_id = t.id
                LEFT JOIN users u ON b.user_id = u.id
                ORDER BY b.booking_date DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Add booking with automatic field mapping
     */
    public function addBooking($data) {
        $sql = "INSERT INTO bookings (
                    user_id, tour_id, customer_name, customer_email, 
                    customer_phone, travel_date, participants, total_amount, 
                    status, notes, advisor_id
                ) VALUES (
                    :user_id, :tour_id, :customer_name, :customer_email,
                    :customer_phone, :travel_date, :participants, :total_amount,
                    'pending', :notes, 1
                )";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':tour_id', $data['tour_id']);
        $stmt->bindParam(':customer_name', $data['customer_name']);
        $stmt->bindParam(':customer_email', $data['customer_email']);
        $stmt->bindParam(':customer_phone', $data['customer_phone']);
        $stmt->bindParam(':travel_date', $data['travel_date']);
        $stmt->bindParam(':participants', $data['participants']);
        $stmt->bindParam(':total_amount', $data['total_amount']);
        $stmt->bindParam(':notes', $data['notes']);
        
        return $stmt->execute();
    }
    
    /**
     * Get users with legacy field mapping
     */
    public function getUsers($role = null) {
        $sql = "SELECT 
                    id,
                    email,
                    CASE 
                        WHEN role IN ('admin', 'super_admin') THEN 'admin'
                        ELSE 'user'
                    END as role,
                    CONCAT(first_name, ' ', last_name) as name,
                    first_name,
                    last_name,
                    phone,
                    status,
                    created_at
                FROM users";
        
        if ($role) {
            if ($role === 'admin') {
                $sql .= " WHERE role IN ('admin', 'super_admin')";
            } else {
                $sql .= " WHERE role = 'client'";
            }
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get statistics for dashboard
     */
    public function getStats() {
        $stats = [];
        
        // Total tours
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM tours WHERE status = 'active'");
        $stmt->execute();
        $stats['tours'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total users (clients)
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'client'");
        $stmt->execute();
        $stats['users'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total bookings
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM bookings");
        $stmt->execute();
        $stats['bookings'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total revenue
        $stmt = $this->conn->prepare("SELECT SUM(total_amount) as revenue FROM bookings WHERE status IN ('confirmed', 'paid', 'completed')");
        $stmt->execute();
        $stats['revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;
        
        return $stats;
    }
    
    /**
     * Generate URL-friendly slug
     */
    private function generateSlug($text) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
        return $slug;
    }
    
    /**
     * Update booking status with legacy compatibility
     */
    public function updateBookingStatus($id, $status) {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    /**
     * Delete/Deactivate tour
     */
    public function deleteTour($id) {
        $sql = "UPDATE tours SET status = 'inactive' WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
?>