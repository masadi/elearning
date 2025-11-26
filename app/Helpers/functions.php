<?php
use App\Models\IndukSemester as Semester;
function aksi($val){
    return ucwords(str_replace('-', ' ', $val));
}
function semester_id(){
    $semester = Semester::where('periode_aktif', 1)->first();
    return $semester ? $semester->semester_id : null;
}
function clean($string){
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
