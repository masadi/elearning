<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pelatihan extends Model
{
    use HasUuids;
    protected $table = 'pelatihan';
	protected $primaryKey = 'pelatihan_id';
    public $guarded = [];
    
    public function sesi()
    {
        return $this->hasMany(SesiLatihan::class, 'pelatihan_id', 'pelatihan_id')->orderBy('urut');
    }
    public function materi(){
        return $this->hasManyThrough(
            MateriSesi::class,
            SesiLatihan::class,
            'pelatihan_id', // Foreign key on the environments table...
            'sesi_latihan_id', // Foreign key on the deployments table...
            'pelatihan_id', // Local key on the projects table...
            'sesi_latihan_id' // Local key on the environments table...
        );
    }
    public function tugas(){
        return $this->hasManyThrough(
            TugasSesi::class,
            SesiLatihan::class,
            'pelatihan_id', // Foreign key on the environments table...
            'sesi_latihan_id', // Foreign key on the deployments table...
            'pelatihan_id', // Local key on the projects table...
            'sesi_latihan_id' // Local key on the environments table...
        );
    }
    public function tes(){
        return $this->hasManyThrough(
            TesFormatif::class,
            SesiLatihan::class,
            'pelatihan_id', // Foreign key on the environments table...
            'sesi_latihan_id', // Foreign key on the deployments table...
            'pelatihan_id', // Local key on the projects table...
            'sesi_latihan_id' // Local key on the environments table...
        );
    }
    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'table_id', 'pelatihan_id')->orderBy('created_at');
    }
}
