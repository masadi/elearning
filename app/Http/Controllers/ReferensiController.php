<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Sekolah;
use App\Models\Ptk;
use Carbon\Carbon;

class ReferensiController extends Controller
{
    public function index(){
        $data = [];
        if(request()->data == 'sekolah'){
            $data = Sekolah::where('user_id', auth()->user()->id)->first();
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
        if($find){
            if($find->delete()){
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-circle-check',
                    'title' => 'Success!',
                    'text' => 'Data '.strtoupper(request()->data).' berhasil dihapus!',
                ];
            } else {
                $data = [
                    'color' => 'error',
                    'icon' => 'tabler-xbox-x',
                    'title' => 'Gagal!',
                    'text' => 'Data '.strtoupper(request()->data).' gagal dihapus! Silahkan coba beberapa saat lagi.',
                ];
            }
        } else {
            $data = [
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Data '.strtoupper(request()->data).' tidak ditemukan!',
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
}
