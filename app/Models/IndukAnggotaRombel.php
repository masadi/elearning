<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndukAnggotaRombel extends Model
{
    use HasUuids;
    protected $table = 'induk.anggota_rombel';
	public $guarded = [];
    public function rombongan_belajar()
    {
        return $this->belongsTo(IndukRombonganBelajar::class, 'rombongan_belajar_id');
    }
    public function nilai()
    {
        return $this->hasOne(IndukNilai::class, 'anggota_rombel_id');
    }
}
