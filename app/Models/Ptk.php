<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ptk extends Model
{
    use HasUuids;
    //public $incrementing = false;
	//public $keyType = 'string';
    protected $table = 'ptk';
	protected $primaryKey = 'ptk_id';
    public $guarded = [];
    
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'sekolah_id');
    }
}
