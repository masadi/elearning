<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\IndukAgama as Agama;
use App\Models\IndukPekerjaan as Pekerjaan;
class Induk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:induk';

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
        $npsn = ['12345678', 'P2965547'];
        $key = 'buku-induk';
        foreach($npsn as $n){
            $user = User::whereHas('sekolah', function($query) use ($n){
                $query->where('npsn', $n);
            })->first();
            foreach(['create', 'read', 'update', 'delete'] as $a){
                $new = Permission::updateOrCreate(
                    [
                        'name' => $key.'-'.$a,
                    ],
                    [
                        'display_name' => ucwords(str_replace('-', ' ', $key)),
                        'description' => $a,
                    ]
                );
                if(!$user->hasPermission($key.'-'.$a)){
                    $user->givePermission($new);
                }
            }
        }
        foreach(['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Kong Hu Chu', 'Kepercayaan kpd Tuhan YME', 'Lainnya'] as $a){
            Agama::updateOrCreate(['nama' => $a]);
        }
        foreach(['Tidak bekerja', 'Nelayan', 'Petani', 'Peternak', 'PNS/TNI/Polri', 'Karyawan Swasta', 'Pedagang Kecil', 'Pedagang Besar', 'Wiraswasta', 'Wirausaha', 'Buruh', 'Pensiunan', 'Tenaga Kerja Indonesia', 'Karyawan BUMN', 'Tidak dapat diterapkan', 'Sudah Meninggal', 'Lainnya'] as $b){
            Pekerjaan::updateOrCreate(['nama' => $b]);
        }
    }
}
