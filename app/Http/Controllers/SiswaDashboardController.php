<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            $bendahara = \App\Models\User::where('role', 'bendahara')->first();
            return view('dashboard-siswa', [
                'siswa'         => null,
                'statusBulanIni'=> null,
                'riwayatBayar'  => collect(),
                'bendaharaEmail'=> $bendahara ? $bendahara->email : 'bendahara@kashin.com',
            ]);
        }

        $now  = Carbon::now();

        // Status iuran bulan ini
        $statusBulanIni = $siswa->pembayaranIurans()
            ->whereMonth('tanggal_bayar', $now->month)
            ->whereYear('tanggal_bayar',  $now->year)
            ->first();

        // Riwayat semua pembayaran
        $riwayatBayar = $siswa->pembayaranIurans()
            ->with('periodeIuran')
            ->latest('tanggal_bayar')
            ->get();

        return view('dashboard-siswa', compact('siswa', 'statusBulanIni', 'riwayatBayar'));
    }
}
