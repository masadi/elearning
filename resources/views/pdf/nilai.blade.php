<h3 class="text-center strong">PRESTASI WARGA BELAJAR</h3>
<br>
<table width="100%" class="table table-bordered">
    <tr>
        <td colspan="2">NAMA SISWA</td>
        <td colspan="4">{{ $pd->nama }}</td>
        <td colspan="4">NIPD/NISN</td>
        <td colspan="4">{{ $pd->nipd }}/{{ $pd->nisn }}</td>
        <td class="text-center" colspan="2">Nomor Peserta Ujian</td>
    </tr>
    <tr>
        <td colspan="2">JENIS KELAMIN</td>
        <td colspan="4">{{ $pd->jenis_kelamin_str }}</td>
        <td colspan="4">Nomor Seri Rapor</td>
        <td colspan="4">{{ $pd->nomor_seri_rapor }}</td>
        <td class="text-center" colspan="2">{{ $pd->nopes }}</td>
    </tr>
    <tr>
        <td colspan="2">TEMPAT TANGGAL LAHIR</td>
        <td colspan="4">{{ $pd->tempat_tanggal_lahir }}</td>
        <td colspan="4">Nomor Seri Ijazah</td>
        <td colspan="4">{{ $pd->nomor_seri_ijazah }}</td>
        <td rowspan="2" class="text-center" colspan="2">LULUS/TIDAK LULUS</td>
    </tr>
    <tr>
        <td colspan="2">TAHUN PELAJARAN</td>
        @foreach ($pd->anggota->chunk(3) as $item)
            <td colspan="4" class="text-center">{{ $item[0]?->semester?->tahun_ajaran }}</td>
        @endforeach
        @if (count($pd->anggota->chunk(3)) == 1)
            <td colspan="4"></td>
            <td colspan="4"></td>
        @endif
        @if (count($pd->anggota->chunk(3)) == 2)
            <td colspan="4"></td>
        @endif
    </tr>
    <tr>
        <td colspan="2">KELAS</td>
        @foreach ($pd->anggota->chunk(3) as $item)
            <td colspan="4" class="text-center">{{ $item[0]?->nama }}</td>
        @endforeach
        @if (count($pd->anggota->chunk(3)) == 1)
            <td colspan="4"></td>
            <td colspan="4"></td>
        @endif
        @if (count($pd->anggota->chunk(3)) == 2)
            <td colspan="4"></td>
        @endif
        <td rowspan="2" class="text-center" colspan="2">LULUS</td>
    </tr>
    <tr>
        <td colspan="2">SEMESTER</td>
        <td colspan="2" class="text-center">SEM 1</td>
        <td colspan="2" class="text-center">SEM 2</td>
        <td colspan="2" class="text-center">SEM 1</td>
        <td colspan="2" class="text-center">SEM 2</td>
        <td colspan="2" class="text-center">SEM 1</td>
        <td colspan="2" class="text-center">SEM 2</td>
    </tr>
    <tr>
        <td colspan="2">KKM</td>
        <td class="text-center">KKM</td>
        <td class="text-center">NILAI</td>
        <td class="text-center">KKM</td>
        <td class="text-center">NILAI</td>
        <td class="text-center">KKM</td>
        <td class="text-center">NILAI</td>
        <td class="text-center">KKM</td>
        <td class="text-center">NILAI</td>
        <td class="text-center">KKM</td>
        <td class="text-center">NILAI</td>
        <td class="text-center">KKM</td>
        <td class="text-center">NILAI</td>
        <td class="text-center">NILAI</td>
        <td class="text-center">NILAI</td>
    </tr>
    <tr>
        <td colspan="2">MATA PELAJARAN</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">KKM</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <?php
    $pembelajaran = [];
    foreach ($mapel as $m) {
        $pembelajaran[$m->kelompok->nama][] = [
            'nama_mata_pelajaran' => $m->mata_pelajaran->nama,
            'kkm' => [$m->rombongan_belajar->semester_id => $m->kkm],
            'nilai' => [$m->rombongan_belajar->semester_id => $m->nilai?->nilai],
        ];
    }
    /*$all_pembelajaran[$pembelajaran->kelompok->nama_kelompok][] = [
                    'deskripsi_mata_pelajaran' => $pembelajaran->single_deskripsi_mata_pelajaran,
                    'nama_mata_pelajaran' => $pembelajaran->nama_mata_pelajaran,
                    //'nilai_akhir_pengetahuan'	=> $nilai_pengetahuan_value,
                    //'nilai_akhir_keterampilan'	=> $nilai_keterampilan_value,
                    'nilai_akhir' => $nilai_akhir,
                    //'predikat'	=> konversi_huruf($kkm, $nilai_akhir, $produktif),
                    //'nilai_akhir_pk' => ($pembelajaran->nilai_akhir_pk) ? $pembelajaran->nilai_akhir_pk->nilai : 0,
                    //'nilai_akhir_pk' => $nilai_pengetahuan_value,
                ];*/
    $j = 1;
    ?>
    @foreach ($pembelajaran as $kelompok => $items)
        <tr>
            <td colspan="15">{{ $kelompok }}</td>
        </tr>
        @foreach ($items as $item)
            <tr>
                <td>{{ $j++ }}</td>
                <td>{{ $item['nama_mata_pelajaran'] }}</td>
                @foreach ($item['kkm'] as $kkm)
                    <td class="text-center">{{ $kkm }}</td>
                @endforeach
                @foreach ($item['nilai'] as $nilai)
                    <td class="text-center">{{ $nilai }}</td>
                @endforeach
                @for ($i = 0; $i < count($item['nilai']) + (12 - count($item['nilai'])); $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach
    @endforeach
</table>
