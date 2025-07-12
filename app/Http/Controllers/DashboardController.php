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
   public function index(Request $request) // Tambahkan Request $request
    {
        // Ambil tahun dan bulan dari request, atau gunakan default
        // Jika tidak ada filter, gunakan semua tahun dan bulan yang tersedia
        $selectedYear = $request->input('year');
        $selectedMonth = $request->input('month');
        $wilayah = WilayahPertanian::with('kecamatan')->get();
        $polygon_kecamatan = Kecamatan::all();
        $polygon_kabupaten = Kabupaten::all(); // Ambil data kabupaten
        // Dapatkan semua tahun unik dari data WilayahPertanian
        $availableYears = WilayahPertanian::select(DB::raw('YEAR(created_at) as year'))
                                        ->distinct()
                                        ->orderBy('year', 'desc')
                                        ->pluck('year');

        // Daftar bulan untuk dropdown
        $availableMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Mulai query
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

        // Tambahkan kondisi filter jika tahun dan bulan dipilih
        if ($selectedYear) {
            $query->whereYear('wilayah_pertanian.created_at', $selectedYear);
        }
        if ($selectedMonth) {
            $query->whereMonth('wilayah_pertanian.created_at', $selectedMonth);
        }

        // Lanjutkan grouping
        $data = $query->groupBy(
                'kecamatans.nama_kecamatan',
                'wilayah_pertanian.nama_komoditas',
                'wilayah_pertanian.warna',
                DB::raw('YEAR(wilayah_pertanian.created_at)'),
                DB::raw('MONTH(wilayah_pertanian.created_at)')
            )
            ->orderBy('kecamatans.nama_kecamatan', 'asc')
            ->get();

        // Kelompokkan berdasarkan kecamatan untuk tabel
        $groupedData = $data->groupBy('nama_kecamatan');

        // Siapkan data untuk grafik per kecamatan (difilter berdasarkan selectedYear dan selectedMonth)
        $chartData = [];
        foreach ($groupedData as $kecamatan => $komoditas) {
            $chartData[$kecamatan] = [
                'labels' => $komoditas->pluck('nama_komoditas')->toArray(),
                'data' => $komoditas->pluck('total_luas')->toArray(),
                'backgroundColor' => $komoditas->pluck('warna')->toArray(),
                'borderColor' => $komoditas->pluck('warna')->toArray(),
            ];
        }

        return view('admin.dashboard', compact('wilayah', 'polygon_kecamatan', 'polygon_kabupaten','groupedData', 'availableYears', 'availableMonths', 'selectedYear', 'selectedMonth', 'chartData'));
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
