<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndukNilai extends Model
{
    use HasUuids;
    protected $table = 'induk.nilai';
	public $guarded = [];
}
