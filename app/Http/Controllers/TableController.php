<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\Ptk;
use App\Models\MataPelajaran;
use App\Models\RombonganBelajar;
use App\Models\MateriAjar;
use App\Models\Pelatihan;
use App\Models\SesiLatihan;
use App\Models\MateriSesi;
use App\Models\TugasSesi;
use App\Models\TesFormatif;
use App\Models\Page;
use App\Models\Galeri;
use App\Models\Program;
use App\Models\Slide;
use App\Models\Pembelajaran;

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
    public function get_sekolah(){
        $data = [
            'lists' => Sekolah::orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('nama', 'LIKE', '%' . request()->q . '%');
                $query->orWhere('npsn', 'LIKE', '%' . request()->q . '%');
                $query->orWhere('alamat', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
        ];
        return response()->json($data);
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
    public function get_pelatihan(){
        $data = [
            'lists' => Pelatihan::withCount(['sesi', 'materi', 'tugas', 'tes'])->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('judul', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
        ];
        return response()->json($data);
    }
    public function get_sesi(){
        $data = [
            'lists' => SesiLatihan::withCount(['dokumen', 'materi', 'tugas', 'tes'])->withWhereHas('pelatihan', function($query){
                $query->where('pelatihan_id', request()->pelatihan_id);
            })->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('judul', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'data' => Pelatihan::find(request()->pelatihan_id),
        ];
        return response()->json($data);
    }
    public function get_materi_sesi(){
        $data = [
            'lists' => MateriSesi::withCount(['dokumen'])->withWhereHas('sesi', function($query){
                $query->where('sesi_latihan_id', request()->sesi_latihan_id);
            })->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('judul', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'data' => SesiLatihan::find(request()->sesi_latihan_id),
        ];
        return response()->json($data);
    }
    public function get_tugas_sesi(){
        $data = [
            'lists' => TugasSesi::withCount(['dokumen'])->withWhereHas('sesi', function($query){
                $query->where('sesi_latihan_id', request()->sesi_latihan_id);
            })->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('judul', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'data' => SesiLatihan::find(request()->sesi_latihan_id),
        ];
        return response()->json($data);
    }
    public function get_tes_sesi(){
        $data = [
            'lists' => TesFormatif::with(['kunci'])->withCount(['jawaban'])->withWhereHas('sesi', function($query){
                $query->where('sesi_latihan_id', request()->sesi_latihan_id);
            })->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('judul', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'data' => SesiLatihan::find(request()->sesi_latihan_id),
        ];
        return response()->json($data);
    }
    public function get_tentang(){
        $user = auth()->user();
        if($user->sekolah_id) {
            $data = Page::whereType('tentang')->with('sekolah')->where('sekolah_id', $user->sekolah_id)->first();
        } else {
            $data = [
                'lists' => Page::whereType('tentang')->with('sekolah')->orderBy(request()->sortBy, request()->orderBy)
                ->when(request()->q, function($query) {
                    $query->where('content', 'LIKE', '%' . request()->q . '%');
                })->paginate(request()->per_page),
                'sekolah' => Sekolah::all(),
            ];
        }
        return response()->json($data);
    }
    public function get_galeri(){
        $data = [
            'lists' => Galeri::where(function($query){
                if(auth()->user()->sekolah_id){
                    $query->where('sekolah_id', auth()->user()->sekolah_id);
                }
            })->with('sekolah')->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('nama', 'LIKE', '%' . request()->q . '%');
                $query->orWhere('lokasi', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'sekolah' => Sekolah::all(),
            'sekolah_id' => auth()->user()->sekolah_id,
        ];
        return response()->json($data);
    }
    public function get_program(){
        $data = [
            'lists' => Program::with('sekolah')->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('nama', 'LIKE', '%' . request()->q . '%');
                $query->orWhere('deskripsi', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'sekolah' => Sekolah::all(),
            'sekolah_id' => auth()->user()->sekolah_id,
        ];
        return response()->json($data);
    }
    public function get_kontak(){
        $user = auth()->user();
        if($user->sekolah_id) {
            $data = Page::whereType('kontak')->with('sekolah')->where('sekolah_id', $user->sekolah_id)->first();
        } else {
            $data = [
                'lists' => Page::whereType('kontak')->with('sekolah')->orderBy(request()->sortBy, request()->orderBy)
                ->when(request()->q, function($query) {
                    $query->where('content', 'LIKE', '%' . request()->q . '%');
                })->paginate(request()->per_page),
                'sekolah' => Sekolah::all(),
            ];
        }
        return response()->json($data);
    }
    public function get_slide(){
        $data = [
            'lists' => Slide::with('sekolah')->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('content', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'sekolah' => Sekolah::all(),
        ];
        return response()->json($data);
    }
    public function get_pembelajaran(){
        $data = [
            'lists' => Pembelajaran::withWhereHas('mata_pelajaran')->withCount('tes')->where(function($query){
                if(auth()->user()->sekolah_id){
                    $query->where('sekolah_id', auth()->user()->sekolah_id);
                }
            })->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('judul', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'sekolah' => Sekolah::all(),
            'sekolah_id' => auth()->user()->sekolah_id,
            'mata_pelajaran' => MataPelajaran::all(),
        ];
        return response()->json($data);
    }
    public function get_tes(){
        $data = [
            'lists' => TesFormatif::with(['kunci', 'jawaban'])->withCount(['jawaban'])->withWhereHas('pembelajaran', function($query){
                $query->whereHas('mata_pelajaran');
                if(auth()->user()->sekolah_id){
                    $query->where('sekolah_id', auth()->user()->sekolah_id);
                }
            })->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('judul', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'sekolah' => Sekolah::all(),
            'sekolah_id' => auth()->user()->sekolah_id,
            'pembelajaran' => Pembelajaran::where(function($query){
                $query->whereHas('mata_pelajaran');
                if(auth()->user()->sekolah_id){
                    $query->where('sekolah_id', auth()->user()->sekolah_id);
                }
            })->get(),
        ];
        return response()->json($data);
    }
}
