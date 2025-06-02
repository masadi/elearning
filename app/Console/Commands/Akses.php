<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class Akses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:akses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('permissions')->truncate();
        $permissions = [
            'dashboard' => [
                'administrator' => ['read'], 
                'guru' => ['read'], 
            ], 
            'referensi-sekolah' => [
                'administrator' => ['create', 'read', 'update', 'delete']
            ],
            'referensi-ptk' => [
                'administrator' => ['create', 'read', 'update', 'delete']
            ],
            'referensi-rombel' => [
                'administrator' => ['create', 'read', 'update', 'delete']
            ],
            'referensi-mata-pelajaran' => [
                'administrator' => ['create', 'read', 'update', 'delete']
            ],
            'pelatihan' => [
                'administrator' => ['create', 'read', 'update', 'delete'], 
                'guru' => ['read']
            ],
            /*'sesi' => [
                'administrator' => ['create'],
            ],
            'materi' => [
                'administrator' => ['create'],
            ],
            'dokumen' => [
                'administrator' => ['create'],
            ],*/
            'profile' => [
                'administrator' => ['read'], 
                'guru' => ['read']
            ],
        ];
        $admin = Role::where('name', 'administrator')->first();
        $user = User::where('email', config('app.email'))->first();
        if(!$user->hasRole('administrator')){
            $user->addRole($admin);
        }
        $not_in = [];
        foreach($permissions as $permission => $roles){
            foreach($roles as $role => $akses){
                foreach($akses as $a){
                    $not_in[] = $permission.'-'.$a;
                    $new = Permission::updateOrCreate(
                        [
                            'name' => $permission.'-'.$a,
                        ],
                        [
                            'display_name' => ucwords(str_replace('-', ' ', $permission)),
                            'description' => $a,
                        ]
                    );
                    $this->info($permission.'-'.$a);//throw $th;
                    $r = Role::where('name', $role)->first();
                    if(!$r->hasPermission($permission.'-'.$a)){
                        $r->givePermission($new);
                    }
                }
            }
        }
        //Permission::whereNotIn('name', $not_in)->delete();
    }
}
