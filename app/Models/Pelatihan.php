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
    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'table_id', 'pelatihan_id');
    }
}
