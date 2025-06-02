<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\Hadir;
use App\Models\DokumenTugas;

class PelatihanController extends Controller
{
    public function index(){
        $data = Pelatihan::with(['sesi' => function($query){
            $query->with([
                'materi.dokumen', 
                'tugas.dokumen', 
                'hadir' => function($query){
                    $query->where('user_id', auth()->user()->id);
                },
                'dokumen_tugas' => function($query){
                    $query->where('user_id', auth()->user()->id);
                }
            ]);
        }])->find(request()->id);
        return response()->json($data);
    }
    public function absen(){
        Hadir::updateOrCreate(
            [
                'sesi_latihan_id' => request()->sesi_latihan_id,
                'user_id' => auth()->user()->id,
            ],
            [
                'hadir' => (request()->hadir) ? 1 : 0,
            ]
        );
    }
    public function unggah(){
        if(request()->file_tugas){
            $nama = request()->file_tugas->getClientOriginalName();
            $path = request()->file_tugas->store('berkas', 'public');
            DokumenTugas::create([
                'nama' => $nama,
                'sesi_latihan_id' => request()->sesi_latihan_id,
                'user_id' => auth()->user()->id,
                'extension' => request()->file_tugas->extension(),
                'path' => basename($path),
            ]);
        }
    }
}
