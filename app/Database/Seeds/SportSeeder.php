<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SportSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('sport')->insertBatch([
            ['nom' => 'Course à pied'],
            ['nom' => 'Natation'],
            ['nom' => 'Vélo'],
            ['nom' => 'Musculation'],
            ['nom' => 'Yoga'],
        ]);
    }
}
