<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tambah kolom bulan (1-12) dan tahun agar periode bisa diurutkan kronologis,
     * bukan hanya secara alfabetis dari nama_periode (string).
     */
    public function up(): void
    {
        Schema::table('periode_iuran', function (Blueprint $table) {
            $table->unsignedTinyInteger('bulan')->nullable()->after('nama_periode')
                  ->comment('1=Januari, 12=Desember');
            $table->unsignedSmallInteger('tahun')->nullable()->after('bulan');
        });

        // Isi nilai bulan & tahun dari nama_periode yang sudah ada (format: "Januari 2026" dll.)
        $bulanMap = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
        ];

        $periodes = DB::table('periode_iuran')->get();
        foreach ($periodes as $p) {
            $parts = explode(' ', strtolower(trim($p->nama_periode)));
            $bulan = null;
            $tahun = null;

            foreach ($parts as $part) {
                if (isset($bulanMap[$part])) {
                    $bulan = $bulanMap[$part];
                } elseif (is_numeric($part) && strlen($part) === 4) {
                    $tahun = (int) $part;
                }
            }

            DB::table('periode_iuran')->where('id', $p->id)->update([
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_iuran', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
        });
    }
};
