@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Data Komoditas
            </h1>
            <a href="{{ route('wilayah.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="bi bi-plus-circle mr-2"></i>
                Tambah Data Wilayah
            </a>
        </div>

        @php
            $admin = auth('admin')->user();
        @endphp

        @if ($admin->role !== 'superadmin')
            <form method="GET" class="mb-6 flex items-center gap-3">
                <label for="filter" class="text-sm font-medium text-gray-700 dark:text-gray-200">
                    Tampilkan Data:
                </label>
                <select name="filter" id="filter" onchange="this.form.submit()"
                    class="block w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition">
                    <option value="">Hanya Bidang Saya</option>
                    <option value="all" {{ request('filter') === 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>
            </form>
        @endif






        {{-- Tabel Data --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama Komoditas</th>
                        <th scope="col" class="px-6 py-3">Kecamatan</th>
                        <th scope="col" class="px-6 py-3">Warna</th>
                        <th scope="col" class="px-6 py-3">Luas (Ha)</th>
                        <th scope="col" class="px-6 py-3">Jumlah Komoditas</th> {{-- ✅ Kolom Baru --}}
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wilayah as $i => $w)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $i + 1 }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $w->nama_komoditas }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $w->kecamatan?->nama_kecamatan ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full"
                                    style="background: {{ $w->warna }}; color: {{ (new \App\Helpers\ColorHelper())->isDark($w->warna) ? 'white' : 'black' }};">
                                    {{ $w->warna }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($w->luas_wilayah, 2) }} Ha
                            </td>
                            <td class="px-6 py-4"> {{-- ✅ Kolom Baru --}}
                                {{ number_format($w->jumlah_komoditas) }}
                            </td>
                            @php
                                $admin = auth('admin')->user();
                                $canEdit = $admin->role === 'superadmin' || $w->bidang_id == $admin->bidang_id;
                            @endphp

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($canEdit)
                                        <a href="{{ route('wilayah.edit', $w->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-md hover:bg-amber-600 transition-colors">
                                            <i class="bi bi-pencil-square mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('wilayah.destroy', $w->id) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                                <i class="bi bi-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic">Tidak dapat diubah</span>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data wilayah ditemukan. Silakan tambahkan data baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDarkMode = () => document.documentElement.classList.contains('dark');

            // Opsi tema untuk SweetAlert2
            const swalTheme = {
                background: isDarkMode() ? '#1f2937' : '#fff', // gray-800
                color: isDarkMode() ? '#d1d5db' : '#111827', // gray-300
            };

            // ✅ Notifikasi Success Otomatis Hilang
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    background: swalTheme.background,
                    color: swalTheme.color
                });
            @endif

                            // ✅ Konfirmasi Hapus
                            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3b82f6', // indigo-500
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        background: swalTheme.background,
                        color: swalTheme.color
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush