<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskify - Simplify Your Productivity</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Page Wrapper -->
    <div class="min-h-screen flex flex-col">

        <!-- Header -->
        <header class="bg-white shadow h-20 flex items-center">
            <div class="max-w-7xl mx-auto w-full px-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-600">Taskify</h1>
                <div class="flex items-center space-x-4">
                    @auth
                    <a href="{{ route('tasks.index') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">View My Tasks</a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline font-medium">Logout</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Login</a>
                    <a href="{{ route('register') }}"
                        class="ml-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Get Started</a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero -->
        <section class="bg-blue-600 text-white flex-grow flex items-center justify-center py-16">
            <div class="max-w-3xl text-center px-6">
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">Organize. Prioritize. Achieve.</h2>
                <p class="text-lg md:text-xl mb-8">Taskify helps you stay on top of your daily tasks with ease. Plan,
                    track, and complete your work with clarity.</p>
                <a href="{{ route('tasks.index') }}"
                    class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">Start
                    Managing Tasks</a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white py-6 border-t">
            <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-600 mb-2 md:mb-0">&copy; {{ date('Y') }} Taskify. All rights reserved.</p>
                <div class="space-x-4 text-sm">
                    <a href="#" class="text-gray-600 hover:text-blue-600">Privacy</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Terms</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Support</a>
                </div>
            </div>
        </footer>
    </div>

</body>

</html>