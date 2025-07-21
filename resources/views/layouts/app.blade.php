<!DOCTYPE html>
<html lang="en" x-data="layout()" x-init="init()" :class="{ 'dark': isDarkMode }">

<head>
    <meta charset="UTF-8">
    <title>Dasbor Admin - Sistem Pertanian</title>
    <link rel="icon"
        href="{{ asset('https://2.bp.blogspot.com/-YOMXduQjo2g/VPUW-ZEttsI/AAAAAAAABi0/ggC1LSO0Lng/s1600/LOGO+KABUPATEN+MAPPI,+PAPUA.png') }}"
        type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        [x-cloak] {
            display: none !important;
        }

        .transition-spacing {
            transition: margin-left 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside
            class="min-h-screen bg-gradient-to-b from-slate-900 to-slate-800 text-white flex flex-col transition-all duration-300"
            :class="isSidebarOpen ? 'w-64' : 'w-20'">
            <div class="flex items-center h-20 px-6 flex-shrink-0 border-b border-slate-700">
                <img src="{{ asset('https://2.bp.blogspot.com/-YOMXduQjo2g/VPUW-ZEttsI/AAAAAAAABi0/ggC1LSO0Lng/s1600/LOGO+KABUPATEN+MAPPI,+PAPUA.png') }}"
                    alt="Logo" class="h-9 w-9 transition-all" :class="!isSidebarOpen && 'mx-auto'">
                <span x-show="isSidebarOpen" class="ml-3 text-lg font-bold tracking-wider">Pertanian Mappi</span>
            </div>

            <nav class="flex-grow mt-6 space-y-2">
                <template x-for="link in navigation" :key="link.name">
                    <a :href="link.href" class="flex items-center py-2.5 transition-all duration-200 relative group"
                        :class="{
                            'bg-indigo-600 text-white shadow-lg': link.active,
                            'text-slate-300 hover:bg-slate-700 hover:text-white': !link.active,
                            'px-6': isSidebarOpen,
                            'justify-center': !isSidebarOpen
                        }">
                        <i :class="[link.icon, link.active ? 'text-white' : 'text-slate-400 group-hover:text-white', 'text-xl']"></i>
                        <span x-show="isSidebarOpen" class="ml-4 text-sm font-medium" x-text="link.name"></span>

                        <div x-show="link.active" class="absolute left-0 top-0 h-full w-1 bg-white"></div>
                        <div x-show="!isSidebarOpen"
                            class="absolute left-full ml-4 px-3 py-1.5 text-sm font-medium text-white bg-slate-800 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-40">
                            <span x-text="link.name"></span>
                        </div>
                    </a>
                </template>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header
                class="bg-white dark:bg-slate-800 shadow-sm border-b border-slate-200 dark:border-slate-700 z-20">
                <div class="flex items-center justify-between h-20 px-6">
                    <div class="flex items-center space-x-4">
                        <button @click="toggleSidebar()"
                            class="p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 focus:outline-none">
                            <i class="bi bi-list text-2xl"></i>
                        </button>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button @click="toggleDarkMode()"
                            class="p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 focus:outline-none">
                            <i class="bi text-xl" :class="isDarkMode ? 'bi-sun' : 'bi-moon-stars-fill'"></i>
                        </button>

                        <div x-data="{ isOpen: false }" class="relative">
                            <button @click="isOpen = !isOpen" class="flex items-center focus:outline-none">
                                <img src="https://cdn-icons-png.flaticon.com/128/149/149071.png" alt="Avatar"
                                    class="h-10 w-10 rounded-full">
                            </button>
                            <div x-show="isOpen" @click.away="isOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-40 py-2"
                                x-cloak>
                                <div class="px-4 py-2 border-b border-slate-200 dark:border-slate-700">
                                    <p class="text-sm font-semibold">Signed in as</p>
                                    <p class="text-sm font-semibold">Role: {{ Auth::user()->role }}</p>
                                    <p class="text-sm truncate text-slate-600 dark:text-slate-300">
                                        {{ Auth::user()->name }}
                                    </p>
                                </div>
                                <div class="py-1 border-t border-slate-200 dark:border-slate-700">
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left flex items-center px-4 py-2 text-sm text-red-500 hover:bg-slate-100 dark:hover:bg-slate-700">
                                            <i class="bi bi-box-arrow-right mr-3"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function layout() {
            return {
                isSidebarOpen: window.innerWidth >= 1024,
                isDarkMode: localStorage.getItem('darkMode') === 'true' || false,
                navigation: [
                    { name: 'Dashboard', href: '{{ route('admin.dashboard') }}', icon: 'bi bi-grid-1x2-fill', active: {{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }} },
                    { name: 'Data Kecamatan', href: '{{ route('kecamatan.index') }}', icon: 'bi bi-geo-alt-fill', active: {{ request()->routeIs('kecamatan.*') ? 'true' : 'false' }} },
                    { name: 'Data Komoditas', href: '{{ route('wilayah.index') }}', icon: 'bi bi-map-fill', active: {{ request()->routeIs('wilayah.*') ? 'true' : 'false' }} },
                    @if(auth('admin')->check() && auth('admin')->user()->role === 'superadmin')
                        { name: 'Kelola Admin', href: '{{ route('admin.manage.index') }}', icon: 'bi bi-person-lines-fill', active: {{ request()->routeIs('admin.manage.*') ? 'true' : 'false' }} },
                    @endif
                ],
                init() {
                    this.$watch('isDarkMode', val => localStorage.setItem('darkMode', val));
                    window.addEventListener('resize', () => {
                        this.isSidebarOpen = window.innerWidth >= 1024;
                    });
                },
                toggleSidebar() {
                    this.isSidebarOpen = !this.isSidebarOpen;
                },
                toggleDarkMode() {
                    this.isDarkMode = !this.isDarkMode;
                }
            }
        }
    </script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
