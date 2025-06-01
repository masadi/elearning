<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\Hadir;

class PelatihanController extends Controller
{
    public function index(){
        $data = Pelatihan::with(['dokumen', 'sesi' => function($query){
            $query->with(['materi.dokumen', 'dokumen', 'hadir' => function($query){
                $query->where('user_id', auth()->user()->id);
            }]);
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
}
