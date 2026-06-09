<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = "kegiatan";
    protected $fillable = ["nama_kegiatan", "estimasi_biaya"];

    public function pengeluaran() {
        return $this->hasMany(Pengeluaran::class);
    }
}
