<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndukRombonganBelajar extends Model
{
    use HasUuids;
    protected $table = 'induk.rombongan_belajar';
    public $guarded = [];
}
