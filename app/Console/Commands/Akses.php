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
            'dashboard', 
            'setting-users-roles',
            'setting-permissions', 
            'setting-profile',
            'referensi-sekolah',
            'referensi-ptk',
            'referensi-rombel',
            'referensi-mata-pelajaran',
            'materi-ajar',
        ];
        $admin = Role::where('name', 'administrator')->first();
        $user = User::where('email', config('app.email'))->first();
        if(!$user->hasRole('administrator')){
            $user->addRole($admin);
        }
        $akses = ['create', 'read', 'update', 'delete'];
        $not_in = [];
        foreach($permissions as $permission){
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
                if(!$admin->hasPermission($permission.'-'.$a)){
                    $admin->givePermission($new);
                }
            }
        }
        Permission::whereNotIn('name', $not_in)->delete();
    }
}
