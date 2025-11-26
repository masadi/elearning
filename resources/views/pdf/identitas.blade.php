@extends('layouts.cetak')
@section('content')
    <h3 class="text-center strong">BUKU INDUK SISWA</h3>
    <br>
    <table>
        <tr>
            <td colspan="4">
                <h3 class="strong">A. DATA LEMBAGA</h3>
            </td>
        </tr>
        <tr>
            <td width="5%">1.</td>
            <td width="30%">Nama PKBM/Kejar</td>
            <td width="5%">:</td>
            <td width="60%">{{ $pd->sekolah->nama }}</td>
        </tr>
        <tr>
            <td>2.</td>
            <td>NPSN</td>
            <td>:</td>
            <td>{{ $pd->sekolah->npsn }}</td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Program Studi</td>
            <td>:</td>
            <td>{{ $pd->program_pilihan }}</td>
        </tr>
        <tr>
            <td>4.</td>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $pd->sekolah->alamat }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4">
                <h3 class="strong">B. DATA PESERTA DIDIK</h3>
            </td>
        </tr>
        <tr>
            <td>5.</td>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $pd->nama }}</td>
        </tr>
        <tr>
            <td>6.</td>
            <td>NIPD / NISN</td>
            <td>:</td>
            <td>{{ $pd->nipd }}/{{ $pd->nisn }}</td>
        </tr>
        <tr>
            <td>7.</td>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $pd->jenis_kelamin_str }}</td>
        </tr>
        <tr>
            <td>8.</td>
            <td>Agama</td>
            <td>:</td>
            <td>{{ $pd->agama?->nama }}</td>
        </tr>
        <tr>
            <td>9.</td>
            <td>Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $pd->tempat_tanggal_lahir }}</td>
        </tr>
        <tr>
            <td>10.</td>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $pd->alamat }}</td>
        </tr>
        <tr>
            <td>11.</td>
            <td>Telepon / HP</td>
            <td>:</td>
            <td>{{ $pd->telepon }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4">
                <h3 class="strong">C. ORANG TUA/WALI</h3>
            </td>
        </tr>
        <tr>
            <td rowspan="3" class="align-top">14.</td>
            <td>Orang Tua</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>a. Ayah</td>
            <td>:</td>
            <td>{{ $pd->nama_ayah }}</td>
        </tr>
        <tr>
            <td>b. Ibu</td>
            <td>:</td>
            <td>{{ $pd->nama_ibu }}</td>
        </tr>
        <tr>
            <td rowspan="3" class="align-top">15.</td>
            <td>Pekerjaan</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>a. Ayah</td>
            <td>:</td>
            <td>{{ $pd->pekerjaan_ayah?->nama }}</td>
        </tr>
        <tr>
            <td>b. Ibu</td>
            <td>:</td>
            <td>{{ $pd->pekerjaan_ibu?->nama }}</td>
        </tr>
        <tr>
            <td rowspan="3" class="align-top">16.</td>
            <td>Agama</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>a. Ayah</td>
            <td>:</td>
            <td>{{ $pd->agama_a?->nama }}</td>
        </tr>
        <tr>
            <td>b. Ibu</td>
            <td>:</td>
            <td>{{ $pd->agama_i?->nama }}</td>
        </tr>
        <tr>
            <td rowspan="3" class="align-top">17.</td>
            <td>Alamat</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>a. Ayah</td>
            <td>:</td>
            <td>{{ $pd->alamat }}</td>
        </tr>
        <tr>
            <td>b. Ibu</td>
            <td>:</td>
            <td>{{ $pd->alamat }}</td>
        </tr>
        <tr>
            <td>18.</td>
            <td>Nama Wali</td>
            <td>:</td>
            <td>{{ $pd->nama_wali }}</td>
        </tr>
        <tr>
            <td>19.</td>
            <td>Pekerjaan Wali</td>
            <td>:</td>
            <td>{{ $pd->pekerjaan_wali?->nama }}</td>
        </tr>
        <tr>
            <td>20.</td>
            <td>Agama Wali</td>
            <td>:</td>
            <td>{{ $pd->agama_w?->nama }}</td>
        </tr>
        <tr>
            <td>21.</td>
            <td>Alamat Wali</td>
            <td>:</td>
            <td>{{ $pd->alamat_wali }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4">
                <h3 class="strong">D. MASUK PKBM/KEJAR INI</h3>
            </td>
        </tr>
        <tr>
            <td>22.</td>
            <td>Dari Sekolah/KBM</td>
            <td>:</td>
            <td>{{ $pd->sekolah_asal }}</td>
        </tr>
        <tr>
            <td>23.</td>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ $pd->diterima_indo }}</td>
        </tr>
        <tr>
            <td>24.</td>
            <td>Surat Pindah</td>
            <td>:</td>
            <td>{{ $pd->surat_pindah }}</td>
        </tr>
        <tr>
            <td>25.</td>
            <td>Program Pilihan</td>
            <td>:</td>
            <td>{{ $pd->program_pilihan }}</td>
        </tr>
        <tr>
            <td>26.</td>
            <td>Nilai UN dan STTB/Ijazah</td>
            <td>:</td>
            <td>{{ $pd->nilai_un }}</td>
        </tr>
    </table>
@endsection
