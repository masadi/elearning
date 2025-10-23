<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TemplatePtkExport implements FromView, WithEvents
{
    private function setList($event, $start, $col, $list){
        $validation = $event->sheet->getCell($col.$start)->getDataValidation();
        $validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
        $validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Value is not in list.');
        $validation->setPromptTitle('Pick from list');
        $validation->setPrompt('Please pick a value from the drop-down list.');
        $validation->setFormula1('"'.$list.'"');
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                for ($i = 2; $i < 50; $i++){
                    $this->setList($event, $i, 'E', 'L,P');
                    $this->setList($event, $i, 'H', 'Islam,Kristen,Katholik,Hindu,Buddha,Kong Hu Chu,Kepercayaan kpd Tuhan YME,Lainnya');
                    $this->setList($event, $i, 'L', 'Islam,Kristen,Katholik,Hindu,Buddha,Kong Hu Chu,Kepercayaan kpd Tuhan YME,Lainnya');
                    $this->setList($event, $i, 'M', 'Tidak bekerja,Nelayan,Petani,Peternak,PNS/TNI/Polri,Karyawan Swasta,Pedagang Kecil,Pedagang Besar,Wiraswasta,Wirausaha,Buruh,Pensiunan,Tenaga Kerja Indonesia,Karyawan BUMN,Tidak dapat diterapkan,Sudah Meninggal,Lainnya');
                    $this->setList($event, $i, 'O', 'Islam,Kristen,Katholik,Hindu,Buddha,Kong Hu Chu,Kepercayaan kpd Tuhan YME,Lainnya');
                    $this->setList($event, $i, 'P', 'Tidak bekerja,Nelayan,Petani,Peternak,PNS/TNI/Polri,Karyawan Swasta,Pedagang Kecil,Pedagang Besar,Wiraswasta,Wirausaha,Buruh,Pensiunan,Tenaga Kerja Indonesia,Karyawan BUMN,Tidak dapat diterapkan,Sudah Meninggal,Lainnya');
                    $this->setList($event, $i, 'R', 'Islam,Kristen,Katholik,Hindu,Buddha,Kong Hu Chu,Kepercayaan kpd Tuhan YME,Lainnya');
                    $this->setList($event, $i, 'S', 'Tidak bekerja,Nelayan,Petani,Peternak,PNS/TNI/Polri,Karyawan Swasta,Pedagang Kecil,Pedagang Besar,Wiraswasta,Wirausaha,Buruh,Pensiunan,Tenaga Kerja Indonesia,Karyawan BUMN,Tidak dapat diterapkan,Sudah Meninggal,Lainnya');
                }
            },
        ];
    }

    public function view(): View
    {
        return view('unduhan.template_pd');
    }
}
