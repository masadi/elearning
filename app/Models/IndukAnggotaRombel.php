<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndukAnggotaRombel extends Model
{
    use HasUuids;
    protected $table = 'induk.anggota_rombel';
	public $guarded = [];
}
