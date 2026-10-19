<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormRegistrasiSiswaController extends Controller
{
    public function form()
    {
        return view('formregistrasisiswa.form');
    }

    public function proses(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'jenis_disabilitas' => 'required|string|max:100',
            'jenis_disabilitas_lainnya' => 'required_if:jenis_disabilitas,Lainnya|nullable|string|max:100',
            'kelas' => 'required|string|max:50',
            'nama_orang_tua' => 'required|string|max:100',
            'kontak_orang_tua' => 'required|string|min:8|max:30',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 100 karakter.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak sesuai.',
            'jenis_disabilitas.required' => 'Jenis disabilitas wajib dipilih.',
            'jenis_disabilitas_lainnya.required_if' => 'Sebutkan jenis disabilitas pada kolom isian.',
            'jenis_disabilitas_lainnya.max' => 'Keterangan disabilitas maksimal 100 karakter.',
            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.max' => 'Kelas maksimal 50 karakter.',
            'nama_orang_tua.required' => 'Nama orang tua wajib diisi.',
            'nama_orang_tua.max' => 'Nama orang tua maksimal 100 karakter.',
            'kontak_orang_tua.required' => 'Kontak orang tua wajib diisi.',
            'kontak_orang_tua.min' => 'Kontak orang tua minimal 8 karakter.',
            'kontak_orang_tua.max' => 'Kontak orang tua maksimal 30 karakter.',
        ]);

        $disabilitas = $validated['jenis_disabilitas'] === 'Lainnya'
            ? $validated['jenis_disabilitas_lainnya']
            : $validated['jenis_disabilitas'];

        $lahir = \Carbon\Carbon::parse($validated['tanggal_lahir']);

        $pendaftaran = [
            'referensi' => 'REG-' . now()->format('Ymd-His'),
            'nama' => $validated['nama_lengkap'],
            'tanggal_lahir' => $lahir->format('d-m-Y'),
            'usia' => $lahir->age,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'disabilitas' => $disabilitas,
            'kelas' => $validated['kelas'],
            'orang_tua' => $validated['nama_orang_tua'],
            'kontak' => $validated['kontak_orang_tua'],
            'waktu' => now()->format('d-m-Y H:i'),
        ];

        return redirect()
            ->route('formregistrasisiswa.konfirmasi')
            ->with('pendaftaran', $pendaftaran);
    }

    public function konfirmasi()
    {
        if (! session('pendaftaran')) {
            return redirect()->route('formregistrasisiswa.form');
        }

        return view('formregistrasisiswa.konfirmasi', ['pendaftaran' => session('pendaftaran')]);
    }
}
