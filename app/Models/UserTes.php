<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserTes extends Model
{
    use HasUuids;
    protected $table = 'user_tes';
	protected $primaryKey = 'user_tes_id';
    public $guarded = [];
}
