<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Book Button</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Book Button Debug</h1>
        
        <div class="bg-blue-50 p-4 rounded mb-4">
            <p><strong>Session user_id:</strong> <?php echo $_SESSION['user_id'] ?? 'NOT SET'; ?></p>
        </div>

        <button onclick="testClick()" class="bg-golden-500 text-black px-6 py-3 rounded font-bold">
            Test Book Click
        </button>

        <div id="result" class="mt-4 p-4 bg-gray-100 rounded hidden"></div>
    </div>

    <script>
        function testClick() {
            console.log('Button clicked');
            document.getElementById('result').classList.remove('hidden');
            document.getElementById('result').innerHTML = 'Button was clicked! Check console.';
            
            // Test if modal exists
            const modal = document.getElementById('loginModal');
            console.log('Login modal exists:', !!modal);
            
            if (modal) {
                modal.classList.remove('hidden');
                console.log('Modal should be visible now');
            }
        }
    </script>
</body>
</html>
