<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Buku\Models\Buku;
use Modules\Peminjaman\Models\Peminjaman;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create(['password' => 'password']);
    }

    private function user(): User
    {
        return User::factory()->create(['password' => 'password']);
    }

    private function png(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('rapor_budi.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg=='));
    }

    public function test_guest_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/peminjaman')->assertRedirect('/login');
        $this->get('/buku')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
    }

    public function test_register_creates_user_role_and_logs_in(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertSame(UserRole::User, User::where('email', 'budi@example.com')->first()->role);
    }

    public function test_register_validation(): void
    {
        $this->post('/register', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'short',
            'password_confirmation' => 'different',
        ])->assertSessionHasErrors(['password']);
    }

    public function test_login_and_logout(): void
    {
        $user = $this->user();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_login_wrong_password(): void
    {
        $user = $this->user();

        $this->post('/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_forbidden_from_admin_users(): void
    {
        $this->actingAs($this->user())->get('/admin/users')->assertForbidden();
    }

    public function test_admin_can_crud_users(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $this->get('/admin/users')->assertOk();
        $this->get('/admin/users/create')->assertOk();

        $this->post('/admin/users', [
            'name' => 'Petugas',
            'email' => 'petugas@example.com',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/admin/users');

        $created = User::where('email', 'petugas@example.com')->firstOrFail();
        $this->assertSame(UserRole::Admin, $created->role);

        $this->put("/admin/users/{$created->id}", [
            'name' => 'Petugas Edit',
            'email' => 'petugas@example.com',
            'role' => 'admin',
            'password' => '',
        ])->assertRedirect('/admin/users');
        $this->assertSame('Petugas Edit', $created->fresh()->name);

        $this->delete("/admin/users/{$created->id}")->assertRedirect('/admin/users');
        $this->assertNull(User::find($created->id));

        $this->delete("/admin/users/{$admin->id}")->assertSessionHas('error');
        $this->assertNotNull(User::find($admin->id));
    }

    public function test_admin_cannot_demote_self(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'user',
            'password' => '',
        ])->assertSessionHasErrors('role');

        $this->assertSame(UserRole::Admin, $admin->fresh()->role);
    }

    public function test_buku_synchronous_crud(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $this->get('/buku')->assertOk();
        $this->get('/buku/create')->assertOk();

        $this->post('/buku', [
            'judul' => 'Laravel Dasar',
            'penulis' => 'Budi',
            'penerbit' => 'Informatika',
            'tahun_terbit' => 2024,
            'stok' => 5,
        ])->assertRedirect('/buku');

        $buku = Buku::firstOrFail();

        $this->get('/buku?search=Laravel')->assertSee('Laravel Dasar');

        $this->put("/buku/{$buku->id}", [
            'judul' => 'Laravel Lanjut',
            'penulis' => 'Budi',
            'penerbit' => 'Informatika',
            'tahun_terbit' => 2024,
            'stok' => 3,
        ])->assertRedirect('/buku');
        $this->assertSame('Laravel Lanjut', $buku->fresh()->judul);

        $this->delete("/buku/{$buku->id}")->assertRedirect('/buku');
        $this->assertSame(0, Buku::count());
    }

    public function test_buku_validation(): void
    {
        $this->actingAs($this->admin())
            ->post('/buku', ['judul' => '', 'stok' => -1])
            ->assertSessionHasErrors(['judul', 'penulis', 'penerbit', 'tahun_terbit', 'stok']);
    }

    public function test_user_can_view_buku_but_not_create(): void
    {
        $this->actingAs($this->user())->get('/buku')->assertOk();
        $this->actingAs($this->user())->get('/buku/create')->assertForbidden();
    }

    public function test_peminjaman_ajax_crud_with_file(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user());

        $this->get('/peminjaman')->assertOk();
        $this->getJson('/peminjaman/data')->assertOk()->assertJsonCount(0);

        $this->postJson('/peminjaman', [
            'nama_peminjam' => 'Budi',
            'judul_buku' => 'Laravel Dasar',
            'tanggal_pinjam' => '2026-01-01',
            'tanggal_kembali' => null,
            'status' => 'Dipinjam',
            'berkas' => $this->png(),
        ])->assertOk()->assertJsonStructure(['success']);

        $peminjaman = Peminjaman::firstOrFail();
        $this->assertNotNull($peminjaman->berkas);
        Storage::disk('public')->assertExists($peminjaman->berkas);

        $this->getJson("/peminjaman/{$peminjaman->id}/edit")
            ->assertOk()
            ->assertJsonPath('nama_peminjam', 'Budi')
            ->assertJsonPath('berkas_url', asset('storage/'.$peminjaman->berkas));

        $oldPath = $peminjaman->berkas;
        $this->postJson("/peminjaman/{$peminjaman->id}", [
            '_method' => 'PUT',
            'nama_peminjam' => 'Budi',
            'judul_buku' => 'Laravel Dasar',
            'tanggal_pinjam' => '2026-01-01',
            'status' => 'Dikembalikan',
            'berkas' => UploadedFile::fake()->create('bukti.pdf', 100, 'application/pdf'),
        ])->assertOk();

        $peminjaman->refresh();
        $this->assertSame('Dikembalikan', $peminjaman->status);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($peminjaman->berkas);

        $this->deleteJson("/peminjaman/{$peminjaman->id}")->assertOk();
        $this->assertSame(0, Peminjaman::count());
        Storage::disk('public')->assertMissing($peminjaman->berkas);
    }

    public function test_peminjaman_validation_json_422(): void
    {
        $this->actingAs($this->user());

        $this->postJson('/peminjaman', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nama_peminjam', 'judul_buku', 'tanggal_pinjam', 'status', 'berkas']);
    }

    public function test_peminjaman_rejects_zip(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user());

        $this->postJson('/peminjaman', [
            'nama_peminjam' => 'Budi',
            'judul_buku' => 'Laravel Dasar',
            'tanggal_pinjam' => '2026-01-01',
            'status' => 'Dipinjam',
            'berkas' => UploadedFile::fake()->create('data.zip', 100, 'application/zip'),
        ])->assertStatus(422)->assertJsonValidationErrors(['berkas']);
    }

    public function test_peminjaman_404(): void
    {
        $this->actingAs($this->user())->getJson('/peminjaman/999/edit')->assertNotFound();
    }
}
