<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndukRombonganBelajar extends Model
{
    use HasUuids;
    protected $table = 'induk.rombongan_belajar';
    public $guarded = [];
    public function anggota_rombel()
    {
        return $this->hasMany(IndukAnggotaRombel::class, 'rombongan_belajar_id', 'id');
    }
    public function pembelajaran()
    {
        return $this->hasMany(IndukPembelajaran::class, 'rombongan_belajar_id', 'id');
    }
}
