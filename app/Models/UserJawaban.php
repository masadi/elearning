<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserJawaban extends Model
{
    use HasUuids;
    protected $table = 'user_jawaban';
	protected $primaryKey = 'user_jawaban_id';
    public $guarded = [];
}
