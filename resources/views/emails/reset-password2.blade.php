<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Atur Ulang Kata Sandi</h2>
            <p class="text-gray-600 text-sm">Silakan masukkan email dan kata sandi baru Anda.</p>
        </div>

        <form action="{{ url('/api/reset-password') }}" method="POST">
            @csrf

            {{-- <input type="hidden" name="token" value="{{ $token }}"> --}}

            <div class="mb-4">
                {{-- <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Alamat Email
                </label>
                <input type="email" name="email" id="email"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    placeholder="email@contoh.com" required value="{{ old('email') }}">
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror --}}




            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Kata Sandi Baru
                </label>
                <input type="password" name="password" id="password"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 "
                    required>

            </div>
<input type="hidden" name="email" value="{{ $email }}">
<input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                    Konfirmasi Kata Sandi
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                Simpan Kata Sandi
            </button>
        </form>
        <div class="mt-6 text-center">
            <a href="{{ url('/login') }}" class="text-sm text-blue-600 hover:underline">
                Kembali ke halaman login
            </a>
        </div>
    </div>

</body>

</html>
