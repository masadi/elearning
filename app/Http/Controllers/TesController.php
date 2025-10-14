<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TesFormatif;
use App\Models\Jawaban;

class TesController extends Controller
{
    public function store(Request $request){
        $user = auth()->user();
        $request->validate([
            'pembelajaran_id' => 'required',
            //'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            //'jumlah_soal' => 'required|integer',
            //'waktu' => 'required|integer',
            ///'status' => 'required',
        ]);
        if(request()->id){
            $tes = TesFormatif::find(request()->id);
            $tes->update([
                'pembelajaran_id' => $request->pembelajaran_id,
                //'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                //'jumlah_soal' => $request->jumlah_soal,
                //'waktu' => $request->waktu,
                //'status' => $request->status,
            ]);
            foreach($request->jawaban as $jawaban){
                if($jawaban['jawaban_id']){
                    $jaw = Jawaban::find($jawaban['jawaban_id']);
                    if($jaw){
                        $jaw->update([
                            'tes_id' => $tes->tes_id,
                            'opsi' => $jawaban['opsi'],
                            'deskripsi' => $jawaban['deskripsi'],
                            'benar' => $jawaban['benar'],
                        ]);
                        continue;
                    }
                } else {
                    Jawaban::create([
                        'tes_id' => $tes->tes_id,
                        'opsi' => $jawaban['opsi'],
                        'deskripsi' => $jawaban['deskripsi'],
                        'benar' => $jawaban['benar'],
                    ]); 
                }
            }
            $data = [
                'color' => 'success',
                'icon' => 'tabler-check',
                'title' => 'Success!',
                'text' => 'Tes formatif berhasil diubah.',
            ];
            return response()->json($data);
        } else {
            $tes = TesFormatif::create([
                'pembelajaran_id' => $request->pembelajaran_id,
                //'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                //'jumlah_soal' => $request->jumlah_soal,
                //'waktu' => $request->waktu,
                //'status' => $request->status,
            ]);
            foreach($request->jawaban as $jawaban){
                Jawaban::create([
                    'tes_id' => $tes->tes_id,
                    'opsi' => $jawaban['opsi'],
                    'deskripsi' => $jawaban['deskripsi'],
                    'benar' => $jawaban['benar'],
                ]);
            }
            $data = [
                'color' => 'success',
                'icon' => 'tabler-check',
                'title' => 'Success!',
                'text' => 'Tes formatif berhasil ditambahkan.',
            ];
            return response()->json($data);
        }
    }
    public function destroy($id){
        $item = TesFormatif::find($id);
            if($item){
                $item->delete();
                $res = [
                    'color' => 'success',
                    'icon' => 'tabler-check',
                    'title' => 'Success!',
                    'text' => 'Data tes formatif berhasil dihapus.',
                ];
            } else {
                $res = [
                    'color' => 'error',
                    'icon' => 'tabler-x',
                    'title' => 'Error!',
                    'text' => 'Data tes formatif gagal dihapus.',
                ];
            }
        return response()->json($res);
    }
}
