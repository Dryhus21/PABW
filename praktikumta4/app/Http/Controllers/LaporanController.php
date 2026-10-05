<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::all();

        return view('laporan.index', compact('laporan'));
    }

    public function create()
    {
        return view('laporan.form');
    }

    public function store(Request $request)
    {
        $laporan = Laporan::create([
            'nama_pelapor' => $request->nama_pelapor,
            'lokasi' => $request->lokasi,
            'tinggi_genangan' => $request->tinggi_genangan,
            'tanggal_kejadian' => $request->tanggal_kejadian,
        ]);

        return view('laporan.konfirmasi', compact('laporan'));
    }
}
