<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Sekolah;

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
        $npsn = ['P2965775','P9997969','P2970187','P2965937','P9999064','P9996629','P9908815','P9952459','P2965854','P2965547', '69873707'];
        foreach ($npsn as $n) {
            $response = Http::get('sync.erapor-smk.net/api/v7/sekolah/'.$n);
            $data = $response->object();
            $data = $data->data;
            Sekolah::updateOrCreate(
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
                    'is_default' => ($n == 'P2965775') ? 1 : 0,
                ]
            );
            $this->info('NPSN '.$n.' OK');
        }
    }
}
