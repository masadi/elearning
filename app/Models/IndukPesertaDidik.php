<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class IndukPesertaDidik extends Model
{
    use HasUuids;
    protected $table = 'induk.peserta_didik';
    public $guarded = [];
    protected $appends = ['tempat_tanggal_lahir', 'tanggal_lahir_indo', 'jenis_kelamin_str', 'diterima_indo'];
    
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'sekolah_id');
    }
    public function agama()
    {
        return $this->belongsTo(IndukAgama::class, 'agama_id');
    }
    public function anggota_rombel()
    {
        return $this->hasOne(IndukAnggotaRombel::class, 'peserta_didik_id');
    }
    public function anggota()
    {
        return $this->hasManyThrough(
            IndukRombonganBelajar::class,
            IndukAnggotaRombel::class,
            'peserta_didik_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the applications table...
            'rombongan_belajar_id' // Local key on the environments table...
        );
        return $this->hasMany(IndukAnggotaRombel::class, 'peserta_didik_id');
    }
    protected function tempatTanggalLahir(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['tempat_lahir']) ? strtoupper($attributes['tempat_lahir']).', '.Carbon::parse($this->attributes['tanggal_lahir'])->translatedFormat('d F Y') : NULL,
        );
    }
    protected function tanggalLahirIndo(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['tanggal_lahir']) ? Carbon::parse($this->attributes['tanggal_lahir'])->translatedFormat('d F Y') : NULL,
        );
    }
    protected function jenisKelaminStr(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['jenis_kelamin']) ? ($attributes['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan' : NULL,
        );
    }
    protected function diterimaIndo(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['diterima']) ? Carbon::parse($this->attributes['diterima'])->translatedFormat('d F Y') : NULL,
        );
    }
    public function pekerjaan_ayah()
    {
        return $this->belongsTo(IndukPekerjaan::class, 'kerja_ayah');
    }
    public function pekerjaan_ibu()
    {
        return $this->belongsTo(IndukPekerjaan::class, 'kerja_ibu');
    }
    public function pekerjaan_wali()
    {
        return $this->belongsTo(IndukPekerjaan::class, 'kerja_wali');
    }
    public function agama_a()
    {
        return $this->belongsTo(IndukAgama::class, 'agama_ayah');
    }
    public function agama_i()
    {
        return $this->belongsTo(IndukAgama::class, 'agama_ibu');
    }
    public function agama_w()
    {
        return $this->belongsTo(IndukAgama::class, 'agama_wali');
    }
}
