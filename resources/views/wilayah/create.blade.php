@extends('layouts.app')

@section('title', 'Tambah Data Wilayah')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

    <div class="absolute inset-x-0 top-0 -z-10 transform-gpu overflow-hidden blur-3xl" aria-hidden="true">
        <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#80ff89] to-[#00c4ff] opacity-10 dark:opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
            style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
        </div>
    </div>

    <div class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-lg rounded-2xl shadow-lg ring-1 ring-black/5 p-6 sm:p-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                Tambah Data Wilayah Komoditas
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Lengkapi detail komoditas dan petakan wilayahnya
                secara akurat.</p>
        </div>

        <form action="{{ route('wilayah.store') }}" method="POST" onsubmit="return cekPolygon()" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Komoditas --}}
                <div>
                    <label for="komoditas_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Nama Komoditas
                    </label>
                    <select id="komoditas_id" name="komoditas_id" required
                        class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                        <option value="">-- Pilih Komoditas --</option>
                        @foreach($komoditas as $item)
                            <option value="{{ $item->id }}" {{ old('komoditas_id', $wilayah->komoditas_id ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- Dropdown Kecamatan --}}
                <div>
                    <label for="kecamatan"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kecamatan</label>
                    <select name="kecamatan_id" id="kecamatan" required
                        class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach ($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->kecamatan_id }}">{{ $kecamatan->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Pilih Warna --}}
                <div>
                    <label for="colorPicker" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Warna
                        Peta</label>
                    <div class="mt-1 flex items-center gap-4">
                        <input type="color" id="colorPicker" value="#ff8c00"
                            class="w-14 h-10 p-1 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 cursor-pointer rounded-lg">
                        <input type="text" id="colorValue" readonly
                            class="block w-full rounded-lg border-slate-300 bg-slate-100 dark:bg-slate-900 shadow-sm dark:border-slate-600 dark:text-slate-300 cursor-not-allowed">
                        <input type="hidden" name="warna" id="warnaInput">
                    </div>
                </div>

                {{-- Luas Wilayah --}}
                <div>
                    <label for="luasInput" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Luas Wilayah
                        (Hektar)</label>
                    <input type="text" name="luas_wilayah" id="luasInput" readonly
                        class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-100 dark:bg-slate-900 shadow-sm dark:border-slate-600 dark:text-slate-300 cursor-not-allowed"
                        placeholder="Akan terisi otomatis">
                </div>
            </div>

            {{-- Jumlah Komoditas --}}
            <div>
                <label for="jumlah_komoditas" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Jumlah Komoditas
                </label>
                <input type="number" id="jumlah_komoditas" name="jumlah_komoditas" min="0" required
                    class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                    placeholder="Masukkan jumlah komoditas (angka)">
            </div>

            {{-- Peta --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gambar Batas Wilayah
                    Komoditas</label>
                <div id="map-wilayah-create"
                    class="h-96 w-full rounded-lg shadow-inner bg-slate-200 dark:bg-slate-700 z-10"></div>
                <input type="hidden" name="polygon" id="polygonInput">
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Pilih kecamatan terlebih dahulu sebagai acuan,
                    lalu gambar polygon wilayah komoditas di dalamnya.</p>
            </div>

            <div class="flex justify-end pt-4 gap-4">
                <a href="{{ route('wilayah.index') }}"
                    class="py-2 px-4 text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 rounded-lg shadow-sm border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">Batal</a>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">Simpan
                    Wilayah</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- Library JavaScript untuk Peta --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
    <script src="https://unpkg.com/@turf/turf/turf.min.js"></script>

    {{-- SCRIPT DI BAWAH INI TIDAK DIUBAH SAMA SEKALI, HANYA MENAMBAHKAN BEBERAPA PENINGKATAN KECIL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Peningkatan: Peta mendukung dark mode & pusat peta lebih relevan
            const isDarkMode = document.documentElement.classList.contains('dark');
            const map = L.map('map-wilayah-create').setView([-6.0, 150.0], 6);
            const lightTile = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
            const darkTile = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

            L.tileLayer(isDarkMode ? darkTile : lightTile, {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
            }).addTo(map);

            // Bagian ini tidak diubah dari kode lama Anda
            const drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);
            let kecamatanLayer = null;
            let kabupatenLayers = [];
            const drawControl = new L.Control.Draw({
                edit: { featureGroup: drawnItems },
                draw: { polygon: true, polyline: false, rectangle: false, circle: false, marker: false }
            });
            map.addControl(drawControl);

            // Peningkatan: Menambahkan feedback teks untuk color picker
            const colorPicker = document.getElementById('colorPicker');
            const colorValue = document.getElementById('colorValue');
            const warnaInput = document.getElementById('warnaInput');
            let selectedColor = colorPicker.value;

            function updateColorUI(color) {
                selectedColor = color;
                warnaInput.value = color;
                colorValue.value = color; // Update nilai teks
            }

            updateColorUI(selectedColor); // Set nilai awal
            colorPicker.addEventListener('change', () => updateColorUI(colorPicker.value));

            // Event listener `draw:created` dari kode lama Anda (tidak diubah)
            map.on('draw:created', function (event) {
                const layer = event.layer;
                drawnItems.clearLayers();
                drawnItems.addLayer(layer);
                layer.setStyle({
                    color: selectedColor,
                    fillColor: selectedColor,
                    fillOpacity: 0.5,
                    weight: 2
                });
                const geojson = layer.toGeoJSON();
                document.getElementById('polygonInput').value = JSON.stringify(geojson);
                const area = turf.area(geojson);
                const luasHa = (area / 10000).toFixed(2);
                document.getElementById('luasInput').value = luasHa;
            });

            // Event listener `change` pada dropdown kecamatan dari kode lama Anda (tidak diubah)
            document.getElementById('kecamatan').addEventListener('change', function () {
                const kecamatanId = this.value;
                if (!kecamatanId) {
                    if (kecamatanLayer) map.removeLayer(kecamatanLayer);
                    kecamatanLayer = null;
                    return;
                }
                fetch(`/get-kecamatan/${kecamatanId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data || !data.polygon) { alert('Data polygon kecamatan tidak ditemukan.'); return; }
                        let geojson;
                        try { geojson = JSON.parse(data.polygon); }
                        catch (e) { alert('Gagal membaca data polygon kecamatan.'); return; }
                        if (kecamatanLayer) map.removeLayer(kecamatanLayer);
                        const warna = data.warna || '#00ff00';
                        kecamatanLayer = L.geoJSON(geojson, { style: { color: '#000000', fillColor: warna, fillOpacity: 0.4 } }).addTo(map);
                        try { map.fitBounds(kecamatanLayer.getBounds()); }
                        catch (e) { console.error("Gagal melakukan zoom ke kecamatan:", e); }
                    })
                    .catch(err => {
                        alert('Gagal memuat data kecamatan.');
                        console.error(err);
                    });
            });

            // Fungsi `loadAllKabupatenPolygon` dari kode lama Anda (tidak diubah)
            function loadAllKabupatenPolygon() {
                fetch('/get-polygon-kabupaten')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(item => {
                            if (!item.polygon) return;
                            let geojson;
                            try { geojson = JSON.parse(item.polygon); }
                            catch (e) { console.error(`Gagal parsing polygon kabupaten ${item.nama}:`, e); return; }
                            const layer = L.geoJSON(geojson, { style: { color: '#000000', fillColor: item.warna, fillOpacity: 0.2 } }).bindPopup(`<strong>${item.nama}</strong>`).addTo(map);
                            kabupatenLayers.push(layer);
                        });
                        if (kabupatenLayers.length > 0) {
                            const group = L.featureGroup(kabupatenLayers);
                            map.fitBounds(group.getBounds());
                        }
                    })
                    .catch(err => { console.error("Gagal memuat data kabupaten:", err); });
            }

            // Fungsi `cekPolygon` dari kode lama Anda (tidak diubah)
            function cekPolygon() {
                if (!document.getElementById('polygonInput').value) {
                    alert('Silakan gambar polygon wilayah terlebih dahulu di peta.');
                    return false;
                }
                return true;
            }

            // Panggil fungsi load semua kabupaten
            loadAllKabupatenPolygon();
        });
    </script>
@endpush