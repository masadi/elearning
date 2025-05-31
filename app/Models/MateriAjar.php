<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MateriAjar extends Model
{
    use HasUuids;
    //public $incrementing = false;
	//public $keyType = 'string';
    protected $table = 'materi_ajar';
	protected $primaryKey = 'materi_ajar_id';
    public $guarded = [];
    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
    }
}
