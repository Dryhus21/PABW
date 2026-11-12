<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Buku\Models\Buku;
use Modules\Peminjaman\Models\Peminjaman;

test('new registrations always get the user role', function () {
    $this->post('/register', [
        'name' => 'Anggota Baru',
        'email' => 'anggota@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'admin',
    ]);

    expect(User::where('email', 'anggota@example.com')->first()->role)->toBe(UserRole::User);
});

test('users can list books but cannot open the book management pages', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/buku')->assertOk();
    $this->get('/buku/create')->assertForbidden();
    $this->post('/buku', [])->assertForbidden();
    $this->get('/admin/users')->assertForbidden();
});

test('admins can create, update, and delete a book', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->post('/buku', [
        'judul' => 'Pemrograman Web',
        'penulis' => 'Dryhus Dzacky',
        'penerbit' => 'Informatika',
        'tahun_terbit' => 2026,
        'stok' => 4,
    ])->assertRedirect(route('buku.index'));

    $buku = Buku::where('judul', 'Pemrograman Web')->first();
    expect($buku)->not->toBeNull();

    $this->put("/buku/{$buku->id}", [
        'judul' => 'Pemrograman Web Lanjut',
        'penulis' => 'Dryhus Dzacky',
        'penerbit' => 'Informatika',
        'tahun_terbit' => 2026,
        'stok' => 6,
    ])->assertRedirect(route('buku.index'));

    expect($buku->fresh()->judul)->toBe('Pemrograman Web Lanjut');

    $this->delete("/buku/{$buku->id}")->assertRedirect(route('buku.index'));

    expect(Buku::find($buku->id))->toBeNull();
});

test('book validation errors are stored under each input', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->post('/buku', ['judul' => ''])
        ->assertSessionHasErrors(['judul', 'penulis', 'penerbit', 'tahun_terbit', 'stok']);
});

test('loans are stored through ajax with an uploaded file', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $response = $this->postJson('/peminjaman', [
        'nama_peminjam' => 'Rina Pratiwi',
        'judul_buku' => 'Clean Code',
        'tanggal_pinjam' => '2026-11-01',
        'tanggal_kembali' => '2026-11-08',
        'status' => 'Dipinjam',
        'berkas' => UploadedFile::fake()->create('bukti.pdf', 200, 'application/pdf'),
    ]);

    $response->assertOk()->assertJsonPath('success', 'Data peminjaman berhasil disimpan.');

    $peminjaman = Peminjaman::first();
    Storage::disk('public')->assertExists($peminjaman->berkas);
});

test('loan validation returns 422 with per field errors', function () {
    $this->actingAs(User::factory()->create());

    $this->postJson('/peminjaman', [
        'nama_peminjam' => '',
        'judul_buku' => 'Clean Code',
        'tanggal_pinjam' => '2026-11-08',
        'tanggal_kembali' => '2026-11-01',
        'status' => 'Dipinjam',
    ])->assertStatus(422)->assertJsonValidationErrors(['nama_peminjam', 'tanggal_kembali', 'berkas']);
});

test('deleting a loan also removes its stored file', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $this->postJson('/peminjaman', [
        'nama_peminjam' => 'Budi Santoso',
        'judul_buku' => 'Clean Code',
        'tanggal_pinjam' => '2026-11-01',
        'tanggal_kembali' => '2026-11-08',
        'status' => 'Dipinjam',
        'berkas' => UploadedFile::fake()->create('bukti.pdf', 200, 'application/pdf'),
    ])->assertOk();

    $peminjaman = Peminjaman::first();
    $path = $peminjaman->berkas;

    $this->deleteJson("/peminjaman/{$peminjaman->id}")->assertOk();

    expect(Peminjaman::count())->toBe(0);
    Storage::disk('public')->assertMissing($path);
});

test('an admin cannot delete or demote their own account', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);

    $this->delete("/admin/users/{$admin->id}")->assertRedirect(route('admin.users.index'));
    expect(User::find($admin->id))->not->toBeNull();

    $this->put("/admin/users/{$admin->id}", [
        'name' => $admin->name,
        'email' => $admin->email,
        'role' => 'user',
    ])->assertSessionHasErrors('role');

    expect($admin->fresh()->role)->toBe(UserRole::Admin);
});
