<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndukTahunAjaran extends Model
{
    public $incrementing = false;
	public $keyType = 'string';
    protected $table = 'induk.tahun_ajaran';
	protected $primaryKey = 'tahun_ajaran_id';
    public $guarded = [];
}
