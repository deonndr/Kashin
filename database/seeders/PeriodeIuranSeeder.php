<?php

namespace Database\Seeders;
use App\Models\PeriodeIuran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodeIuranSeeder extends Seeder
{
    public function run(): void
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        foreach ($months as $num => $month) {
            PeriodeIuran::create([
                'nama_periode' => "$month 2026",
                'nominal_tagihan' => 50000,
                'bulan' => $num,
                'tahun' => 2026,
            ]);
        }
    }
}
