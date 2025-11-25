<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndukSemester extends Model
{
    public $incrementing = false;
	//public $keyType = 'string';
    protected $table = 'induk.semester';
	protected $primaryKey = 'semester_id';
    public $guarded = [];
}
