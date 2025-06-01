<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Dokumen extends Model
{
    use HasUuids;
    protected $table = 'dokumen';
	protected $primaryKey = 'dokumen_id';
    public $guarded = [];
}
