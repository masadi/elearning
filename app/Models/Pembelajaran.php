<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pembelajaran extends Model
{
    use HasUuids;
    //public $incrementing = false;
	//public $keyType = 'string';
    protected $table = 'pembelajaran';
	protected $primaryKey = 'pembelajaran_id';
    public $guarded = [];
    
    public function rombongan_belajar()
    {
        return $this->belongsTo(RombonganBelajar::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
    }
    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id', 'ptk_id');
    }
}
