<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Language;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Ptk;
use App\Models\Pelatihan;
use App\Models\SesiLatihan;

class SettingController extends Controller
{
    public function users_roles(){
        //User::whereNotNull('id')->update(['password' => bcrypt('Qwerty345')]);
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
                'users' => User::withWhereHas('roles', function($query){
                    $query->where('name', 'guru');
                })->orderBy(request()->sortBy, request()->orderBy)
                ->when(request()->q, function($query) {
                    $query->where('name', 'LIKE', '%' . request()->q . '%');
                    $query->orWhere('email', 'LIKE', '%' . request()->q . '%');
                })->when(request()->role_id, function($query) {
                    $query->whereHas('roles', function($query){
                        $query->where('id', request()->role_id);
                    });
                })->paginate(request()->per_page),
                'statistik' => [
                    [
                        'title' => 'PTK',
                        'stats' => Ptk::count(),
                        'icon' => 'tabler-users-group',
                        'color' => 'primary',
                    ],
                    [
                        'title' => 'Pelatihan',
                        'stats' => Pelatihan::count(),
                        'icon' => 'tabler-checklist',
                        'color' => 'error',
                    ],
                    [
                        'title' => 'Sesi Latihan',
                        'stats' => SesiLatihan::count(),
                        'icon' => 'tabler-versions',
                        'color' => 'warning',
                    ],
                    [
                        'title' => 'Ujian',
                        'stats' => 0,
                        'icon' => 'tabler-message-check',
                        'color' => 'success',
                    ]
                ],
                'aplikasi' => [
                    [
                        'title' => 'Aplikasi',
                        'stats' => 'v1.0.0',
                        'icon' => 'tabler-code',
                        'color' => 'success',
                    ],
                    [
                        'title' => 'Database',
                        'stats' => 'v1.0.0',
                        'icon' => 'tabler-database',
                        'color' => 'info',
                    ]
                ]
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
    public function destroy($query, $id){
        $data = [
            'color' => 'error',
            'icon' => 'tabler-xbox-x',
            'title' => 'Failed!',
            'text' => 'Query not found. Please try again later!',
        ];
        $find = NULL;
        if($query == 'user'){
            $find = User::find($id);
        }
        if($find){
            if($find->delete()){
                $data = [
                    'color' => 'success',
                    'icon' => 'tabler-circle-check',
                    'title' => 'Success!',
                    'text' => $query.' berhasil dihapus!',
                ];
            } else {
                $data = [
                    'color' => 'error',
                    'icon' => 'tabler-xbox-x',
                    'title' => 'Failed!',
                    'text' => $query.' tidak ditemukan. Silahkan coba beberapa lagi!',
                ];
            }
        }
        return response()->json($data);
    }
    public function generate_user(){
        $role = Role::where('name', 'guru')->first();
        Ptk::doesntHave('user')->whereNotNull('email')->whereNotNull('nik')->chunk(100, function($data) use ($role){
            foreach($data as $d){
                $user = User::create([
                    'name' => $d->nama,
                    'username' => $d->nik,
                    'email' => $d->email,
                    'password' => bcrypt($d->nik),
                    'avatar' => '/images/avatars/avatar-1.png'
                ]);
                if(!$user->hasRole($role)){
                    $user->addRole($role);
                }
                $d->user_id = $user->id;
                $d->save();
            }
        });
        $data = [
            'color' => 'success',
            'icon' => 'tabler-circle-check',
            'title' => 'Success!',
            'text' => 'Pengguna berhasil di generate!',
        ];
        return response()->json($data);
    }
}
