<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\Ptk;
use App\Models\MataPelajaran;
use App\Models\RombonganBelajar;
use App\Models\MateriAjar;

class TableController extends Controller
{
    public function index(){
        $function = 'get_'.str_replace('-', '_', request()->data);
        try {
            return $this->{$function}();
        } catch (\Throwable $th) {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => $th->getMessage(),
            ];
            return response()->json($data);
        }
    }
    private function wherePtk(){
        return function($query){
            $query->whereHas('sekolah', function($query){
                $query->where('user_id', auth()->user()->id);
            });
        };
    }
    public function get_ptk(){
        $data = [
            'lists' => Ptk::where($this->wherePtk())->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('nama', 'LIKE', '%' . request()->q . '%');
                $query->where($this->wherePtk());
                $query->orWhere('email', 'LIKE', '%' . request()->q . '%');
                $query->where($this->wherePtk());
            })->paginate(request()->per_page),
            'sekolah' => Sekolah::where('user_id', auth()->user()->id)->first(),
        ];
        return response()->json($data);
    }
    public function get_mapel(){
        $data = [
            'lists' => MataPelajaran::orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('nama', 'LIKE', '%' . request()->q . '%');
                $query->orWhere('alias', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
        ];
        return response()->json($data);
    }
    public function get_rombel(){
        $data = [
            'lists' => RombonganBelajar::with('walas')->where($this->wherePtk())->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('nama', 'LIKE', '%' . request()->q . '%');
                $query->where($this->wherePtk());
            })->paginate(request()->per_page),
            'sekolah' => Sekolah::where('user_id', auth()->user()->id)->first(),
        ];
        return response()->json($data);
    }
    public function get_materi_ajar(){
        $data = [
            'lists' => MateriAjar::withWhereHas('pembelajaran', function($query){
                $query->withWhereHas('rombongan_belajar', function($query){
                    $query->where($this->wherePtk());
                });
            })->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('judul', 'LIKE', '%' . request()->q . '%');
                $query->whereHas('pembelajaran', function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where($this->wherePtk());
                    });
                });
            })->paginate(request()->per_page),
        ];
        return response()->json($data);
    }
}
