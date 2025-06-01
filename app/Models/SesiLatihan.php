<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SesiLatihan extends Model
{
    use HasUuids;
    protected $table = 'sesi_latihan';
	protected $primaryKey = 'sesi_latihan_id';
    public $guarded = [];
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id', 'pelatihan_id');
    }
    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'table_id', 'sesi_latihan_id');
    }
    public function materi()
    {
        return $this->hasMany(MateriSesi::class, 'sesi_latihan_id', 'sesi_latihan_id');
    }
}
