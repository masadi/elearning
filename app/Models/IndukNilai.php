<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndukNilai extends Model
{
    use HasUuids;
    protected $table = 'induk.nilai';
	public $guarded = [];
    public function pd(){
        return $this->hasOneThrough(
            IndukPesertaDidik::class,
            IndukAnggotaRombel::class,
            'id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'anggota_rombel_id', // Local key on the applications table...
            'peserta_didik_id' // Local key on the environments table...
        );
    }
}
