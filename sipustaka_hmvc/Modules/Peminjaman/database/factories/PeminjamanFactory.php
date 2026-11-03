<?php

namespace Modules\Peminjaman\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Peminjaman\Models\Peminjaman;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Peminjaman::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalPinjam = fake()->dateTimeBetween('-2 months', 'now');

        return [
            'nama_peminjam' => fake()->name(),
            'judul_buku' => fake()->sentence(3),
            'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
            'tanggal_kembali' => null,
            'status' => 'Dipinjam',
        ];
    }
}
