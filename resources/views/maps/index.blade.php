@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="container">
        <br>
        <h2 class="mb-4 text-center">Peta Komoditas Pertanian</h2>

        <div class="row mb-4">
            <div class="col-md-6">
                <select id="filterKomoditasSelect" class="form-control">
                    <option value="">-- Filter berdasarkan Komoditas --</option>
                    @php
                        $komoditasList = $wilayah->pluck('nama_komoditas')->unique();
                    @endphp
                    @foreach($komoditasList as $komoditas)
                        <option value="{{ $komoditas }}">{{ $komoditas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <select id="filterKecamatanSelect" class="form-control">
                    <option value="">-- Filter berdasarkan Kecamatan --</option>
                    @foreach($polygon_kecamatan as $kecamatan)
                        <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="map" class="mb-4"></div>
    </div>
@endsection

@push('scripts')
    <style>
        #map {
            height: 500px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            z-index: 1;
            /* peta di bawah elemen lain */
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

        /* Leaflet tweaks */
        .leaflet-popup-content-wrapper {
            border-radius: 18px !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        .leaflet-popup-tip {
            background: #e8f5e9 !important;
        }

        .popup-card-region {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(135deg, #fff3e0, #ffffff);
            /* Oranye lembut */
            padding: 16px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            color: #e65100;
            width: 260px;
            animation: fadeInScale 0.4s ease-out;
        }

        .popup-card-region.kabupaten {
            background: linear-gradient(135deg, #e3f2fd, #ffffff);
            /* Biru muda */
            color: #1565c0;
        }

        .popup-card-region .popup-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 6px;
        }

        .popup-card-region .icon {
            font-size: 22px;
            margin-right: 10px;
        }

        .popup-card-region .popup-title {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .popup-card-region .popup-body p {
            margin: 6px 0;
            font-size: 0.95rem;
            color: #424242;
        }

        .popup-card-region .popup-body i {
            margin-right: 5px;
            color: inherit;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var map = L.map('map').setView([-6.2, 106.8], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            // Membuat pane dengan zIndex lebih tinggi untuk komoditas
            map.createPane('commodityPane');
            map.getPane('commodityPane').style.zIndex = 400;

            // Membuat pane untuk layer kabupaten (opsional, untuk mengatur z-index jika diperlukan)
            map.createPane('kabupatenPane');
            map.getPane('kabupatenPane').style.zIndex = 300;

            const dataWilayah = @json($wilayah);
            const dataKecamatan = @json($polygon_kecamatan);
            const dataKabupaten = @json($polygon_kabupaten ?? []); // Pastikan variabel ini dikirim dari controller
            const layerGroup = L.layerGroup().addTo(map);
            const kabupatenLayerGroup = L.layerGroup().addTo(map); // Layer group untuk kabupaten

            // Fungsi untuk merender polygon kabupaten di peta
            function renderKabupaten() {
                dataKabupaten.forEach(kb => {
                    if (kb.polygon_kabupaten) {
                        try {
                            const geojson = JSON.parse(kb.polygon_kabupaten);
                            const layer = L.geoJSON(geojson, {
                                style: {
                                    color: kb.warna_kabupaten || '#800080', // Ungu default
                                    weight: 2,
                                    fillOpacity: 0.4,
                                },
                                pane: 'kabupatenPane' // Menggunakan pane kabupaten
                            }).addTo(kabupatenLayerGroup);

                            layer.bindPopup(`
            <div class="popup-card-animated">
                <div class="popup-header">
                    <i class="fas fa-map icon"></i>
                    <div class="popup-title">${kb.nama_kabupaten}</div>
                </div>
                <div class="popup-body">
                    <p><strong>Luas:</strong> ${kb.luas_kabupaten} Ha</p>
                </div>
            </div>
        `);


                        } catch (e) {
                            console.error('Gagal parsing polygon_kabupaten (kabupaten):', e, kb.polygon_kabupaten);
                        }
                    }
                });
            }

            // Fungsi untuk merender peta dengan filter komoditas dan kecamatan
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
                                style: {
                                    color: w.warna || '#3388ff',
                                    weight: 2,
                                    fillOpacity: 0.4
                                },
                                pane: 'commodityPane' // Menggunakan pane komoditas
                            }).addTo(layerGroup);

                            layer.bindPopup(`
            <div class="popup-card-animated">
                <div class="popup-header">
                    <i class="fas fa-seedling icon"></i>
                    <div class="popup-title">${w.nama_komoditas}</div>
                </div>
                <div class="popup-body">
                    <p><strong>Luas:</strong> ${w.luas_wilayah} Ha</p>
                    <p><strong>Kecamatan:</strong> ${w.kecamatan.nama_kecamatan}</p>
                    <p><i class="fas fa-calendar-plus"></i> <small>Dibuat: ${new Date(w.created_at).toLocaleDateString('id-ID')}</small></p>
                    <p><i class="fas fa-calendar-check"></i> <small>Update: ${new Date(w.updated_at).toLocaleDateString('id-ID')}</small></p>
                </div>
            </div>
        `);



                            const bounds = layer.getBounds();
                            if (bounds.isValid()) {
                                boundsArray.push(bounds);
                            }
                        } catch (e) {
                            console.error('Gagal parsing GeoJSON:', e, w.polygon);
                        }
                    }
                });

                // Menyesuaikan tampilan peta dengan data yang sesuai filter
                if (boundsArray.length > 0) {
                    let combinedBounds = boundsArray[0];
                    for (let i = 1; i < boundsArray.length; i++) {
                        combinedBounds.extend(boundsArray[i]);
                    }
                    map.fitBounds(combinedBounds, { padding: [30, 30], maxZoom: 16 });
                } else {
                    map.setView([-6.2, 106.8], 10); // Posisi peta default
                }
            }

            // Fungsi untuk merender kecamatan di peta
            function renderKecamatan() {
                dataKecamatan.forEach(k => {
                    if (k.polygon_kecamatan) {
                        try {
                            const geojson = JSON.parse(k.polygon_kecamatan);
                            const layer = L.geoJSON(geojson, {
                                style: {
                                    color: k.warna || '#000000',
                                    weight: 1,
                                    fillOpacity: 0.4,
                                    dashArray: '4,4'
                                }
                            }).addTo(map); // Ditambahkan langsung ke map, bukan layerGroup

                            layer.bindPopup(`
            <div class="popup-card-animated">
                <div class="popup-header">
                    <i class="fas fa-location-dot icon"></i>
                    <div class="popup-title">${k.nama_kecamatan}</div>
                </div>
                <div class="popup-body">
                    <p><strong>Luas:</strong> ${k.luas_kecamatan} Ha</p>
                </div>
            </div>
        `);

                        } catch (e) {
                            console.error('Gagal parsing polygon_kecamatan:', e, k.polygon_kecamatan);
                        }
                    }
                });
            }

            renderKabupaten(); // Render kabupaten TERLEBIH DAHULU
            renderKecamatan(); // Render kecamatan
            renderMap();     // Lalu render komoditas dengan pane di atasnya

            // Event listener untuk filter komoditas
            document.getElementById('filterKomoditasSelect').addEventListener('change', () => {
                const komoditas = document.getElementById('filterKomoditasSelect').value;
                const kecamatan = document.getElementById('filterKecamatanSelect').value;
                renderMap(komoditas, kecamatan);
            });

            // Event listener untuk filter kecamatan
            document.getElementById('filterKecamatanSelect').addEventListener('change', () => {
                const komoditas = document.getElementById('filterKomoditasSelect').value;
                const kecamatan = document.getElementById('filterKecamatanSelect').value;
                renderMap(komoditas, kecamatan);
            });
        });
    </script>
@endpush