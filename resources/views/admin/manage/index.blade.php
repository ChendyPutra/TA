@extends('layouts.app')

@section('content')
<div class="p-6 sm:p-10 bg-gray-50 min-h-screen">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Admin</h2>
            <p class="text-sm text-gray-500">Manajemen akun admin berdasarkan bidang yang tersedia</p>
        </div>
        <a href="{{ route('admin.manage.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md shadow hover:bg-indigo-700 transition">
            + Tambah Admin
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-md bg-green-100 border border-green-300 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Admin --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr class="text-left text-sm text-gray-600 font-semibold">
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Bidang</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($admins as $admin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $admin->name }}</td>
                        <td class="px-6 py-4">{{ $admin->email }}</td>
                        <td class="px-6 py-4">{{ $admin->bidang->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('admin.manage.edit', $admin->id) }}"
                               class="inline-block px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-medium rounded-md">
                                Edit
                            </a>
                            <form action="{{ route('admin.manage.destroy', $admin->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus admin ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data admin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
