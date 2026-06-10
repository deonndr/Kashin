<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\PembayaranIuran;
use App\Models\Pengeluaran;
use App\Models\PeriodeIuran;
use App\Models\Siswa;

class LaporanController extends Controller
{
    public function index()
    {
        $totalMasuk  = PembayaranIuran::sum('jumlah_bayar');
        $totalKeluar = Pengeluaran::sum('nominal_keluar');
        $saldo       = $totalMasuk - $totalKeluar;

        // Rekap per periode
        $periodes = PeriodeIuran::with(['pembayaranIurans.siswa'])
            ->orderBy('nama_periode')
            ->get()
            ->map(function ($p) {
                $lunas     = $p->pembayaranIurans->where('status_bayar', 'Lunas')->count();
                $belum     = $p->pembayaranIurans->where('status_bayar', 'Belum Lunas')->count();
                $total     = $p->pembayaranIurans->sum('jumlah_bayar');
                return [
                    'nama'     => $p->nama_periode,
                    'nominal'  => $p->nominal_tagihan,
                    'lunas'    => $lunas,
                    'belum'    => $belum,
                    'total'    => $total,
                ];
            });

        // Rekap kegiatan
        $kegiatans = Kegiatan::with('pengeluaran')->get()->map(function ($k) {
            $realisasi = $k->pengeluaran->sum('nominal_keluar');
            return [
                'nama'      => $k->nama_kegiatan,
                'estimasi'  => $k->estimasi_biaya,
                'realisasi' => $realisasi,
                'sisa'      => $k->estimasi_biaya - $realisasi,
            ];
        });

        return view('laporan.index', compact('totalMasuk', 'totalKeluar', 'saldo', 'periodes', 'kegiatans'));
    }

    public function kegiatanPublic()
    {
        $kegiatans = Kegiatan::with('pengeluaran')->latest()->get()->map(function ($k) {
            $realisasi = $k->pengeluaran->sum('nominal_keluar');
            return [
                'nama'       => $k->nama_kegiatan,
                'estimasi'   => $k->estimasi_biaya,
                'realisasi'  => $realisasi,
                'pengeluaran'=> $k->pengeluaran,
            ];
        });

        return view('laporan.kegiatan-public', compact('kegiatans'));
    }
}
