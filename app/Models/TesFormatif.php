<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TesFormatif extends Model
{
    use HasUuids;
    protected $table = 'tes_formatif';
	protected $primaryKey = 'tes_id';
    public $guarded = [];
    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
    }
    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'tes_id', 'tes_id');
    }
    public function kunci()
    {
        return $this->hasOne(Jawaban::class, 'tes_id', 'tes_id')->where('benar', 1);
    }
    public function user_jawaban()
    {
        return $this->hasOne(UserJawaban::class, 'tes_id', 'tes_id');
    }
}
