<?php

namespace Database\Seeders;
use App\Models\PeriodeIuran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodeIuranSeeder extends Seeder
{
    public function run(): void
    {
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        foreach ($months as $month) {
            PeriodeIuran::create([
                'nama_periode' => "$month 2026",
                'nominal_tagihan' => 5000,
            ]);
        }
    }
}
