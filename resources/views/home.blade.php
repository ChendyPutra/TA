<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <title>Pertanian Kabupaten Mappi</title>
    <link rel="icon" href="{{ asset('https://2.bp.blogspot.com/-YOMXduQjo2g/VPUW-ZEttsI/AAAAAAAABi0/ggC1LSO0Lng/s1600/LOGO+KABUPATEN+MAPPI,+PAPUA.png') }}" type="image/x-icon">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        // PENINGKATAN: Palet warna diperbarui untuk tampilan lebih segar
                        'agri-green': {
                            light: '#F0FDF4',
                            DEFAULT: '#22C55E',
                            dark: '#16A34A'
                        },
                        'agri-dark': {
                            DEFAULT: '#1E293B',
                            light: '#334155'
                        }
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        .mobile-menu, .faq-content { display: none; }
        /* Style untuk popup Leaflet tetap dipertahankan karena bergantung pada JS */
        .popup-card-animated { font-family: 'Poppins', sans-serif; background: white; padding: 1rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 260px; border-left: 5px solid #22C55E; }
        .popup-header { font-size: 1.1rem; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; }
        .popup-body p { margin: 0.5rem 0; font-size: 0.875rem; color: #475569; }
        .leaflet-popup-content-wrapper { background: transparent; box-shadow: none; padding: 0; }
        .leaflet-popup-tip { background: white; }
    </style>
</head>
<body class="bg-white text-agri-dark antialiased font-sans">

    <header class="bg-white/80 backdrop-blur-sm sticky top-0 z-50 shadow-sm">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="flex items-center space-x-3">
                <img src="https://2.bp.blogspot.com/-YOMXduQjo2g/VPUW-ZEttsI/AAAAAAAABi0/ggC1LSO0Lng/s1600/LOGO+KABUPATEN+MAPPI,+PAPUA.png" alt="Logo Kabupaten Mappi" class="h-9 w-9">
                <span class="text-lg font-semibold text-agri-dark tracking-wide" >Komoditas Pertanian Mappi</span>
            </a>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-slate-600 hover:text-agri-green-dark transition-colors duration-300">Beranda</a>
                <a href="#insights" class="text-slate-600 hover:text-agri-green-dark transition-colors duration-300">Wawasan Data</a>
                <a href="#map-section" class="text-slate-600 hover:text-agri-green-dark transition-colors duration-300">Peta Digital</a>
            </div>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-agri-dark focus:outline-none">
                    <i class="bi bi-list text-3xl"></i>
                </button>
            </div>
        </nav>
        <div id="mobile-menu" class="mobile-menu md:hidden bg-white border-t border-gray-200">
            <div class="px-6 pt-2 pb-4 space-y-2">
                <a href="#home" class="nav-link-mobile block text-slate-600 hover:bg-agri-green-light rounded-md py-2 px-3">Beranda</a>
                <a href="#insights" class="nav-link-mobile block text-slate-600 hover:bg-agri-green-light rounded-md py-2 px-3">Wawasan Data</a>
                <a href="#map-section" class="nav-link-mobile block text-slate-600 hover:bg-agri-green-light rounded-md py-2 px-3">Peta Digital</a>
            </div>
        </div>
    </header>

    <main>
        <section id="home" class="relative bg-cover bg-center min-h-[90vh] flex items-center justify-center" style="background-image: url('https://images.unsplash.com/photo-1560493676-04071c5f467b?q=80&w=1974&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-gradient-to-t from-agri-dark/80 via-agri-dark/40 to-transparent"></div>
            <div class="container mx-auto px-6 text-center relative z-10 -mt-16">
                <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-4 animate__animated animate__fadeInDown">
                    <span class="text-agri-green">Data Cerdas</span> untuk Pertanian Mappi
                </h1>
                <p class="text-lg md:text-xl text-slate-200 max-w-3xl mx-auto mb-8 animate__animated animate__fadeInUp animate__delay-1s">
                    Jelajahi data dan analisis real-time untuk memberdayakan petani, mengoptimalkan sumber daya, dan membangun ketahanan pangan di Kabupaten Mappi.
                </p>
                <a href="#map-section" class="bg-agri-green hover:bg-agri-green-dark text-white font-bold py-4 px-8 rounded-full text-lg transition-all duration-300 inline-flex items-center space-x-2 shadow-lg hover:shadow-xl hover:scale-105 transform animate__animated animate__pulse animate__delay-2s">
                    <i class="bi bi-map-fill"></i>
                    <span>Jelajahi Peta Interaktif</span>
                </a>
            </div>
        </section>

        <section id="insights" class="py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-agri-dark">Wawasan Data Pertanian</h2>
                    <p class="text-slate-500 mt-2 text-lg">Potensi pertanian Kabupaten Mappi dalam angka.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl p-8 flex items-center space-x-6 transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                        <div class="flex-shrink-0 p-5 rounded-2xl bg-gradient-to-br from-sky-100 to-sky-200 text-sky-600">
                            <i class="bi bi-signpost-split-fill text-4xl"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-base font-medium">Total Kecamatan</p>
                            <p class="text-4xl font-bold text-agri-dark">{{ $groupedData->count() }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl p-8 flex items-center space-x-6 transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                        <div class="flex-shrink-0 p-5 rounded-2xl bg-gradient-to-br from-emerald-100 to-emerald-200 text-emerald-600">
                            <i class="bi bi-flower1 text-4xl"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-base font-medium">Jenis Komoditas</p>
                            <p class="text-4xl font-bold text-agri-dark">{{ $groupedData->flatten(1)->pluck('nama_komoditas')->unique()->count() }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl p-8 flex items-center space-x-6 transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                        <div class="flex-shrink-0 p-5 rounded-2xl bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600">
                            <i class="bi bi-bounding-box-circles text-4xl"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-base font-medium">Total Luas Wilayah</p>
                            <p class="text-4xl font-bold text-agri-dark">{{ number_format($groupedData->flatten(1)->sum('total_luas'), 0) }} <span class="text-2xl text-slate-400">Ha</span></p>
                        </div>
                    </div>
                </div>

                <div id="map-section" class="pt-16">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold text-agri-dark">Peta Digital Pertanian</h2>
                        <p class="text-slate-500 mt-2 text-lg">Visualisasi geospasial sebaran komoditas di Kabupaten Mappi.</p>
                    </div>
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border border-gray-100">
                        <div class="flex flex-col md:flex-row gap-4 mb-6">
                            <select id="filterKomoditasSelect" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-agri-green focus:border-agri-green p-3 transition">
                                <option value="">-- Semua Komoditas --</option>
                                @php $komoditasList = $wilayah->pluck('nama_komoditas')->unique()->sort(); @endphp
                                @foreach($komoditasList as $komoditas)
                                    <option value="{{ $komoditas }}">{{ $komoditas }}</option>
                                @endforeach
                            </select>
                            <select id="filterKecamatanSelect" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-agri-green focus:border-agri-green p-3 transition">
                                <option value="">-- Semua Kecamatan --</option>
                                @foreach($polygon_kecamatan as $kecamatan)
                                    <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="map" class="h-[550px] w-full rounded-lg z-10 border border-gray-200"></div>
                    </div>
                </div>
            </div>
        </section>

        <section id="analysis" class="py-24 bg-agri-green-light">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                 <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-agri-dark">Analisis Data Terperinci</h2>
                    <p class="text-slate-500 mt-2 text-lg">Lihat lebih dalam distribusi komoditas dan data berdasarkan waktu.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mb-12">
                    <div class="lg:col-span-3 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-agri-dark mb-1 text-center">Distribusi Komoditas Global</h3>
                        <p class="text-center text-gray-500 text-sm mb-4">di Seluruh Kabupaten Mappi</p>
                        <div class="h-96 w-full">
                            <canvas id="globalCommodityChart"></canvas>
                        </div>
                    </div>

                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-8 flex flex-col justify-center border border-gray-100">
                        <h3 class="font-bold text-xl text-agri-dark mb-5">Filter Berdasarkan Waktu</h3>
                        <form action="{{ route('home') }}" method="GET" class="space-y-4">
                            <div>
                                <label for="year" class="block mb-2 text-sm font-medium text-gray-900">Tahun:</label>
                                <select name="year" id="year" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-agri-green focus:border-agri-green p-3">
                                    <option value="">Semua Tahun</option>
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="month" class="block mb-2 text-sm font-medium text-gray-900">Bulan:</label>
                                <select name="month" id="month" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-agri-green focus:border-agri-green p-3">
                                    <option value="">Semua Bulan</option>
                                    @foreach($availableMonths as $monthNum => $monthName)
                                        <option value="{{ $monthNum }}" {{ $selectedMonth == $monthNum ? 'selected' : '' }}>{{ $monthName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pt-2">
                                <button type="submit" class="w-full bg-agri-green text-white font-bold py-3 px-4 rounded-lg hover:bg-agri-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-agri-green transition-all duration-300 flex items-center justify-center">
                                    <i class="bi bi-funnel-fill mr-2"></i> Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-24 mb-12">
                    <h3 class="text-3xl font-bold text-agri-dark">Rincian per Kecamatan</h3>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @forelse($groupedData as $kecamatan => $komoditas)
                        <div class="kecamatan-card bg-white rounded-2xl shadow-lg overflow-hidden h-full flex flex-col border border-gray-100 animate__animated animate__fadeInUp">
                            <div class="p-6">
                                <h5 class="text-xl font-bold text-center text-agri-dark-light mb-5">{{ $kecamatan }}</h5>
                                
                                <div class="mb-5">
                                    <ul class="flex justify-center bg-gray-100 p-1.5 rounded-lg" id="kecamatanTab-{{ Str::slug($kecamatan) }}" role="tablist">
                                        <li class="w-1/2" role="presentation">
                                            <button class="w-full text-center p-2 rounded-md transition-colors duration-300" id="table-tab-{{ Str::slug($kecamatan) }}" data-bs-toggle="tab" data-bs-target="#table-{{ Str::slug($kecamatan) }}" type="button" role="tab" aria-controls="table-{{ Str::slug($kecamatan) }}" aria-selected="true">
                                                <i class="bi bi-table mr-1"></i> Data Tabel
                                            </button>
                                        </li>
                                        <li class="w-1/2" role="presentation">
                                            <button class="w-full text-center p-2 rounded-md transition-colors duration-300" id="chart-tab-{{ Str::slug($kecamatan) }}" data-bs-toggle="tab" data-bs-target="#chart-{{ Str::slug($kecamatan) }}-content" type="button" role="tab" aria-controls="chart-{{ Str::slug($kecamatan) }}-content" aria-selected="false">
                                                <i class="bi bi-bar-chart-fill mr-1"></i> Grafik
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="tab-content px-6 pb-6 flex-grow" id="kecamatanTabContent-{{ Str::slug($kecamatan) }}">
                                <div class="tab-pane fade" id="table-{{ Str::slug($kecamatan) }}" role="tabpanel" aria-labelledby="table-tab-{{ Str::slug($kecamatan) }}">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-left text-slate-500">
                                            <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 rounded-l-lg">No</th>
                                                    <th scope="col" class="px-6 py-3">Komoditas</th>
                                                    <th scope="col" class="px-6 py-3">Warna</th>
                                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                                    <th scope="col" class="px-6 py-3 rounded-r-lg text-right">Luas (Ha)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach($komoditas as $i => $w)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $i + 1 }}</td>
                                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $w->nama_komoditas }}</td>
                                                        <td class="px-6 py-4">
                                                            <span class="px-3 py-1 text-xs font-semibold rounded-full text-white shadow" style="background-color: {{ $w->warna }};">
                                                                {{ $w->warna }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4">{{ number_format($w->jumlah_komoditas) }}</td>
                                                        <td class="px-6 py-4 text-right">{{ number_format($w->total_luas, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="chart-{{ Str::slug($kecamatan) }}-content" role="tabpanel" aria-labelledby="chart-tab-{{ Str::slug($kecamatan) }}">
                                    <div class="h-96">
                                        <canvas id="chart-{{ Str::slug($kecamatan) }}"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="lg:col-span-2 bg-sky-100 border-l-4 border-sky-500 text-sky-800 p-6 rounded-r-lg shadow-md text-center">
                            <i class="bi bi-info-circle text-2xl mr-3"></i>
                            <p class="font-semibold">Tidak ada data komoditas yang ditemukan untuk filter yang Anda pilih.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

       
    </main>

    <footer class="bg-agri-dark text-slate-300">
        <div class="container mx-auto px-6 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <h4 class="text-xl font-semibold text-white mb-4">Portal Data Pertanian Mappi</h4>
                    <p class="text-sm text-slate-400 max-w-md">Memberdayakan masa depan pertanian Kabupaten Mappi melalui transparansi dan solusi data cerdas untuk kesejahteraan bersama.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Tautan Cepat</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#home" class="hover:text-agri-green transition-colors">Beranda</a></li>
                        <li><a href="#insights" class="hover:text-agri-green transition-colors">Wawasan Data</a></li>
                        <li><a href="#map-section" class="hover:text-agri-green transition-colors">Peta Digital</a></li>
                        <li><a href="#faq" class="hover:text-agri-green transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Hubungi Kami</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li class="flex items-start"><i class="bi bi-geo-alt-fill mr-3 mt-1 text-agri-green"></i><span>Jl. Poros, Kepi, Kabupaten Mappi, Papua Selatan</span></li>
                        <li class="flex items-center"><i class="bi bi-envelope-fill mr-3 text-agri-green"></i><span>kontak@pertanianmappi.go.id</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="bg-black/20 py-4">
            <div class="container mx-auto px-6 text-center text-sm text-slate-400">
                &copy; {{ date('Y') }} Dinas Pertanian Kabupaten Mappi. All Rights Reserved.
            </div>
        </div>
    </footer>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    
    {{-- Script untuk Tab (agar tab Bootstrap tetap berfungsi dengan style Tailwind) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Skrip untuk mengaktifkan fungsionalitas tab Bootstrap
            var triggerTabList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tab"]'));
            triggerTabList.forEach(function (triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);

                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
            
            // Atur tab aktif pertama secara manual untuk setiap kartu
            document.querySelectorAll('.kecamatan-card').forEach(card => {
                const firstTab = card.querySelector('[data-bs-toggle="tab"]');
                const firstPane = card.querySelector('.tab-pane');
                if (firstTab && firstPane) {
                    firstTab.classList.add('active', 'border-indigo-500', 'text-indigo-600');
                    firstTab.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                    firstPane.classList.add('show', 'active');
                }
            });

            // Styling untuk tab
            document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
                tab.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                
                tab.addEventListener('show.bs.tab', function (e) {
                    // Hapus kelas aktif dari semua tab dalam grup yang sama
                    const tabGroup = e.target.closest('ul').querySelectorAll('button');
                    tabGroup.forEach(t => {
                        t.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                        t.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                    });
                    
                    // Tambahkan kelas aktif ke tab yang diklik
                    e.target.classList.add('active', 'border-indigo-500', 'text-indigo-600');
                    e.target.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                });
            });
        });
    </script>
    
    {{-- Script utama untuk Chart dan Leaflet (TIDAK DIUBAH) --}}
    <script>
        // Kode JavaScript Anda yang ada untuk Chart.js dan Leaflet diletakkan di sini.
        // Tidak ada yang perlu diubah dari fungsionalitasnya.
        Chart.register(ChartDataLabels); // Register datalabels plugin globally 

        // Data chart yang sudah diformat dari controller 
        var chartData = @json($chartData);
        var selectedYear = {{ $selectedYear ?? 'null' }};
        var selectedMonth = {{ $selectedMonth ?? 'null' }};
        var availableMonths = @json($availableMonths);

        // --- Global Chart --- 
        var globalCommodityChartCtx = document.getElementById('globalCommodityChart').getContext('2d');
        var allKomoditasData = [];
        var allKomoditasColors = [];
        var allKomoditasLabels = [];

        // Aggregate data for the global chart 
        @php
            $globalKomoditas = collect();
            foreach ($groupedData as $kecamatan => $komoditas) {
                foreach ($komoditas as $w) {
                    $globalKomoditas->push((object) ['nama_komoditas' => $w->nama_komoditas, 'total_luas' => $w->total_luas, 'warna' => $w->warna]);
                }
            }
            $aggregatedGlobalKomoditas = $globalKomoditas->groupBy('nama_komoditas')->map(function ($item) {
                return (object) [
                    'total_luas' => $item->sum('total_luas'),
                    'warna' => $item->first()->warna // Ambil warna pertama untuk komoditas tersebut 
                ];
            });
        @endphp

        @foreach($aggregatedGlobalKomoditas as $nama_komoditas => $data)
            allKomoditasLabels.push("{{ $nama_komoditas }}");
            allKomoditasData.push({{ $data->total_luas }});
            allKomoditasColors.push("{{ $data->warna }}");
        @endforeach

        new Chart(globalCommodityChartCtx, {
            type: 'doughnut', // Atau 'pie' 
            data: {
                labels: allKomoditasLabels,
                datasets: [{
                    label: 'Total Luas (Ha)',
                    data: allKomoditasData,
                    backgroundColor: allKomoditasColors,
                    borderColor: '#ffffff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false,
                    },
                    legend: {
                        position: 'right', // Posisi legenda di kanan 
                        labels: {
                            boxWidth: 20,
                            padding: 15,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(2);
                                    label += new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(context.parsed) + ' Ha (' + percentage + '%)';
                                }
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff',
                        textAlign: 'center',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: (value, ctx) => {
                            const total = ctx.dataset.data.reduce((sum, val) => sum + val, 0);
                            const percentage = ((value / total) * 100);
                            if (percentage < 5) { // Sembunyikan label jika persentase terlalu kecil
                                return null;
                            }
                            return percentage.toFixed(1) + '%';
                        },
                        display: function (context) {
                            return context.dataset.data[context.dataIndex] > 0; // Hanya tampilkan jika data > 0 
                        }
                    }
                }
            }
        });

        // --- Charts Per Kecamatan --- 
        @foreach($groupedData as $kecamatan => $komoditas)
            var ctx = document.getElementById('chart-{{ Str::slug($kecamatan) }}').getContext('2d');
            var dataForChart = chartData['{{ $kecamatan }}'];

            if (dataForChart) {
                let titleText = 'Distribusi Komoditas di Kecamatan {{ $kecamatan }} (Ha)';
                if (selectedMonth && selectedYear) {
                    titleText += ` - ${availableMonths[selectedMonth]} ${selectedYear}`;
                } else if (selectedYear) {
                    titleText += ` - Tahun ${selectedYear}`;
                } else if (selectedMonth) {
                    titleText += ` - Bulan ${availableMonths[selectedMonth]}`;
                } else {
                    titleText += ' (Semua Waktu)';
                }

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: dataForChart.labels,
                        datasets: [{
                            label: 'Luas Wilayah (Ha)',
                            data: dataForChart.data,
                            backgroundColor: dataForChart.backgroundColor,
                            borderColor: dataForChart.borderColor,
                            borderWidth: 1,
                            borderRadius: 5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y', // Membuat bar chart horizontal 
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Luas Wilayah (Ha)',
                                    font: { size: 12, weight: 'bold' }
                                },
                                ticks: {
                                    callback: function (value) {
                                        return new Intl.NumberFormat('id-ID').format(value) + ' Ha';
                                    }
                                },
                                grid: { display: true, color: '#e9ecef' }
                            },
                            y: {
                                grid: { display: false }
                            }
                        },
                        plugins: {
                            title: { display: false }, // Judul sudah ada di atas
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 12 },
                                padding: 10,
                                displayColors: true,
                                callbacks: {
                                    label: function (context) {
                                        let label = context.dataset.label || '';
                                        if (label) { label += ': '; }
                                        if (context.parsed.x !== null) {
                                            label += new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(context.parsed.x) + ' Ha';
                                        }
                                        return label;
                                    }
                                }
                            },
                            datalabels: {
                                anchor: 'end',
                                align: 'end',
                                color: '#444',
                                font: { weight: 'bold', size: 10 },
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 1 }).format(value);
                                },
                                display: function (context) {
                                    return context.dataset.data[context.dataIndex] > 0;
                                }
                            }
                        }
                    }
                });
            }
        @endforeach
        document.addEventListener("DOMContentLoaded", function () {
            // Kode JavaScript Leaflet Anda yang ada di sini.
            // Tidak ada yang perlu diubah dari fungsionalitasnya.
            var map = L.map('map').setView([-6.2, 106.8], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            map.createPane('commodityPane');
            map.getPane('commodityPane').style.zIndex = 400;

            map.createPane('kabupatenPane');
            map.getPane('kabupatenPane').style.zIndex = 300;

            const dataWilayah = @json($wilayah);
            const dataKecamatan = @json($polygon_kecamatan);
            const dataKabupaten = @json($polygon_kabupaten ?? []);
            const layerGroup = L.layerGroup().addTo(map);
            const kabupatenLayerGroup = L.layerGroup().addTo(map);

            function renderKabupaten() {
                dataKabupaten.forEach(kb => {
                    if (kb.polygon_kabupaten) {
                        try {
                            const geojson = JSON.parse(kb.polygon_kabupaten);
                            const layer = L.geoJSON(geojson, {
                                style: { color: kb.warna_kabupaten || '#800080', weight: 2, fillOpacity: 0.4, },
                                pane: 'kabupatenPane'
                            }).addTo(kabupatenLayerGroup);
                            layer.bindPopup(`<div class="popup-card-animated"><div class="popup-header"><i class="fas fa-map icon"></i><div class="popup-title" ><a href="https://mappikab.go.id/" target="_blank" style="color: inherit; text-decoration: none;">${kb.nama_kabupaten}</a></div></div><div class="popup-body"><p><strong>Luas:</strong> ${kb.luas_kabupaten} Ha</p></div></div>`);
                        } catch (e) { console.error('Gagal parsing polygon_kabupaten (kabupaten):', e, kb.polygon_kabupaten); }
                    }
                });
            }

            function renderMap(filterKomoditas = '', filterKecamatan = '') {
                layerGroup.clearLayers();
                const boundsArray = [];
                dataWilayah.forEach(w => {
                    const matchKomoditas = !filterKomoditas || w.nama_komoditas === filterKomoditas;
                    const matchKecamatan = !filterKecamatan || w.kecamatan_id == filterKecamatan;
                    if (matchKomoditas && matchKecamatan && w.polygon) {
                        try {
                            const geojson = JSON.parse(w.polygon);
                            const layer = L.geoJSON(geojson, {
                                style: { color: w.warna || '#3388ff', weight: 2, fillOpacity: 0.4 },
                                pane: 'commodityPane'
                            }).addTo(layerGroup);
                            layer.bindPopup(`<div class="popup-card-animated"><div class="popup-header"><i class="fas fa-seedling icon"></i><div class="popup-title">${w.nama_komoditas}</div></div><div class="popup-body"><p><strong>Luas:</strong> ${w.luas_wilayah} Ha</p><p><strong>Jumlah:</strong> ${w.jumlah_komoditas} unit</p><p><strong>Kecamatan:</strong> ${w.kecamatan.nama_kecamatan}</p><p><i class="fas fa-calendar-plus"></i> <small>Dibuat: ${new Date(w.created_at).toLocaleDateString('id-ID')}</small></p><p><i class="fas fa-calendar-check"></i> <small>Update: ${new Date(w.updated_at).toLocaleDateString('id-ID')}</small></p></div></div>`);
                            const bounds = layer.getBounds();
                            if (bounds.isValid()) { boundsArray.push(bounds); }
                        } catch (e) { console.error('Gagal parsing GeoJSON:', e, w.polygon); }
                    }
                });

                if (boundsArray.length > 0) {
                    let combinedBounds = boundsArray[0];
                    for (let i = 1; i < boundsArray.length; i++) { combinedBounds.extend(boundsArray[i]); }
                    map.fitBounds(combinedBounds, { padding: [30, 30], maxZoom: 16 });
                } else { map.setView([-6.2, 106.8], 10); }
            }
            
            function renderKecamatan() {
                dataKecamatan.forEach(k => {
                    if (k.polygon_kecamatan) {
                        try {
                            const geojson = JSON.parse(k.polygon_kecamatan);
                            const layer = L.geoJSON(geojson, {
                                style: { color: k.warna || '#000000', weight: 1, fillOpacity: 0.4, dashArray: '4,4' }
                            }).addTo(map);
                            layer.bindPopup(`<div class="popup-card-animated"><div class="popup-header"><i class="fas fa-location-dot icon"></i><div class="popup-title">${k.nama_kecamatan}</div></div><div class="popup-body"><p><strong>Luas:</strong> ${k.luas_kecamatan} Ha</p></div></div>`);
                        } catch (e) { console.error('Gagal parsing polygon_kecamatan:', e, k.polygon_kecamatan); }
                    }
                });
            }

            renderKabupaten();
            renderKecamatan();
            renderMap();

            document.getElementById('filterKomoditasSelect').addEventListener('change', () => {
                const komoditas = document.getElementById('filterKomoditasSelect').value;
                const kecamatan = document.getElementById('filterKecamatanSelect').value;
                renderMap(komoditas, kecamatan);
            });
            document.getElementById('filterKecamatanSelect').addEventListener('change', () => {
                const komoditas = document.getElementById('filterKomoditasSelect').value;
                const kecamatan = document.getElementById('filterKecamatanSelect').value;
                renderMap(komoditas, kecamatan);
            });
        });
    </script>

    {{-- CSS dan JS untuk Peta Leaflet (TIDAK DIUBAH) --}}
    <style>
        /* CSS untuk Pop-up Leaflet dipertahankan karena bersifat dinamis dan kompleks untuk di-tailwind */
        #map {
            /* Style dasar sudah dihandle tailwind, ini hanya fallback */
            height: 500px;
            width: 100%;
        }

        .popup-card-animated {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #ffffff);
            padding: 16px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            color: #2e7d32;
            width: 260px;
            animation: fadeInScale 0.4s ease-out;
        }

        .popup-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #c8e6c9;
            padding-bottom: 6px;
        }

        .popup-header .icon {
            font-size: 22px;
            margin-right: 10px;
            color: #388e3c;
        }

        .popup-title {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .popup-body p {
            margin: 6px 0;
            font-size: 0.95rem;
            color: #424242;
        }

        .popup-body i {
            margin-right: 5px;
            color: #66bb6a;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.85);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .leaflet-popup-content-wrapper {
            border-radius: 18px !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        .leaflet-popup-tip {
            background: #e8f5e9 !important;
        }
        
        /* Dan seterusnya untuk style popup lainnya... */
        .popup-card-region { font-family: 'Segoe UI', Tahoma, sans-serif; background: linear-gradient(135deg, #fff3e0, #ffffff); padding: 16px; border-radius: 16px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); color: #e65100; width: 260px; animation: fadeInScale 0.4s ease-out; }
        .popup-card-region.kabupaten { background: linear-gradient(135deg, #e3f2fd, #ffffff); color: #1565c0; }
        .popup-card-region .popup-header { display: flex; align-items: center; margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 6px; }
        .popup-card-region .icon { font-size: 22px; margin-right: 10px; }
        .popup-card-region .popup-title { font-size: 1.1rem; font-weight: bold; }
        .popup-card-region .popup-body p { margin: 6px 0; font-size: 0.95rem; color: #424242; }
        .popup-card-region .popup-body i { margin-right: 5px; color: inherit; }
    </style>

</body>
</html>