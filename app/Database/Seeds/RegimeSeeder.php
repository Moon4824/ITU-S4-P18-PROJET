<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegimeSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('regime')->insertBatch([
            ['nom' => 'Régime Méditerranéen', 'pct_viande' => 20, 'pct_poisson' => 50, 'pct_volaille' => 30],
            ['nom' => 'Régime Masika be', 'pct_viande' => 20, 'pct_poisson' => 50, 'pct_volaille' => 30],
            ['nom' => 'Régime Hyperprotéiné', 'pct_viande' => 50, 'pct_poisson' => 20, 'pct_volaille' => 30],
            ['nom' => 'Régime avoine', 'pct_viande' => 50, 'pct_poisson' => 20, 'pct_volaille' => 30],
            ['nom' => 'Régime Équilibré', 'pct_viande' => 30, 'pct_poisson' => 30, 'pct_volaille' => 40],
            ['nom' => 'Régime gras', 'pct_viande' => 30, 'pct_poisson' => 30, 'pct_volaille' => 40],
            ['nom' => 'Régime Minceur', 'pct_viande' => 15, 'pct_poisson' => 45, 'pct_volaille' => 40],
            ['nom' => 'Régime végétarien', 'pct_viande' => 15, 'pct_poisson' => 45, 'pct_volaille' => 40],
            ['nom' => 'Régime Prise de masse', 'pct_viande' => 40, 'pct_poisson' => 20, 'pct_volaille' => 40],
            ['nom' => 'Régime de viande MG', 'pct_viande' => 40, 'pct_poisson' => 20, 'pct_volaille' => 40],
        ]);
    }
}
