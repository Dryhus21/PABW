<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller{
    public function form(){
        return view ('formMahasiswa');
    }
     public function proses(Request $request){
        $nim = $request->input('nim');
        $nama = $request->input('nama');
        $prodi = $request->input('prodi');
        $semester = $request->input('semester');

        return view ('hasilMahasiswa', compact('nim','nama','prodi','semester'));
     }
}
?>