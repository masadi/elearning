<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;

class KontenController extends Controller
{
    public function store(Request $request){
        $function = 'store_'.str_replace('-', '_', $request->data);
        try {
            return $this->{$function}($request);
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
        $function = 'destroy_'.str_replace('-', '_', $data);
        try {
            return $this->{$function}($id);
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
    private function store_slide(Request $request){
        $request->validate([
            'sekolah_id' => 'required',
            'gambar' => 'required||mimes:jpg,jpeg,png',
        ]);
        $upload = request()->gambar->store('images', 'public');
        $gambar = basename($upload);
        $data = Slide::create([
            'sekolah_id' => $request->sekolah_id,
            'gambar' => $gambar,
        ]);
        if ($data) {
            return response()->json([
                'color' => 'success',
                'icon' => 'tabler-check',
                'title' => 'Success!',
                'text' => 'Data berhasil disimpan.',
            ]);
        } else {
            return response()->json([
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Data gagal disimpan.',
            ]);
        }
    }
    private function destroy_slide($id){
        $data = Slide::find($id);
        if ($data) {
            $data->delete();
            return response()->json([
                'color' => 'success',
                'icon' => 'tabler-check',
                'title' => 'Success!',
                'text' => 'Data berhasil dihapus.',
            ]);
        } else {
            return response()->json([
                'color' => 'error',
                'icon' => 'tabler-xbox-x',
                'title' => 'Failed!',
                'text' => 'Data tidak ditemukan.',
            ]);
        }
    }
}
