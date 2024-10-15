<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Project QR</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .floating-qr {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }

        .qr-background {
            opacity: 0.05;
        }
    </style>
</head>
<body class="bg-gray-50 relative overflow-hidden">
    <!-- QR Code Background Animation -->
    <div class="absolute inset-0 flex justify-center items-center qr-background">
        <svg class="floating-qr w-96 h-96" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="none">
            <rect width="512" height="512" rx="15%" fill="url(#grad1)"/>
            <path d="M50 50h80v80H50zm140 0h80v80h-80zm140 0h80v80h-80zm-280 140h80v80H50zm140 0h80v80h-80zm140 0h80v80h-80zm-280 140h80v80H50zm140 0h80v80h-80zm140 0h80v80h-80zm-280-280h80v80H50zm140 0h80v80h-80zm140 0h80v80h-80z"/>
        </svg>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-lg relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold text-gray-800">Project QR</a>
                </div>
                <div class="flex">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-lg font-medium">Login</a>
                    <a href="{{ route('register') }}" class="ml-4 bg-blue-600 text-white px-4 py-2 rounded-md text-lg font-medium hover:bg-blue-700">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Section -->
    <div class="relative flex items-center justify-center min-h-screen bg-gray-100 py-12">
        <div class="relative z-10 max-w-4xl mx-auto text-center">
            <h1 class="text-5xl font-extrabold text-gray-800 mb-4">Welcome to Project QR</h1>
            <p class="text-xl text-gray-600 mb-8">Scan QR codes to access product details with ease.</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md text-lg font-medium hover:bg-blue-700 shadow-lg">Login</a>
                <a href="{{ route('register') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-md text-lg font-medium hover:bg-gray-300 shadow-lg">Register</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white shadow-lg relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center">
            <p class="text-gray-500">© 2024 Project QR. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
