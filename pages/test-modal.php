<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Booking Modal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn-primary {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            color: black;
            font-weight: bold;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #c49f2f 0%, #e3c037 100%);
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }
        .btn-secondary:hover {
            background: #cbd5e1;
        }
        .text-gradient {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Test Booking Modal</h1>
        
        <div class="bg-white p-8 rounded-lg shadow-lg mb-6">
            <h2 class="text-xl font-bold mb-4">Sample Tour: Serengeti Safari Adventure</h2>
            <p class="text-gray-600 mb-4">Price: $3,299 per person</p>
            
            <button onclick="openBookingModal(1, 'Serengeti Safari Adventure', 3299, '')" 
                    class="btn-primary px-8 py-4 rounded-lg text-lg">
                <i class="fas fa-calendar-check mr-2"></i>
                Book This Tour
            </button>
        </div>
        
        <div class="bg-white p-8 rounded-lg shadow-lg mb-6">
            <h2 class="text-xl font-bold mb-4">Sample Tour 2: Kilimanjaro Trek</h2>
            <p class="text-gray-600 mb-4">Price: $4,599 per person</p>
            
            <button onclick="openBookingModal(2, 'Kilimanjaro Trek', 4599, '')" 
                    class="btn-primary px-8 py-4 rounded-lg text-lg">
                <i class="fas fa-calendar-check mr-2"></i>
                Book This Tour
            </button>
        </div>
        
        <div class="mt-8 bg-blue-50 border border-blue-200 p-4 rounded-lg">
            <h3 class="font-bold mb-2">Testing Instructions:</h3>
            <ol class="list-decimal list-inside space-y-1 text-sm">
                <li>Click any "Book This Tour" button above</li>
                <li>The booking modal should appear as an overlay</li>
                <li>Fill in the form fields across 4 steps</li>
                <li>Click "Next" to navigate between steps</li>
                <li>On the last step, click "Confirm Booking"</li>
            </ol>
        </div>
        
        <div class="mt-4 bg-green-50 border border-green-200 p-4 rounded-lg">
            <h3 class="font-bold mb-2">Debug Info:</h3>
            <p class="text-sm" id="debugInfo">Checking modal status...</p>
        </div>
    </div>

    <!-- Include the enhanced booking modal -->
    <?php include 'enhanced-booking-modal.php'; ?>

    <script>
        // Debug check
        window.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('bookingModal');
            const funcExists = typeof openBookingModal === 'function';
            
            const debugInfo = document.getElementById('debugInfo');
            debugInfo.innerHTML = `
                <strong>Modal Element:</strong> ${modal ? '✅ Found' : '❌ Not Found'}<br>
                <strong>openBookingModal Function:</strong> ${funcExists ? '✅ Loaded' : '❌ Not Loaded'}<br>
                <strong>Status:</strong> ${modal && funcExists ? '✅ Ready to use' : '❌ There is an issue'}
            `;
            
            console.log('Modal element:', modal);
            console.log('openBookingModal function:', funcExists);
        });
    </script>
</body>
</html>
