<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Sekolah;
use App\Models\Galeri;
use App\Models\Program;

class LamanController extends Controller
{
    public function index(){
        $sekolah = Sekolah::where('is_default', 1)->first();
        if(request()->type == 'galeri'){
            $pages = Galeri::where('sekolah_id', $sekolah?->sekolah_id)->orderBy('created_at', 'DESC')->get();
        } elseif(request()->type == 'program'){
            $pages = Program::where('sekolah_id', $sekolah?->sekolah_id)->orderBy('created_at', 'DESC')->get();
        } elseif(request()->type == 'last-program'){
            $pages = Program::where('sekolah_id', $sekolah?->sekolah_id)->orderBy('created_at', 'DESC')->take(2)->get();
        } elseif(request()->type == 'program-terbaru'){
            $pages = Program::where('sekolah_id', $sekolah?->sekolah_id)->orderBy('created_at', 'DESC')->offset(2)->limit(3)->get();
        } else {
            $pages = Page::whereType(request()->type)->where('sekolah_id', $sekolah?->sekolah_id)->first();
        }
        return response()->json($pages);
    }
    public function store(Request $request){
        $user = auth()->user();
        if($request->type == 'galeri'){
            $request->validate([
                'sekolah_id' => 'required',
                'nama' => 'required|string',
                'foto_id_gdrive' => 'required|string',
                'folder_id_gdrive' => 'required|string',
                'tanggal' => 'nullable|date',
                'lokasi' => 'nullable|string',
            ]);
            if(request()->id){
                $page = Galeri::find(request()->id);
                $page->update([
                    'sekolah_id' => $request->sekolah_id,
                    'nama' => $request->nama,
                    'foto_id_gdrive' => $request->foto_id_gdrive,
                    'folder_id_gdrive' => $request->folder_id_gdrive,
                    'tanggal' => $request->tanggal,
                    'lokasi' => $request->lokasi,
                ]);
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-check',
                    'title' => 'Success!',
                    'text' => 'Galeri berhasil diubah.',
                ];
                return response()->json($data);
            } else {
                $page = Galeri::create([
                    'sekolah_id' => $request->sekolah_id,
                    'nama' => $request->nama,
                    'foto_id_gdrive' => $request->foto_id_gdrive,
                    'folder_id_gdrive' => $request->folder_id_gdrive,
                    'tanggal' => $request->tanggal,
                    'lokasi' => $request->lokasi,
                ]);
            }
            $data = [
                'color' => 'success',
                'icon' => 'tabler-check',
                'title' => 'Success!',
                'text' => 'Galeri berhasil ditambahkan.',
            ];
            return response()->json($data);
        } elseif($request->type == 'program'){
            $request->validate([
                'sekolah_id' => 'required',
                'nama' => 'required|string',
                'foto' => 'required|mimes:jpg,jpeg,png',
            ]);
            $upload = request()->foto->store('images', 'public');
            $foto = basename($upload);
            if(request()->id){
                $page = Program::find(request()->id);
                $page->update([
                    'sekolah_id' => $request->sekolah_id,
                    'nama' => $request->nama,
                    'deskripsi' => $request->content ?? '',
                    'foto' => $foto,
                ]);
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-check',
                    'title' => 'Success!',
                    'text' => 'Program berhasil diubah.',
                ];
                return response()->json($data);
            } else {
                $page = Program::create([
                    'sekolah_id' => $request->sekolah_id,
                    'nama' => $request->nama,
                    'deskripsi' => $request->content ?? '',
                    'foto' => $foto,
                ]);
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-check',
                    'title' => 'Success!',
                    'text' => 'Program berhasil ditambahkan.',
                ];
                return response()->json($data);
            }
        } else {
            $request->validate([
                'type' => 'required|string',
                'content' => 'required|string',
            ]);
            $page = Page::updateOrCreate(
                [
                    'sekolah_id' => $user->sekolah_id ?? $request->sekolah_id,
                    'type' => $request->type,
                ],
                ['content' => $request->content]
            );
            $data = [
                'color' => 'success',
                'icon' => 'tabler-check',
                'title' => 'Success!',
                'text' => 'Laman berhasil disimpan.',
            ];
        }
        return response()->json($data);
    }
    public function destroy($id){
        $page = Page::findOrFail($id);
        $page->delete();
        $data = [
            'color' => 'success',
            'icon' => 'tabler-check',
            'title' => 'Success!',
            'text' => 'Laman berhasil dihapus.',
        ];
        return response()->json($data);
    }
    public function destroy_galeri($id){
        $page = Galeri::findOrFail($id);
        $page->delete();
        $data = [
            'color' => 'success',
            'icon' => 'tabler-check',
            'title' => 'Success!',
            'text' => 'Galeri berhasil dihapus.',
        ];
        return response()->json($data);
    }
    public function destroy_program($id){
        $page = Program::findOrFail($id);
        $page->delete();
        $data = [
            'color' => 'success',
            'icon' => 'tabler-check',
            'title' => 'Success!',
            'text' => 'Program berhasil dihapus.',
        ];
        return response()->json($data);
    }
    public function get_galeri($slug){
        $galeri = Galeri::where('slug', $slug)->firstOrFail();
        return response()->json($galeri);
    }
    public function get_program($slug){
        $program = Program::where('slug', $slug)->firstOrFail();
        return response()->json($program);
    }
}
