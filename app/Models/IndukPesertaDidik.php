<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndukPesertaDidik extends Model
{
    use HasUuids;
    protected $table = 'induk.peserta_didik';
    public $guarded = [];
    public function anggota_rombel()
    {
        return $this->hasOne(IndukAnggotaRombel::class, 'peserta_didik_id');
    }
}
