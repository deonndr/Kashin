<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\PembayaranIuran;
use App\Models\Pengeluaran;
use App\Models\Siswa;
use App\Models\PeriodeIuran;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // ─── Saldo & Finansial ───
        $totalMasuk    = PembayaranIuran::sum('jumlah_bayar');
        $totalKeluar   = Pengeluaran::sum('nominal_keluar');
        $saldoBersih   = $totalMasuk - $totalKeluar;

        // ─── Statistik Bulan Ini ───
        $totalSiswa    = Siswa::count();

        // Siswa yang sudah bayar bulan ini (ada record Lunas di bulan berjalan)
        $sudahLunasIds = PembayaranIuran::where('status_bayar', 'Lunas')
            ->whereMonth('tanggal_bayar', $now->month)
            ->whereYear('tanggal_bayar',  $now->year)
            ->pluck('siswa_id')
            ->unique()
            ->count();

        // Siswa belum bayar sama sekali bulan ini
        $sudahBayarIds = PembayaranIuran::whereMonth('tanggal_bayar', $now->month)
            ->whereYear('tanggal_bayar',  $now->year)
            ->pluck('siswa_id')
            ->unique();

        $belumBayarCount = Siswa::whereNotIn('id', $sudahBayarIds)->count();

        // Kegiatan "aktif" bulan ini (dibuat bulan ini)
        $kegiatanAktif = Kegiatan::whereMonth('created_at', $now->month)
            ->whereYear('created_at',  $now->year)
            ->count();

        // ─── Transaksi Terakhir (UNION masuk + keluar) ───
        $transaksiMasuk = PembayaranIuran::with('siswa')
            ->latest()          // sort by created_at DESC (jam realtime dicatat)
            ->take(8)
            ->get()
            ->map(fn($p) => [
                'type'    => 'in',
                'label'   => $p->siswa->nama_siswa ?? 'Siswa',
                'sub'     => 'Iuran ' . Carbon::parse($p->created_at)->translatedFormat('d M, H.i'),
                'amount'  => $p->jumlah_bayar,
                'date'    => $p->created_at,
            ]);

        $transaksiKeluar = Pengeluaran::with('kegiatan')
            ->latest()          // sort by created_at DESC (jam realtime dicatat)
            ->take(8)
            ->get()
            ->map(fn($p) => [
                'type'    => 'out',
                'label'   => $p->nama_pengeluaran,
                'sub'     => ($p->kegiatan->nama_kegiatan ?? 'Kegiatan') . ' · ' . Carbon::parse($p->created_at)->translatedFormat('d M, H.i'),
                'amount'  => $p->nominal_keluar,
                'date'    => $p->created_at,
            ]);

        $transaksiTerakhir = $transaksiMasuk->concat($transaksiKeluar)
            ->sortByDesc('date')
            ->take(5)
            ->values();

        // ─── Kegiatan & Progress Dana ───
        $kegiatanList = Kegiatan::with('pengeluaran')
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($k) {
                $realisasi = $k->pengeluaran->sum('nominal_keluar');
                $pct = $k->estimasi_biaya > 0
                    ? min(100, round($realisasi / $k->estimasi_biaya * 100))
                    : 0;
                return [
                    'nama'       => $k->nama_kegiatan,
                    'pct'        => $pct,
                    'realisasi'  => $realisasi,
                    'estimasi'   => $k->estimasi_biaya,
                ];
            });

        // ─── Daftar Belum Lunas Bulan Ini ───
        $belumLunasList = Siswa::whereNotIn('id', $sudahBayarIds)->get();

        // Warna avatar
        $avatarColors = [
            '#4f8ef7','#7c5cfc','#22c55e','#f59e0b','#ef4444',
            '#06b6d4','#ec4899','#8b5cf6','#10b981','#f97316',
        ];

        return view('dashboard', compact(
            'saldoBersih', 'totalMasuk', 'totalKeluar',
            'sudahLunasIds', 'totalSiswa', 'belumBayarCount',
            'kegiatanAktif', 'transaksiTerakhir',
            'kegiatanList', 'belumLunasList', 'avatarColors', 'now'
        ));
    }
}
