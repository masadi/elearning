<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Carbon\Carbon;

class Program extends Model
{
    use HasSlug;
    public $guarded = [];
    protected $appends = ['tanggal_indo', 'tanggal', 'bulan'];
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nama')
            ->saveSlugsTo('slug');
    }
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'sekolah_id');
    }
    protected function tanggalIndo(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['created_at']) ? Carbon::parse($this->attributes['created_at'])->translatedFormat('d F Y') : NULL,
        );
    }
    protected function tanggal(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['created_at']) ? Carbon::parse($this->attributes['created_at'])->format('d') : NULL,
        );
    }
    protected function bulan(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['created_at']) ? Carbon::parse($this->attributes['created_at'])->translatedFormat('F') : NULL,
        );
    }
}
