<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guest is redirected to login from module pages', function () {
    $this->get('/pendaftaran')->assertRedirect(route('login'));
    $this->get('/admin/users')->assertRedirect(route('login'));
});

test('registered user always gets the user role', function () {
    $this->post('/register', [
        'name' => 'Mahasiswa',
        'email' => 'mahasiswa@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'admin',
    ]);

    expect(User::where('email', 'mahasiswa@example.com')->first()->isAdmin())->toBeFalse();
});

test('user cannot open the user management page', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin/users')
        ->assertForbidden();
});

test('admin can see the user list rendered by the module page', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get('/admin/users')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('UserManagement::index', false)
            ->has('users.data', 1));
});

test('admin can create a user', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post('/admin/users', [
            'name' => 'User Baru',
            'email' => 'baru@example.com',
            'role' => 'user',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertRedirect('/admin/users')
        ->assertSessionHas('success');

    $this->assertDatabaseHas('users', ['email' => 'baru@example.com', 'role' => 'user']);
});

test('admin cannot demote or delete their own account', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put("/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'user',
        ])
        ->assertSessionHasErrors('role');

    $this->actingAs($admin)
        ->delete("/admin/users/{$admin->id}")
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
});
