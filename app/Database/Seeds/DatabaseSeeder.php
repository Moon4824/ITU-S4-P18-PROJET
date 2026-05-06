<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('InterpretationIMCSeeder');
        $this->call('TypeObjectifSeeder');
        $this->call('UserRoleSeeder');
        $this->call('UtilisateurSeeder');
        $this->call('RegimeSeeder');
        $this->call('RegimeDetailSeeder');
        $this->call('SportSeeder');
        $this->call('CodeArgentSeeder');
    }
}