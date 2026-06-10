<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tambah unique constraint pada (siswa_id, periode_iuran_id)
     * agar satu siswa tidak bisa memiliki 2 record untuk periode yang sama.
     */
    public function up(): void
    {
        Schema::table('pembayaran_iuran', function (Blueprint $table) {
            $table->unique(['siswa_id', 'periode_iuran_id'], 'unique_siswa_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_iuran', function (Blueprint $table) {
            $table->dropUnique('unique_siswa_periode');
        });
    }
};
