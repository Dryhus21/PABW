<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Pendaftaran\Models\Daftar;
use Tests\TestCase;

class PendaftaranModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function makeUser(UserRole $role = UserRole::User): User
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }

    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'nama' => 'Budi Santoso',
            'asal_sekolah' => 'SMA Negeri 1 Bandung',
            'prodi_tujuan' => 'Informatika',
            'email' => 'budi@example.com',
            'no_hp' => '081234567890',
        ], $overrides);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/pendaftaran')->assertRedirect(route('login'));
    }

    public function test_user_cannot_open_admin_users(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->get('/admin/users')->assertForbidden();
    }

    public function test_admin_can_open_admin_users(): void
    {
        $admin = $this->makeUser(UserRole::Admin);

        $this->actingAs($admin)->get('/admin/users')->assertOk();
    }

    public function test_index_and_data_endpoint(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->get('/pendaftaran')->assertOk();

        $daftar = Daftar::create($this->validPayload());

        $this->actingAs($user)
            ->getJson('/pendaftaran/data')
            ->assertOk()
            ->assertJsonFragment(['nama' => $daftar->nama]);
    }

    public function test_store_requires_file_and_returns_422(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->postJson('/pendaftaran', $this->validPayload())
            ->assertStatus(422)
            ->assertJsonValidationErrors(['berkas']);
    }

    public function test_store_rejects_zip_file(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->postJson('/pendaftaran', $this->validPayload([
                'berkas' => UploadedFile::fake()->create('data.zip', 10, 'application/zip'),
            ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['berkas']);
    }

    public function test_store_creates_record_and_stores_file(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $this->actingAs($user)
            ->postJson('/pendaftaran', $this->validPayload([
                'berkas' => UploadedFile::fake()->create('rapor_budi.pdf', 100, 'application/pdf'),
            ]))
            ->assertOk()
            ->assertJson(['success' => 'Data berhasil disimpan.']);

        $daftar = Daftar::firstOrFail();

        $this->assertSame('rapor_budi.pdf', $daftar->nama_berkas);
        Storage::disk('public')->assertExists($daftar->berkas);
    }

    public function test_update_replaces_file_and_destroy_removes_it(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $this->actingAs($user)->postJson('/pendaftaran', $this->validPayload([
            'berkas' => UploadedFile::fake()->create('rapor_budi.pdf', 100, 'application/pdf'),
        ]))->assertOk();

        $daftar = Daftar::firstOrFail();
        $oldPath = $daftar->berkas;

        $this->actingAs($user)->putJson("/pendaftaran/{$daftar->id}", $this->validPayload([
            'prodi_tujuan' => 'Sistem Informasi',
            'berkas' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
        ]))->assertOk();

        $daftar->refresh();
        $this->assertSame('surat.pdf', $daftar->nama_berkas);
        $this->assertNotSame($oldPath, $daftar->berkas);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($daftar->berkas);

        $newPath = $daftar->berkas;

        $this->actingAs($user)->deleteJson("/pendaftaran/{$daftar->id}")->assertOk();

        $this->assertSame(0, Daftar::count());
        Storage::disk('public')->assertMissing($newPath);
    }

    public function test_update_without_file_keeps_old_file(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $this->actingAs($user)->postJson('/pendaftaran', $this->validPayload([
            'berkas' => UploadedFile::fake()->create('rapor_budi.pdf', 100, 'application/pdf'),
        ]))->assertOk();

        $daftar = Daftar::firstOrFail();
        $Path = $daftar->berkas;

        $this->actingAs($user)->putJson("/pendaftaran/{$daftar->id}", $this->validPayload([
            'prodi_tujuan' => 'Sistem Informasi',
        ]))->assertOk();

        $daftar->refresh();
        $this->assertSame($Path, $daftar->berkas);
        Storage::disk('public')->assertExists($Path);
    }

    public function test_edit_endpoint_returns_json(): void
    {
        $user = $this->makeUser();
        $daftar = Daftar::create($this->validPayload());

        $this->actingAs($user)
            ->getJson("/pendaftaran/{$daftar->id}/edit")
            ->assertOk()
            ->assertJsonFragment(['nama' => 'Budi Santoso'])
            ->assertJsonStructure(['id', 'nama', 'asal_sekolah', 'prodi_tujuan', 'email', 'no_hp', 'berkas_url']);
    }
}
