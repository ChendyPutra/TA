<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kabupaten; // 1. TAMBAHKAN: Import model Kabupaten
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::all();
        return view('kecamatan.index', compact('kecamatan'));
    }

    public function create()
    {
        // 2. UBAH: Ambil juga data kabupaten
        $kabupaten = Kabupaten::first(); // Asumsi hanya ada 1 kabupaten (Mappi)
        $kecamatans = Kecamatan::all();

        // Kirim kedua data ke view
        return view('kecamatan.create', compact('kecamatans', 'kabupaten'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_kecamatan' => 'required',
            'warna' => 'required',
            'polygon_kecamatan' => 'required',
            'luas_kecamatan' => 'required|numeric',
        ]);

        Kecamatan::create($request->all());

        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil ditambahkan');
    }

    // Saya akan menggunakan Route Model Binding di sini untuk kode yang lebih bersih
    public function edit(Kecamatan $kecamatan)
    {
        // 3. UBAH: Ambil juga data kabupaten
        $kabupaten = Kabupaten::first();

        // Kirim kedua data ke view
        return view('kecamatan.edit', compact('kecamatan', 'kabupaten'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'nama_kecamatan' => 'required',
            'warna' => 'required',
            'polygon_kecamatan' => 'required',
            'luas_kecamatan' => 'required|numeric',
        ]);

        $kecamatan->update($request->all());

        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil diperbarui');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil dihapus');
    }
}