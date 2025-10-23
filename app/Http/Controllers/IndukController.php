<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\IndukAnggotaRombel as AnggotaRombel;
use App\Models\IndukKelompok as Kelompok;
use App\Models\IndukMataPelajaran as MataPelajaran;
use App\Models\IndukNilai as Nilai;
use App\Models\IndukPembelajaran as Pembelajaran;
use App\Models\IndukPesertaDidik as PesertaDidik;
use App\Models\IndukRombonganBelajar as RombonganBelajar;
use App\Models\IndukSemester as Semester;
use App\Models\IndukTahunAjaran as TahunAjaran;
use App\Models\IndukAgama as Agama;
use App\Models\IndukPekerjaan as Pekerjaan;
use Carbon\Carbon;

class IndukController extends Controller
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
    public function store(){
        $function = 'save_'.str_replace('-', '_', request()->data);
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
    public function destroy(){
        $function = 'delete_'.str_replace('-', '_', request()->route('data'));
        try {
            return $this->{$function}(request()->route('id'));
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
    private function get_kelompok(){
        $user = auth()->user();
        $data = [
            'lists' => Kelompok::where('sekolah_id', $user->sekolah_id)->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('nama', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'sekolah_id' => $user->sekolah_id,
        ];
        return response()->json($data);
    }
    private function save_kelompok(){
        request()->validate([
            'nama' => 'required',
        ]);
        $item = new Kelompok;
        if(request()->id){
            $item = $item->find(request()->id);
        }
        $item->sekolah_id = request()->sekolah_id;
        $item->nama = request()->nama;
        $item->save();
        return response()->json([
            'color' => 'success',
            'icon' => 'tabler-check',
            'title' => 'Success!',
            'text' => 'Data berhasil disimpan.',
        ]);
    }
    private function delete_kelompok($id){
        $item = Kelompok::find($id);
        if($item){
            if($item->delete()){
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-check',
                    'title' => 'Success!',
                    'text' => 'Data berhasil dihapus.',
                ];
            } else {
                $data = [
                    'color' => 'error',
                    'icon' => 'tabler-xbox-x',
                    'title' => 'Failed!',
                    'text' => 'Data gagal dihapus.',
                ];
            }
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Data tidak ditemukan.',
            ];
        }
        return response()->json($data);
    }
    private function get_mata_pelajaran(){
        $user = auth()->user();
        $data = [
            'lists' => MataPelajaran::where('sekolah_id', $user->sekolah_id)->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('nama', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'sekolah_id' => $user->sekolah_id,
        ];
        return response()->json($data);
    }
    private function save_mata_pelajaran(){
        request()->validate([
            'nama' => 'required',
        ]);
        $item = new MataPelajaran;
        if(request()->id){
            $item = $item->find(request()->id);
        }
        $item->sekolah_id = request()->sekolah_id;
        $item->nama = request()->nama;
        $item->alias = request()->alias;
        $item->save();
        return response()->json([
            'color' => 'success',
            'icon' => 'tabler-check',
            'title' => 'Success!',
            'text' => 'Data berhasil disimpan.',
        ]);
    }
    private function delete_mata_pelajaran($id){
        $item = MataPelajaran::find($id);
        if($item){
            if($item->delete()){
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-check',
                    'title' => 'Success!',
                    'text' => 'Data berhasil dihapus.',
                ];
            } else {
                $data = [
                    'color' => 'error',
                    'icon' => 'tabler-xbox-x',
                    'title' => 'Failed!',
                    'text' => 'Data gagal dihapus.',
                ];
            }
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Data tidak ditemukan.',
            ];
        }
        return response()->json($data);
    }
    private function get_peserta_didik(){
        $user = auth()->user();
        $data = [
            'lists' => PesertaDidik::where('sekolah_id', $user->sekolah_id)->orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('nama', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page),
            'sekolah_id' => $user->sekolah_id,
            'agama' => Agama::all(),
            'pekerjaan' => Pekerjaan::all(),
        ];
        return response()->json($data);
    }
    public function import(){
        $file_excel = request()->file_excel->store('files', 'public');
        $data = ['imported_data' => $this->imported_data($file_excel)];
        return response()->json($data);
    }
    private function imported_data($file_excel){
        $imported_data = (new FastExcel)->import(storage_path('/app/public/'.$file_excel));
        $collection = collect($imported_data);
        $multiplied = $collection->map(function ($items, $key) {
            foreach($items as $k => $v){
                $k = str_replace('.','',$k);
                $k = str_replace(' ','_',$k);
                $k = str_replace('/','_',$k);
                $k = strtolower($k);
                $item[$k] = (is_object($v)) ? $v->format('Y-m-d') : $v;
            }
            return $item;
        });
        $result = [];
        foreach($multiplied->all() as $urut => $data){
            $result[$urut] = $data;
        }
        return $result;
    }
    public function save_peserta_didik(){
        if(request()->id){
            $item = PesertaDidik::find(request()->id);
            $insert = $item->update(
                [
                    'nipd' => request()->nipd,
                    'nama' => request()->nama,
                    'nisn' => request()->nisn,
                    'agama_id' => request()->agama_id,
                    'jenis_kelamin' => request()->jenis_kelamin,
                    'tempat_lahir' => request()->tempat_lahir,
                    'tanggal_lahir' => request()->tanggal_lahir,
                    'alamat' => request()->alamat,
                    'telepon' => request()->telepon,
                    'nama_ayah' => request()->nama_ayah,
                    'nama_ibu' => request()->nama_ibu,
                    'kerja_ayah' => request()->kerja_ayah,
                    'kerja_ibu' => request()->kerja_ibu,
                    'agama_ayah' => request()->agama_ayah,
                    'agama_ibu' => request()->agama_ibu,
                    'nama_wali' => request()->nama_wali,
                    'kerja_wali' => request()->kerja_wali,
                    'agama_wali' => request()->agama_wali,
                    'alamat_wali' => request()->alamat_wali,
                    'sekolah_asal' => request()->sekolah_asal,
                    'diterima' => request()->tanggal_diterima,
                    'surat_pindah' => request()->nomor_surat_pindah,
                    'nipd_asal' => request()->nipd_asal,
                    'program_pilihan' => request()->program_pilihan,
                    'nilai_un' => request()->nilai_un,
                ]
            );
        } else {
            $insert = 0;
            foreach(request()->item as $item){
                $insert++;
                $agama = Agama::where('nama', $item['agama'])->first();
                $agama_ayah = Agama::where('nama', $item['agama_ayah'])->first();
                $agama_ibu = Agama::where('nama', $item['agama_ibu'])->first();
                $agama_wali = Agama::where('nama', $item['agama_wali'])->first();
                $kerja_ayah = Pekerjaan::where('nama', $item['pekerjaan_ayah'])->first();
                $kerja_ibu = Pekerjaan::where('nama', $item['pekerjaan_ibu'])->first();
                $kerja_wali = Pekerjaan::where('nama', $item['pekerjaan_wali'])->first();
                PesertaDidik::updateOrCreate(
                    [
                        'sekolah_id' => request()->sekolah_id,
                        'nipd' => $item['nipd'],
                    ],
                    [
                        'nama' => $item['nama'],
                        'nisn' => $item['nisn'],
                        'agama_id' => $agama?->id,
                        'jenis_kelamin' => $item['jenis_kelamin'],
                        'tempat_lahir' => $item['tempat_lahir'],
                        'tanggal_lahir' => Carbon::parse($item['tanggal_lahir'])->format('Y-m-d'),
                        'alamat' => $item['alamat'],
                        'telepon' => $item['telepon'],
                        'nama_ayah' => $item['nama_ayah'],
                        'nama_ibu' => $item['nama_ibu'],
                        'kerja_ayah' => $kerja_ayah?->id,
                        'kerja_ibu' => $kerja_ibu?->id,
                        'agama_ayah' => $agama_ayah?->id,
                        'agama_ibu' => $agama_ibu->id,
                        'nama_wali' => $item['nama_wali'],
                        'kerja_wali' => $kerja_wali->id,
                        'agama_wali' => $agama_wali->id,
                        'alamat_wali' => $item['alamat_wali'],
                        'sekolah_asal' => $item['sekolah_asal'],
                        'diterima' => $item['tanggal_diterima'],
                        'surat_pindah' => $item['nomor_surat_pindah'],
                        'nipd_asal' => $item['nipd_asal'],
                        'program_pilihan' => $item['program_pilihan'],
                        'nilai_un' => $item['nilai_un'],
                    ]
                );
            }
        }
        if($insert){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Peserta Didik berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
            ];
        }
        return response()->json($data);
    }
    private function delete_pd($id){
        $item = PesertaDidik::find($id);
        if($item){
            if($item->delete()){
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-check',
                    'title' => 'Success!',
                    'text' => 'Data berhasil dihapus.',
                ];
            } else {
                $data = [
                    'color' => 'error',
                    'icon' => 'tabler-xbox-x',
                    'title' => 'Failed!',
                    'text' => 'Data gagal dihapus.',
                ];
            }
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Data tidak ditemukan.',
            ];
        }
        return response()->json($data);
    }
}
