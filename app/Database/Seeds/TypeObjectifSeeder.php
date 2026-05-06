<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeObjectifSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['libelle' => 'Augmenter poids'],
            ['libelle' => 'Réduire poids'],
            ['libelle' => 'Atteindre IMC idéal'],
        ];

        $this->db->table('type_objectif')->insertBatch($data);
    }
}
