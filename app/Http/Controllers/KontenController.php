<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Ptk;
use App\Models\Page;

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
    private function store_ptk(Request $request){
        $request->validate([
            'sekolah_id' => 'required',
            'nama' => 'required',
            'jabatan' => 'required',
            'avatar' => 'required||mimes:jpg,jpeg,png',
        ]);
        $upload = request()->avatar->store('images', 'public');
        $avatar = basename($upload);
        $data = Ptk::create([
            'sekolah_id' => $request->sekolah_id,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'avatar' => $avatar,
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
    private function destroy_ptk($id){
        $data = Ptk::find($id);
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
    private function store_visi(Request $request){
        $image = NULL;
        if ($request->hasFile('visi_image')) {
            $upload = request()->visi_image->store('images', 'public');
            $image = basename($upload);
        }
        $data = Page::updateOrCreate(
            [
                'sekolah_id' => $request->sekolah_id,
                'type' => $request->data,
            ],
            [
                'content' => $request->visi_text,
                'image' => $image,
            ]
        );
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
    private function store_misi(Request $request){
        $image = NULL;
        if ($request->hasFile('misi_image')) {
            $upload = request()->misi_image->store('images', 'public');
            $image = basename($upload);
        }
        $data = Page::updateOrCreate(
            [
                'sekolah_id' => $request->sekolah_id,
                'type' => $request->data,
            ],
            [
                'content' => $request->misi_text,
                'image' => $image,
            ]
        );
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
}
