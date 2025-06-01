<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatihan;

class PelatihanController extends Controller
{
    public function index(){
        $data = Pelatihan::with(['dokumen', 'sesi' => function($query){
            $query->with(['materi.dokumen', 'dokumen']);
        }])->find(request()->id);
        return response()->json($data);
    }
}
