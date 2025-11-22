<?php
/**
 * Check Tour Isolation by Country
 * Verifies that tours are properly linked to countries
 */

require_once 'config/database.php';

echo "=" . str_repeat("=", 100) . "\n";
echo "TOUR ISOLATION CHECK - Country-Specific Tours Analysis\n";
echo "=" . str_repeat("=", 100) . "\n\n";

// 1. Get tours by country
echo "1. TOURS BY COUNTRY (First 20 tours)\n";
echo str_repeat("-", 100) . "\n";

$stmt = $pdo->query("
    SELECT t.id, t.name, t.country_id, c.name as country_name, t.status 
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    ORDER BY c.name, t.name 
    LIMIT 20
");
$tours = $stmt->fetchAll();

if (empty($tours)) {
    echo "   No tours found in database.\n";
} else {
    foreach ($tours as $tour) {
        printf(
            "   ID: %-4d | Country: %-25s | Status: %-8s | Tour: %s\n", 
            $tour['id'], 
            $tour['country_name'] ?? 'NO COUNTRY ASSIGNED', 
            $tour['status'],
            $tour['name']
        );
    }
}

echo "\n";

// 2. Count tours per country
echo "2. TOUR COUNT BY COUNTRY\n";
echo str_repeat("-", 100) . "\n";

$stmt = $pdo->query("
    SELECT 
        c.name as country, 
        c.slug,
        COUNT(t.id) as total_tours,
        SUM(CASE WHEN t.status = 'active' THEN 1 ELSE 0 END) as active_tours,
        SUM(CASE WHEN t.featured = 1 THEN 1 ELSE 0 END) as featured_tours
    FROM countries c 
    LEFT JOIN tours t ON c.id = t.country_id 
    WHERE c.status = 'active'
    GROUP BY c.id, c.name, c.slug
    ORDER BY total_tours DESC, c.name
");
$counts = $stmt->fetchAll();

foreach ($counts as $row) {
    printf(
        "   %-30s (%-15s) : %2d total | %2d active | %2d featured\n", 
        $row['country'],
        $row['slug'],
        $row['total_tours'],
        $row['active_tours'],
        $row['featured_tours']
    );
}

echo "\n";

// 3. Check for tours without country assignment
echo "3. TOURS WITHOUT COUNTRY ASSIGNMENT\n";
echo str_repeat("-", 100) . "\n";

$stmt = $pdo->query("
    SELECT id, name, status 
    FROM tours 
    WHERE country_id IS NULL 
    ORDER BY name
");
$orphan_tours = $stmt->fetchAll();

if (empty($orphan_tours)) {
    echo "   ✅ All tours are properly assigned to countries!\n";
} else {
    echo "   ⚠️  WARNING: Found " . count($orphan_tours) . " tours without country assignment:\n";
    foreach ($orphan_tours as $tour) {
        printf("      ID: %-4d | Status: %-8s | Tour: %s\n", $tour['id'], $tour['status'], $tour['name']);
    }
}

echo "\n";

// 4. Test Rwanda-specific query
echo "4. RWANDA-SPECIFIC TOURS (What visitors see on visit-rw subdomain)\n";
echo str_repeat("-", 100) . "\n";

$stmt = $pdo->query("
    SELECT c.id, c.name 
    FROM countries c 
    WHERE c.slug = 'visit-rw' AND c.status = 'active'
");
$rwanda = $stmt->fetch();

if ($rwanda) {
    $stmt = $pdo->prepare("
        SELECT id, name, price, duration_days, featured, status 
        FROM tours 
        WHERE country_id = ? AND status = 'active' 
        ORDER BY featured DESC, name
    ");
    $stmt->execute([$rwanda['id']]);
    $rwanda_tours = $stmt->fetchAll();
    
    echo "   Country: " . $rwanda['name'] . " (ID: " . $rwanda['id'] . ")\n";
    echo "   Active Tours: " . count($rwanda_tours) . "\n\n";
    
    if (empty($rwanda_tours)) {
        echo "   No active tours found for Rwanda.\n";
    } else {
        foreach ($rwanda_tours as $tour) {
            printf(
                "   %-50s | $%-8s | %d days | %s\n",
                $tour['name'],
                number_format($tour['price'], 2),
                $tour['duration_days'],
                $tour['featured'] ? '⭐ Featured' : ''
            );
        }
    }
} else {
    echo "   Rwanda not found in database.\n";
}

echo "\n";

// 5. Test Kenya-specific query
echo "5. KENYA-SPECIFIC TOURS (What visitors see on visit-ke subdomain)\n";
echo str_repeat("-", 100) . "\n";

$stmt = $pdo->query("
    SELECT c.id, c.name 
    FROM countries c 
    WHERE c.slug = 'visit-ke' AND c.status = 'active'
");
$kenya = $stmt->fetch();

if ($kenya) {
    $stmt = $pdo->prepare("
        SELECT id, name, price, duration_days, featured, status 
        FROM tours 
        WHERE country_id = ? AND status = 'active' 
        ORDER BY featured DESC, name
    ");
    $stmt->execute([$kenya['id']]);
    $kenya_tours = $stmt->fetchAll();
    
    echo "   Country: " . $kenya['name'] . " (ID: " . $kenya['id'] . ")\n";
    echo "   Active Tours: " . count($kenya_tours) . "\n\n";
    
    if (empty($kenya_tours)) {
        echo "   No active tours found for Kenya.\n";
    } else {
        foreach ($kenya_tours as $tour) {
            printf(
                "   %-50s | $%-8s | %d days | %s\n",
                $tour['name'],
                number_format($tour['price'], 2),
                $tour['duration_days'],
                $tour['featured'] ? '⭐ Featured' : ''
            );
        }
    }
} else {
    echo "   Kenya not found in database.\n";
}

echo "\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "SUMMARY\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "✅ Tour isolation is working correctly if:\n";
echo "   1. All tours have a country_id assigned\n";
echo "   2. Each country shows different tours\n";
echo "   3. Rwanda tours != Kenya tours\n";
echo "   4. No orphan tours (tours without country)\n";
echo "=" . str_repeat("=", 100) . "\n";
?>

