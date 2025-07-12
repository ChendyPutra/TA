<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pertanian MAPPI</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        // Menggunakan Poppins sebagai font utama
                        sans: ['Poppins', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('https://2.bp.blogspot.com/-YOMXduQjo2g/VPUW-ZEttsI/AAAAAAAABi0/ggC1LSO0Lng/s1600/LOGO+KABUPATEN+MAPPI,+PAPUA.png') }}" type="image/x-icon">

</head>
<body class="bg-gray-900">

    <div class="absolute inset-0 bg-gradient-to-br from-green-900/50 via-gray-900 to-gray-900"></div>

    <div class="relative flex items-center justify-center min-h-screen p-4">

        <div class="w-full max-w-md p-8 space-y-8 bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl ring-1 ring-white/10">
            
            <div class="text-center">
                <img src="{{ asset('https://2.bp.blogspot.com/-YOMXduQjo2g/VPUW-ZEttsI/AAAAAAAABi0/ggC1LSO0Lng/s1600/LOGO+KABUPATEN+MAPPI,+PAPUA.png') }}" alt="Logo Dinas Pertanian" class="w-20 h-20 mx-auto mb-4 rounded-full">
                <h1 class="text-3xl font-bold text-white">
                    Selamat Datang
                </h1>
                <p class="mt-2 text-gray-300">
                    Sistem Informasi Pertanian Kabupaten MAPPI
                </p>
            </div>

            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-gray-800 text-gray-400 rounded-md">Login dengan Akun Anda</span>
                </div>
            </div>

            <div>
                <a href="{{ route('google.redirect') }}" 
                   class="w-full inline-flex items-center justify-center px-5 py-3 text-base font-medium text-gray-800 dark:text-gray-200
                          bg-white dark:bg-white/10 rounded-lg shadow-sm 
                          hover:bg-gray-100 dark:hover:bg-white/20 
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-white
                          transition-all duration-200 transform hover:scale-105">
                    
                    <img class="w-6 h-6 mr-3" src="https://cdn-icons-png.flaticon.com/128/2991/2991148.png" alt="Google Logo">
                    Masuk dengan Google
                </a>
            </div>

            <div class="text-center">
                <a href="{{ route('admin.login') }}" class="text-sm text-gray-400 hover:text-white hover:underline">
                    Login sebagai Admin
                </a>
            </div>

        </div>
    </div>

</body>
</html>