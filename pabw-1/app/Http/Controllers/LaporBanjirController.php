<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporBanjirController extends Controller
{
    public function form()
    {
        return view('laporbanjir.form');
    }

    public function proses(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'tinggi_genangan' => 'required|numeric|min:0|max:1000',
        ], [
            'nama.required' => 'Nama pelapor wajib diisi.',
            'nama.max' => 'Nama pelapor maksimal 100 karakter.',
            'kecamatan.required' => 'Kecamatan lokasi kejadian wajib diisi.',
            'kecamatan.max' => 'Nama kecamatan maksimal 100 karakter.',
            'desa.required' => 'Desa atau kelurahan lokasi kejadian wajib diisi.',
            'desa.max' => 'Nama desa atau kelurahan maksimal 100 karakter.',
            'tinggi_genangan.required' => 'Tinggi genangan air wajib diisi.',
            'tinggi_genangan.numeric' => 'Tinggi genangan harus berupa angka.',
            'tinggi_genangan.min' => 'Tinggi genangan tidak boleh kurang dari 0.',
            'tinggi_genangan.max' => 'Tinggi genangan tidak masuk akal, periksa kembali pengukuran.',
        ]);

        $tinggi = (float) $validated['tinggi_genangan'];

        if ($tinggi >= 70) {
            $kategori = 'Banjir Tinggi';
            $deskripsiKategori = 'Air setinggi orang dewasa atau lebih, segera evakuasi ke tempat aman.';
        } elseif ($tinggi >= 30) {
            $kategori = 'Genangan Sedang';
            $deskripsiKategori = 'Air masuk permukiman dan bisa mengganggu lalu lintas warga.';
        } else {
            $kategori = 'Genangan Rendah';
            $deskripsiKategori = 'Air menggenang di permukaan jalan, masih bisa dilalui dengan hati-hati.';
        }

        $laporan = [
            'referensi' => 'LB-' . now()->format('Ymd-His'),
            'nama' => $validated['nama'],
            'kecamatan' => $validated['kecamatan'],
            'desa' => $validated['desa'],
            'tinggi' => $tinggi,
            'kategori' => $kategori,
            'deskripsiKategori' => $deskripsiKategori,
            'waktu' => now()->format('d-m-Y H:i'),
        ];

        return redirect()
            ->route('laporbanjir.konfirmasi')
            ->with('laporan', $laporan);
    }

    public function konfirmasi()
    {
        if (! session('laporan')) {
            return redirect()->route('laporbanjir.form');
        }

        return view('laporbanjir.konfirmasi', ['laporan' => session('laporan')]);
    }
}
