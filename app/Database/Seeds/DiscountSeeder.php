<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $db->table('discount')->truncate();

        $today = new \DateTime();
        for ($i = 0; $i < 10; $i++) {
            $dateStr = $today->format('Y-m-d');
            $nominal = 100000 + ($i * 50000); // 100k, 150k, 200k...
            
            $data = [
                'tanggal' => $dateStr,
                'nominal' => $nominal,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];
            $db->table('discount')->insert($data);
            $today->modify('+1 day');
        }
    }
}
