<?php

namespace Modules\Buku\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Buku\Models\Buku;

class BukuDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            ['judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'penerbit' => 'Prentice Hall', 'tahun_terbit' => 2008, 'stok' => 5],
            ['judul' => 'Laravel: Up & Running', 'penulis' => 'Matt Stauffer', 'penerbit' => "O'Reilly Media", 'tahun_terbit' => 2019, 'stok' => 3],
            ['judul' => 'Sistem Basis Data', 'penulis' => 'Fathansyah', 'penerbit' => 'Informatika', 'tahun_terbit' => 2018, 'stok' => 7],
            ['judul' => 'Algoritma dan Pemrograman', 'penulis' => 'Rinaldi Munir', 'penerbit' => 'Informatika', 'tahun_terbit' => 2016, 'stok' => 4],
            ['judul' => 'Rekayasa Perangkat Lunak', 'penulis' => 'Rosa A. S.', 'penerbit' => 'Modula', 'tahun_terbit' => 2016, 'stok' => 2],
            ['judul' => 'Jaringan Komputer', 'penulis' => 'Andrew S. Tanenbaum', 'penerbit' => 'Pearson', 'tahun_terbit' => 2011, 'stok' => 6],
            ['judul' => 'Belajar Pemrograman Python', 'penulis' => 'Budi Raharjo', 'penerbit' => 'Informatika', 'tahun_terbit' => 2020, 'stok' => 8],
        ];

        foreach ($books as $book) {
            Buku::updateOrCreate(['judul' => $book['judul']], $book);
        }
    }
}
