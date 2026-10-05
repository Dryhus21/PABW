<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = [
            [
                'nama' => 'Budi Santoso',
                'lokasi' => 'Desa Cangkuang, Kecamatan Rancaekek',
                'tinggi' => 25,
            ],
            [
                'nama' => 'Siti Aminah',
                'lokasi' => 'Kelurahan Dayeuhkolot',
                'tinggi' => 50,
            ],
            [
                'nama' => 'Andi Pratama',
                'lokasi' => 'Desa Bojongsoang',
                'tinggi' => 90,
            ],
            [
                'nama' => 'Rina Marlina',
                'lokasi' => 'Kecamatan Baleendah',
                'tinggi' => 70,
            ],
        ];

        return view('laporan.index', compact('laporan'));
    }

    public function create()
    {
        return view('laporan.form');
    }

    public function store(Request $request)
    {
        $laporan = [
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'tinggi' => $request->tinggi,
        ];

        return view('laporan.konfirmasi', compact('laporan'));
    }
}
