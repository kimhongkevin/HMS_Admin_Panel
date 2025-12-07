<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Hospital Management System</h1>
            <p class="text-gray-600 mb-8">Welcome to your healthcare solution</p>
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-6 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50">
                    Register
                </a>
            </div>
        </div>
    </div>
</body>
</html>