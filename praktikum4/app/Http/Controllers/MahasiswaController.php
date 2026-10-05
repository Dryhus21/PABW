<?php

namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function form(){
        return view ('form');
    }

    public function simpan (Request $request){
        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'jurusan' => $request->jurusan,
        ]);

        return "Data mahasiswa berhasil ditambahkan";
    }

    public function daftar(){
        $data = Mahasiswa::all();
        return view('mahasiswa', ['mahasiswa' => $data]);
    }
}
