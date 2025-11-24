<?php
// CLIENT PORTAL & OWNERSHIP PROTECTION FUNCTIONS

/**
 * Check if client already exists in registry
 * Returns client data if found, false if not
 */
function checkExistingClient($pdo, $email, $phone) {
    $stmt = $pdo->prepare("
        SELECT cr.*, 
               u.first_name, u.last_name, u.email as owner_email, u.phone as owner_phone
        FROM client_registry cr
        LEFT JOIN users u ON cr.owned_by_user_id = u.id
        WHERE cr.client_email = ? OR cr.client_phone = ?
        AND cr.ownership_status = 'active'
        LIMIT 1
    ");
    $stmt->execute([$email, $phone]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Generate unique portal code
 */
function generatePortalCode($clientName) {
    $initials = '';
    $nameParts = explode(' ', trim($clientName));
    foreach ($nameParts as $part) {
        if (!empty($part)) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    }
    $year = date('Y');
    $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    return $initials . '-' . $year . '-' . $random;
}

/**
 * Create new client portal
 */
function createClientPortal($pdo, $data) {
    // Check if client already exists
    $existing = checkExistingClient($pdo, $data['email'], $data['phone']);
    if ($existing) {
        return [
            'success' => false,
            'error' => 'CLIENT_EXISTS',
            'existing_client' => $existing
        ];
    }
    
    // Generate unique portal code
    $portalCode = generatePortalCode($data['name']);
    
    // Ensure uniqueness
    $stmt = $pdo->prepare("SELECT id FROM client_registry WHERE portal_code = ?");
    $stmt->execute([$portalCode]);
    while ($stmt->fetch()) {
        $portalCode = generatePortalCode($data['name']);
        $stmt->execute([$portalCode]);
    }
    
    // Create portal URL with correct path
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $baseDir = str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'])));
    $portalUrl = $protocol . '://' . $host . $baseDir . '/portal.php?code=' . $portalCode;
    
    // Calculate expiry (30 days from now)
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    // Insert into registry
    $stmt = $pdo->prepare("
        INSERT INTO client_registry (
            client_name, client_email, client_phone, client_country, client_interest,
            portal_code, portal_url,
            owned_by_user_id, owned_by_name, owned_by_role,
            source, expires_at, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['country'] ?? null,
        $data['interest'] ?? null,
        $portalCode,
        $portalUrl,
        $data['owner_id'],
        $data['owner_name'],
        $data['owner_role'],
        $data['source'] ?? 'direct',
        $expiresAt,
        $data['created_by']
    ]);
    
    if ($result) {
        return [
            'success' => true,
            'portal_code' => $portalCode,
            'portal_url' => $portalUrl,
            'expires_at' => $expiresAt
        ];
    }
    
    return ['success' => false, 'error' => 'DATABASE_ERROR'];
}

/**
 * Add tours to client portal
 */
function addToursToPortal($pdo, $portalCode, $tourIds) {
    $stmt = $pdo->prepare("
        INSERT INTO portal_tours (portal_code, tour_id, display_order)
        VALUES (?, ?, ?)
    ");
    
    $order = 1;
    foreach ($tourIds as $tourId) {
        $stmt->execute([$portalCode, $tourId, $order++]);
    }
    
    return true;
}

/**
 * Get portal details
 */
function getPortalDetails($pdo, $portalCode) {
    $stmt = $pdo->prepare("
        SELECT cr.*, 
               u.first_name, u.last_name, u.email as owner_email, u.phone as owner_phone
        FROM client_registry cr
        LEFT JOIN users u ON cr.owned_by_user_id = u.id
        WHERE cr.portal_code = ?
    ");
    $stmt->execute([$portalCode]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get tours for portal
 */
function getPortalTours($pdo, $portalCode) {
    $stmt = $pdo->prepare("
        SELECT t.*, pt.custom_price, pt.notes
        FROM portal_tours pt
        JOIN tours t ON pt.tour_id = t.id
        WHERE pt.portal_code = ?
        ORDER BY pt.display_order
    ");
    $stmt->execute([$portalCode]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Log portal activity
 */
function logPortalActivity($pdo, $portalCode, $activityType, $activityData = null) {
    $stmt = $pdo->prepare("
        INSERT INTO portal_activity (portal_code, activity_type, activity_data, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $portalCode,
        $activityType,
        $activityData ? json_encode($activityData) : null,
        $_SERVER['REMOTE_ADDR'] ?? null,
        $_SERVER['HTTP_USER_AGENT'] ?? null
    ]);
    
    // Update last activity
    $stmt = $pdo->prepare("UPDATE client_registry SET last_activity = NOW() WHERE portal_code = ?");
    $stmt->execute([$portalCode]);
}

/**
 * Create ownership alert
 */
function createOwnershipAlert($pdo, $portalCode, $alertType, $message) {
    // Get portal owner
    $portal = getPortalDetails($pdo, $portalCode);
    if (!$portal) return false;
    
    $stmt = $pdo->prepare("
        INSERT INTO ownership_alerts (portal_code, alert_type, advisor_id, alert_message)
        VALUES (?, ?, ?, ?)
    ");
    
    return $stmt->execute([
        $portalCode,
        $alertType,
        $portal['owned_by_user_id'],
        $message
    ]);
}

/**
 * Send portal link via SMS/Email
 */
function sendPortalLink($portalCode, $clientEmail, $clientPhone, $clientName, $ownerName) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $baseDir = str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'])));
    $portalUrl = $protocol . '://' . $host . $baseDir . '/portal.php?code=' . $portalCode;
    
    // Email
    $subject = "Your Personal Travel Portal - Forever Young Tours";
    $message = "
        <h2>Welcome to Forever Young Tours, $clientName!</h2>
        <p>Your personal travel portal is ready:</p>
        <p><strong><a href='$portalUrl'>$portalUrl</a></strong></p>
        <p>Your advisor: <strong>$ownerName</strong></p>
        <p>In your portal you can:</p>
        <ul>
            <li>View tours selected for you</li>
            <li>See prices and itineraries</li>
            <li>Chat with your advisor</li>
            <li>Make bookings</li>
            <li>Track your travel plans</li>
        </ul>
        <p>Best regards,<br>Forever Young Tours Team</p>
    ";
    
    // Send email (implement your email function)
    // sendEmail($clientEmail, $subject, $message);
    
    // SMS
    $smsMessage = "Hello $clientName! Your travel portal: $portalUrl - Your advisor: $ownerName. Track everything here!";
    // sendSMS($clientPhone, $smsMessage);
    
    return true;
}
