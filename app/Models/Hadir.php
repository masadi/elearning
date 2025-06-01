<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Hadir extends Model
{
    use HasUuids;
    protected $table = 'hadir';
	protected $primaryKey = 'hadir_id';
    public $guarded = [];
}
