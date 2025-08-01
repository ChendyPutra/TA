@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">

        {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Data Komoditas
        </h1>
        <a href="{{ route('wilayah.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
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
    <div class="overflow-x-auto rounded-xl">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200 border border-black dark:border-gray-600 shadow-md rounded-xl overflow-hidden">
            <thead class="bg-indigo-600 from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 text-xs uppercase text-white dark:text-gray-300 tracking-wider">
                <tr>
                    <th class="px-6 py-4 border-b">No</th>
                    <th class="px-6 py-4 border-b">Nama Komoditas</th>
                    <th class="px-6 py-4 border-b">Kecamatan</th>
                    <th class="px-6 py-4 border-b">Warna</th>
                    <th class="px-6 py-4 border-b">Luas (Ha)</th>
                    <th class="px-6 py-4 border-b">Jumlah Komoditas</th>
                    <th class="px-6 py-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wilayah as $i => $w)
                <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-900' }} border-b dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700 transition duration-150">
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $i + 1 }}</td>
                    <td class="px-6 py-4">{{ $w->nama_komoditas }}</td>
                    <td class="px-6 py-4">{{ $w->kecamatan?->nama_kecamatan ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold shadow"
                              style="background: {{ $w->warna }}; color: {{ (new \App\Helpers\ColorHelper())->isDark($w->warna) ? '#fff' : '#000' }};">
                            {{ $w->warna }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ number_format($w->luas_wilayah, 2) }} Ha</td>
                    <td class="px-6 py-4">{{ number_format($w->jumlah_komoditas) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            @php
                                $canEdit = $admin->role === 'superadmin' || $w->bidang_id == $admin->bidang_id;
                            @endphp
                            @if ($canEdit)
                                <a href="{{ route('wilayah.edit', $w->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 rounded-md hover:bg-yellow-600 transition">
                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('wilayah.destroy', $w->id) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition">
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
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 italic">
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