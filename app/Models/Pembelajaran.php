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
    
    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
    public function tes()
    {
        return $this->hasMany(TesFormatif::class, 'pembelajaran_id', 'pembelajaran_id');
    }
}
