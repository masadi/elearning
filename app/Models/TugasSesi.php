<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TugasSesi extends Model
{
    use HasUuids;
    protected $table = 'tugas_sesi';
	protected $primaryKey = 'tugas_sesi_id';
    public $guarded = [];
    public function sesi()
    {
        return $this->belongsTo(SesiLatihan::class, 'sesi_latihan_id', 'sesi_latihan_id')->orderBy('created_at');
    }
    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'table_id', 'tugas_sesi_id')->orderBy('created_at');
    }
}
