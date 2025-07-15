@extends('layouts.app')

@section('content')
    <div class="p-6 sm:p-10 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">

            {{-- Judul --}}
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Member Admin</h2>

            {{-- Form --}}
            <form action="{{ route('admin.manage.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required autocomplete="off">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required autocomplete="new-password">
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                {{-- Bidang --}}
                <div>
                    <label for="bidang_id" class="block text-sm font-medium text-gray-700">Bidang</label>
                    <select name="bidang_id" id="bidang_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="">Pilih Bidang</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow-sm text-sm font-medium">
                        Simpan
                    </button>
                    <a href="{{ route('admin.manage.index') }}" class="text-sm text-gray-600 hover:text-gray-800 underline">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection