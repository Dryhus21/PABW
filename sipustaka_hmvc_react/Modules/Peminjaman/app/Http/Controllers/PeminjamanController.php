<?php

namespace Modules\Peminjaman\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Peminjaman\Http\Requests\PeminjamanRequest;
use Modules\Peminjaman\Models\Peminjaman;

class PeminjamanController extends Controller
{
    /**
     * Display the React page. Data is loaded via AJAX.
     */
    public function index(): Response
    {
        return Inertia::render('Peminjaman::index');
    }

    /**
     * Return all loans as JSON.
     */
    public function getData(): JsonResponse
    {
        return response()->json(Peminjaman::latest()->get());
    }

    /**
     * Store a newly created loan together with its uploaded file.
     */
    public function store(PeminjamanRequest $request): JsonResponse
    {
        $data = $request->safe()->except('berkas');
        $file = $request->file('berkas');
        $data['berkas'] = $file->store('berkas_peminjaman', 'public');
        $data['nama_berkas'] = $file->getClientOriginalName();

        Peminjaman::create($data);

        return response()->json(['success' => 'Data peminjaman berhasil disimpan.']);
    }

    /**
     * Return a single loan for the edit form.
     */
    public function edit(Peminjaman $peminjaman): JsonResponse
    {
        return response()->json($peminjaman);
    }

    /**
     * Update the specified loan. The old file is replaced only when a new one is uploaded.
     */
    public function update(PeminjamanRequest $request, Peminjaman $peminjaman): JsonResponse
    {
        $data = $request->safe()->except('berkas');

        if ($request->hasFile('berkas')) {
            if ($peminjaman->berkas) {
                Storage::disk('public')->delete($peminjaman->berkas);
            }

            $file = $request->file('berkas');
            $data['berkas'] = $file->store('berkas_peminjaman', 'public');
            $data['nama_berkas'] = $file->getClientOriginalName();
        }

        $peminjaman->update($data);

        return response()->json(['success' => 'Data peminjaman berhasil diperbarui.']);
    }

    /**
     * Remove the specified loan and its uploaded file.
     */
    public function destroy(Peminjaman $peminjaman): JsonResponse
    {
        if ($peminjaman->berkas) {
            Storage::disk('public')->delete($peminjaman->berkas);
        }

        $peminjaman->delete();

        return response()->json(['success' => 'Data peminjaman berhasil dihapus.']);
    }
}
