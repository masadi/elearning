<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Role;

class Pkbm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pkbm';

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
        Sekolah::updateOrCreate(
                ['sekolah_id' => '0e8b732a-1324-49f7-b135-126272e01b65'],
                [
                    'npsn' => '12345678',
                    'nama' => 'PKBM Demo',
                    'alamat' => 'Jl. Pendidikan No. 1',
                    'desa_kelurahan' => 'Pendidikan',
                    //'kecamatan' => $data->kecamatan,
                    //'kabupaten' => $data->kabupaten,
                    //'provinsi' => $data->provinsi,
                    'kodepos' => '1234567',
                    'email' => 'sekolah@email.com',
                    'telpon' => '1234567890',
                    'is_default' => 1,
                ]
            );
        $npsn = ['P2965775','P9997969','P2970187','P2965937','P9999064','P9996629','P9908815','P9952459','P2965854','P2965547', '69873707'];
        $role = Role::where('name', 'sekolah')->first();
        foreach ($npsn as $n) {
            $response = Http::get('sync.erapor-smk.net/api/v7/sekolah/'.$n);
            $data = $response->object();
            $data = $data->data;
            $sekolah = Sekolah::updateOrCreate(
                ['npsn' => $n],
                [
                    'nama' => $data->nama,
                    'alamat' => $data->alamat_jalan,
                    'desa_kelurahan' => $data->desa_kelurahan,
                    //'kecamatan' => $data->kecamatan,
                    //'kabupaten' => $data->kabupaten,
                    //'provinsi' => $data->provinsi,
                    'kodepos' => $data->kode_pos,
                    'email' => $data->email,
                    'telpon' => $data->nomor_telepon,
                    'website' => $data->website,
                    'fax' => $data->nomor_fax,
                ]
            );
            $user = User::updateOrCreate(
                [
                'sekolah_id' => $sekolah->sekolah_d,
                ],
                [
                    'name' => $data->nama,
                    'username' => $n,
                    'email' => $n.'@email.com',
                    'password' => bcrypt($$n),
                    'avatar' => '/images/avatars/avatar-1.png'
                ]
            );
            if(!$user->hasRole('sekolah')){
                $user->addRole($role);
            }
            $this->info('NPSN '.$n.' OK');
        }
    }
}
