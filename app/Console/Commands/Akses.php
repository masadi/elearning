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
        $permissions = [
            'dashboard' => ['administrator', 'guru'], 
            //'setting-users-roles',
            //'setting-permissions', 
            'referensi-sekolah' => ['administrator'],
            'referensi-ptk' => ['administrator'],
            'referensi-rombel' => ['administrator'],
            'referensi-mata-pelajaran' => ['administrator'],
            //'materi-ajar',
            'pelatihan' => ['administrator', 'guru'],
            'profile' => ['administrator', 'guru'],
        ];
        $admin = Role::where('name', 'administrator')->first();
        $user = User::where('email', config('app.email'))->first();
        if(!$user->hasRole('administrator')){
            $user->addRole($admin);
        }
        $akses = ['create', 'read', 'update', 'delete'];
        $not_in = [];
        foreach($permissions as $permission => $roles){
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
                foreach($roles as $role){
                    $r = Role::where('name', $role)->first();
                    if(!$r->hasPermission($permission.'-'.$a)){
                        $r->givePermission($new);
                    }
                }
            }
        }
        Permission::whereNotIn('name', $not_in)->delete();
    }
}
