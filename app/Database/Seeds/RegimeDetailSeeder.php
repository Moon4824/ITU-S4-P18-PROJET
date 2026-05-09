<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegimeDetailSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [1, 14, 9.99, -5.00],
            [2, 20, 6.00, -6.00],
            [3, 9, 14.00, 3.00],
            [4, 13, 3.00, 7.00],
            [5, 5, 3.00, -1.50],
            [6, 4, 6.00, -5.00],
            [7, 26, 2.00, -4.00],
            [7, 27, 12.00, -8.50],
            [8, 26, 5.50, 4.00],
            [9, 25, 16.00, 8.00],
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
