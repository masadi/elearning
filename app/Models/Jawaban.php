<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Jawaban extends Model
{
    use HasUuids;
    protected $table = 'jawaban';
	protected $primaryKey = 'jawaban_id';
    public $guarded = [];
    protected $appends = ['is_checked'];
    protected $hidden = [
        'benar',
    ];
    protected function isChecked(): Attribute
    {
        return new Attribute(
            get: fn () => FALSE,
        );
    }
}
