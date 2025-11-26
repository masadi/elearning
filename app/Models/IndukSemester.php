<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class IndukSemester extends Model
{
    public $incrementing = false;
	//public $keyType = 'string';
    protected $table = 'induk.semester';
	protected $primaryKey = 'semester_id';
    public $guarded = [];
    protected $appends = ['tahun_ajaran'];
    protected function tahunAjaran(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['tahun_ajaran_id'].'/'.$attributes['tahun_ajaran_id']+1,
        );
    }
}
