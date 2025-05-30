<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TemplatePtkExport;
use Excel;

class ExportController extends Controller
{
    public function template_ptk(){
        return Excel::download(new TemplatePtkExport, 'template-ptk.xlsx');
    }
}
