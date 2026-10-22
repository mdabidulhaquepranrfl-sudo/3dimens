<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - ShopAdmin</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#f0f4ff', 100: '#dce6fe', 500: '#4f72d9', 600: '#3b5dc8', 700: '#2e4aad' },
                        surface: '#f8f9fc',
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .btn-primary { background: #4f72d9; color: #fff; }
        .btn-primary:hover { background: #3b5dc8; }
        input:focus {
            border-color: #4f72d9 !important;
            box-shadow: 0 0 0 3px rgba(79, 114, 217, 0.15) !important;
            outline: none;
        }
    </style>
</head>
<body class="bg-surface min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-brand-500 shadow-lg shadow-brand-500/30 mb-3 text-white">
                <i class="fa-solid fa-store text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">ShopAdmin</h1>
            <p class="text-sm text-gray-500 mt-1">Sign in to your admin account</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50 p-6 sm:p-8">
            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-xs font-medium">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span class="font-semibold">Authentication failed</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email', 'admin@shop.com') }}" required autofocus
                            class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 transition-all"
                            placeholder="admin@shop.com" />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                            class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 transition-all"
                            placeholder="••••••••" />
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        <span>Remember me</span>
                    </label>
                    <span class="text-gray-400">Restricted access</span>
                </div>

                <button type="submit"
                    class="w-full btn-primary font-semibold py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 shadow-md shadow-brand-500/20">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Sign In to Admin</span>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400">Default Demo: <span class="font-medium text-gray-600">admin@shop.com</span> / <span class="font-medium text-gray-600">password</span></p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} ShopAdmin. All rights reserved.</p>
    </div>

</body>
</html>
