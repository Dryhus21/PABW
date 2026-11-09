<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Pendaftaran\Models\Daftar;

beforeEach(function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());
});

function dataPendaftar(array $overrides = []): array
{
    return [
        'nama' => 'Budi Santoso',
        'asal_sekolah' => 'SMA Negeri 1',
        'prodi_tujuan' => 'Informatika',
        'email' => 'budi@example.com',
        'no_hp' => '081234567890',
        ...$overrides,
    ];
}

test('index renders the react page of the module', function () {
    $this->get('/pendaftaran')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Pendaftaran::index', false));
});

test('data endpoint returns registrations as json', function () {
    Daftar::factory()->count(3)->create();

    $this->getJson('/pendaftaran/data')->assertOk()->assertJsonCount(3);
});

test('store returns 422 when the form is empty', function () {
    $this->postJson('/pendaftaran', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['nama', 'asal_sekolah', 'prodi_tujuan', 'email', 'no_hp', 'berkas']);
});

test('store saves the data and the uploaded file', function () {
    $this->postJson('/pendaftaran', dataPendaftar([
        'berkas' => UploadedFile::fake()->create('rapor_budi.png', 100, 'image/png'),
    ]))->assertOk()->assertJson(['success' => 'Data berhasil disimpan.']);

    $daftar = Daftar::first();
    expect($daftar->nama_berkas)->toBe('rapor_budi.png');
    Storage::disk('public')->assertExists($daftar->berkas);
});

test('zip file is rejected', function () {
    $this->postJson('/pendaftaran', dataPendaftar([
        'berkas' => UploadedFile::fake()->create('berkas.zip', 100),
    ]))->assertStatus(422)->assertJsonValidationErrors('berkas');
});

test('update replaces the old file', function () {
    $this->postJson('/pendaftaran', dataPendaftar([
        'berkas' => UploadedFile::fake()->create('lama.png', 100, 'image/png'),
    ]));

    $daftar = Daftar::first();
    $oldPath = $daftar->berkas;

    $this->postJson("/pendaftaran/{$daftar->id}", dataPendaftar([
        '_method' => 'PUT',
        'prodi_tujuan' => 'Sistem Informasi',
        'berkas' => UploadedFile::fake()->create('surat_keterangan.pdf', 100, 'application/pdf'),
    ]))->assertOk();

    Storage::disk('public')->assertMissing($oldPath);
    expect($daftar->fresh()->prodi_tujuan)->toBe('Sistem Informasi');
});

test('destroy deletes the data and its file, unknown id returns 404', function () {
    $this->postJson('/pendaftaran', dataPendaftar([
        'berkas' => UploadedFile::fake()->create('rapor.png', 100, 'image/png'),
    ]));

    $daftar = Daftar::first();

    $this->deleteJson("/pendaftaran/{$daftar->id}")->assertOk();
    Storage::disk('public')->assertMissing($daftar->berkas);
    $this->assertDatabaseMissing('daftars', ['id' => $daftar->id]);

    $this->deleteJson('/pendaftaran/999')->assertNotFound();
});
