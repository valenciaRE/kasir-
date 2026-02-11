<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow">

        <h2 class="text-2xl font-bold text-center mb-6">
            Register Akun
        </h2>

        {{-- Error dari server --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-sm mb-1">Username</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border rounded px-3 py-2 focus:ring focus:border-blue-300"
                    required>
                <small class="text-gray-500">Wajib diisi</small>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border rounded px-3 py-2 focus:ring focus:border-blue-300"
                    required>
                <small class="text-gray-500">Contoh: email@gmail.com</small>
            </div>

            {{-- Password --}}
            <div class="mb-4 relative">
                <label class="block text-sm mb-1">Password</label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full border rounded px-3 py-2 pr-10 focus:ring focus:border-blue-300"
                    required
                >

                <!-- ICON MATA -->
                <span
                    class="absolute right-3 top-9 cursor-pointer text-gray-500"
                    onclick="togglePassword('password', this)">
                    👁
                </span>

                <small class="text-gray-500">Minimal 10 karakter</small>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-6 relative">
                <label class="block text-sm mb-1">Konfirmasi Password</label>

                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="w-full border rounded px-3 py-2 pr-10 focus:ring focus:border-blue-300"
                    required
                >

                <!-- ICON MATA -->
                <span
                    class="absolute right-3 top-9 cursor-pointer text-gray-500"
                    onclick="togglePassword('password_confirmation', this)">
                    👁
                </span>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Register
            </button>
        </form>

        <p class="text-center text-sm mt-4">
            Sudah punya akun?
            <a href="{{ route('login.form') }}" class="text-blue-600 hover:underline">
                Login
            </a>
        </p>

    </div>
</div>

{{-- SCRIPT TOGGLE PASSWORD --}}
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);

        if (input.type === "password") {
            input.type = "text";
            icon.textContent = "🙈";
        } else {
            input.type = "password";
            icon.textContent = "👁";
        }
    }
</script>

</body>
</html>