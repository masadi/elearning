<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Language;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class SettingController extends Controller
{
    public function users_roles(){
        //update beda server
        if (request()->isMethod('post')) {
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Kategori Barang berhasil disimpan!',
            ];
        } else {
            $data = [
                'users' => User::with('roles')->orderBy(request()->sortBy, request()->orderBy)
                ->when(request()->q, function($query) {
                    $query->where('name', 'LIKE', '%' . request()->q . '%');
                    $query->orWhere('email', 'LIKE', '%' . request()->q . '%');
                })->when(request()->role_id, function($query) {
                    $query->whereHas('roles', function($query){
                        $query->where('id', request()->role_id);
                    });
                })->paginate(request()->per_page),
                'roles' => Role::withCount('users')->with(['users' => function($query){
                    $query->take(4);
                }])->orderBy('id')->get(),
            ];
        }
        return response()->json($data);
    }
    public function permissions(){
        if (request()->isMethod('post')) {
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Kategori Barang berhasil disimpan!',
            ];
        } else {
            $data = [];
        }
        return response()->json($data);
    }
    public function profile(){
        if (request()->isMethod('post')) {
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Kategori Barang berhasil disimpan!',
            ];
        } else {
            $data = [];
        }
        return response()->json($data);
    }
    public function languages(){
        if (request()->isMethod('post')) {
            request()->validate([
                'pt' => ['required', 'string', Rule::unique('languages')->ignore(request()->lang_id, 'lang_id')],
                'en' => ['required', 'string', Rule::unique('languages')->ignore(request()->lang_id, 'lang_id')],
                'te' => ['required', 'string', Rule::unique('languages')->ignore(request()->lang_id, 'lang_id')],
                'id' => ['required', 'string', Rule::unique('languages')->ignore(request()->lang_id, 'lang_id')],
            ]);
            $find = new Language;
            if(request()->lang_id){
                $find = $find::find(request()->lang_id);
            }
            $find->pt = request()->pt;
            $find->en = request()->en;
            $find->te = request()->te;
            $find->id = request()->id;
            $find->save();
            $data = [
                'color' => 'success',
                'icon' => 'tabler-circle-check',
                'title' => 'Success!',
                'text' => 'Kategori Barang berhasil disimpan!',
            ];
        } else {
            $data = Language::orderBy(request()->sortBy, request()->orderBy)
            ->when(request()->q, function($query) {
                $query->where('pt', 'LIKE', '%' . request()->q . '%');
                $query->orWhere('en', 'LIKE', '%' . request()->q . '%');
                $query->orWhere('te', 'LIKE', '%' . request()->q . '%');
                $query->orWhere('id', 'LIKE', '%' . request()->q . '%');
            })->paginate(request()->per_page);
        }
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function destroy($query, $id){
        $data = [
            'color' => 'error',
            'icon' => 'tabler-xbox-x',
            'title' => 'Failed!',
            'text' => 'Query not found. Please try again later!',
        ];
        if($query == 'languages'){
            $find = Language::find($id);
            if($find && $find->delete()){
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-circle-check',
                    'title' => 'Success!',
                    'text' => 'Language deleted successfully!',
                ];
            } else {
                $data = [
                    'color' => 'error',
                    'icon' => 'tabler-xbox-x',
                    'title' => 'Failed!',
                    'text' => 'Language failed to delete. Please try again later!',
                ];
            }
        }
        return response()->json($data);
    }
}
