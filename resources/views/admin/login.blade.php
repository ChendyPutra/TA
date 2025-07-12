<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Dinas Pertanian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Menggunakan font Poppins sebagai font utama */
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-200">
    
    <div class="flex items-center justify-center min-h-screen bg-green-900/10 p-4" 
         style="background-image: url('https://www.transparenttextures.com/patterns/dot-grid.png');">

        <div class="w-full max-w-4xl flex rounded-2xl shadow-2xl overflow-hidden">
            
            <div class="hidden md:flex md:w-1/2 flex-col items-center justify-center p-12 text-center text-white bg-cover" 
                 style="background-image: url('https://i.pinimg.com/736x/43/e8/b6/43e8b6922decd20adc133a8136b10276.jpg');">
                <div class="bg-black/50 p-8 rounded-xl">
                    <h1 class="text-4xl font-bold mb-4">Selamat Datang Kembali</h1>
                    <p class="text-gray-200">Sistem Administrasi Dinas Pertanian Kabupaten MAPPI</p>
                </div>
            </div>

            <div class="w-full md:w-1/2 p-8 sm:p-12 bg-gray-100/80 backdrop-blur-lg">
                
                <div class="text-center mb-8">
                    <img src="https://2.bp.blogspot.com/-YOMXduQjo2g/VPUW-ZEttsI/AAAAAAAABi0/ggC1LSO0Lng/s1600/LOGO+KABUPATEN+MAPPI,+PAPUA.png" alt="Logo Dinas" class="mx-auto h-20 w-20 rounded-full shadow-md">
                    <h2 class="mt-6 text-2xl sm:text-3xl font-bold text-gray-900">
                        Login Admin
                    </h2>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                        <p class="font-bold">Gagal Login</p>
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required autofocus
                                   class="w-full px-4 py-3 rounded-lg bg-white border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="w-full px-4 py-3 rounded-lg bg-white border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-transform duration-150 hover:scale-105">
                            Login
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</body>
</html>