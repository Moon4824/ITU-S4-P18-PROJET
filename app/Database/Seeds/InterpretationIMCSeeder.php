<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InterpretationIMCSeeder extends Seeder
{
     public function run()
    {
        $data = [
            ['libelle' => 'Sous-poids', 'min' => null,  'max' => 18.49],
            ['libelle' => 'Normal',     'min' => 18.50, 'max' => 24.99],
            ['libelle' => 'Surpoids',   'min' => 25.00, 'max' => 29.99],
            ['libelle' => 'Obésité',    'min' => 30.00, 'max' => null],
        ];

        $this->db->table('interpretation_imc')->insertBatch($data);
    }
}
