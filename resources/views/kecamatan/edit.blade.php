@extends('layouts.app')

@section('title', 'Edit Data Kecamatan')

@section('content')

{{-- Link CSS untuk Peta --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

{{-- Menyiapkan data polygon dan kabupaten dengan aman untuk JavaScript --}}
@php
    $polygonData = old('polygon_kecamatan', $kecamatan->polygon_kecamatan);
@endphp

{{-- Latar belakang tematik dengan efek aurora --}}
<div class="absolute inset-x-0 top-0 -z-10 transform-gpu overflow-hidden blur-3xl" aria-hidden="true">
    <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#80ff89] to-[#00c4ff] opacity-10 dark:opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
</div>

{{-- Kartu Form Utama --}}
<div class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg rounded-2xl shadow-lg ring-1 ring-black/5 p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
            Edit Data Kecamatan: {{ $kecamatan->nama_kecamatan }}
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ubah detail dan gambar ulang batas wilayah jika diperlukan.</p>
    </div>

    <form action="{{ route('kecamatan.update', $kecamatan->kecamatan_id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Nama Kecamatan --}}
        <div>
            <label for="nama_kecamatan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Kecamatan</label>
            <input type="text" id="nama_kecamatan" name="nama_kecamatan" value="{{ old('nama_kecamatan', $kecamatan->nama_kecamatan) }}" required
                   class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Pilih Warna --}}
            <div>
                <label for="colorPicker" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Warna Peta</label>
                <div class="mt-1 flex items-center gap-4">
                    <input type="color" id="colorPicker" value="{{ old('warna', $kecamatan->warna) }}" 
                           class="w-14 h-10 p-1 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 cursor-pointer rounded-lg">
                    <input type="text" id="colorValueText" readonly value="{{ old('warna', $kecamatan->warna) }}"
                           class="block w-full rounded-lg border-slate-300 bg-slate-100 dark:bg-slate-900 shadow-sm dark:border-slate-600 dark:text-slate-300 cursor-not-allowed">
                    <input type="hidden" name="warna" id="warnaInput" value="{{ old('warna', $kecamatan->warna) }}">
                </div>
            </div>

            {{-- Luas Kecamatan --}}
            <div>
                <label for="luasInput" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Luas (Hektar)</label>
                <input type="text" name="luas_kecamatan" id="luasInput" value="{{ old('luas_kecamatan', $kecamatan->luas_kecamatan) }}" readonly
                       class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-100 dark:bg-slate-900 shadow-sm dark:border-slate-600 dark:text-slate-300 cursor-not-allowed">
            </div>
        </div>

        {{-- Peta untuk Menggambar Polygon --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Edit Batas Wilayah</label>
            <div id="map" class="h-96 w-full rounded-lg shadow-inner bg-slate-200 dark:bg-slate-700 z-10"></div>
            <input type="hidden" name="polygon_kecamatan" id="polygonInput" value="{{ $polygonData }}">
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end pt-4 gap-4">
            <a href="{{ route('kecamatan.index') }}" class="py-2 px-4 text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 rounded-lg shadow-sm border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                Kembali
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Library JavaScript untuk Peta --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6.5.0/turf.min.js"></script>

<script>
    // [BARU] Mengambil data kabupaten dari controller
    const kabupatenData = @json($kabupaten ?? null);

    const isDarkMode = document.documentElement.classList.contains('dark');
    const map = L.map('map').setView([-6.0, 139.0], 8);
    const lightTile = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    const darkTile = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

    L.tileLayer(isDarkMode ? darkTile : lightTile, {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
    }).addTo(map);

    map.createPane('kabupatenPane').style.zIndex = 250;
    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        edit: { featureGroup: drawnItems },
        draw: { polygon: true, polyline: false, rectangle: false, circle: false, marker: false }
    });
    map.addControl(drawControl);

    const colorPicker = document.getElementById('colorPicker');
    const colorValueText = document.getElementById('colorValueText');
    const warnaInput = document.getElementById('warnaInput');
    let selectedColor = colorPicker.value;
    
    function updateColorUI(color) {
        selectedColor = color;
        warnaInput.value = color;
        if (colorValueText) colorValueText.value = color;
        drawnItems.eachLayer(layer => {
            layer.setStyle({ color: selectedColor, fillColor: selectedColor });
        });
    }
    
    updateColorUI(selectedColor);
    colorPicker.addEventListener('input', () => updateColorUI(colorPicker.value));

    // [FUNGSI BARU] untuk memuat poligon kabupaten
    function loadKabupatenPolygon() {
        if (!kabupatenData || !kabupatenData.polygon_kabupaten) return;
        try {
            const geojson = JSON.parse(kabupatenData.polygon_kabupaten);
            const kabupatenLayer = L.geoJSON(geojson, {
                style: {
                    color: '#8A2BE2', weight: 2, fillOpacity: 0.05,
                    dashArray: '5, 5', pane: 'kabupatenPane', interactive: false
                }
            }).addTo(map);

            // Zoom out ke kabupaten hanya jika tidak ada poligon kecamatan yang sedang diedit
            if (drawnItems.getLayers().length === 0 && kabupatenLayer.getBounds().isValid()) {
                map.fitBounds(kabupatenLayer.getBounds());
            }
        } catch (e) { console.error("Gagal menampilkan poligon kabupaten:", e); }
    }

    // --- LOGIKA UTAMA UNTUK MEMUAT DATA YANG ADA ---
    let existingGeoJSON;
    try {
        const rawJsonString = @json($polygonData);
        if (rawJsonString && rawJsonString.trim() !== '') {
            existingGeoJSON = JSON.parse(rawJsonString);
        }
    } catch (error) { console.error("Gagal parsing polygon_kecamatan:", error); }

    if (existingGeoJSON) {
        try {
            const existingLayer = L.geoJSON(existingGeoJSON, {
                style: { color: selectedColor, fillColor: selectedColor, fillOpacity: 0.5, weight: 2 }
            });
            existingLayer.eachLayer(layer => drawnItems.addLayer(layer));
            if (existingLayer.getBounds().isValid()) {
                map.fitBounds(existingLayer.getBounds()); // Prioritaskan zoom ke kecamatan yang diedit
            }
        } catch (error) { console.error("Gagal membuat layer dari GeoJSON yang ada:", error); }
    }
    // --- AKHIR LOGIKA MEMUAT DATA ---

    function updateFormFields(layer) {
        const geojson = layer.toGeoJSON();
        document.getElementById('polygonInput').value = JSON.stringify(geojson);
        const area = turf.area(geojson);
        document.getElementById('luasInput').value = (area / 10000).toFixed(2);
    }
    
    map.on('draw:created', function (event) {
        // ... (fungsi draw:created Anda yang lama, tidak perlu diubah) ...
    });
    map.on('draw:edited', function (event) {
        // ... (fungsi draw:edited Anda yang lama, tidak perlu diubah) ...
    });
    map.on('draw:deleted', function () {
        // ... (fungsi draw:deleted Anda yang lama, tidak perlu diubah) ...
    });

    // Panggil fungsi untuk memuat latar belakang kabupaten
    document.addEventListener("DOMContentLoaded", loadKabupatenPolygon);
</script>
@endpush