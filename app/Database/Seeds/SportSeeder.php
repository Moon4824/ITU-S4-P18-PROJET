<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SportSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('sport')->insertBatch([
            ['nom' => 'Course à pied', 'apport_poids' => -1],
            ['nom' => 'Natation', 'apport_poids' => -1],
            ['nom' => 'Vélo', 'apport_poids' => -1],
            ['nom' => 'Musculation', 'apport_poids' => 1],
            ['nom' => 'basketball', 'apport_poids' => -1],
            ['nom' => 'Yoga', 'apport_poids' => 0],
        ]);
    }
}
