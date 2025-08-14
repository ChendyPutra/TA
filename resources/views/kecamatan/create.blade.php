@extends('layouts.app')

@section('title', 'Tambah Data Kecamatan')

@section('content')

{{-- Link CSS untuk Peta --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

{{-- Latar belakang tematik --}}
<div class="absolute inset-x-0 top-0 -z-10 transform-gpu overflow-hidden blur-3xl" aria-hidden="true">
    <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#80ff89] to-[#00c4ff] opacity-10 dark:opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
</div>

{{-- Kartu Form Utama --}}
<div class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg rounded-2xl shadow-lg ring-1 ring-black/5 p-6 sm:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
            Tambah Data Kecamatan Baru
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Isi detail dan gambar batas wilayah pada peta.</p>
    </div>

    <form action="{{ route('kecamatan.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Nama Kecamatan --}}
        <div>
            <label for="nama_kecamatan" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Kecamatan</label>
            <input type="text" id="nama_kecamatan" name="nama_kecamatan" required
                   class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Pilih Warna --}}
            <div>
                <label for="colorPicker" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Warna Peta</label>
                <div class="mt-1 flex items-center gap-4">
                    <input type="color" id="colorPicker" value="#4ade80" 
                           class="w-14 h-10 p-1 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 cursor-pointer rounded-lg">
                    <input type="text" id="colorValueText" readonly 
                           class="block w-full rounded-lg border-slate-300 bg-slate-100 dark:bg-slate-900 shadow-sm dark:border-slate-600 dark:text-slate-300 cursor-not-allowed">
                    <input type="hidden" name="warna" id="warnaInput">
                </div>
            </div>

            {{-- Luas Kecamatan --}}
            <div>
                <label for="luasInput" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Luas (Hektar)</label>
                <input type="text" name="luas_kecamatan" id="luasInput" readonly
                       class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-100 dark:bg-slate-900 shadow-sm dark:border-slate-600 dark:text-slate-300 cursor-not-allowed"
                       placeholder="Terisi otomatis setelah menggambar">
            </div>
        </div>

        {{-- Peta untuk Menggambar Polygon --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gambar Batas Wilayah</label>
            <div id="map" class="h-96 w-full rounded-lg shadow-inner bg-slate-200 dark:bg-slate-700 z-10"></div>
            <input type="hidden" name="polygon_kecamatan" id="polygonInput">
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end pt-4 gap-4">
            <a href="{{ route('kecamatan.index') }}" class="py-2 px-4 text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 rounded-lg shadow-sm border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                Batal
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                Simpan Kecamatan
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
    const kecamatanData = @json($kecamatans);
    const isDarkMode = document.documentElement.classList.contains('dark');
    const map = L.map('map').setView([-6.0, 139.0], 8); // Center di Mappi
    const lightTile = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    const darkTile = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

    L.tileLayer(isDarkMode ? darkTile : lightTile, {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
    }).addTo(map);

    // [BARU] Membuat pane untuk menempatkan poligon kabupaten di lapisan belakang
    map.createPane('kabupatenPane').style.zIndex = 250;

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        edit: { featureGroup: drawnItems },
        draw: {
            polygon: true,
            polyline: false, rectangle: false, circle: false, marker: false
        }
    });
    map.addControl(drawControl);
    
    // ... (Logika color picker Anda yang sudah ada) ...
    const colorPicker = document.getElementById('colorPicker');
    const colorValueText = document.getElementById('colorValueText');
    const warnaInput = document.getElementById('warnaInput');
    let selectedColor = colorPicker.value;
    
    function updateColorUI(color) {
        selectedColor = color;
        warnaInput.value = color;
        if (colorValueText) colorValueText.value = color;
    }
    updateColorUI(selectedColor);
    colorPicker.addEventListener('change', () => updateColorUI(colorPicker.value));

    // Event listener `draw:created` Anda yang sudah ada
    map.on('draw:created', function (event) {
        const layer = event.layer;
        drawnItems.clearLayers();
        drawnItems.addLayer(layer);
        layer.setStyle({
            color: selectedColor, fillColor: selectedColor,
            fillOpacity: 0.5, weight: 2
        });
        const geojson = layer.toGeoJSON();
        document.getElementById('polygonInput').value = JSON.stringify(geojson);
        const area = turf.area(geojson);
        const luasHa = (area / 10000).toFixed(2);
        document.getElementById('luasInput').value = luasHa;
    });

    // [FUNGSI BARU] untuk memuat poligon kabupaten
    function loadKabupatenPolygon() {
        if (!kabupatenData || !kabupatenData.polygon_kabupaten) {
            console.warn("Data poligon kabupaten tidak tersedia dari controller.");
            return;
        }

        try {
            const geojson = JSON.parse(kabupatenData.polygon_kabupaten);
            const kabupatenLayer = L.geoJSON(geojson, {
                style: {
                    color: '#8A2BE2', // Warna ungu untuk batas kabupaten
                    weight: 2,
                    fillOpacity: 0.05,
                    dashArray: '5, 5'
                },
                pane: 'kabupatenPane', // Menempatkan di pane belakang
                interactive: false // Agar tidak bisa di-klik
            }).addTo(map);

            // Zoom peta agar pas dengan batas kabupaten
            if (kabupatenLayer.getBounds().isValid()) {
                map.fitBounds(kabupatenLayer.getBounds());
            }
        } catch (e) {
            console.error("Gagal parsing atau menampilkan poligon kabupaten:", e);
        }
    }
  function loadKecamatanPolygons() {
    if (!kecamatanData || !Array.isArray(kecamatanData)) return;

    kecamatanData.forEach((item) => {
        if (!item.polygon_kecamatan) return;

        try {
            const geojson = JSON.parse(item.polygon_kecamatan);
            const layer = L.geoJSON(geojson, {
                style: {
                    color: item.warna || '#00bcd4',
                    weight: 1,
                    fillOpacity: 0.4
                },
                onEachFeature: function (feature, layer) {
                    // Tooltip saat hover
                    layer.bindTooltip(item.nama_kecamatan, {
                        permanent: false,
                        direction: 'center',
                        className: 'kecamatan-tooltip'
                    });

                    // Hapus efek border hitam saat diklik (disable default Leaflet click styling)
                    layer.on('click', function (e) {
                        e.target.setStyle({
                            weight: 1,
                            color: item.warna || '#00bcd4'
                        });
                    });
                }
            }).addTo(map);
        } catch (e) {
            console.warn('Gagal parsing polygon kecamatan:', item.nama_kecamatan, e);
        }
    });
}



    // Panggil fungsi ini setelah DOM dimuat
   document.addEventListener("DOMContentLoaded", () => {
    loadKabupatenPolygon();
    loadKecamatanPolygons();
});

</script>
@endpush