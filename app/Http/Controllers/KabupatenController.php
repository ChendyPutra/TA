<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use Illuminate\Http\Request;

class KabupatenController extends Controller
{
    public function index()
    {
        $kabupaten = Kabupaten::all();
        return view('kabupaten.index', compact('kabupaten'));
    }

    public function create()
    {
        $kabupaten = Kabupaten::all();  // Ambil semua data kabupaten
        return view('kabupaten.create', compact('kabupaten'));  // Kirim data kabupaten ke view
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'nama_kabupaten' => 'required',
            'warna_kabupaten' => 'required',
            'polygon_kabupaten' => 'required',
            'luas_kabupaten' => 'required|numeric',
        ]);

        Kabupaten::create([
            'nama_kabupaten' => $request->nama_kabupaten,
            'warna_kabupaten' => $request->warna_kabupaten,
            'polygon_kabupaten' => $request->polygon_kabupaten,
            'luas_kabupaten' => $request->luas_kabupaten,
        ]);

        return redirect()->route('kabupaten.index')->with('success', 'Data kabupaten berhasil ditambahkan');
    }
    public function edit($id)
{
    $kabupaten = Kabupaten::findOrFail($id);
    return view('kabupaten.edit', compact('kabupaten'));
}

public function update(Request $request, $id)
{
    $request->validate([
         'nama_kabupaten' => 'required',
            'warna_kabupaten' => 'required',
            'polygon_kabupaten' => 'required',
            'luas_kabupaten' => 'required|numeric',
    ]);

    $kabupaten = Kabupaten::findOrFail($id);
    $kabupaten->update($request->all());

    return redirect()->route('kabupaten.index')->with('success', 'Data kabupaten berhasil diperbarui');
}

public function destroy($id)
{
    $kabupaten = Kabupaten::findOrFail($id);
    $kabupaten->delete();

    return redirect()->route('kabupaten.index')->with('success', 'Data kabupaten berhasil dihapus');
}
}
