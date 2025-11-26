<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndukPembelajaran extends Model
{
    use HasUuids;
    protected $table = 'induk.pembelajaran';
    public $guarded = [];
    public function mata_pelajaran()
    {
        return $this->belongsTo(IndukMataPelajaran::class, 'mata_pelajaran_id');
    }
    public function rombongan_belajar()
    {
        return $this->belongsTo(IndukRombonganBelajar::class, 'rombongan_belajar_id');
    }
    public function nilai()
    {
        return $this->hasOne(IndukNilai::class, 'pembelajaran_id');
    }
    public function kelompok()
    {
        return $this->belongsTo(IndukKelompok::class, 'kelompok_id');
    }
}
