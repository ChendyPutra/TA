<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\WilayahPertanian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class DashboardController extends Controller
{
   public function index(Request $request)
{
    $admin = auth('admin')->user(); // ✅ Ambil admin yang login

    // Filter tahun & bulan
    $selectedYear = $request->input('year');
    $selectedMonth = $request->input('month');

    // Polygon tetap semua
    $polygon_kecamatan = Kecamatan::all();
    $polygon_kabupaten = Kabupaten::all();

    // 🔥 Filter data wilayah sesuai role
    $wilayahQuery = WilayahPertanian::with('kecamatan');

    if ($admin->role !== 'superadmin') {
        $wilayahQuery->where('bidang_id', $admin->bidang_id);
    }

    $wilayah = $wilayahQuery->get();

    // Tahun yang tersedia
    $availableYears = WilayahPertanian::select(DB::raw('YEAR(created_at) as year'))
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');

    $availableMonths = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    // 🔥 Query grafik dan rekap
    $query = WilayahPertanian::join('kecamatans', 'wilayah_pertanian.kecamatan_id', '=', 'kecamatans.kecamatan_id')
        ->select(
            'kecamatans.nama_kecamatan',
            'wilayah_pertanian.nama_komoditas',
            'wilayah_pertanian.warna',
            DB::raw('YEAR(wilayah_pertanian.created_at) as tahun'),
            DB::raw('MONTH(wilayah_pertanian.created_at) as bulan'),
            DB::raw('SUM(wilayah_pertanian.luas_wilayah) as total_luas'),
            DB::raw('SUM(wilayah_pertanian.jumlah_komoditas) as jumlah_komoditas')
        );

    // Filter sesuai bidang jika bukan superadmin
    if ($admin->role !== 'superadmin') {
        $query->where('wilayah_pertanian.bidang_id', $admin->bidang_id);
    }

    // Filter tahun & bulan jika dipilih
    if ($selectedYear) {
        $query->whereYear('wilayah_pertanian.created_at', $selectedYear);
    }

    if ($selectedMonth) {
        $query->whereMonth('wilayah_pertanian.created_at', $selectedMonth);
    }

    $data = $query->groupBy(
            'kecamatans.nama_kecamatan',
            'wilayah_pertanian.nama_komoditas',
            'wilayah_pertanian.warna',
            DB::raw('YEAR(wilayah_pertanian.created_at)'),
            DB::raw('MONTH(wilayah_pertanian.created_at)')
        )
        ->orderBy('kecamatans.nama_kecamatan', 'asc')
        ->get();

    // Group data untuk tabel dan grafik
    $groupedData = $data->groupBy('nama_kecamatan');

    $chartData = [];
    foreach ($groupedData as $kecamatan => $komoditas) {
        $chartData[$kecamatan] = [
            'labels' => $komoditas->pluck('nama_komoditas')->toArray(),
            'data' => $komoditas->pluck('total_luas')->toArray(),
            'backgroundColor' => $komoditas->pluck('warna')->toArray(),
            'borderColor' => $komoditas->pluck('warna')->toArray(),
        ];
    }

    return view('admin.dashboard', compact(
        'wilayah',
        'polygon_kecamatan',
        'polygon_kabupaten',
        'groupedData',
        'availableYears',
        'availableMonths',
        'selectedYear',
        'selectedMonth',
        'chartData'
    ));
}
    public function map()
{
    $wilayah = WilayahPertanian::with('kecamatan')->get();
    $polygon_kecamatan = Kecamatan::all();
    $polygon_kabupaten = Kabupaten::all(); // Ambil data kabupaten
    return view('admin.dashboard', [
        'wilayah' => $wilayah,
        'polygon_kecamatan' => $polygon_kecamatan,
        'polygon_kabupaten' => $polygon_kabupaten, // Kirim data kabupaten ke view
    ]);
}

    
}
