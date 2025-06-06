<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Sekolah;
use App\Models\Ptk;
use App\Models\MataPelajaran;
use App\Models\RombonganBelajar;
use App\Models\Pembelajaran;
use App\Models\MateriAjar;
use App\Models\Pelatihan;
use App\Models\SesiLatihan;
use App\Models\MateriSesi;
use App\Models\TugasSesi;
use App\Models\UjianSesi;
use App\Models\Dokumen;
use App\Models\TesFormatif;
use App\Models\Jawaban;
use Carbon\Carbon;

class ReferensiController extends Controller
{
    public function index(){
        $data = [];
        if(request()->data == 'sekolah'){
            $data = Sekolah::where('user_id', auth()->user()->id)->first();
        }
        if(request()->data == 'ptk'){
            $data = Ptk::whereHas('sekolah', function($query){
                $query->where('user_id', auth()->user()->id);
            })->get();
        }
        if(request()->data == 'rombel'){
            $data = RombonganBelajar::with('walas')->whereHas('sekolah', function($query){
                $query->where('user_id', auth()->user()->id);
            })->when(request()->tingkat, function($query){
                $query->where('tingkat', request()->tingkat);
            })->get();
        }
        if(request()->data == 'mapel'){
            $data = MataPelajaran::orderBy('id')->get();
        }
        if(request()->data == 'pembelajaran'){
            $data = Pembelajaran::with('ptk')->whereHas('rombongan_belajar.sekolah', function($query){
                $query->where('user_id', auth()->user()->id);
            })->when(request()->rombongan_belajar_id, function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            })->get();
        }
        return response()->json($data);
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
    public function destroy($data, $id){
        $find = NULL;
        if($data == 'ptk'){
            $find = Ptk::find($id);
        }
        if($data == 'mapel'){
            $find = MataPelajaran::find($id);
        }
        if($data == 'materi-ajar'){
            $find = MateriAjar::find($id);
        }
        if($data == 'pelatihan'){
            $find = Pelatihan::find($id);
        }
        if($data == 'sesi'){
            $find = SesiLatihan::find($id);
        }
        if($data == 'materi-sesi'){
            $find = MateriSesi::find($id);
        }
        if($data == 'dokumen'){
            $find = Dokumen::find($id);
        }
        if($data == 'tes-formatif'){
            $find = TesFormatif::find($id);
        }
        if($find){
            if($find->delete()){
                if(in_array($data, ['pelatihan', 'sesi', 'materi-sesi'])){
                    Dokumen::where('table_id', $id)->delete();
                }
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-circle-check',
                    'title' => 'Success!',
                    'text' => 'Data '.aksi(request()->data).' berhasil dihapus!',
                ];
            } else {
                $data = [
                    'color' => 'error',
                    'icon' => 'tabler-xbox-x',
                    'title' => 'Gagal!',
                    'text' => 'Data '.aksi(request()->data).' gagal dihapus! Silahkan coba beberapa saat lagi.',
                ];
            }
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Data '.aksi(request()->data).' tidak ditemukan!',
            ];
        }
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
    public function save_sekolah(){
        $find = new Sekolah;
        if(request()->sekolah_id){
            $find->find(request()->sekolah_id);
        }
        $find->nama = request()->nama;
        $find->npsn = request()->npsn;
        $find->alamat = request()->alamat;
        $find->desa_kelurahan = request()->desa_kelurahan;
        $find->kecamatan = request()->kecamatan;
        $find->kabupaten = request()->kabupaten;
        $find->provinsi = request()->provinsi;
        $find->kodepos = request()->kodepos;
        $find->telpon = request()->telpon;
        $find->fax = request()->fax;
        $find->email = request()->email;
        $find->website = request()->website;
        if(request()->logo){
            $path = request()->logo->store('public');
            $find->logo = basename($path);
        }
        $find->user_id = auth()->user()->id;
        $find->save();
        $data = [
             'color' => 'success',
            'icon' => 'tabler-circle-check',
            'title' => 'Success!',
            'text' => 'Data Sekolah berhasil disimpan!',
        ];
        return $data;
    }
    public function save_import_ptk(){
        $insert = 0;
        foreach(request()->item as $item){
            $insert++;
            Ptk::updateOrCreate(
                [
                    'sekolah_id' => request()->sekolah_id,
                    'nik' => $item['nik'],
                ],
                [
                    'nama' => $item['nama'],
                    'nuptk' => $item['nuptk'],
                    'email' => $item['email'],
                    'jenis_kelamin' => $item['jenis_kelamin'],
                    'tempat_lahir' => $item['tempat_lahir'],
                    'tanggal_lahir' => Carbon::parse($item['tanggal_lahir'])->format('Y-m-d'),
                ]
            );
        }
        if($insert){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data PTK berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
            ];
        }
        return $data;
    }
    public function save_update_ptk(){
        $find = Ptk::find(request()->ptk_id);
        $find->nama = request()->nama;
        $find->nuptk = request()->nuptk;
        $find->email = request()->email;
        $find->jenis_kelamin = request()->jenis_kelamin;
        $find->tempat_lahir = request()->tempat_lahir;
        $find->tanggal_lahir = request()->tanggal_lahir;
        $find->nik = request()->nik;
        $path = NULL;
        if(request()->avatar){
            $path = request()->avatar->store('public');
            $find->avatar = basename($path);
        }
        if($find->save()){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data PTK berhasil diperbaharui!',
                'path' => $path,
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Data PTK gagal diperbaharui! Silahkan coba beberapa saat lagi.',
            ];
        }
        return $data;
    }
    public function save_update_mapel(){
        $find = MataPelajaran::find(request()->id);
        $find->nama = request()->nama;
        $find->alias = request()->alias;
        if($find->save()){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Mata Pelajaran berhasil diperbaharui!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Data Mata Pelajaran gagal diperbaharui! Silahkan coba beberapa saat lagi.',
            ];
        }
        return $data;
    }
    public function save_rombel(){
        $insert = 0;   
        foreach(request()->nama as $key => $nama){
            $insert++;
            RombonganBelajar::create([
                'sekolah_id' => request()->sekolah_id,
                'nama' => $nama,
                'ptk_id' => request()->ptk_id[$key],
                'tingkat' => request()->tingkat[$key],
                'semester_id' => 20251,
            ]);
        }
        if($insert){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Rombongan Belajar berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function save_mapel(){
        $insert = 0;
        foreach(request()->nama as $key => $nama){
            $insert++;
            MataPelajaran::create([
                'nama' => $nama,
                'alias' => request()->alias[$key]
            ]);
        }
        if($insert){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Mata Pelajaran berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function save_pembelajaran(){
        $insert = 0;
        foreach(request()->ptk_id as $key => $ptk_id){
            $insert++;
            Pembelajaran::create([
                'ptk_id' => $ptk_id,
                'rombongan_belajar_id' => request()->rombongan_belajar_id[$key],
                'mata_pelajaran_id' => request()->mata_pelajaran_id,
                'nama_mata_pelajaran' => request()->nama_mata_pelajaran,
            ]);
        }
        if($insert){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Pembelajaran berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function save_update_rombel(){
        $find = RombonganBelajar::find(request()->rombongan_belajar_id);
        $find->nama = request()->nama;
        $find->ptk_id = request()->wali_id;
        $find->tingkat = request()->tingkat;
        $find->save();
        $insert = 0;
        foreach(request()->ptk_id as $key => $ptk_id){
            $insert++;
            Pembelajaran::create([
                'ptk_id' => $ptk_id,
                'rombongan_belajar_id' => request()->rombongan_belajar_id,
                'mata_pelajaran_id' => request()->mata_pelajaran_id[$key],
                'nama_mata_pelajaran' => request()->nama_mata_pelajaran[$key],
            ]);
        }
        if($insert){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Pembelajaran berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function save_materi_ajar(){
        $find = new MateriAjar;
        $find->pembelajaran_id = request()->pembelajaran_id;
        $find->judul = request()->judul;
        $find->deskripsi = request()->deskripsi;
        if($find->save()){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Materi Ajar berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function show(){
        $data = [];
        if(request()->data == 'materi-ajar'){
            $materi = MateriAjar::with(['pembelajaran.rombongan_belajar'])->find(request()->id);
            $data = [
                'materi' => $materi,
                'rombel' => RombonganBelajar::whereTingkat($materi->pembelajaran->rombongan_belajar->tingkat)->orderBy('nama')->get(),
                'pembelajaran' => Pembelajaran::where('rombongan_belajar_id', $materi->pembelajaran->rombongan_belajar->rombongan_belajar_id)->orderBy('nama_mata_pelajaran')->get(),
            ];
        }
        if(request()->data == 'pelatihan'){
            $data = Pelatihan::with('dokumen')->find(request()->id);
        }
        if(request()->data == 'sesi'){
            if(request()->pelatihan_id){
                $data = Pelatihan::withCount('sesi')->find(request()->pelatihan_id);
            } else {
                $data = SesiLatihan::with('dokumen')->find(request()->sesi_latihan_id);
            }
        }
        if(request()->data == 'materi-sesi'){
            if(request()->sesi_latihan_id){
                $data = SesiLatihan::find(request()->sesi_latihan_id);
            } else {
                $data = MateriSesi::with('dokumen')->find(request()->materi_sesi_id);
            }
        }
        if(request()->data == 'tugas-sesi'){
            if(request()->sesi_latihan_id){
                $data = SesiLatihan::find(request()->sesi_latihan_id);
            } else {
                $data = TugasSesi::with('dokumen')->find(request()->tugas_sesi_id);
            }
        }
        if(request()->data == 'tes-formatif'){
            $data = TesFormatif::with(['jawaban' => function($query){
                $query->orderBy('opsi');
            }])->find(request()->id);
        }
        return response()->json($data);
    }
    public function save_update_materi_ajar(){
        $find = MateriAjar::find(request()->materi_ajar_id);
        $find->pembelajaran_id = request()->pembelajaran_id;
        $find->judul = request()->judul;
        $find->deskripsi = request()->deskripsi;
        if($find->save()){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Materi Ajar berhasil diperbaharui!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function save_pelatihan(){
        $find = new Pelatihan;
        if(request()->pelatihan_id){
            $find = $find->find(request()->pelatihan_id);
        }
        $find->judul = request()->judul;
        $find->deskripsi = request()->deskripsi;
        if($find->save()){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Pelatihan berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function save_sesi(){
        $find = new SesiLatihan;
        if(request()->sesi_latihan_id){
            $find = $find->find(request()->sesi_latihan_id);
        } else {
            $find->pelatihan_id = request()->pelatihan_id;
        }
        $find->urut = request()->urut;
        $find->judul = request()->judul;
        $find->deskripsi = request()->deskripsi;
        $find->bobot_hadir = request()->bobot_hadir;
        $find->bobot_materi = request()->bobot_materi;
        $find->bobot_tugas = request()->bobot_tugas;
        $find->bobot_tes = request()->bobot_tes;
        if($find->save()){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Sesi Latihan berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function save_materi_sesi(){
        $find = new MateriSesi;
        if(request()->materi_sesi_id){
            $find = $find->find(request()->materi_sesi_id);
        } else {
            $find->sesi_latihan_id = request()->sesi_latihan_id;
        }
        $find->judul = request()->judul;
        $find->deskripsi = request()->deskripsi;
        if($find->save()){
            foreach(request()->nama as $i => $nama){
                if(request()->berkas[$i]){
                    $nama = ($nama) ? $nama : request()->berkas[$i]->getClientOriginalName();
                    $path = request()->berkas[$i]->store('berkas', 'public');
                    Dokumen::create([
                        'nama' => $nama,
                        'table_name' => 'materi-sesi',
                        'table_id' => $find->materi_sesi_id,
                        'extension' => request()->berkas[$i]->extension(),
                        'path' => basename($path),
                    ]);
                }
            }
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Materi Sesi berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function save_tugas_sesi(){
        $find = new TugasSesi;
        if(request()->tugas_sesi_id){
            $find = $find->find(request()->tugas_sesi_id);
        } else {
            $find->sesi_latihan_id = request()->sesi_latihan_id;
        }
        $find->judul = request()->judul;
        $find->deskripsi = request()->deskripsi;
        if($find->save()){
            foreach(request()->nama as $i => $nama){
                if(request()->berkas[$i]){
                    $nama = ($nama) ? $nama : request()->berkas[$i]->getClientOriginalName();
                    $path = request()->berkas[$i]->store('berkas', 'public');
                    Dokumen::create([
                        'nama' => $nama,
                        'table_name' => 'tugas-sesi',
                        'table_id' => $find->tugas_sesi_id,
                        'extension' => request()->berkas[$i]->extension(),
                        'path' => basename($path),
                    ]);
                }
            }
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Tugas Sesi berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
                'request' => request()->all(),
            ];
        }
        return $data;
    }
    public function save_tes_sesi(){
        $insert = 0;
        $input = json_decode(request()->input, TRUE);
        if(request()->tes_id){
            $tes = TesFormatif::find(request()->tes_id);
            $tes->deskripsi = $input['deskripsi'];
            $tes->save();
            foreach($input['jawaban'] as $jawaban){
                $insert++;
                $answer = Jawaban::find($jawaban['jawaban_id']);
                $answer->opsi = $jawaban['opsi'];
                $answer->deskripsi = $jawaban['deskripsi'];
                $answer->benar = $jawaban['benar'];
                $answer->save();
            }
        } else {
            $tes = TesFormatif::create([
                'sesi_latihan_id' => $input['sesi_latihan_id'],
                'deskripsi' => $input['deskripsi'],
            ]);
            foreach($input['jawaban'] as $jawaban){
                $insert++;
                Jawaban::create([
                    'tes_id' => $tes->tes_id,
                    'opsi' => $jawaban['opsi'],
                    'deskripsi' => $jawaban['deskripsi'],
                    'benar' => $jawaban['benar'],
                ]);
            }
        }
        if($insert){
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Data Tes Formatif berhasil disimpan!',
            ];
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Tidak ada data disimpan!',
            ];
        }
        return $data;
    }
}
