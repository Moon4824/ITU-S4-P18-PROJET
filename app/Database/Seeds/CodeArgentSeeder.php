<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CodeArgentSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('code_argent')->insertBatch([
            ['code' => '933772902550071', 'valeur' => 50, 'est_valide' => 1],
            ['code' => '458375423622141', 'valeur' => 10, 'est_valide' => 1],
            ['code' => '870855474463671', 'valeur' => 15, 'est_valide' => 1],
            ['code' => '986976544918694', 'valeur' => 50, 'est_valide' => 1],
            ['code' => '724856515746817', 'valeur' => 20, 'est_valide' => 1],
            ['code' => '398993629620321', 'valeur' => 10, 'est_valide' => 1],
            ['code' => '658883308381427', 'valeur' => 250, 'est_valide' => 1],
            ['code' => '533216367186595', 'valeur' => 5, 'est_valide' => 1],
            ['code' => '973053118021121', 'valeur' => 100, 'est_valide' => 0],
            ['code' => '830064473687696', 'valeur' => 15, 'est_valide' => 1],
            ['code' => '159557106512045', 'valeur' => 50, 'est_valide' => 1],
            ['code' => '882953932288321', 'valeur' => 20, 'est_valide' => 1],
            ['code' => '934612380418957', 'valeur' => 100, 'est_valide' => 1],
            ['code' => '642285810966344', 'valeur' => 50, 'est_valide' => 1],
            ['code' => '320554871504056', 'valeur' => 50, 'est_valide' => 1],
        ]);
    }
}
