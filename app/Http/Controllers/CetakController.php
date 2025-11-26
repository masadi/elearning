<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndukPesertaDidik as PesertaDidik;
use App\Models\IndukPembelajaran as Pembelajaran;
use PDF;

class CetakController extends Controller
{
    public function index($id){
		$pd = PesertaDidik::with([
			'sekolah',
			'agama',
			'pekerjaan_ayah',
			'pekerjaan_ibu',
			'pekerjaan_wali',
			'agama_a',
			'agama_i',
			'agama_w',
            'anggota' => function($query){
				$query->orderBy('semester_id');
				$query->with('semester');
            }
        ])->find($id);
        $params = [
            'pd' => $pd,
			'mapel' => Pembelajaran::with([
				'mata_pelajaran',
				'kelompok',
				'rombongan_belajar',
				'nilai' => function($query) use ($pd){
					$query->whereHas('pd', function($query) use ($pd){
						$query->where('peserta_didik_id', $pd->id);
					});
				}
			])->where(function($query) use ($pd){
				$query->whereNotNull('kelompok_id');
				$query->whereNotNull('nomor_urut');
				$query->whereHas('rombongan_belajar', function($query) use ($pd){
					$query->whereHas('anggota_rombel', function($query) use ($pd){
						$query->where('peserta_didik_id', $pd->id);
					});
				});
			})->orderBy('kelompok_id')->orderBy('nomor_urut')->get(),
        ];
        $pdf = PDF::loadView('pdf.blank', $params, [], [
			'mode' => '+aCJK',
			'autoScriptToLang' => true,
			'autoLangToFont' => true,
			'format' => [210, 330],
			'margin_left' => 15,
			'margin_right' => 15,
			'margin_top' => 15,
			'margin_bottom' => 15,
			'margin_header' => 5,
			'margin_footer' => 5,
		]);
        $pdf->getMpdf()->defaultfooterfontsize=7;
		$pdf->getMpdf()->defaultfooterline=0;
		$general_title = clean($pd->nama);
		$pdf->getMpdf()->SetFooter($general_title.'|{PAGENO}|Dicetak dari '.config('app.name').' v.'.config('app.version'));
        $identitas = view('pdf.identitas', $params);
		$pdf->getMpdf()->WriteHTML($identitas);
		$pdf->getMpdf()->WriteHTML('<pagebreak />');
        $nilai = view('pdf.nilai', $params);
		$pdf->getMpdf()->WriteHTML($nilai);
        return $pdf->stream('document.pdf');
    }
}
