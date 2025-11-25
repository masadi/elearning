<?php
use App\Models\IndukSemester as Semester;
function aksi($val){
    return ucwords(str_replace('-', ' ', $val));
}
function semester_id(){
    $semester = Semester::where('periode_aktif', 1)->first();
    return $semester ? $semester->semester_id : null;
}
