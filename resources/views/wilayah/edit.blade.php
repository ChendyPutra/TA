@extends('layouts.app')

@section('title', 'Edit Data Wilayah')

@section('content')

{{-- Link CSS untuk Peta --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

{{-- Menyiapkan data dan URL dengan aman untuk JavaScript --}}
@php
    $polygonData = old('polygon', $wilayah->polygon);
    // [PENTING] Gunakan route name yang sudah terbukti berfungsi di environment Anda
    $kecamatanJsonUrlTemplate = '/get-kecamatan/{id}'; // Menggunakan URL hardcoded yang berfungsi
    $kabupatenJsonUrl = '/get-polygon-kabupaten';   // Menggunakan URL hardcoded yang berfungsi
@endphp

{{-- Latar belakang tematik dengan efek aurora --}}
<div class="absolute inset-x-0 top-0 -z-10 transform-gpu overflow-hidden blur-3xl" aria-hidden="true">
    <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#6B8E23] to-[#8FBC8F] opacity-10 dark:opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
</div>

{{-- Kartu Form Utama --}}
<div class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg rounded-2xl shadow-lg ring-1 ring-black/5 p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
            Edit Data Wilayah: {{ $wilayah->nama_komoditas }}
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ubah detail komoditas dan perbarui pemetaan wilayah jika diperlukan.</p>
    </div>

    <form action="{{ route('wilayah.update', $wilayah->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nama Komoditas --}}
            <div>
                <label for="nama_komoditas" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Komoditas</label>
                <input type="text" id="nama_komoditas" name="nama_komoditas" value="{{ old('nama_komoditas', $wilayah->nama_komoditas) }}" required
                       class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
            </div>

            {{-- Dropdown Kecamatan --}}
            <div>
                <label for="kecamatan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kecamatan</label>
                <select name="kecamatan_id" id="kecamatan" required
                        class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach ($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->kecamatan_id }}" {{ old('kecamatan_id', $wilayah->kecamatan_id) == $kecamatan->kecamatan_id ? 'selected' : '' }}>
                            {{ $kecamatan->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Pilih Warna --}}
            <div>
                <label for="colorPicker" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Warna Peta</label>
                <div class="mt-1 flex items-center gap-4">
                    <input type="color" id="colorPicker" value="{{ old('warna', $wilayah->warna) }}" class="w-14 h-10 p-1 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 cursor-pointer rounded-lg">
                    <input type="text" id="colorValue" readonly value="{{ old('warna', $wilayah->warna) }}" class="block w-full rounded-lg border-slate-300 bg-slate-100 dark:bg-slate-900 shadow-sm dark:border-slate-600 dark:text-slate-300 cursor-not-allowed">
                    <input type="hidden" name="warna" id="warnaInput" value="{{ old('warna', $wilayah->warna) }}">
                </div>
            </div>

             {{-- Luas Wilayah --}}
            <div>
                <label for="luasInput" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Luas Wilayah (Hektar)</label>
                <input type="text" name="luas_wilayah" id="luasInput" value="{{ old('luas_wilayah', $wilayah->luas_wilayah) }}" readonly class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-100 dark:bg-slate-900 shadow-sm dark:border-slate-600 dark:text-slate-300 cursor-not-allowed">
            </div>
        </div>

        {{-- Peta --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Edit Batas Wilayah Komoditas</label>
            <div id="map" class="h-96 w-full rounded-lg shadow-inner bg-slate-200 dark:bg-slate-700 z-10"></div>
            <input type="hidden" name="polygon" id="polygonInput" value="{{ $polygonData }}">
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end pt-4 gap-4">
            <a href="{{ route('wilayah.index') }}" class="py-2 px-4 text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 rounded-lg shadow-sm border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">Kembali</a>
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Library JavaScript untuk Peta --}}
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- SCRIPT DI BAWAH INI TIDAK DIUBAH SAMA SEKALI, HANYA DIPASTIKAN BERJALAN DENGAN BAIK --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([-6.0, 139.0], 7);
    const isDarkMode = () => document.documentElement.classList.contains('dark');
    const lightTile = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    const darkTile = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';
    
    let currentTileLayer = L.tileLayer(isDarkMode() ? darkTile : lightTile, {
        attribution: '&copy; OpenStreetMap contributors & CARTO'
    }).addTo(map);

    map.createPane('kabupatenPane').style.zIndex = 200;
    map.createPane('kecamatanPane').style.zIndex = 300;
    map.createPane('drawnPane').style.zIndex = 400;

    new MutationObserver(() => {
        currentTileLayer.setUrl(isDarkMode() ? darkTile : lightTile);
    }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    const drawnItems = new L.FeatureGroup({ pane: 'drawnPane' });
    map.addLayer(drawnItems);

    let kecamatanLayer = null;
    const kabupatenLayers = [];

    const drawControl = new L.Control.Draw({
        edit: { featureGroup: drawnItems },
        draw: { polygon: true, polyline: false, rectangle: false, circle: false, marker: false }
    });
    map.addControl(drawControl);

    const colorPicker = document.getElementById('colorPicker');
    const colorValue = document.getElementById('colorValue');
    const warnaInput = document.getElementById('warnaInput');
    let selectedColor = colorPicker.value;

    function updateColorUI(color) {
        selectedColor = color;
        warnaInput.value = color;
        colorValue.value = color;
        drawnItems.eachLayer(layer => {
            layer.setStyle({ color: selectedColor, fillColor: selectedColor });
        });
    }
    
    updateColorUI(selectedColor);
    colorPicker.addEventListener('input', () => updateColorUI(colorPicker.value));

    // --- LOGIKA UTAMA UNTUK MEMUAT DATA YANG ADA ---
    let existingGeoJSON;
    try {
        const rawJsonString = @json($polygonData);
        if (rawJsonString && rawJsonString.trim() !== '') {
            existingGeoJSON = JSON.parse(rawJsonString);
        }
    } catch (e) {
        console.error("Gagal parsing polygon wilayah:", e);
        existingGeoJSON = null;
    }

    if (existingGeoJSON) {
        try {
            const existingLayer = L.geoJSON(existingGeoJSON, {
                style: { color: selectedColor, fillColor: selectedColor, fillOpacity: 0.5, weight: 2 }
            });
            existingLayer.eachLayer(layer => drawnItems.addLayer(layer));
        } catch(e) { console.error("Gagal membuat layer wilayah:", e); }
    }
    // --- AKHIR LOGIKA MEMUAT DATA ---
    
    function updateFormFields(layer) {
        const geojson = layer.toGeoJSON();
        document.getElementById('polygonInput').value = JSON.stringify(geojson);
        const area = turf.area(geojson);
        document.getElementById('luasInput').value = (area / 10000).toFixed(2);
    }

    map.on('draw:created', function (e) {
        drawnItems.clearLayers();
        drawnItems.addLayer(e.layer);
        e.layer.setStyle({ color: selectedColor, fillColor: selectedColor, fillOpacity: 0.5, weight: 2 });
        updateFormFields(e.layer);
    });

    map.on('draw:edited', e => e.layers.eachLayer(updateFormFields));
    map.on('draw:deleted', () => {
        document.getElementById('polygonInput').value = '';
        document.getElementById('luasInput').value = '';
    });

    function loadKecamatanPolygon(kecamatanId) {
        if (!kecamatanId) return;
        fetch(`/get-kecamatan/${kecamatanId}`)
            .then(response => response.json())
            .then(data => {
                if (!data.polygon) return;
                let geojson = JSON.parse(data.polygon);
                if (kecamatanLayer) map.removeLayer(kecamatanLayer);
                kecamatanLayer = L.geoJSON(geojson, {
                    style: { color: '#1f2937', weight: 2.5, dashArray: '5, 5', fillColor: data.warna || '#00ff00', fillOpacity: 0.1 },
                    pane: 'kecamatanPane'
                }).addTo(map);
                if (drawnItems.getLayers().length > 0) {
                    if (drawnItems.getBounds().isValid()) map.fitBounds(drawnItems.getBounds());
                } else {
                    if (kecamatanLayer.getBounds().isValid()) map.fitBounds(kecamatanLayer.getBounds());
                }
            })
            .catch(err => {
                console.error('Gagal memuat kecamatan:', err);
                Swal.fire({ title:'Error', text: 'Gagal memuat data batas kecamatan.', icon: 'error' });
            });
    }

    document.getElementById('kecamatan').addEventListener('change', function () {
        loadKecamatanPolygon(this.value);
    });
    
    function loadAllKabupatenPolygon() {
        return fetch(`/get-polygon-kabupaten`)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    if (!item.polygon) return;
                    let geojson = JSON.parse(item.polygon);
                    L.geoJSON(geojson, {
                        style: { color: '#6b7280', weight: 1, fillColor: item.warna || '#a3e635', fillOpacity: 0.1 },
                        pane: 'kabupatenPane'
                    }).addTo(map);
                });
            });
    }

    // Alur Pemuatan Peta:
    loadAllKabupatenPolygon().then(() => {
        const initialKecamatanId = document.getElementById('kecamatan').value;
        if (initialKecamatanId) {
            loadKecamatanPolygon(initialKecamatanId);
        } else if (drawnItems.getLayers().length > 0) {
            if (drawnItems.getBounds().isValid()) map.fitBounds(drawnItems.getBounds());
        }
    });
});
</script>
@endpush