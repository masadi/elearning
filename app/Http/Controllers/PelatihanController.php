<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\Hadir;
use App\Models\DokumenTugas;
use App\Models\TesFormatif;
use App\Models\UserTes;
use App\Models\UserJawaban;

class PelatihanController extends Controller
{
    public function index(){
        $data = Pelatihan::with(['sesi' => function($query){
            $query->with([
                'materi.dokumen', 
                'tugas.dokumen', 
                'tes',
                'user_tes' => function($query){
                    $query->where('user_id', auth()->user()->id);
                },
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
    public function get_soal(){
        $soal = TesFormatif::with(['jawaban', 'user_jawaban' => function($query){
            $query->where('user_id', auth()->user()->id);
        }])->find(request()->tes_id);
        if(request()->jawaban_id){
            UserJawaban::updateOrCreate(
                [
                    'tes_id' => request()->tes_id_jawaban,
                    'user_id' => auth()->user()->id,
                ],
                [
                    'jawaban_id' => request()->jawaban_id,
                ]
            );
        } else {
            UserTes::firstOrCreate([
                'sesi_latihan_id' => $soal->sesi_latihan_id,
                'user_id' => auth()->user()->id,
            ]);
        }
        $data = [
            'soal' => $soal,
            'jml_soal' => TesFormatif::where('sesi_latihan_id', request()->sesi_latihan_id)->count(),
        ];
        return response()->json($data);
    }
    public function tes_selesai(){
        $data = UserTes::where('sesi_latihan_id', request()->sesi_latihan_id)->update(['status' => 1]);
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
