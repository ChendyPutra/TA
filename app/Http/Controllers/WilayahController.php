<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\WilayahPertanian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WilayahController extends Controller
{
    public function index()
{
    $wilayah = WilayahPertanian::with('kecamatan')->get();
    return view('wilayah.index', compact('wilayah'));
}

    public function create()
    {
        $kecamatans = Kecamatan::all(); // Ambil data kecamatan dari tabel
        return view('wilayah.create', compact('kecamatans'));
    }
    

    public function store(Request $request)
{
    $request->validate([
        'nama_komoditas' => 'required',
        'kecamatan_id' => 'required',
        'warna' => 'required',
        'polygon' => 'required',
        'luas_wilayah' => 'required|numeric',
        'jumlah_komoditas' => 'required|numeric|min:0', // Tambahkan validasi ini
    ]);

    WilayahPertanian::create([
        'nama_komoditas' => $request->nama_komoditas,
        'kecamatan_id' => $request->kecamatan_id,
        'warna' => $request->warna,
        'polygon' => $request->polygon,
        'luas_wilayah' => $request->luas_wilayah,
        'jumlah_komoditas' => $request->jumlah_komoditas, // Tambahkan ini
    ]);

    return redirect()->route('wilayah.index')->with('success', 'Data wilayah berhasil ditambahkan');
}


public function edit($id)
{
    $wilayah = WilayahPertanian::findOrFail($id);
    $kecamatans = Kecamatan::all(); // Ambil semua data kecamatan
    return view('wilayah.edit', compact('wilayah', 'kecamatans')); // Teruskan $kecamatans ke view
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_komoditas' => 'required',
        'kecamatan_id' => 'required',
        'warna' => 'required',
        'polygon' => 'required',
        'luas_wilayah' => 'required|numeric',
        'jumlah_komoditas' => 'required|numeric|min:0', // Tambahkan validasi ini
    ]);

    $wilayah = WilayahPertanian::findOrFail($id);

    $wilayah->update([
        'nama_komoditas' => $request->nama_komoditas,
        'kecamatan_id' => $request->kecamatan_id,
        'warna' => $request->warna,
        'polygon' => $request->polygon,
        'luas_wilayah' => $request->luas_wilayah,
        'jumlah_komoditas' => $request->jumlah_komoditas, // Tambahkan ini
    ]);

    return redirect()->route('wilayah.index')->with('success', 'Data wilayah berhasil diperbarui');
}


public function destroy($id)
{
    $wilayah = WilayahPertanian::findOrFail($id);
    $wilayah->delete();

    return redirect()->route('wilayah.index')->with('success', 'Data wilayah berhasil dihapus');
}


public function home(Request $request)
{
    // 1. Filter Tahun dan Bulan
    $selectedYear = $request->input('year');
    $selectedMonth = $request->input('month');

    // 2. Tahun dan Bulan Tersedia
    $availableYears = WilayahPertanian::select(DB::raw('YEAR(created_at) as year'))
        ->distinct()->orderBy('year', 'desc')->pluck('year');

    $availableMonths = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    // 3. Data Mentah untuk Peta
    $wilayah = WilayahPertanian::with('kecamatan')->get();
    $polygon_kecamatan = Kecamatan::all();
    $polygon_kabupaten = Kabupaten::all();

    // 4. Query Data Terfilter
    $query = WilayahPertanian::join('kecamatans', 'wilayah_pertanian.kecamatan_id', '=', 'kecamatans.kecamatan_id')
        ->select(
            'kecamatans.nama_kecamatan',
            'wilayah_pertanian.nama_komoditas',
            'wilayah_pertanian.warna',
            DB::raw('YEAR(wilayah_pertanian.created_at) as tahun'),
            DB::raw('MONTH(wilayah_pertanian.created_at) as bulan'),
            DB::raw('SUM(wilayah_pertanian.luas_wilayah) as total_luas'),
            DB::raw('SUM(wilayah_pertanian.jumlah_komoditas) as jumlah_komoditas') // ✅ PENTING
        );

    // 5. Filter tahun & bulan
    if ($selectedYear) {
        $query->whereYear('wilayah_pertanian.created_at', $selectedYear);
    }
    if ($selectedMonth) {
        $query->whereMonth('wilayah_pertanian.created_at', $selectedMonth);
    }

    // 6. Group & Ambil Data
    $data = $query->groupBy(
        'kecamatans.nama_kecamatan',
        'wilayah_pertanian.nama_komoditas',
        'wilayah_pertanian.warna',
        DB::raw('YEAR(wilayah_pertanian.created_at)'),
        DB::raw('MONTH(wilayah_pertanian.created_at)')
    )->get();

    $groupedData = $data->groupBy('nama_kecamatan');

    // 7. Data untuk Grafik
    $chartData = [];
    foreach ($groupedData as $kecamatan => $komoditas) {
        $chartData[$kecamatan] = [
            'labels' => $komoditas->pluck('nama_komoditas')->toArray(),
            'luas' => $komoditas->pluck('total_luas')->toArray(),
            'jumlah' => $komoditas->pluck('jumlah_komoditas')->toArray(),
            'backgroundColor' => $komoditas->pluck('warna')->toArray(),
            'borderColor' => $komoditas->pluck('warna')->toArray(),
        ];
    }

    // 8. Statistik Ringkas
    $totalKecamatan = $groupedData->count();
    $totalKomoditas = $data->pluck('nama_komoditas')->unique()->count();
    $totalLuas = $data->sum('total_luas');
    $totalWilayahDipetakan = WilayahPertanian::count();

    // 9. Kirim ke View
    return view('home', compact(
        'wilayah',
        'polygon_kecamatan',
        'polygon_kabupaten',
        'groupedData',
        'chartData',
        'availableYears',
        'availableMonths',
        'selectedYear',
        'selectedMonth',
        'totalKecamatan',
        'totalKomoditas',
        'totalLuas',
        'totalWilayahDipetakan'
    ));
}


   // WilayahController.php
public function getKecamatan($id)
{
    $kecamatan = Kecamatan::find($id);

    if (!$kecamatan) {
        return response()->json(['error' => 'Kecamatan tidak ditemukan'], 404);
    }

    // Pastikan polygon_kecamatan berisi data GeoJSON yang valid
    return response()->json([
        'polygon' => $kecamatan->polygon_kecamatan, // Mengambil data polygon_kecamatan
        'warna' => $kecamatan->warna // Warna kecamatan (jika ada)
    ]);
}

public function getPolygonKabupaten()
{
    $kabupatens = Kabupaten::all();

    $data = $kabupatens->map(function ($kabupaten) {
        return [
            'nama' => $kabupaten->nama_kabupaten,
            'warna' => $kabupaten->warna_kabupaten ?? '#8888ff',
            'polygon' => $kabupaten->polygon_kabupaten,
        ];
    });

    return response()->json($data);
}







    
}
