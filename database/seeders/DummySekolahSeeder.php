<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummySekolahSeeder extends Seeder
{
    public function run(): void
    {
        /** =======================
         *  DATA KELAS
         *  ======================= */
        $kelasList = ['X', 'XI', 'XII'];
        $kelasIds = [];

        foreach ($kelasList as $namaKelas) {
            $kelasIds[] = DB::table('kelas')->insertGetId([
                'nama_kelas' => $namaKelas,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /** =======================
         *  DATA SISWA
         *  ======================= */
        $nama = [
            'L' => ['Andi', 'Budi', 'Dimas', 'Rizki'],
            'P' => ['Siti', 'Aulia', 'Putri', 'Rani'],
        ];

        $kota = ['Jakarta', 'Bandung', 'Semarang'];
        $nis = 2024001;

        foreach ($kelasIds as $idKelas) {
            for ($i = 1; $i <= 4; $i++) {
                $jk = rand(0, 1) ? 'L' : 'P';

                DB::table('siswa')->insert([
                    'nis' => (string) $nis++,
                    'nama' => $nama[$jk][array_rand($nama[$jk])],
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => $kota[array_rand($kota)],
                    'tanggal_lahir' => Carbon::create(2007, rand(1, 12), rand(1, 28)),
                    'alamat' => 'Jl. Sekolah No. ' . rand(1, 50),
                    'foto' => null,
                    'id_kelas' => $idKelas,
                    'uid' => (string) rand(10000000, 99999999),
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        /** =======================
         *  DATA GURU
         *  ======================= */
        $guruNama = ['Agus', 'Slamet', 'Rina', 'Fitri', 'Wahyu'];
        $nip = 19800101;

        foreach ($guruNama as $namaGuru) {
            $jk = in_array($namaGuru, ['Rina', 'Fitri']) ? 'P' : 'L';

            DB::table('guru')->insert([
                'nip' => (string) $nip++,
                'nama' => $namaGuru,
                'jenis_kelamin' => $jk,
                'tempat_lahir' => $kota[array_rand($kota)],
                'tanggal_lahir' => Carbon::create(1985, rand(1, 12), rand(1, 28)),
                'alamat' => 'Jl. Guru No. ' . rand(1, 30),
                'foto' => null,
                'uid' => (string) rand(10000000, 99999999),
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
