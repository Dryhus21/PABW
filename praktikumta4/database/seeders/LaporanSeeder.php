<?php

namespace Database\Seeders;

use App\Models\Laporan;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('id_ID');

        $kecamatan = [
            'Dayeuhkolot',
            'Baleendah',
            'Bojongsoang',
            'Rancaekek',
            'Majalaya',
        ];

        for ($i = 0; $i < 15; $i++) {
            Laporan::create([
                'nama_pelapor' => $faker->name(),
                'lokasi' => 'Kecamatan '.$faker->randomElement($kecamatan),
                'tinggi_genangan' => $faker->numberBetween(10, 150),
                'tanggal_kejadian' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            ]);
        }
    }
}
