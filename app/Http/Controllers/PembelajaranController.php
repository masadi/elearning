<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelajaran;
use App\Models\FotoPembelajaran;

class PembelajaranController extends Controller
{
    public function store(Request $request){
        $user = auth()->user();
        $request->validate([
            'sekolah_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'status' => 'required',
        ]);
        $gambar = [];
        foreach(request()->foto as $key => $foto){
            $upload = $foto->store('images', 'public');
            $gambar[$key] = basename($upload);
        }
        if(request()->id){
            $page = Pembelajaran::find(request()->id);
            $page->update([
                'sekolah_id' => $request->sekolah_id,
                'mata_pelajaran_id' => $request->mata_pelajaran_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ]);
            $data = [
                'color' => 'success',
                'icon' => 'tabler-check',
                'title' => 'Success!',
                'text' => 'Pembelajaran berhasil diubah.',
            ];
            foreach($gambar as $urut => $img){
                FotoPembelajaran::create([
                    'pembelajaran_id' => $page->pembelajaran_id,
                    'foto' => $img,
                    'urut' => $urut,
                ]);
            }
            return response()->json($data);
        } else {
            $page = Pembelajaran::create([
                'sekolah_id' => $request->sekolah_id,
                'mata_pelajaran_id' => $request->mata_pelajaran_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ]);
            $data = [
                'color' => 'success',
                'icon' => 'tabler-check',
                'title' => 'Success!',
                'text' => 'Pembelajaran berhasil ditambahkan.',
            ];
            foreach($gambar as $urut => $img){
                FotoPembelajaran::create([
                    'pembelajaran_id' => $page->pembelajaran_id,
                    'foto' => $img,
                    'urut' => $urut,
                ]);
            }
            return response()->json($data);
        }
    }
    public function destroy($id){
        $item = Pembelajaran::find($id);
            if($item){
                $item->delete();
                $res = [
                    'color' => 'success',
                    'icon' => 'tabler-check',
                    'title' => 'Success!',
                    'text' => 'Data pembelajaran berhasil dihapus.',
                ];
            } else {
                $res = [
                    'color' => 'error',
                    'icon' => 'tabler-alert-circle',
                    'title' => 'Gagal!',
                    'text' => 'Data pembelajaran tidak ditemukan.',
                ];
            }
        return response()->json($res);
    }
}
