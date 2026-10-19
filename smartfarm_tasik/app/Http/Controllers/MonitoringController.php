<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function home()
    {
        $laporan = Monitoring::latest()->get();
        return view('laporan.home', compact('laporan'));
    }

    public function create()
    {
        return view('laporan.monitoring');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_tanaman' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kelembapan_tanah' => 'required|numeric',
            'suhu_udara' => 'required|numeric',
            'kelembapan_udara' => 'required|numeric',
        ]);

        $data['status_lahan'] = $this->tentukanStatus($data['kelembapan_tanah']);

        Monitoring::create($data);

        return redirect()->route('riwayat')->with('success', 'Laporan berhasil disimpan.');
    }

    public function riwayat()
    {
        $laporan = Monitoring::latest()->get();
        return view('laporan.riwayat', compact('laporan'));
    }

    public function tentang()
    {
        return view('laporan.tentang');
    }

    private function tentukanStatus($kelembapan)
    {
        if ($kelembapan < 35) {
            return 'Kering - Lakukan Penyiraman';
        } elseif ($kelembapan <= 70) {
            return 'Optimal - Pertahankan Kondisi Lahan';
        }
        else {
                $status = 'Basah - Hentikan Penyiraman Sementara';
            }

        return 'Basah';
    }
}
