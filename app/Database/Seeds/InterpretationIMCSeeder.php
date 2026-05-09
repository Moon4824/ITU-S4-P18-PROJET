<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InterpretationIMCSeeder extends Seeder
{
     public function run()
    {
        // Remove duplicate rows keeping the lowest id per libelle
        $this->db->query("DELETE t1 FROM interpretation_imc t1 INNER JOIN interpretation_imc t2 WHERE t1.libelle = t2.libelle AND t1.id > t2.id");

        $data = [
            ['libelle' => 'Sous-poids', 'min' => null,  'max' => 18.49],
            ['libelle' => 'Normal',     'min' => 18.50, 'max' => 24.99],
            ['libelle' => 'Surpoids',   'min' => 25.00, 'max' => 29.99],
            ['libelle' => 'Obésité',    'min' => 30.00, 'max' => null],
        ];

        $builder = $this->db->table('interpretation_imc');

        foreach ($data as $row) {
            $exists = $builder->where('libelle', $row['libelle'])->get()->getRowArray();
            if ($exists) {
                continue;
            }

            $builder->insert($row);
        }
    }
}
