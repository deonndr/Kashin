<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = "siswa";
    protected $fillable = ["nisn","nama_siswa","kelas"];

    public function pembayaranIurans() {
        return $this->hasMany(PembayaranIuran::class);
    }
}
