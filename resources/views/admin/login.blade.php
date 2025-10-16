<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    @vite('resources/css/app.css') <!-- Include TailwindCSS -->
    @vite('resources/ts/login.ts')
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-center mb-6">Admin Login</h1>

        <form id="login-form" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-gray-700 mb-1">Email</label>
                <input type="email" id="email" placeholder="Email" required
                    class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="password" class="block text-gray-700 mb-1">Password</label>
                <input type="password" id="password" placeholder="Password" required
                    class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-200">
                Login
            </button>
        </form>

        <p id="message" class="text-center text-red-500 mt-4"></p>
    </div>
</body>

</html>