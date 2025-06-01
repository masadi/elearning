<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatihan;

class PelatihanController extends Controller
{
    public function index(){
        $data = Pelatihan::with(['sesi'])->find(request()->id);
        return response()->json($data);
    }
}
