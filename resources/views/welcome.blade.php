<!DOCTYPE html>
<html lang="en" class="dark"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Peta Wilayah Interaktif</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', // Mengaktifkan mode gelap berdasarkan class
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6.5.0/turf.min.js"></script>

    <style>
        /* Mencegah flicker saat Alpine.js dimuat */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">

    <div 
        class="flex h-screen" 
        x-data="mapEditor()" 
        x-init="initLeaflet()"
    >
        <aside class="flex-shrink-0 w-96 bg-white dark:bg-gray-800 shadow-lg flex flex-col h-full">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold">Editor Wilayah</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gambar poligon di peta untuk memulai.</p>
            </div>
            
            <form @submit.prevent="saveWilayah()" class="flex-grow p-4 space-y-4 overflow-y-auto">
                <div>
                    <label for="name" class="block text-sm font-medium">Nama Wilayah</label>
                    <input type="text" id="name" x-model="formData.name" required
                           class="mt-1 block w-full rounded-md bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium">Status Keamanan</label>
                    <select id="status" x-model="formData.status"
                            class="mt-1 block w-full rounded-md bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="aman">Aman</option>
                        <option value="rawan">Rawan</option>
                        <option value="bahaya">Bahaya</option>
                    </select>
                </div>

                <div>
                    <label for="colorPicker" class="block text-sm font-medium">Warna Poligon</label>
                    <input type="color" id="colorPicker" x-model="formData.color" @input="updatePolygonColor()"
                           class="mt-1 w-full h-10 p-1 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 cursor-pointer rounded-md">
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-medium">Keterangan</label>
                    <textarea id="keterangan" x-model="formData.keterangan" required rows="4"
                              class="mt-1 block w-full rounded-md bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium">Gambar (Opsional)</label>
                    <input type="file" id="image" @change="formData.image = $event.target.files[0]"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/50 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100">
                </div>
                
                <div x-show="drawnPolygon" class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md text-sm" x-cloak>
                    <p>Luas Wilayah: <strong x-text="polygonArea"></strong> Ha</p>
                </div>

                <div class="pt-4">
                    <button type="submit" :disabled="!drawnPolygon || isSaving"
                            class="w-full inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isSaving">Simpan Wilayah</span>
                        <span x-show="isSaving">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </aside>

        <main class="flex-grow h-full relative">
            <div id="map" class="h-full w-full"></div>
            <button @click="$store.darkMode.toggle()" class="absolute top-4 right-4 bg-white dark:bg-gray-800 p-2 rounded-full shadow-lg z-[1000]">
                <i class="bi" :class="$store.darkMode.on ? 'bi-sun-fill' : 'bi-moon-fill'"></i>
                 <svg x-show="!$store.darkMode.on" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon-fill" viewBox="0 0 16 16"><path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278"/></svg>
                 <svg x-show="$store.darkMode.on" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sun-fill" viewBox="0 0 16 16"><path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707"/></svg>
            </button>
        </main>
    </div>

<script>
    // Global store untuk dark mode
    document.addEventListener('alpine:init', () => {
        Alpine.store('darkMode', {
            on: document.documentElement.classList.contains('dark'),
            toggle() {
                this.on = !this.on;
                document.documentElement.classList.toggle('dark', this.on);
            }
        })
    });

    // Logika utama untuk editor peta
    function mapEditor() {
        return {
            map: null,
            drawnItems: null,
            drawnPolygon: null,
            polygonArea: 0,
            isSaving: false,
            formData: {
                name: '',
                status: 'aman',
                color: '#3b82f6', // Warna default Indigo
                keterangan: '',
                image: null,
            },

            initLeaflet() {
                // Inisialisasi peta
                this.map = L.map('map').setView([-2.5, 118.0], 5); // Center di Indonesia
                
                const lightTile = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                const darkTile = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

                const tileLayer = L.tileLayer(Alpine.store('darkMode').on ? darkTile : lightTile, {
                    attribution: '&copy; OpenStreetMap contributors & CARTO'
                }).addTo(this.map);
                
                // Ganti tile layer saat tema berubah
                this.$watch('$store.darkMode.on', (on) => {
                    tileLayer.setUrl(on ? darkTile : lightTile);
                });

                // Inisialisasi layer untuk poligon yang digambar
                this.drawnItems = new L.FeatureGroup();
                this.map.addLayer(this.drawnItems);

                // Inisialisasi kontrol gambar
                const drawControl = new L.Control.Draw({
                    edit: { featureGroup: this.drawnItems },
                    draw: { polygon: true, polyline: false, rectangle: false, circle: false, marker: false }
                });
                this.map.addControl(drawControl);

                // Menangani event saat poligon digambar
                this.map.on('draw:created', (e) => this.onDrawCreated(e));
                this.map.on('draw:edited', (e) => this.onDrawEdited(e));
                this.map.on('draw:deleted', () => this.onDrawDeleted());
            },

            onDrawCreated(event) {
                const layer = event.layer;
                this.drawnItems.clearLayers(); // Hanya izinkan satu poligon
                this.drawnItems.addLayer(layer);
                this.updatePolygonData(layer);
            },
            
            onDrawEdited(event) {
                event.layers.eachLayer(layer => this.updatePolygonData(layer));
            },

            onDrawDeleted() {
                this.drawnPolygon = null;
                this.polygonArea = 0;
            },

            updatePolygonData(layer) {
                layer.setStyle({ color: this.formData.color, fillColor: this.formData.color, fillOpacity: 0.5 });
                this.drawnPolygon = layer.toGeoJSON();
                this.polygonArea = (turf.area(this.drawnPolygon) / 10000).toFixed(2); // Kalkulasi luas
            },

            updatePolygonColor() {
                if (this.drawnItems.getLayers().length > 0) {
                    this.drawnItems.eachLayer(layer => {
                        layer.setStyle({ color: this.formData.color, fillColor: this.formData.color });
                    });
                }
            },

            async saveWilayah() {
                if (!this.drawnPolygon) {
                    this.showSwal('Peringatan', 'Anda harus menggambar poligon di peta terlebih dahulu.', 'warning');
                    return;
                }
                
                this.isSaving = true;
                
                const data = new FormData();
                data.append('name', this.formData.name);
                data.append('status', this.formData.status);
                data.append('warna', this.formData.color);
                data.append('keterangan', this.formData.keterangan);
                data.append('koordinat', JSON.stringify(this.drawnPolygon.geometry.coordinates)); // Kirim GeoJSON
                if (this.formData.image) {
                    data.append('image', this.formData.image);
                }

                try {
                    const response = await fetch('/map/store', {
                        method: 'POST',
                        // Headers tidak diperlukan untuk FormData
                        body: data,
                        // Tambahkan CSRF token jika ini adalah aplikasi Laravel
                        // headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                    });

                    if (!response.ok) {
                        throw new Error(`Server merespon dengan status: ${response.status}`);
                    }

                    const result = await response.json();
                    this.showSwal('Berhasil!', 'Data wilayah berhasil disimpan.', 'success');
                    
                    // Reset form setelah berhasil
                    this.drawnItems.clearLayers();
                    this.drawnPolygon = null;
                    this.polygonArea = 0;
                    // Reset form fields lain jika perlu
                    
                } catch (error) {
                    console.error('Error:', error);
                    this.showSwal('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
                } finally {
                    this.isSaving = false;
                }
            },
            
            showSwal(title, text, icon) {
                const isDark = Alpine.store('darkMode').on;
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    background: isDark ? '#1f2937' : '#fff',
                    color: isDark ? '#d1d5db' : '#111827',
                });
            }
        }
    }
</script>

</body>
</html>