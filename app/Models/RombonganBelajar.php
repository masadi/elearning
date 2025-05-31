<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RombonganBelajar extends Model
{
    use HasUuids;
    //public $incrementing = false;
	//public $keyType = 'string';
    protected $table = 'rombongan_belajar';
	protected $primaryKey = 'rombongan_belajar_id';
    public $guarded = [];
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'sekolah_id');
    }
    public function walas()
    {
        return $this->hasOne(Ptk::class, 'ptk_id', 'ptk_id');
    }
}
