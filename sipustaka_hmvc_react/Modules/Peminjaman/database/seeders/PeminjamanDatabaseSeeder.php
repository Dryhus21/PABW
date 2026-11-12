<?php

namespace Modules\Peminjaman\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Buku\Models\Buku;
use Modules\Peminjaman\Models\Peminjaman;

class PeminjamanDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = Buku::query()->orderBy('judul')->pluck('judul')->all();
        $titles = $titles !== [] ? $titles : ['Clean Code'];

        $rows = [
            ['nama_peminjam' => 'Rina Pratiwi', 'judul_buku' => $titles[0], 'tanggal_pinjam' => '2026-10-02', 'tanggal_kembali' => '2026-10-09', 'status' => 'Dikembalikan'],
            ['nama_peminjam' => 'Budi Santoso', 'judul_buku' => $titles[1 % count($titles)], 'tanggal_pinjam' => '2026-10-05', 'tanggal_kembali' => '2026-10-12', 'status' => 'Dipinjam'],
            ['nama_peminjam' => 'Siti Aminah', 'judul_buku' => $titles[2 % count($titles)], 'tanggal_pinjam' => '2026-10-07', 'tanggal_kembali' => '2026-10-14', 'status' => 'Dipinjam'],
            ['nama_peminjam' => 'Andi Wijaya', 'judul_buku' => $titles[3 % count($titles)], 'tanggal_pinjam' => '2026-10-09', 'tanggal_kembali' => '2026-10-16', 'status' => 'Dikembalikan'],
            ['nama_peminjam' => 'Dewi Lestari', 'judul_buku' => $titles[4 % count($titles)], 'tanggal_pinjam' => '2026-10-10', 'tanggal_kembali' => '2026-10-17', 'status' => 'Dipinjam'],
        ];

        foreach ($rows as $row) {
            Peminjaman::updateOrCreate(
                ['nama_peminjam' => $row['nama_peminjam'], 'tanggal_pinjam' => $row['tanggal_pinjam']],
                $row,
            );
        }
    }
}
