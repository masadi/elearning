<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndukMataPelajaran extends Model
{
    protected $table = 'induk.mata_pelajaran';
	public $guarded = [];
    public function pembelajaran()
    {
        return $this->hasOne(IndukPembelajaran::class, 'mata_pelajaran_id');
    }
}
