<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeObjectifSeeder extends Seeder
{
    public function run()
    {
        $this->db->query(
            'DELETE t1 FROM type_objectif t1
             INNER JOIN type_objectif t2
             ON t1.libelle = t2.libelle
             AND t1.id > t2.id'
        );

        $data = [
            ['libelle' => 'Augmenter poids'],
            ['libelle' => 'Réduire poids'],
            ['libelle' => 'Atteindre IMC idéal'],
        ];

        $existing = array_map(
            static fn (array $row): string => (string) $row['libelle'],
            $this->db->table('type_objectif')->select('libelle')->get()->getResultArray()
        );

        $toInsert = array_values(array_filter($data, static function (array $row) use ($existing): bool {
            return ! in_array((string) $row['libelle'], $existing, true);
        }));

        if ($toInsert !== []) {
            $this->db->table('type_objectif')->insertBatch($toInsert);
        }
    }
}
