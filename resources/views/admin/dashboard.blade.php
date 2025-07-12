@extends('layouts.app')

@section('content')
    {{-- Dependensi yang dipertahankan: Bootstrap Icons untuk ikon & Animate.css jika masih digunakan --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- Kontainer utama dengan background abu-abu muda untuk kontras --}}
    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 text-center mb-4">Dashboard Wilayah Komoditas</h1>
            <p class="text-center text-gray-500 mb-10">Analisis Data Pertanian Kabupaten Mappi</p>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md mb-8 animate__animated animate__fadeInDown" role="alert">
                    <div class="flex">
                        <div class="py-1"><i class="bi bi-check-circle-fill mr-3"></i></div>
                        <div>
                            <p class="font-bold">Berhasil</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Ringkasan Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-6 hover:shadow-xl hover:scale-105 transform transition-all duration-300 animate__animated animate__fadeInUp">
                    <div class="flex-shrink-0 p-4 rounded-full bg-sky-100 text-sky-600">
                        <i class="bi bi-geo-alt-fill text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Kecamatan</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $groupedData->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-6 hover:shadow-xl hover:scale-105 transform transition-all duration-300 animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="flex-shrink-0 p-4 rounded-full bg-emerald-100 text-emerald-600">
                        <i class="bi bi-flower1 text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Komoditas</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $groupedData->flatten(1)->pluck('nama_komoditas')->unique()->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-6 hover:shadow-xl hover:scale-105 transform transition-all duration-300 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="flex-shrink-0 p-4 rounded-full bg-amber-100 text-amber-600">
                        <i class="bi bi-bounding-box-circles text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Luas Wilayah</p>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($groupedData->flatten(1)->sum('total_luas'), 2) }} <span class="text-xl">Ha</span></p>
                    </div>
                </div>
            </div>

            {{-- Peta dan Filter --}}
            <div class="bg-white p-6 rounded-xl shadow-lg mb-12">
                 <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <select id="filterKomoditasSelect" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition">
                        <option value="">-- Filter Berdasarkan Komoditas --</option>
                        @php
                            $komoditasList = $wilayah->pluck('nama_komoditas')->unique();
                        @endphp
                        @foreach($komoditasList as $komoditas)
                            <option value="{{ $komoditas }}">{{ $komoditas }}</option>
                        @endforeach
                    </select>
                     <select id="filterKecamatanSelect" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition">
                        <option value="">-- Filter Berdasarkan Kecamatan --</option>
                        @foreach($polygon_kecamatan as $kecamatan)
                            <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="map" class="h-[500px] w-full rounded-lg shadow-inner z-10 border border-gray-200"></div>
            </div>

            {{-- Grafik Global & Filter Tanggal --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                {{-- Global Chart --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6 animate__animated animate__fadeInUp">
                    <h2 class="text-xl font-semibold text-gray-700 mb-1 text-center">Distribusi Komoditas Global</h2>
                    <p class="text-center text-gray-500 text-sm mb-4">di Kabupaten Mappi</p>
                    <div class="h-96">
                        <canvas id="globalCommodityChart"></canvas>
                    </div>
                </div>

                {{-- Filter Tahun dan Bulan --}}
                <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col justify-center animate__animated animate__fadeInDown">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">Filter Data Waktu</h3>
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="year" class="block mb-2 text-sm font-medium text-gray-900">Tahun:</label>
                            <select name="year" id="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                <option value="">Semua Tahun</option>
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="month" class="block mb-2 text-sm font-medium text-gray-900">Bulan:</label>
                            <select name="month" id="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                <option value="">Semua Bulan</option>
                                @foreach($availableMonths as $monthNum => $monthName)
                                    <option value="{{ $monthNum }}" {{ $selectedMonth == $monthNum ? 'selected' : '' }}>{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 flex items-center justify-center">
                                <i class="bi bi-funnel-fill mr-2"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bagian Per Kecamatan --}}
            <h2 class="text-2xl font-bold text-gray-800 text-center mt-16 mb-8 animate__animated animate__fadeIn">Detail Data & Grafik Per Kecamatan</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @forelse($groupedData as $kecamatan => $komoditas)
                    <div class="animate__animated animate__zoomIn">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden h-full flex flex-col">
                            <div class="p-6">
                                <h5 class="text-xl font-bold text-center text-indigo-700 mb-5">{{ $kecamatan }}</h5>

                                {{-- Navigasi Tab dengan gaya Tailwind --}}
                                <div class="mb-4 border-b border-gray-200">
                                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="kecamatanTab-{{ Str::slug($kecamatan) }}" role="tablist">
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-300" id="table-tab-{{ Str::slug($kecamatan) }}" data-bs-toggle="tab" data-bs-target="#table-{{ Str::slug($kecamatan) }}" type="button" role="tab" aria-controls="table-{{ Str::slug($kecamatan) }}" aria-selected="true">
                                                <i class="bi bi-table mr-1"></i> Detail Tabel
                                            </button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-300" id="chart-tab-{{ Str::slug($kecamatan) }}" data-bs-toggle="tab" data-bs-target="#chart-{{ Str::slug($kecamatan) }}-content" type="button" role="tab" aria-controls="chart-{{ Str::slug($kecamatan) }}-content" aria-selected="false">
                                                <i class="bi bi-bar-chart-fill mr-1"></i> Grafik
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="tab-content px-6 pb-6 flex-grow" id="kecamatanTabContent-{{ Str::slug($kecamatan) }}">
                                <div class="tab-pane fade" id="table-{{ Str::slug($kecamatan) }}" role="tabpanel" aria-labelledby="table-tab-{{ Str::slug($kecamatan) }}">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-left text-gray-500">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
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
                    </div>
                @empty
                    <div class="lg:col-span-2 bg-sky-100 border-l-4 border-sky-500 text-sky-700 p-6 rounded-md shadow-md text-center animate__animated animate__fadeIn">
                        <i class="bi bi-info-circle text-2xl mr-3"></i>
                        <p class="font-semibold">Tidak ada data komoditas yang ditemukan untuk filter yang dipilih.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Script Chart.js & Datalabels (TIDAK DIUBAH) --}}
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
    </script>
@endsection

@push('scripts')
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

    <script>
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
@endpush