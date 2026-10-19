<?php

namespace Database\Seeders;

use App\Models\Monitoring;
use Illuminate\Database\Seeder;

class MonitoringSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('id_ID');

        $lahan = [
            'Singaparna',
            'Ciawi',
            'Manojaya',
        ];

        $tanaman = [
            'Padi', 
            'Mentimun',
            'Tomat',
        ];

        for ($i = 0; $i < 25; $i++) {
            $kelembapan = $faker->numberBetween(10, 100);

            if ($kelembapan < 35) {
                $status = 'Kering - Lakukan Penyiraman';
            } elseif ($kelembapan <= 70) {
                $status = 'Normal - Pertahankan Kondisi Lahan';
            } else {
                $status = 'Basah - Hentikan Penyiraman Sementara';
            }

            Monitoring::create([
                'nama_tanaman' => $faker->randomElement($tanaman),
                'lokasi' => $faker->randomElement($lahan),
                'kelembapan_tanah' => $kelembapan,
                'suhu_udara' => $faker->randomFloat(1, 20, 35),
                'kelembapan_udara' => $faker->numberBetween(40, 95),
                'status_lahan' => $status,
            ]);
        }
    }
}
