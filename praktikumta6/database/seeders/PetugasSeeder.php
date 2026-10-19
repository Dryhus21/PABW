<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('id_ID');

        User::updateOrCreate(
            ['email' => 'admin@bpbd.go.id'],
            [
                'name' => 'Admin BPBD',
                'password' => Hash::make('admin12345'),
            ]
        );

        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => 'petugas'.$i.'@bpbd.go.id'],
                [
                    'name' => $faker->name(),
                    'password' => Hash::make('petugas123'),
                ]
            );
        }
    }
}
