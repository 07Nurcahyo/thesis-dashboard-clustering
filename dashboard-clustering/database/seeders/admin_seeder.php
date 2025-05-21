<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class admin_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // 'username' => 'admin',
            // 'password' => Hash::make('root'),
            // 'nama_admin' => 'yanto',
            // 'jenis_kelamin' => 'L',
            // 'created_at' => now()

            'username' => 'bagusnurcahyo4@gmail.com',
            'password' => Hash::make('d0nat_k3nt@ng'),
            'nama_admin' => 'bagus',
            'jenis_kelamin' => 'L',
            'created_at' => now()
        ];
        DB::table('users')->insert($data);
    }
}
