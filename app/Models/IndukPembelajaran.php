<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndukPembelajaran extends Model
{
    use HasUuids;
    protected $table = 'induk.pembelajaran';
    public $guarded = [];
    public function mata_pelajaran()
    {
        return $this->belongsTo(IndukMataPelajaran::class, 'mata_pelajaran_id');
    }
}
