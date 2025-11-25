<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IndukTahunAjaran as TahunAjaran;
use App\Models\IndukSemester as Semester;

class Referensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:referensi';

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
        $ajaran = [
            [
                'tahun_ajaran_id' => 2025,
                'nama' => '2025/2026',
                'periode_aktif' => 1,   
                'semester' => [
                    [
                        'semester_id' => 20251,
                        'nama' => '2025/2026 Ganjil',
                        'semester' => 1,
                        'periode_aktif' => 1,
                    ],
                    [
                        'semester_id' => 20252,
                        'nama' => '2025/2026 Genap',
                        'semester' => 2,
                        'periode_aktif' => 0,
                    ]
                ],
            ]
        ];
        foreach($ajaran as $a){
            TahunAjaran::updateOrCreate(
                [
                    'tahun_ajaran_id' => $a['tahun_ajaran_id'],
                ],
                [
                    'nama' => $a['nama'],
                    'periode_aktif' => $a['periode_aktif'],
                ]
            );
            foreach($a['semester'] as $semester){
                Semester::updateOrCreate(
                    [
                        'semester_id' => $semester['semester_id'],
                    ],
                    [
                        'tahun_ajaran_id' => $a['tahun_ajaran_id'],
                        'nama' => $semester['nama'],
                        'periode_aktif' => $semester['periode_aktif'],
                    ]
                );
            }
        }
    }
}
