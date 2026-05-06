<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegimeDetailSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [1, 7, 15.00, -0.50],
            [1, 14, 25.00, -1.00],
            [1, 30, 45.00, -2.50],
            [2, 7, 18.00, 0.80],
            [2, 14, 32.00, 1.50],
            [2, 30, 55.00, 3.00],
            [3, 7, 12.00, -0.30],
            [3, 14, 20.00, -0.80],
            [3, 30, 38.00, -1.50],
            [4, 7, 14.00, -0.80],
            [4, 14, 24.00, -1.80],
            [4, 30, 42.00, -3.50],
            [5, 7, 20.00, 1.00],
            [5, 14, 35.00, 2.00],
            [5, 30, 60.00, 4.50],
        ];

        foreach ($data as $d) {
            $this->db->table('regime_detail')->insert([
                'id_regime' => $d[0],
                'duree' => $d[1],
                'prix' => $d[2],
                'variation_poids' => $d[3],
            ]);
        }
    }
}
