<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DokumenTugas extends Model
{
    use HasUuids;
    protected $table = 'dokumen_tugas';
	protected $primaryKey = 'dokumen_tugas_id';
    public $guarded = [];
}
