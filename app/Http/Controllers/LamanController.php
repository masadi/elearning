<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class LamanController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'type' => 'required|string|in:tentang,privasi',
            'content' => 'required|string',
        ]);
        $page = Page::updateOrCreate(
            [
                'sekolah_id' => $request->sekolah_id,
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
}
