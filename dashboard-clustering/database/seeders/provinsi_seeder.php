<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class provinsi_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_provinsi' => '1',
                'nama_provinsi' => 'ACEH',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '2',
                'nama_provinsi' => 'Sumatera Utara',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '3',
                'nama_provinsi' => 'Sumatera Barat',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '4',
                'nama_provinsi' => 'Riau',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '5',
                'nama_provinsi' => 'Jambi',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '6',
                'nama_provinsi' => 'Sumatera Selatan',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '7',
                'nama_provinsi' => 'Bengkulu',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '8',
                'nama_provinsi' => 'Lampung',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '9',
                'nama_provinsi' => 'Kepulauan Bangka Belitung',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '10',
                'nama_provinsi' => 'Kepulauan Riau',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '11',
                'nama_provinsi' => 'DKI Jakarta',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '12',
                'nama_provinsi' => 'Jawa Barat',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '13',
                'nama_provinsi' => 'Jawa Tengah',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '14',
                'nama_provinsi' => 'DI Yogyakarta',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '15',
                'nama_provinsi' => 'Jawa Timur',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '16',
                'nama_provinsi' => 'Banten',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '17',
                'nama_provinsi' => 'Bali',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '18',
                'nama_provinsi' => 'Nusa Tenggara Barat',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '19',
                'nama_provinsi' => 'Nusa Tenggara Timur',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '20',
                'nama_provinsi' => 'Kalimantan Barat',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '21',
                'nama_provinsi' => 'Kalimantan Tengah',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '22',
                'nama_provinsi' => 'Kalimantan Selatan',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '23',
                'nama_provinsi' => 'Kalimantan Timur',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '24',
                'nama_provinsi' => 'Kalimantan Utara',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '25',
                'nama_provinsi' => 'Sulawesi Utara',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '26',
                'nama_provinsi' => 'Sulawesi Tengah',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '27',
                'nama_provinsi' => 'Sulawesi Selatan',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '28',
                'nama_provinsi' => 'Sulawesi Tenggara',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '29',
                'nama_provinsi' => 'Gorontalo',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '30',
                'nama_provinsi' => 'Sulawesi Barat',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '31',
                'nama_provinsi' => 'Maluku',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '32',
                'nama_provinsi' => 'Maluku Utara',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '33',
                'nama_provinsi' => 'Papua Barat',
                'created_at' => now()
            ],
            [
                'id_provinsi' => '34',
                'nama_provinsi' => 'Papua',
                'created_at' => now()
            ]
        ];
        DB::table('provinsi')->insert($data);
    }
}
